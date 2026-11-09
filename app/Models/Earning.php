<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Earning extends Model
{
    protected $fillable = [
        'user_id', 'order_id', 'order_item_id', 'product_id',
        'amount', 'status', 'matures_at', 'withdrawal_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'matures_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function withdrawal(): BelongsTo
    {
        return $this->belongsTo(Withdrawal::class);
    }

    public function scopeMature($q)
    {
        return $q->where('status', 'mature');
    }
}
