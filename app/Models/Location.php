<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Location extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public const REGIONS = [
        'kktc' => 'Kuzey Kıbrıs',
        'turkiye' => 'Türkiye',
    ];

    /**
     * Footer bağlantıları ve yapısal veri şehir listesi önbellekli tutuluyor.
     * Panelden bir şehir eklenip yayına alındığında sitede aynı anda görünsün
     * diye kayıt değiştikçe önbelleği düşürüyoruz.
     */
    protected static function booted(): void
    {
        $flush = function (): void {
            Cache::forget('footer_locations');
            Cache::forget('seo_area_served');
        };

        static::saved($flush);
        static::deleted($flush);
    }

    protected function casts(): array
    {
        return [
            'highlights' => 'array',
            'highlights_en' => 'array',
            'latitude' => 'float',
            'longitude' => 'float',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Bu şehirdeki ofis. Bağlıysa sayfa "buradayız" der ve yapısal veriye
     * gerçek adres/koordinat basılır; bağlı değilse yalnız `areaServed`.
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function hasOffice(): bool
    {
        return $this->office_id !== null;
    }

    public function scopeRegion(Builder $query, string $region): Builder
    {
        return $query->where('region', $region);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function regionLabel(): string
    {
        if (app()->getLocale() === 'en') {
            return $this->region === 'turkiye' ? 'Türkiye' : 'North Cyprus';
        }

        return self::REGIONS[$this->region] ?? $this->region;
    }
}
