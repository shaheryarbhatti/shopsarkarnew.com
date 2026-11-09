<?php

use App\Models\Setting;
use App\Services\CartService;
use Illuminate\Support\Facades\Storage;

if (! function_exists('cart')) {
    function cart(): CartService
    {
        return app(CartService::class);
    }
}

if (! function_exists('setting')) {
    /**
     * Get a setting value (optionally with default).
     */
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('setting_bool')) {
    function setting_bool(string $key, bool $default = false): bool
    {
        return Setting::boolean($key, $default);
    }
}

if (! function_exists('money')) {
    /**
     * Format an amount with the store currency symbol.
     */
    function money($amount): string
    {
        $symbol = setting('currency_symbol', 'Rs.');
        return $symbol . ' ' . number_format((float) $amount, 0);
    }
}

if (! function_exists('acl_permissions')) {
    /**
     * Flat list of every permission key defined in config/acl.php.
     */
    function acl_permissions(): array
    {
        $keys = [];
        foreach (config('acl.groups', []) as $group) {
            foreach (array_keys($group['permissions'] ?? []) as $key) {
                $keys[] = $key;
            }
        }
        return $keys;
    }
}

if (! function_exists('setting_file')) {
    /**
     * Resolve a stored file setting to a public URL.
     */
    function setting_file(string $key, ?string $default = null): ?string
    {
        $value = setting($key);
        if (! $value) {
            return $default;
        }
        return Storage::url($value);
    }
}
