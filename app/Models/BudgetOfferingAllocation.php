<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetOfferingAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'offering_type',
        'allocated_amount',
        'used_amount',
        'available_amount',
        'is_primary',
        'notes'
    ];

    protected $casts = [
        'allocated_amount' => 'decimal:2',
        'used_amount' => 'decimal:2',
        'available_amount' => 'decimal:2',
        'is_primary' => 'boolean',
    ];

    // Relationships
    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    // Accessors
    public function getRemainingAmountAttribute()
    {
        return $this->allocated_amount - $this->used_amount;
    }

    public function getUtilizationPercentageAttribute()
    {
        if ($this->allocated_amount == 0) return 0;
        return round(($this->used_amount / $this->allocated_amount) * 100, 2);
    }

    public function getIsFullyUtilizedAttribute()
    {
        return $this->used_amount >= $this->allocated_amount;
    }

    // Scopes
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeByOfferingType($query, $offeringType)
    {
        return $query->where('offering_type', $offeringType);
    }

    public function scopeWithRemainingFunds($query)
    {
        return $query->whereRaw('allocated_amount > used_amount');
    }
}