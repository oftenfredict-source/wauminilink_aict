<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'budget_id',
        'expense_category',
        'expense_name',
        'amount',
        'expense_date',
        'payment_method',
        'reference_number',
        'description',
        'vendor',
        'receipt_number',
        'status',
        'approved_by',
        'approved_date',
        'notes',
        'recorded_by',
        'approval_status',
        'pastor_approved_by',
        'pastor_approved_at',
        'approval_notes',
        'rejection_reason'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'approved_date' => 'date',
        'pastor_approved_at' => 'datetime',
    ];

    // Relationships
    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function pastorApprover()
    {
        return $this->belongsTo(User::class, 'pastor_approved_by');
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

    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeApprovedByPastor($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeRejectedByPastor($query)
    {
        return $query->where('approval_status', 'rejected');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('expense_category', $category);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('expense_date', [$startDate, $endDate]);
    }
}