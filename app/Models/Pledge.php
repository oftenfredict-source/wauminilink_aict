<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pledge extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'pledge_amount',
        'amount_paid',
        'pledge_date',
        'due_date',
        'pledge_type',
        'payment_frequency',
        'purpose',
        'notes',
        'status',
        'recorded_by'
    ];

    protected $casts = [
        'pledge_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'pledge_date' => 'date',
        'due_date' => 'date',
    ];

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('pledge_type', $type);
    }

    public function scopeByMember($query, $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    // Accessors
    public function getRemainingAmountAttribute()
    {
        return $this->pledge_amount - $this->amount_paid;
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->pledge_amount == 0) return 0;
        return round(($this->amount_paid / $this->pledge_amount) * 100, 2);
    }

    public function getIsCompletedAttribute()
    {
        return $this->amount_paid >= $this->pledge_amount;
    }
}
