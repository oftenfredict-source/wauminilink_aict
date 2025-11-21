<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromiseGuest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'promised_service_date',
        'service_id',
        'status',
        'notified_at',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'promised_service_date' => 'date',
        'notified_at' => 'datetime',
    ];

    /**
     * Get the Sunday service this guest promised to attend
     */
    public function service()
    {
        return $this->belongsTo(SundayService::class, 'service_id');
    }

    /**
     * Get the user who created this promise guest record
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to get pending promise guests (not yet notified)
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get notified promise guests
     */
    public function scopeNotified($query)
    {
        return $query->where('status', 'notified');
    }

    /**
     * Scope to get promise guests for a specific service date
     */
    public function scopeForServiceDate($query, $date)
    {
        return $query->whereDate('promised_service_date', $date);
    }

    /**
     * Scope to get promise guests that need notification (1 day before service)
     */
    public function scopeNeedsNotification($query)
    {
        $tomorrow = now()->addDay()->startOfDay();
        return $query->where('status', 'pending')
            ->whereDate('promised_service_date', $tomorrow)
            ->whereNull('notified_at');
    }
}




