<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeeklyAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'leader_id',
        'week_start_date',
        'week_end_date',
        'position',
        'duties',
        'notes',
        'assigned_by',
        'is_active',
    ];

    protected $casts = [
        'week_start_date' => 'date',
        'week_end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the leader for this assignment
     */
    public function leader()
    {
        return $this->belongsTo(Leader::class);
    }

    /**
     * Get the user who assigned this
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Scope for active assignments
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for current week
     */
    public function scopeCurrentWeek($query)
    {
        $today = now()->toDateString();
        return $query->where('week_start_date', '<=', $today)
                    ->where('week_end_date', '>=', $today);
    }

    /**
     * Scope for specific position
     */
    public function scopePosition($query, $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Get position display name
     */
    public function getPositionDisplayAttribute()
    {
        $positions = [
            'pastor' => 'Mchungaji',
            'assistant_pastor' => 'Msaidizi wa Mchungaji',
            'secretary' => 'Katibu',
            'assistant_secretary' => 'Msaidizi wa Katibu',
            'treasurer' => 'Mweka Hazina',
            'assistant_treasurer' => 'Msaidizi wa Mweka Hazina',
            'elder' => 'Mzee wa Kanisa',
            'deacon' => 'Shamashi',
            'deaconess' => 'Shamasha',
            'youth_leader' => 'Kiongozi wa Vijana',
            'children_leader' => 'Kiongozi wa Watoto',
            'worship_leader' => 'Kiongozi wa Ibada',
            'choir_leader' => 'Kiongozi wa Kwaya',
            'usher_leader' => 'Kiongozi wa Wakaribishaji',
            'evangelism_leader' => 'Kiongozi wa Uinjilisti',
            'prayer_leader' => 'Kiongozi wa Maombi',
            'other' => 'Kiongozi'
        ];

        return $positions[$this->position] ?? ucfirst(str_replace('_', ' ', $this->position));
    }
}
