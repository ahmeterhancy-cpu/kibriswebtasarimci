<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Sector extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'needs' => 'array',
            'needs_en' => 'array',
            'features' => 'array',
            'features_en' => 'array',
            'faq' => 'array',
            'faq_en' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /** Footer bağlantıları önbellekli; kayıt değişince düşürülür. */
    protected static function booted(): void
    {
        $flush = fn () => Cache::forget('footer_sectors');

        static::saved($flush);
        static::deleted($flush);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
