<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Office extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        $flush = function (): void {
            Cache::forget('footer_offices');
            Cache::forget('seo_offices');
        };

        static::saved($flush);
        static::deleted($flush);
    }

    public function scopeActive(Builder $query): Builder
    {
        // Merkez her zaman başta; gerisi sıra numarasına göre.
        return $query->where('is_active', true)
            ->orderByDesc('is_primary')
            ->orderBy('sort_order');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    /** "Bellapais, Girne, Kuzey Kıbrıs" — tek satırda tam adres. */
    public function fullAddress(): string
    {
        return implode(', ', array_filter([
            $this->t('address'),
            $this->t('city'),
            $this->t('country'),
        ]));
    }

    /** Yol tarifi bağlantısı; elle girilmemişse adresten üretilir. */
    public function mapsUrl(): string
    {
        return $this->maps_url
            ?: 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($this->fullAddress());
    }
}
