<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sermon extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'preacher',
        'date',
        'summary',
        'video_url',
        'audio_url',
        'thumbnail_url',
        'is_featured',
    ];

    protected $casts = [
        'date' => 'date',
        'is_featured' => 'boolean',
    ];
}
