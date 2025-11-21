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
        'recorded_by',
        'approval_status',
        'approved_by',
        'approved_at',
        'approval_notes',
        'rejection_reason',
        'last_reminder_sent_at'
    ];

    protected $casts = [
        'pledge_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'pledge_date' => 'date',
        'due_date' => 'date',
        'last_reminder_sent_at' => 'datetime',
    ];

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payments()
    {
        return $this->hasMany(PledgePayment::class);
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

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('approval_status', 'rejected');
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
