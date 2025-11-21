<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundingRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'budget_id',
        'requested_amount',
        'available_amount',
        'shortfall_amount',
        'reason',
        'suggested_allocations',
        'status',
        'approval_notes',
        'approved_by',
        'approved_at',
        'rejection_reason'
    ];

    protected $casts = [
        'requested_amount' => 'decimal:2',
        'available_amount' => 'decimal:2',
        'shortfall_amount' => 'decimal:2',
        'suggested_allocations' => 'array',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Accessors
    public function getShortfallPercentageAttribute()
    {
        if ($this->requested_amount == 0) return 0;
        return round(($this->shortfall_amount / $this->requested_amount) * 100, 2);
    }

    public function getIsUrgentAttribute()
    {
        return $this->shortfall_percentage > 50; // More than 50% shortfall is urgent
    }
}