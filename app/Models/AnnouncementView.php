<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementView extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement_id',
        'member_id',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    /**
     * Get the announcement
     */
    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    /**
     * Get the member
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
