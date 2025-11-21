<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'amount',
        'donation_date',
        'donation_type',
        'payment_method',
        'reference_number',
        'purpose',
        'notes',
        'recorded_by',
        'is_verified',
        'is_anonymous',
        'approval_status',
        'approved_by',
        'approved_at',
        'approval_notes',
        'rejection_reason'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'donation_date' => 'date',
        'is_verified' => 'boolean',
        'is_anonymous' => 'boolean',
        'approved_at' => 'datetime',
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

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
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

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('donation_date', [$startDate, $endDate]);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('donation_type', $type);
    }

    public function scopeAnonymous($query)
    {
        return $query->where('is_anonymous', true);
    }

    public function scopeFromMembers($query)
    {
        return $query->whereNotNull('member_id');
    }

    public function scopeFromNonMembers($query)
    {
        return $query->whereNull('member_id');
    }
}
