<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'can_approve_finances',
        'profile_picture',
        'phone_number',
        'login_blocked_until',
        'member_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'can_approve_finances' => 'boolean',
        'login_blocked_until' => 'datetime',
    ];

    // Helper methods for roles and permissions
    public function isPastor()
    {
        return $this->role === 'pastor' || $this->can_approve_finances;
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTreasurer()
    {
        return $this->role === 'treasurer';
    }

    public function canApproveFinances()
    {
        return $this->can_approve_finances || $this->isPastor() || $this->isAdmin();
    }

    public function canManageLeadership()
    {
        return $this->isPastor() || $this->role === 'secretary' || $this->isAdmin();
    }

    public function isSecretary()
    {
        return $this->role === 'secretary';
    }

    public function isMember()
    {
        return $this->role === 'member';
    }

    /**
     * Get the member associated with this user
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permissionSlug): bool
    {
        // Admin has all permissions
        if ($this->isAdmin()) {
            return true;
        }

        return \DB::table('role_permissions')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_permissions.role', $this->role)
            ->where('permissions.slug', $permissionSlug)
            ->exists();
    }

    /**
     * Get all permissions for this user's role
     */
    public function getPermissions()
    {
        return \DB::table('permissions')
            ->join('role_permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->where('role_permissions.role', $this->role)
            ->select('permissions.*')
            ->get();
    }

    /**
     * Get activity logs for this user
     */
    public function activityLogs()
    {
        return $this->hasMany(\App\Models\ActivityLog::class);
    }

    /**
     * Get active sessions for this user
     */
    public function activeSessions()
    {
        return \DB::table('sessions')
            ->where('user_id', $this->id)
            ->where('last_activity', '>', now()->subHours(24)->timestamp)
            ->get();
    }

    /**
     * Check if user is currently blocked from logging in
     */
    public function isLoginBlocked(): bool
    {
        return $this->login_blocked_until && $this->login_blocked_until->isFuture();
    }

    /**
     * Get remaining block time in minutes
     */
    public function getRemainingBlockTime(): ?int
    {
        if (!$this->isLoginBlocked()) {
            return null;
        }
        
        return now()->diffInMinutes($this->login_blocked_until);
    }
}
