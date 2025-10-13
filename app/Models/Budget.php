<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_name',
        'budget_type',
        'fiscal_year',
        'start_date',
        'end_date',
        'total_budget',
        'allocated_amount',
        'spent_amount',
        'description',
        'status',
        'created_by'
    ];

    protected $casts = [
        'total_budget' => 'decimal:2',
        'allocated_amount' => 'decimal:2',
        'spent_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'fiscal_year' => 'integer',
    ];

    // Relationships
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByFiscalYear($query, $year)
    {
        return $query->where('fiscal_year', $year);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('budget_type', $type);
    }

    public function scopeCurrent($query)
    {
        $now = now();
        return $query->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now)
                    ->where('status', 'active');
    }

    // Accessors
    public function getRemainingAmountAttribute()
    {
        return $this->total_budget - $this->spent_amount;
    }

    public function getUtilizationPercentageAttribute()
    {
        if ($this->total_budget == 0) return 0;
        return round(($this->spent_amount / $this->total_budget) * 100, 2);
    }

    public function getIsOverBudgetAttribute()
    {
        return $this->spent_amount > $this->total_budget;
    }

    public function getIsNearLimitAttribute()
    {
        $threshold = $this->total_budget * 0.9; // 90% threshold
        return $this->spent_amount >= $threshold && $this->spent_amount < $this->total_budget;
    }
}
