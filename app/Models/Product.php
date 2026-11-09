<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'short_description', 'description',
        'price', 'discounted_price', 'contributor_price', 'thumbnail', 'stock',
        'manage_stock', 'status', 'is_featured', 'meta_title', 'meta_description',
        'views', 'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'contributor_price' => 'decimal:2',
        'manage_stock' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProductMedia::class)->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved')->latest();
    }

    /** Average star rating from approved reviews (0 if none). */
    public function getAverageRatingAttribute(): float
    {
        return round((float) $this->approvedReviews()->avg('rating'), 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return (int) $this->approvedReviews()->count();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /** Final price customer pays */
    public function getFinalPriceAttribute(): float
    {
        return (float) ($this->discounted_price && $this->discounted_price > 0
            ? $this->discounted_price
            : $this->price);
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->discounted_price && $this->discounted_price > 0 && $this->discounted_price < $this->price;
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if (! $this->has_discount) {
            return null;
        }
        return (int) round((($this->price - $this->discounted_price) / $this->price) * 100);
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return Storage::url($this->thumbnail);
        }
        return asset('images/placeholder.svg');
    }

    public function isInStock(): bool
    {
        return ! $this->manage_stock || $this->stock > 0;
    }
}
