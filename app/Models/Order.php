<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'contributor_id', 'ref_code',
        'customer_name', 'email', 'phone', 'country', 'state', 'city', 'address', 'note',
        'payment_method', 'payment_status', 'status',
        'subtotal', 'shipping_amount', 'tax_amount', 'discount', 'total', 'currency',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public const STATUSES = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contributor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contributor_id');
    }

    public function earnings(): HasMany
    {
        return $this->hasMany(Earning::class);
    }

    public static function generateOrderNumber(): string
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending' => 'amber',
            'processing' => 'blue',
            'shipped' => 'indigo',
            'completed' => 'emerald',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}
