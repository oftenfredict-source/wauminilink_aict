<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SettingsApiController extends Controller
{
    /**
     * Get all settings
     */
    public function index(Request $request): JsonResponse
    {
        $query = SystemSetting::query();

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by group
        if ($request->has('group')) {
            $query->where('group', $request->group);
        }

        // Filter by editable status
        if ($request->has('editable')) {
            $query->where('is_editable', $request->boolean('editable'));
        }

        // Search by key or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 50);
        $settings = $query->orderBy('category')
                         ->orderBy('group')
                         ->orderBy('sort_order')
                         ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $settings->items(),
            'pagination' => [
                'current_page' => $settings->currentPage(),
                'last_page' => $settings->lastPage(),
                'per_page' => $settings->perPage(),
                'total' => $settings->total(),
            ]
        ]);
    }

    /**
     * Get a specific setting
     */
    public function show(string $key): JsonResponse
    {
        $setting = SystemSetting::where('key', $key)->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $setting
        ]);
    }

    /**
     * Get setting value
     */
    public function getValue(string $key): JsonResponse
    {
        $value = SettingsService::get($key);

        return response()->json([
            'success' => true,
            'key' => $key,
            'value' => $value
        ]);
    }

    /**
     * Set setting value
     */
    public function setValue(Request $request, string $key): JsonResponse
    {
        $setting = SystemSetting::where('key', $key)->first();

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found'
            ], 404);
        }

        if (!$setting->is_editable) {
            return response()->json([
                'success' => false,
                'message' => 'Setting is not editable'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'value' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $oldValue = $setting->value;
            $result = SettingsService::set($key, $request->value, 'api_updated');

            return response()->json([
                'success' => true,
                'message' => 'Setting updated successfully',
                'data' => [
                    'key' => $key,
                    'old_value' => $oldValue,
                    'new_value' => $request->value
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating setting: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update settings
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $settings = [];
            $updatedCount = 0;
            $errors = [];

            foreach ($request->settings as $settingData) {
                $setting = SystemSetting::where('key', $settingData['key'])->first();

                if (!$setting) {
                    $errors[] = "Setting '{$settingData['key']}' not found";
                    continue;
                }

                if (!$setting->is_editable) {
                    $errors[] = "Setting '{$settingData['key']}' is not editable";
                    continue;
                }

                try {
                    $oldValue = $setting->value;
                    SettingsService::set($settingData['key'], $settingData['value'], 'api_bulk_updated');
                    $settings[] = [
                        'key' => $settingData['key'],
                        'old_value' => $oldValue,
                        'new_value' => $settingData['value']
                    ];
                    $updatedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Error updating '{$settingData['key']}': " . $e->getMessage();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Bulk update completed. {$updatedCount} settings updated.",
                'data' => [
                    'updated_count' => $updatedCount,
                    'settings' => $settings,
                    'errors' => $errors
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error during bulk update: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get settings by category
     */
    public function getByCategory(string $category): JsonResponse
    {
        $settings = SettingsService::getCategory($category);

        return response()->json([
            'success' => true,
            'category' => $category,
            'data' => $settings
        ]);
    }

    /**
     * Get grouped settings
     */
    public function getGrouped(): JsonResponse
    {
        $groupedSettings = SystemSetting::getGroupedSettings();

        return response()->json([
            'success' => true,
            'data' => $groupedSettings
        ]);
    }

    /**
     * Get audit logs
     */
    public function getAuditLogs(Request $request): JsonResponse
    {
        $filters = $request->only([
            'setting_key', 'user_id', 'action', 'date_from', 'date_to'
        ]);

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 20);

        $filters['limit'] = $limit;
        $filters['offset'] = ($page - 1) * $limit;

        $logs = SettingsService::getAuditLogs($filters);

        return response()->json([
            'success' => true,
            'data' => $logs,
            'pagination' => [
                'current_page' => (int) $page,
                'per_page' => $limit
            ]
        ]);
    }

    /**
     * Export settings
     */
    public function export(Request $request): JsonResponse
    {
        $category = $request->get('category');
        $group = $request->get('group');

        $query = SystemSetting::where('is_editable', true);

        if ($category) {
            $query->where('category', $category);
        }

        if ($group) {
            $query->where('group', $group);
        }

        $settings = $query->orderBy('category')
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

        return response()->json([
            'success' => true,
            'data' => $settings,
            'export_info' => [
                'exported_at' => now()->toISOString(),
                'exported_by' => auth()->user()->name ?? 'API',
                'total_settings' => $settings->count(),
                'filters' => [
                    'category' => $category,
                    'group' => $group
                ]
            ]
        ]);
    }

    /**
     * Import settings
     */
    public function import(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $importedCount = 0;
            $errors = [];

            foreach ($request->settings as $settingData) {
                $setting = SystemSetting::where('key', $settingData['key'])->first();

                if (!$setting) {
                    $errors[] = "Setting '{$settingData['key']}' not found";
                    continue;
                }

                if (!$setting->is_editable) {
                    $errors[] = "Setting '{$settingData['key']}' is not editable";
                    continue;
                }

                try {
                    $oldValue = $setting->value;
                    SettingsService::set($settingData['key'], $settingData['value'], 'api_imported');
                    $importedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Error importing '{$settingData['key']}': " . $e->getMessage();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Import completed. {$importedCount} settings imported.",
                'data' => [
                    'imported_count' => $importedCount,
                    'errors' => $errors
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error during import: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset settings to defaults
     */
    public function reset(Request $request): JsonResponse
    {
        $category = $request->get('category');
        $defaults = config('settings.defaults');

        try {
            DB::beginTransaction();

            $resetCount = 0;

            if ($category) {
                // Reset specific category
                foreach ($defaults as $key => $config) {
                    if ($config['category'] === $category) {
                        SettingsService::set($key, $config['value'], 'api_reset');
                        $resetCount++;
                    }
                }
                $message = "{$category} settings reset to defaults. {$resetCount} settings updated.";
            } else {
                // Reset all settings
                foreach ($defaults as $key => $config) {
                    SettingsService::set($key, $config['value'], 'api_reset');
                    $resetCount++;
                }
                $message = "All settings reset to defaults. {$resetCount} settings updated.";
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'reset_count' => $resetCount,
                    'category' => $category
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error resetting settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get settings statistics
     */
    public function statistics(): JsonResponse
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
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_settings' => $totalSettings,
                'editable_settings' => $editableSettings,
                'public_settings' => $publicSettings,
                'category_breakdown' => $categoryStats,
                'type_breakdown' => $typeStats,
                'recent_changes' => $recentChanges
            ]
        ]);
    }
}
