<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tithe extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'amount',
        'tithe_date',
        'payment_method',
        'reference_number',
        'notes',
        'recorded_by',
        'is_verified'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tithe_date' => 'date',
        'is_verified' => 'boolean',
    ];

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tithe_date', [$startDate, $endDate]);
    }

    public function scopeByMember($query, $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }
}
