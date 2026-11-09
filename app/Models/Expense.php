<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'title', 'category', 'amount', 'note', 'spent_on', 'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'spent_on' => 'date',
    ];

    public const CATEGORIES = [
        'Marketing', 'Salaries', 'Packaging', 'Shipping',
        'Inventory', 'Rent', 'Utilities', 'Other',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
