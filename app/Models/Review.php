<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Review extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'author_name', 'rating', 'title', 'body',
        'video_path', 'youtube_id', 'status', 'is_verified_buyer',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified_buyer' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($q)
    {
        return $q->where('status', 'approved');
    }

    /** Public URL of the uploaded (server) video, if any. */
    public function getVideoUrlAttribute(): ?string
    {
        return $this->video_path ? Storage::url($this->video_path) : null;
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'approved' => 'emerald',
            'rejected' => 'red',
            default => 'amber',
        };
    }
}
