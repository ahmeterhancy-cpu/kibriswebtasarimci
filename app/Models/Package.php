<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'features_en' => 'array',
            'is_popular' => 'boolean',
            'is_ecommerce' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /** "6.900 ₺" — binlik ayracı Türkçe biçimde. */
    public function formatPrice(?int $amount): ?string
    {
        if ($amount === null) {
            return null;
        }

        return number_format($amount, 0, ',', '.').' '.$this->currency;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
