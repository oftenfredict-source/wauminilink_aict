<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        // Initialize default settings if they don't exist
        $this->initializeDefaultSettings();
        
        // Get grouped settings
        $groupedSettings = SystemSetting::getGroupedSettings();
        $categories = config('settings.categories');
        $groups = config('settings.groups');
        
        return view('settings.index', compact('groupedSettings', 'categories', 'groups'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $settings = $request->except(['_token', '_method']);
        
        try {
            DB::beginTransaction();
            
            foreach ($settings as $key => $value) {
                $setting = SystemSetting::where('key', $key)->first();
                
                if ($setting && $setting->is_editable) {
                    // Validate the value
                    $this->validateSetting($setting, $value);
                    
                    // Update the setting
                    $setting->update(['value' => $value]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('settings.index')
                ->with('success', 'Settings updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('settings.index')
                ->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }

    /**
     * Update settings for a specific category
     */
    public function updateCategory(Request $request, $category)
    {
        $settings = $request->except(['_token', '_method']);
        
        try {
            DB::beginTransaction();
            
            foreach ($settings as $key => $value) {
                $setting = SystemSetting::where('key', $key)
                    ->where('category', $category)
                    ->first();
                
                if ($setting && $setting->is_editable) {
                    $this->validateSetting($setting, $value);
                    $setting->update(['value' => $value]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('settings.index', ['category' => $category])
                ->with('success', ucfirst($category) . ' settings updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('settings.index', ['category' => $category])
                ->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }

    /**
     * Reset settings to default values
     */
    public function reset(Request $request)
    {
        $category = $request->input('category');
        $defaults = config('settings.defaults');
        
        try {
            DB::beginTransaction();
            
            if ($category) {
                // Reset specific category
                foreach ($defaults as $key => $config) {
                    if ($config['category'] === $category) {
                        SystemSetting::setValue($key, $config['value'], $config['type']);
                    }
                }
                $message = ucfirst($category) . ' settings reset to defaults.';
            } else {
                // Reset all settings
                foreach ($defaults as $key => $config) {
                    SystemSetting::setValue($key, $config['value'], $config['type']);
                }
                $message = 'All settings reset to defaults.';
            }
            
            DB::commit();
            
            return redirect()->route('settings.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('settings.index')
                ->with('error', 'Error resetting settings: ' . $e->getMessage());
        }
    }

    /**
     * Export settings
     */
    public function export()
    {
        $settings = SystemSetting::where('is_editable', true)
            ->orderBy('category')
            ->orderBy('group')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($setting) {
                return [
                    'key' => $setting->key,
                    'value' => $setting->value,
                    'type' => $setting->type,
                    'category' => $setting->category,
                    'group' => $setting->group,
                    'description' => $setting->description
                ];
            });

        $filename = 'settings_export_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($settings)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }

    /**
     * Import settings
     */
    public function import(Request $request)
    {
        $request->validate([
            'settings_file' => 'required|file|mimes:json|max:2048'
        ]);

        try {
            $content = file_get_contents($request->file('settings_file')->getPathname());
            $importedSettings = json_decode($content, true);

            if (!is_array($importedSettings)) {
                throw new \Exception('Invalid JSON format');
            }

            DB::beginTransaction();

            foreach ($importedSettings as $settingData) {
                if (isset($settingData['key']) && isset($settingData['value'])) {
                    $setting = SystemSetting::where('key', $settingData['key'])->first();
                    if ($setting && $setting->is_editable) {
                        $this->validateSetting($setting, $settingData['value']);
                        $setting->update(['value' => $settingData['value']]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('settings.index')
                ->with('success', 'Settings imported successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('settings.index')
                ->with('error', 'Error importing settings: ' . $e->getMessage());
        }
    }

    /**
     * Get setting value via AJAX
     */
    public function getValue($key)
    {
        $value = SystemSetting::getValue($key);
        return response()->json(['value' => $value]);
    }

    /**
     * Set setting value via AJAX
     */
    public function setValue(Request $request, $key)
    {
        $setting = SystemSetting::where('key', $key)->first();
        
        if (!$setting || !$setting->is_editable) {
            return response()->json(['error' => 'Setting not found or not editable'], 404);
        }

        try {
            $this->validateSetting($setting, $request->value);
            $oldValue = $setting->value;
            $setting->update(['value' => $request->value]);
            
            // Log the change
            \App\Services\SettingsService::logChange($key, $oldValue, $request->value, 'updated');
            
            return response()->json(['success' => true, 'value' => $request->value]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Get audit logs
     */
    public function auditLogs(Request $request)
    {
        $filters = $request->only(['setting_key', 'user_id', 'action', 'date_from', 'date_to']);
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 20);
        
        $filters['limit'] = $limit;
        $filters['offset'] = ($page - 1) * $limit;
        
        $logs = \App\Services\SettingsService::getAuditLogs($filters);
        
        // Get total count for pagination
        $totalCount = \App\Models\SettingAuditLog::count();
        $totalPages = ceil($totalCount / $limit);
        
        // Add setting names to logs
        $logs = $logs->map(function ($log) {
            $setting = SystemSetting::where('key', $log->setting_key)->first();
            $log->setting_name = $setting ? ucwords(str_replace('_', ' ', $setting->key)) : null;
            $log->action_description = $log->action_description;
            return $log;
        });
        
        return response()->json([
            'logs' => $logs,
            'pagination' => [
                'current_page' => (int) $page,
                'total_pages' => $totalPages,
                'total_count' => $totalCount,
                'per_page' => $limit
            ]
        ]);
    }

    /**
     * Create settings backup
     */
    public function backup()
    {
        $settings = SystemSetting::where('is_editable', true)
            ->orderBy('category')
            ->orderBy('group')
            ->orderBy('sort_order')
            ->get()
            ->map(function ($setting) {
                return [
                    'key' => $setting->key,
                    'value' => $setting->value,
                    'type' => $setting->type,
                    'category' => $setting->category,
                    'group' => $setting->group,
                    'description' => $setting->description,
                    'validation_rules' => $setting->validation_rules,
                    'options' => $setting->options
                ];
            });

        $backup = [
            'version' => '1.0',
            'created_at' => now()->toISOString(),
            'created_by' => auth()->user()->name ?? 'System',
            'settings' => $settings
        ];

        $filename = 'settings_backup_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($backup)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }

    /**
     * Restore settings from backup
     */
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:json|max:5120' // 5MB max
        ]);

        try {
            $content = file_get_contents($request->file('backup_file')->getPathname());
            $backup = json_decode($content, true);

            if (!is_array($backup) || !isset($backup['settings'])) {
                throw new \Exception('Invalid backup file format');
            }

            DB::beginTransaction();

            $restoredCount = 0;
            foreach ($backup['settings'] as $settingData) {
                if (isset($settingData['key']) && isset($settingData['value'])) {
                    $setting = SystemSetting::where('key', $settingData['key'])->first();
                    if ($setting && $setting->is_editable) {
                        $oldValue = $setting->value;
                        $this->validateSetting($setting, $settingData['value']);
                        $setting->update(['value' => $settingData['value']]);
                        
                        // Log the restore
                        \App\Services\SettingsService::logChange(
                            $settingData['key'], 
                            $oldValue, 
                            $settingData['value'], 
                            'imported',
                            ['backup_file' => $request->file('backup_file')->getClientOriginalName()]
                        );
                        
                        $restoredCount++;
                    }
                }
            }

            DB::commit();

            return redirect()->route('settings.index')
                ->with('success', "Settings restored successfully. {$restoredCount} settings updated.");

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('settings.index')
                ->with('error', 'Error restoring settings: ' . $e->getMessage());
        }
    }

    /**
     * Initialize default settings if they don't exist
     */
    private function initializeDefaultSettings()
    {
        $defaults = config('settings.defaults');
        
        foreach ($defaults as $key => $config) {
            if (!SystemSetting::where('key', $key)->exists()) {
                SystemSetting::create([
                    'key' => $key,
                    'value' => $config['value'],
                    'type' => $config['type'],
                    'category' => $config['category'],
                    'group' => $config['group'],
                    'description' => $config['description'],
                    'is_editable' => true,
                    'is_public' => false,
                    'validation_rules' => $config['validation_rules'] ?? null,
                    'options' => $config['options'] ?? null,
                    'sort_order' => 0
                ]);
            }
        }
    }

    /**
     * Validate setting value
     */
    private function validateSetting(SystemSetting $setting, $value)
    {
        if (!$setting->validation_rules) {
            return;
        }

        $rules = [$setting->key => $setting->validation_rules];
        $data = [$setting->key => $value];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first($setting->key));
        }
    }

    /**
     * Get settings analytics
     */
    public function analytics()
    {
        $totalSettings = SystemSetting::count();
        $editableSettings = SystemSetting::where('is_editable', true)->count();
        $publicSettings = SystemSetting::where('is_public', true)->count();

        $categoryStats = SystemSetting::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->get()
            ->pluck('count', 'category');

        $typeStats = SystemSetting::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type');

        $recentChanges = \App\Models\SettingAuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $changesByDay = \App\Models\SettingAuditLog::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $changesByUser = \App\Models\SettingAuditLog::with('user')
            ->selectRaw('user_id, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $mostChangedSettings = \App\Models\SettingAuditLog::selectRaw('setting_key, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('setting_key')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return view('settings.analytics', compact(
            'totalSettings',
            'editableSettings', 
            'publicSettings',
            'categoryStats',
            'typeStats',
            'recentChanges',
            'changesByDay',
            'changesByUser',
            'mostChangedSettings'
        ));
    }

    /**
     * Legacy method for backward compatibility
     */
    private function writeEnv(array $data): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return;
        }
        $content = file_get_contents($envPath);
        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*$/m";
            $line = $key.'='.preg_replace('/\n|\r/', '', $value);
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $line, $content);
            } else {
                $content .= "\n".$line;
            }
        }
        file_put_contents($envPath, $content);
    }
}


