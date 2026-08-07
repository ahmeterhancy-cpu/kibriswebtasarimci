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

    /** Paket formundaki "bu pakette sunulacak" seçimi: fiyatlı-fiyatsız hepsi. */
    public static function options(): array
    {
        return static::query()
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(fn (self $a) => [
                $a->slug => $a->price
                    ? $a->name.' — '.number_format($a->price, 0, ',', '.').' ₺'
                    : $a->name.' — ücretsiz',
            ])
            ->all();
    }

    /** Paket formundaki "zaten dahil" seçimi için: yalnız ücretli modüller. */
    public static function pricedOptions(): array
    {
        return static::query()
            ->whereNotNull('price')
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(fn (self $a) => [
                $a->slug => $a->name.' — '.number_format($a->price, 0, ',', '.').' ₺',
            ])
            ->all();
    }
}
