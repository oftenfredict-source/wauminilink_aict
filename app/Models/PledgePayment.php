<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PledgePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pledge_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'notes',
        'recorded_by',
        'approval_status',
        'approved_by',
        'approved_at',
        'approval_notes',
        'rejection_reason'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    // Relationships
    public function pledge()
    {
        return $this->belongsTo(Pledge::class);
    }

    public function member()
    {
        return $this->hasOneThrough(Member::class, Pledge::class, 'id', 'id', 'pledge_id', 'member_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
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
}
