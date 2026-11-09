<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankDetail extends Model
{
    protected $fillable = [
        'user_id', 'type', 'account_number', 'account_title', 'bank_name', 'iban', 'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public const TYPES = ['easypaisa', 'jazzcash', 'bank'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function label(): string
    {
        return match ($this->type) {
            'easypaisa' => 'EasyPaisa',
            'jazzcash' => 'JazzCash',
            'bank' => 'Bank Account',
            default => ucfirst($this->type),
        };
    }

    public function summary(): string
    {
        if ($this->type === 'bank') {
            return trim(($this->bank_name ?? '') . ' — ' . ($this->iban ?? ''), ' —');
        }
        return $this->label() . ' — ' . ($this->account_number ?? '');
    }
}
