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

if (! function_exists('page_content')) {
    /**
     * Render CMS page content, replacing {{ token }} placeholders with live
     * store settings so contact details etc. stay in sync with Settings.
     *
     * Supported tokens: contact_email, contact_phone, site_name, currency_symbol.
     */
    function page_content(?string $content): string
    {
        if (! $content) {
            return '';
        }

        $tokens = [
            'contact_email'   => (string) setting('contact_email'),
            'contact_phone'   => (string) setting('contact_phone'),
            'site_name'       => (string) setting('site_name', 'Shop'),
            'currency_symbol' => (string) setting('currency_symbol', 'Rs.'),
        ];

        return preg_replace_callback('/\{\{\s*([a-z_]+)\s*\}\}/i', function ($m) use ($tokens) {
            $key = strtolower($m[1]);
            // Unknown token → leave it as-is so nothing silently disappears.
            return array_key_exists($key, $tokens) ? e($tokens[$key]) : $m[0];
        }, $content);
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
