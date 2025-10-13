<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SettingAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'old_value',
        'new_value',
        'action',
        'user_id',
        'ip_address',
        'user_agent',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user who made the change
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the setting that was changed
     */
    public function setting()
    {
        return SystemSetting::where('key', $this->setting_key)->first();
    }

    /**
     * Get formatted action description
     */
    public function getActionDescriptionAttribute(): string
    {
        $actions = [
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'reset' => 'Reset to Default',
            'imported' => 'Imported',
            'exported' => 'Exported',
            'bulk_updated' => 'Bulk Updated'
        ];

        return $actions[$this->action] ?? ucfirst($this->action);
    }

    /**
     * Get human-readable change description
     */
    public function getChangeDescriptionAttribute(): string
    {
        $setting = $this->setting();
        $settingName = $setting ? ucwords(str_replace('_', ' ', $setting->key)) : $this->setting_key;

        switch ($this->action) {
            case 'created':
                return "Setting '{$settingName}' was created with value: {$this->new_value}";
            case 'updated':
                return "Setting '{$settingName}' changed from '{$this->old_value}' to '{$this->new_value}'";
            case 'deleted':
                return "Setting '{$settingName}' was deleted";
            case 'reset':
                return "Setting '{$settingName}' was reset to default value: {$this->new_value}";
            case 'imported':
                return "Setting '{$settingName}' was imported with value: {$this->new_value}";
            case 'exported':
                return "Settings were exported";
            case 'bulk_updated':
                return "Multiple settings were updated";
            default:
                return "Setting '{$settingName}' was {$this->action}";
        }
    }

    /**
     * Scope for recent changes
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for specific setting
     */
    public function scopeForSetting($query, $key)
    {
        return $query->where('setting_key', $key);
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for specific action
     */
    public function scopeForAction($query, $action)
    {
        return $query->where('action', $action);
    }
}
