<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductMedia extends Model
{
    protected $table = 'product_media';

    protected $fillable = [
        'product_id', 'type', 'file_path', 'url', 'poster_path', 'sort_order',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /** Resolved poster/thumbnail URL for an uploaded video (null when not set). */
    public function getPosterUrlAttribute(): ?string
    {
        return $this->poster_path ? Storage::url($this->poster_path) : null;
    }

    /** Resolved source URL for front-end */
    public function getSrcAttribute(): string
    {
        if ($this->type === 'youtube') {
            return $this->url ?? '';
        }
        return $this->file_path ? Storage::url($this->file_path) : '';
    }

    /** YouTube embed id */
    public function getYoutubeIdAttribute(): ?string
    {
        if ($this->type !== 'youtube' || ! $this->url) {
            return null;
        }
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->url, $m);
        return $m[1] ?? null;
    }

    public function getIsVideoAttribute(): bool
    {
        return in_array($this->type, ['video', 'youtube']);
    }
}
