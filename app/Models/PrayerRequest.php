<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrayerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'subject',
        'content',
        'is_anonymous',
        'status',
        'pastor_note',
    ];

    /**
     * Get the member who sent the prayer request.
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
