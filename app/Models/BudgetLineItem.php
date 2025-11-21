<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetLineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'item_name',
        'amount',
        'responsible_person',
        'notes',
        'order'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'order' => 'integer',
    ];

    // Relationships
    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
}
