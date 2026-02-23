<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'year',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'recorded_by',
        'approval_status',
        'approved_by',
        'approved_at',
        'notes',
        'category',
        'child_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function child()
    {
        return $this->belongsTo(Child::class);
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

    public function scopeYear($query, $year)
    {
        return $query->where('year', $year);
    }
}
