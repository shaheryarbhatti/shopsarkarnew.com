<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'referral_code',
        'is_contributor',
        'referred_by',
        'referred_at',
        'country',
        'state',
        'city',
        'address',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_contributor' => 'boolean',
            'is_active' => 'boolean',
            'referred_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->referral_code)) {
                $user->referral_code = static::generateReferralCode();
            }
        });
    }

    public static function generateReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }

    public function referralLink(): string
    {
        return url('/?ref=' . $this->referral_code);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /** The contributor this customer is permanently attributed to. */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function bankDetails(): HasMany
    {
        return $this->hasMany(BankDetail::class);
    }

    public function earnings(): HasMany
    {
        return $this->hasMany(Earning::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function defaultBankDetail(): ?BankDetail
    {
        return $this->bankDetails()->where('is_default', true)->first()
            ?? $this->bankDetails()->latest()->first();
    }

    // ----- Earnings helpers (maturity is driven by matures_at) -----

    /** Lifetime earnings (excludes cancelled). */
    public function totalEarnings(): float
    {
        return (float) $this->earnings()->where('status', '!=', 'cancelled')->sum('amount');
    }

    /** Earnings whose maturity date has passed. */
    public function maturedEarnings(): float
    {
        return (float) $this->earnings()
            ->where('status', '!=', 'cancelled')
            ->where('matures_at', '<=', now())
            ->sum('amount');
    }

    /** Earnings still maturing (not yet withdrawable). */
    public function pendingEarnings(): float
    {
        return (float) $this->earnings()
            ->where('status', '!=', 'cancelled')
            ->where('matures_at', '>', now())
            ->sum('amount');
    }

    /** Amount tied up in requested/approved/paid withdrawals. */
    public function lockedWithdrawals(): float
    {
        return (float) $this->withdrawals()->whereIn('status', ['pending', 'approved', 'paid'])->sum('amount');
    }

    /**
     * Balance a contributor can withdraw right now:
     * matured earnings that have NOT already been paid out (status withdrawn),
     * minus any withdrawal still being processed (pending/approved).
     * Paid withdrawals are not subtracted again — the earnings they consumed
     * are already excluded by their 'withdrawn' status.
     */
    public function availableBalance(): float
    {
        $matured = (float) $this->earnings()
            ->whereNotIn('status', ['cancelled', 'withdrawn'])
            ->where('matures_at', '<=', now())
            ->sum('amount');

        $inProcess = (float) $this->withdrawals()
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');

        return max(0, $matured - $inProcess);
    }

    public function withdrawnAmount(): float
    {
        return (float) $this->withdrawals()->where('status', 'paid')->sum('amount');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('super-admin') || $this->hasRole('admin');
    }
}
