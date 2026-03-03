<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'head_id',
        'status',
    ];

    /**
     * Get the member who is the head of the department.
     */
    public function head()
    {
        return $this->belongsTo(Member::class, 'head_id');
    }

    /**
     * Get all members assigned to this department.
     */
    public function members()
    {
        return $this->belongsToMany(Member::class, 'department_member')
            ->withPivot('role')
            ->withTimestamps();
    }
}

