<?php

namespace App\Observers;

use App\Models\SlugHistory;
use App\Support\ContentRoutes;
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
     * Gözlenecek model sınıfları.
     *
     * @return array<int, class-string>
     */
    public static function models(): array
    {
        return ContentRoutes::models();
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
        if (! $model->wasChanged()) {
            return;
        }

        // Adres değiştiyse eskisini sakla: o adrese verilmiş dış bağlantılar
        // 404'e düşmesin, 301 ile yenisine taşınsın.
        if ($model->wasChanged('slug')) {
            $eski = $model->getOriginal('slug');

            if (filled($eski) && $eski !== $model->slug) {
                SlugHistory::updateOrCreate(
                    ['model_type' => $model::class, 'slug' => $eski],
                    ['model_id' => $model->getKey()],
                );

                // Kayıt eski adresine geri dönmüşse o satır artık yanlış.
                SlugHistory::where('model_type', $model::class)
                    ->where('slug', $model->slug)
                    ->delete();
            }
        }

        IndexNow::ping($this->urlsFor($model));
    }

    public function deleted(Model $model): void
    {
        IndexNow::ping($this->urlsFor($model));
    }

    /** @return array<int, string> */
    private function urlsFor(Model $model): array
    {
        $entry = ContentRoutes::for($model::class);

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
