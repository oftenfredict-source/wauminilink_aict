<?php

namespace App\Services;

use App\Models\SystemSetting;
use App\Models\SettingAuditLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class SettingsService
{
    /**
     * Get a setting value with caching
     */
    public static function get($key, $default = null)
    {
        return SystemSetting::getValue($key, $default);
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value, $action = 'updated')
    {
        $setting = SystemSetting::where('key', $key)->first();
        if ($setting) {
            $oldValue = $setting->value;
            $result = SystemSetting::setValue($key, $value, $setting->type);
            
            // Log the change
            self::logChange($key, $oldValue, $value, $action);
            
            return $result;
        }
        return false;
    }

    /**
     * Bulk update settings
     */
    public static function bulkUpdate(array $settings, $action = 'bulk_updated')
    {
        $changes = [];
        
        foreach ($settings as $key => $value) {
            $setting = SystemSetting::where('key', $key)->first();
            if ($setting) {
                $oldValue = $setting->value;
                SystemSetting::setValue($key, $value, $setting->type);
                $changes[] = [
                    'key' => $key,
                    'old_value' => $oldValue,
                    'new_value' => $value
                ];
            }
        }
        
        // Log bulk changes
        self::logBulkChange($changes, $action);
        
        return count($changes);
    }

    /**
     * Log setting change
     */
    public static function logChange($key, $oldValue, $newValue, $action = 'updated', $metadata = [])
    {
        SettingAuditLog::create([
            'setting_key' => $key,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'action' => $action,
            'user_id' => Auth::id(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'metadata' => $metadata
        ]);
    }

    /**
     * Log bulk changes
     */
    public static function logBulkChange(array $changes, $action = 'bulk_updated', $metadata = [])
    {
        SettingAuditLog::create([
            'setting_key' => 'multiple',
            'old_value' => null,
            'new_value' => null,
            'action' => $action,
            'user_id' => Auth::id(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'metadata' => array_merge($metadata, ['changes' => $changes])
        ]);
    }

    /**
     * Get audit logs
     */
    public static function getAuditLogs($filters = [])
    {
        $query = SettingAuditLog::with('user')->orderBy('created_at', 'desc');

        if (isset($filters['setting_key'])) {
            $query->where('setting_key', $filters['setting_key']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['limit'])) {
            $query->limit($filters['limit']);
        }

        return $query->get();
    }

    /**
     * Get all settings by category
     */
    public static function getCategory($category)
    {
        return SystemSetting::getByCategory($category);
    }

    /**
     * Get church information
     */
    public static function getChurchInfo()
    {
        return [
            'name' => self::get('church_name', 'Waumini Church'),
            'address' => self::get('church_address', ''),
            'phone' => self::get('church_phone', ''),
            'email' => self::get('church_email', ''),
        ];
    }

    /**
     * Get membership settings
     */
    public static function getMembershipSettings()
    {
        return [
            'child_max_age' => self::get('child_max_age', 18),
            'age_reference' => self::get('age_reference', 'today'),
            'auto_generate_member_id' => self::get('auto_generate_member_id', true),
            'member_id_prefix' => self::get('member_id_prefix', 'WM'),
            'require_phone_verification' => self::get('require_phone_verification', false),
            'allow_duplicate_phone' => self::get('allow_duplicate_phone', false),
        ];
    }

    /**
     * Get finance settings
     */
    public static function getFinanceSettings()
    {
        return [
            'enable_tithes' => self::get('enable_tithes', true),
            'enable_offerings' => self::get('enable_offerings', true),
            'enable_donations' => self::get('enable_donations', true),
            'enable_pledges' => self::get('enable_pledges', true),
            'enable_budgets' => self::get('enable_budgets', true),
            'enable_expenses' => self::get('enable_expenses', true),
            'require_expense_approval' => self::get('require_expense_approval', true),
            'expense_approval_threshold' => self::get('expense_approval_threshold', 100000),
            'auto_generate_receipts' => self::get('auto_generate_receipts', true),
        ];
    }

    /**
     * Get notification settings
     */
    public static function getNotificationSettings()
    {
        return [
            'enable_email_notifications' => self::get('enable_email_notifications', true),
            'enable_sms_notifications' => self::get('enable_sms_notifications', false),
            'notification_email' => self::get('notification_email', ''),
            'notification_sms_provider' => self::get('notification_sms_provider', 'local'),
            'celebrations_notification_days' => self::get('celebrations_notification_days', 7),
            'events_notification_days' => self::get('events_notification_days', 3),
        ];
    }

    /**
     * Get security settings
     */
    public static function getSecuritySettings()
    {
        return [
            'session_timeout' => self::get('session_timeout', 120),
            'require_password_change' => self::get('require_password_change', false),
            'password_min_length' => self::get('password_min_length', 8),
            'max_login_attempts' => self::get('max_login_attempts', 5),
            'lockout_duration' => self::get('lockout_duration', 15),
        ];
    }

    /**
     * Get appearance settings
     */
    public static function getAppearanceSettings()
    {
        return [
            'theme_color' => self::get('theme_color', 'primary'),
            'sidebar_style' => self::get('sidebar_style', 'dark'),
            'show_member_photos' => self::get('show_member_photos', true),
            'items_per_page' => self::get('items_per_page', 25),
        ];
    }

    /**
     * Get general settings
     */
    public static function getGeneralSettings()
    {
        return [
            'app_name' => self::get('app_name', 'Waumini Link'),
            'app_version' => self::get('app_version', '1.0.0'),
            'timezone' => self::get('timezone', 'Africa/Dar_es_Salaam'),
            'date_format' => self::get('date_format', 'd/m/Y'),
            'currency' => self::get('currency', 'TZS'),
        ];
    }

    /**
     * Check if a feature is enabled
     */
    public static function isFeatureEnabled($feature)
    {
        return self::get($feature, false);
    }

    /**
     * Get currency symbol
     */
    public static function getCurrencySymbol()
    {
        $currency = self::get('currency', 'TZS');
        $symbols = [
            'TZS' => 'TSh',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
        ];
        return $symbols[$currency] ?? $currency;
    }

    /**
     * Get formatted date
     */
    public static function formatDate($date, $format = null)
    {
        $dateFormat = $format ?? self::get('date_format', 'd/m/Y');
        return $date->format($dateFormat);
    }

    /**
     * Get formatted currency
     */
    public static function formatCurrency($amount)
    {
        $currency = self::get('currency', 'TZS');
        $symbol = self::getCurrencySymbol();
        
        if ($currency === 'TZS') {
            return $symbol . ' ' . number_format($amount, 0, '.', ',');
        }
        
        return $symbol . ' ' . number_format($amount, 2, '.', ',');
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        SystemSetting::clearCache();
    }
}
