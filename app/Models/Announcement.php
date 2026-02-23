<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'target_type',
        'start_date',
        'end_date',
        'is_active',
        'is_pinned',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    /**
     * Scope a query to only include announcements targeted for a specific member or all members.
     */
    public function scopeTargetedFor($query, $memberId = null)
    {
        return $query->where(function ($q) use ($memberId) {
            $q->where('target_type', 'all');

            if ($memberId) {
                $q->orWhere(function ($specific) use ($memberId) {
                    $specific->where('target_type', 'specific')
                        ->whereHas('targetedMembers', function ($targeted) use ($memberId) {
                            $targeted->where('members.id', $memberId);
                        });
                });
            }
        });
    }

    /**
     * Get the members targeted by this announcement (if target_type is specific)
     */
    public function targetedMembers()
    {
        return $this->belongsToMany(Member::class, 'announcement_member')
            ->withTimestamps();
    }

    /**
     * Get the user who created this announcement
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all views for this announcement
     */
    public function views()
    {
        return $this->hasMany(AnnouncementView::class);
    }

    /**
     * Get members who have viewed this announcement
     */
    public function viewedByMembers()
    {
        return $this->belongsToMany(Member::class, 'announcement_views')
            ->withPivot('viewed_at')
            ->withTimestamps();
    }

    /**
     * Check if a specific member has viewed this announcement
     */
    public function isViewedBy($memberId)
    {
        return $this->views()->where('member_id', $memberId)->exists();
    }

    /**
     * Scope to get active announcements
     */
    public function scopeActive($query)
    {
        $now = Carbon::now()->toDateString();
        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>', $now);
            });
    }

    /**
     * Scope to get pinned announcements
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Check if announcement is currently active
     */
    public function isCurrentlyActive()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now()->toDateString();

        if ($this->start_date && $this->start_date->format('Y-m-d') > $now) {
            return false;
        }

        if ($this->end_date && $this->end_date->format('Y-m-d') <= $now) {
            return false;
        }

        return true;
    }
}
