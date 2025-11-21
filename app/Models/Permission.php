<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
    ];

    /**
     * Check if a role has this permission
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('role', $role)->exists();
    }

    /**
     * Get all roles that have this permission
     */
    public function roles()
    {
        return $this->hasMany(\App\Models\RolePermission::class);
    }
}

