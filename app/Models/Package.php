<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public const TYPE_PROJECT = 'project';

    public const TYPE_CARE = 'care';

    /** Paket türü: tek seferlik proje mi, aylık bakım mı. */
    public const TYPES = [
        self::TYPE_PROJECT => 'Proje paketi (tek seferlik)',
        self::TYPE_CARE => 'Bakım paketi (aylık)',
    ];

    /** Teklif sihirbazındaki proje türleri (paket bunlardan hangilerinde çıkacak). */
    public const PROJECT_TYPES = [
        'tanitim' => 'Tanıtım / tek sayfa site',
        'kurumsal' => 'Kurumsal web sitesi',
        'eticaret' => 'E-ticaret / online mağaza',
        'mobil' => 'iOS / Android uygulama',
        'yazilim' => 'Özel web yazılımı',
        'yenileme' => 'Mevcut siteyi yenileme',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'features_en' => 'array',
            'project_types' => 'array',
            'included_extras' => 'array',
            'is_popular' => 'boolean',
            'is_ecommerce' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /** Tek seferlik proje paketleri — vitrin, teklif sihirbazı, ana sayfa. */
    public function scopeProjects(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_PROJECT);
    }

    /** Aylık bakım paketleri. */
    public function scopeCare(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_CARE);
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
