<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_type',
        'service_id',
        'member_id',
        'child_id',
        'attended_at',
        'recorded_by',
        'notes',
    ];

    protected $casts = [
        'attended_at' => 'datetime',
    ];

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function sundayService()
    {
        return $this->belongsTo(SundayService::class, 'service_id');
    }

    public function specialEvent()
    {
        return $this->belongsTo(SpecialEvent::class, 'service_id');
    }

    /**
     * Get the attendee (either member or child)
     */
    public function attendee()
    {
        if ($this->member_id) {
            return $this->member;
        } elseif ($this->child_id) {
            return $this->child;
        }
        return null;
    }

    /**
     * Get the attendee's name
     */
    public function getAttendeeNameAttribute()
    {
        if ($this->member_id) {
            return $this->member ? $this->member->full_name : 'Unknown Member';
        } elseif ($this->child_id) {
            return $this->child ? $this->child->full_name : 'Unknown Child';
        }
        return 'Unknown';
    }

    /**
     * Check if this attendance is for a child
     */
    public function isChildAttendance()
    {
        return !is_null($this->child_id);
    }

    /**
     * Check if this attendance is for a member
     */
    public function isMemberAttendance()
    {
        return !is_null($this->member_id);
    }

    // Scopes
    public function scopeForService($query, $serviceType, $serviceId)
    {
        return $query->where('service_type', $serviceType)
                    ->where('service_id', $serviceId);
    }

    public function scopeByMember($query, $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    public function scopeByChild($query, $childId)
    {
        return $query->where('child_id', $childId);
    }

    public function scopeMembersOnly($query)
    {
        return $query->whereNotNull('member_id');
    }

    public function scopeChildrenOnly($query)
    {
        return $query->whereNotNull('child_id');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('attended_at', [$startDate, $endDate]);
    }

    public function scopeSundayServices($query)
    {
        return $query->where('service_type', 'sunday_service');
    }

    public function scopeSpecialEvents($query)
    {
        return $query->where('service_type', 'special_event');
    }

    // Helper methods
    public function getServiceAttribute()
    {
        if ($this->service_type === 'sunday_service') {
            return $this->sundayService;
        } elseif ($this->service_type === 'special_event') {
            return $this->specialEvent;
        }
        return null;
    }

    // Get the appropriate service based on service_type
    public function getService()
    {
        if ($this->service_type === 'sunday_service') {
            return $this->sundayService;
        } elseif ($this->service_type === 'special_event') {
            return $this->specialEvent;
        }
        return null;
    }
}
