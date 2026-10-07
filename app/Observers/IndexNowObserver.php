<?php

namespace App\Observers;

use App\Models\BlogPost;
use App\Models\Location;
use App\Models\Sector;
use App\Models\Service;
use App\Models\Work;
use App\Support\IndexNow;
use Illuminate\Database\Eloquent\Model;

/**
 * İçerik kaydedildiğinde/silindiğinde etkilenen adresleri IndexNow'a bildirir.
 *
 * Bildirilen adresler: değişen kaydın iki dildeki detay sayfası, bağlı olduğu
 * liste sayfası ve ana sayfa. Ana sayfa listede olmalı çünkü işler, hizmetler
 * ve blog oradan da gösteriliyor.
 *
 * Kayıt gerçekten değişmediyse (`wasChanged()` boş) bildirim atlanıyor;
 * Filament'te "kaydet" düğmesine hiçbir şeyi değiştirmeden basmak da `saved`
 * tetikliyor ve her basışta arama motoru çağırmanın anlamı yok.
 */
class IndexNowObserver
{
    /**
     * Model sınıfı => [detay rotası, liste rotası, rota parametresi].
     * Rota adları TR; EN sürümü başına 'en.' eklenerek bulunuyor.
     *
     * @var array<class-string, array{0: string, 1: string, 2: string}>
     */
    private const MAP = [
        Service::class => ['services.show', 'services.index', 'service'],
        Work::class => ['works.show', 'works.index', 'work'],
        Sector::class => ['sectors.show', 'sectors.index', 'sector'],
        Location::class => ['locations.show', 'locations.index', 'location'],
        BlogPost::class => ['blog.show', 'blog.index', 'post'],
    ];

    /**
     * Gözlenecek model sınıfları.
     *
     * @return array<int, class-string>
     */
    public static function models(): array
    {
        return array_keys(self::MAP);
    }

    public function created(Model $model): void
    {
        IndexNow::ping($this->urlsFor($model));
    }

    /**
     * `saved` yerine `updated` kullanılıyor ve ayrıca `wasChanged()`
     * denetleniyor: Filament'te hiçbir alanı değiştirmeden "kaydet"e basmak
     * da bu olayı tetikliyor, her basışta arama motorunu çağırmanın anlamı yok.
     */
    public function updated(Model $model): void
    {
        if ($model->wasChanged()) {
            IndexNow::ping($this->urlsFor($model));
        }
    }

    public function deleted(Model $model): void
    {
        IndexNow::ping($this->urlsFor($model));
    }

    /** @return array<int, string> */
    private function urlsFor(Model $model): array
    {
        $entry = self::MAP[$model::class] ?? null;

        if ($entry === null) {
            return [];
        }

        [$show, $index, $param] = $entry;

        // Slug değiştiyse ESKİ adresi de bildiriyoruz: arama motorunun artık
        // 404 veren eski sayfayı dizinden düşürmesi için tek yol bu.
        $slugs = array_unique(array_filter([
            $model->slug,
            $model->getOriginal('slug'),
        ]));

        $urls = [
            ...$this->bothLocales('home'),
            ...$this->bothLocales($index),
        ];

        foreach ($slugs as $slug) {
            $urls = [...$urls, ...$this->bothLocales($show, [$param => $slug])];
        }

        return $urls;
    }

    /**
     * Bir rotanın TR ve EN adresi. Rota üretilemezse (parametre uymuyorsa)
     * o dil sessizce atlanır.
     *
     * @return array<int, string>
     */
    private function bothLocales(string $name, array $params = []): array
    {
        return array_values(array_filter([
            $this->safeRoute($name, $params),
            $this->safeRoute('en.'.$name, $params),
        ]));
    }

    private function safeRoute(string $name, array $params): ?string
    {
        try {
            return route($name, $params);
        } catch (\Throwable) {
            return null;
        }
    }
}
