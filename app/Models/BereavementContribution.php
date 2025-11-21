<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BereavementContribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'bereavement_event_id',
        'member_id',
        'has_contributed',
        'contribution_amount',
        'amount', // Support both column names
        'contribution_date',
        'contribution_type',
        'payment_method',
        'reference_number',
        'notes',
        'recorded_by',
        'contributor_name',
        'contributor_phone',
    ];

    protected $casts = [
        'has_contributed' => 'boolean',
        'contribution_amount' => 'decimal:2',
        'amount' => 'decimal:2',
        'contribution_date' => 'date',
        'is_verified' => 'boolean',
        'approved_at' => 'datetime',
    ];

    /**
     * Accessor for contribution_amount - use amount (since table uses 'amount' column)
     */
    public function getContributionAmountAttribute()
    {
        return $this->attributes['amount'] ?? 0;
    }

    /**
     * Mutator to set amount when contribution_amount is set
     */
    public function setContributionAmountAttribute($value)
    {
        $this->attributes['amount'] = $value;
    }

    // Relationships
    public function bereavementEvent()
    {
        return $this->belongsTo(BereavementEvent::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Scopes
    public function scopeContributed($query)
    {
        return $query->where('has_contributed', true);
    }

    public function scopeNotContributed($query)
    {
        return $query->where('has_contributed', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('contribution_type', $type);
    }
}

