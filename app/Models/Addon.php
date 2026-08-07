<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Teklif sihirbazındaki ek modül.
 *
 * `price` boşsa modül ücretsizdir — liste fiyatı olmayan proje türlerinde
 * (mobil uygulama, özel yazılım) kapsamı anlatmaya yarar, toplama girmez.
 */
class Addon extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'project_types' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Paket formundaki "zaten dahil" seçimi için: yalnız ücretli modüller. */
    public static function pricedOptions(): array
    {
        return static::query()
            ->whereNotNull('price')
            ->orderBy('sort_order')
            ->pluck('name', 'slug')
            ->all();
    }
}
