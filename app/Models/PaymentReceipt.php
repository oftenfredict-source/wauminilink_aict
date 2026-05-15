<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'receipt_type',
        'amount',
        'reference_number',
        'file_path',
        'status',
        'notes',
        'uploaded_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'uploaded_at' => 'datetime'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
