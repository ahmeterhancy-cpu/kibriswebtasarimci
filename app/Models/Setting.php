<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Dile duyarlı, önbellekli site ayarı.
 *
 * `value` Türkçe, `value_en` İngilizce. EN aktifse ve karşılığı doluysa o
 * dönülür; aksi halde Türkçesine düşülür.
 */
class Setting extends Model
{
    protected $guarded = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        $locale = app()->getLocale();

        $value = Cache::rememberForever("setting_{$key}_{$locale}", function () use ($key, $locale) {
            $setting = static::query()->where('key', $key)->first();

            if (! $setting) {
                return null;
            }

            if ($locale === 'en' && filled($setting->value_en)) {
                return $setting->value_en;
            }

            return $setting->value;
        });

        return filled($value) ? $value : $default;
    }

    /** Admin formunda dil başına ham değer okumak için. */
    public static function getRaw(string $key, string $locale = 'tr', mixed $default = null): mixed
    {
        $setting = static::query()->where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        return $locale === 'en' ? ($setting->value_en ?? $default) : ($setting->value ?? $default);
    }

    public static function set(string $key, mixed $value, string $group = 'general', ?string $valueEn = null): void
    {
        $payload = ['value' => $value, 'group' => $group];

        if (func_num_args() >= 4) {
            $payload['value_en'] = $valueEn;
        }

        static::query()->updateOrCreate(['key' => $key], $payload);

        static::forget($key);
    }

    public static function forget(string $key): void
    {
        Cache::forget("setting_{$key}_tr");
        Cache::forget("setting_{$key}_en");
    }

    protected static function booted(): void
    {
        static::saved(fn (self $setting) => static::forget($setting->key));
        static::deleted(fn (self $setting) => static::forget($setting->key));
    }
}
