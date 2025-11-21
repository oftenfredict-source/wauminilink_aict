<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_date','title','speaker','start_time','end_time','venue',
        'attendance_count','budget_amount','category','description','notes'
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
        'attendance_count' => 'integer',
        'budget_amount' => 'decimal:2',
    ];

    /**
     * Get attendance records for this event
     */
    public function attendances()
    {
        return $this->hasMany(ServiceAttendance::class, 'service_id')
            ->where('service_type', 'special_event');
    }

    /**
     * Get members who attended this event
     */
    public function attendingMembers()
    {
        return $this->belongsToMany(Member::class, 'service_attendances', 'service_id', 'member_id')
            ->wherePivot('service_type', 'special_event')
            ->withPivot('attended_at', 'recorded_by', 'notes')
            ->withTimestamps();
    }
}



