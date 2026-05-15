<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'type',
        'preferred_date',
        'description',
        'status',
        'admin_notes',
        'scheduled_at',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'scheduled_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
