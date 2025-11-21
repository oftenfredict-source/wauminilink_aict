<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'category',
        'group',
        'description',
        'is_editable',
        'is_public',
        'validation_rules',
        'options',
        'sort_order'
    ];

    protected $casts = [
        'is_editable' => 'boolean',
        'is_public' => 'boolean',
        'validation_rules' => 'array',
        'options' => 'array',
        'sort_order' => 'integer'
    ];

    /**
     * Get a setting value by key with caching
     */
    public static function getValue($key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? static::castValue($setting->value, $setting->type) : $default;
        });
    }

    /**
     * Set a setting value and clear cache
     */
    public static function setValue($key, $value, $type = 'string')
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'updated_at' => now()
            ]
        );
        
        Cache::forget("setting.{$key}");
        return $setting;
    }

    /**
     * Get all settings by category
     */
    public static function getByCategory($category)
    {
        return Cache::remember("settings.category.{$category}", 3600, function () use ($category) {
            return static::where('category', $category)
                ->where('is_editable', true)
                ->orderBy('sort_order')
                ->orderBy('key')
                ->get()
                ->mapWithKeys(function ($setting) {
                    return [$setting->key => static::castValue($setting->value, $setting->type)];
                });
        });
    }

    /**
     * Get settings grouped by category and group
     */
    public static function getGroupedSettings()
    {
        try {
            return Cache::remember('settings.grouped', 3600, function () {
                return static::where('is_editable', true)
                    ->orderBy('category')
                    ->orderBy('group')
                    ->orderBy('sort_order')
                    ->orderBy('key')
                    ->get()
                    ->groupBy(['category', 'group'])
                    ->map(function ($categories) {
                        return $categories->map(function ($groups) {
                            return $groups->map(function ($setting) {
                                try {
                                    $setting->value = static::castValue($setting->value, $setting->type);
                                } catch (\Exception $e) {
                                    // If casting fails, use raw value
                                    \Log::warning("Failed to cast setting value for {$setting->key}: " . $e->getMessage());
                                }
                                return $setting;
                            });
                        });
                    });
            });
        } catch (\Exception $e) {
            \Log::error("Error loading grouped settings: " . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Cast value based on type
     */
    private static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'json':
                return json_decode($value, true);
            case 'array':
                return is_string($value) ? json_decode($value, true) : $value;
            default:
                return $value;
        }
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        Cache::forget('settings.grouped');
        $keys = static::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("setting.{$key}");
        }
        foreach (['general', 'membership', 'finance', 'notifications', 'security', 'appearance'] as $category) {
            Cache::forget("settings.category.{$category}");
        }
    }

    /**
     * Boot method to clear cache on model changes
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            static::clearCache();
        });

        static::deleted(function () {
            static::clearCache();
        });
    }
}