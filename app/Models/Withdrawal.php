<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id', 'amount', 'status', 'bank_detail_id', 'bank_snapshot',
        'admin_note', 'processed_at', 'processed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'bank_snapshot' => 'array',
        'processed_at' => 'datetime',
    ];

    public const STATUSES = ['pending', 'approved', 'rejected', 'paid'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bankDetail(): BelongsTo
    {
        return $this->belongsTo(BankDetail::class);
    }

    public function earnings(): HasMany
    {
        return $this->hasMany(Earning::class);
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending' => 'amber',
            'approved' => 'blue',
            'paid' => 'emerald',
            'rejected' => 'red',
            default => 'gray',
        };
    }
}
