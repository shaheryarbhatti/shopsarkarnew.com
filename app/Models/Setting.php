<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type'];

    public $timestamps = true;

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings.all'));
        static::deleted(fn () => Cache::forget('settings.all'));
    }

    /** All settings as key => value (cached) */
    public static function all(...$args)
    {
        return Cache::rememberForever('settings.all', function () {
            return static::query()->pluck('value', 'key')->toArray();
        });
    }

    public static function get(string $key, $default = null)
    {
        $all = static::all();
        return $all[$key] ?? $default;
    }

    public static function put(string $key, $value, string $group = 'general', string $type = 'text'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value, 'group' => $group, 'type' => $type]
        );
    }

    public static function boolean(string $key, bool $default = false): bool
    {
        $val = static::get($key);
        if ($val === null) {
            return $default;
        }
        return in_array($val, ['1', 1, true, 'true', 'on'], true);
    }
}
