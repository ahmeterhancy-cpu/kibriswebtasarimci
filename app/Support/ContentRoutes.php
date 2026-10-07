<?php

namespace App\Support;

use App\Models\BlogPost;
use App\Models\Location;
use App\Models\Sector;
use App\Models\Service;
use App\Models\Work;

/**
 * Adresi olan içerik modelleri ve bağlı oldukları rotalar.
 *
 * Tek kaynak: hem IndexNow bildirimleri hem de eski adres yönlendirmeleri
 * bu haritayı okuyor. İki yerde ayrı liste tutmak, biri güncellenip diğeri
 * unutulduğunda sessizce bozulurdu.
 */
final class ContentRoutes
{
    /**
     * Model sınıfı => [detay rotası, liste rotası, rota parametresi].
     * Rota adları TR; EN sürümü başına 'en.' eklenerek bulunuyor.
     *
     * @var array<class-string, array{0: string, 1: string, 2: string}>
     */
    public const MAP = [
        Service::class => ['services.show', 'services.index', 'service'],
        Work::class => ['works.show', 'works.index', 'work'],
        Sector::class => ['sectors.show', 'sectors.index', 'sector'],
        Location::class => ['locations.show', 'locations.index', 'location'],
        BlogPost::class => ['blog.show', 'blog.index', 'post'],
    ];

    /** @return array<int, class-string> */
    public static function models(): array
    {
        return array_keys(self::MAP);
    }

    /** @return array{0: string, 1: string, 2: string}|null */
    public static function for(string $model): ?array
    {
        return self::MAP[$model] ?? null;
    }
}
