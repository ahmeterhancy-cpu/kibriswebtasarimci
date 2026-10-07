<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * IndexNow istemcisi — içerik değişince arama motorlarına haber verir.
 *
 * Normalde Bing/Yandex sitenin değiştiğini kendi tarayıcısı uğradığında
 * anlıyor; yeni bir sitede bu haftalar sürebiliyor. IndexNow bu beklemeyi
 * kaldırıyor: panelden bir yazı yayınlandığı anda adresi tek bir çağrıyla
 * bildiriyoruz, dakikalar içinde taranıyor. Bing ChatGPT'nin web aramasını
 * da beslediği için bu bildirim yapay zekâ sonuçlarına da yansıyor.
 *
 * Doğrulama: anahtar, sitenin kökünde `<anahtar>.txt` dosyasında DURMALI
 * (public/ içinde, depoya dahil). Dosya kaybolursa bildirimler sessizce
 * reddedilir.
 *
 * DİKKAT: Burada atılan hiçbir hata yukarı taşınmıyor. Bu çağrı yönetim
 * panelindeki "kaydet" akışının içinde çalışıyor; arama motoru ulaşılmaz
 * olduğunda ya da sunucuda CA sertifika paketi eksik olduğunda (cURL 60)
 * kullanıcının kaydı başarısız olmamalı.
 */
final class IndexNow
{
    private const ENDPOINT = 'https://api.indexnow.org/IndexNow';

    public static function key(): ?string
    {
        $key = config('services.indexnow.key');

        return is_string($key) && $key !== '' ? $key : null;
    }

    /** Anahtar yoksa ya da yerel/test ortamındaysak sessizce kapalı. */
    public static function enabled(): bool
    {
        return self::key() !== null && (bool) config('services.indexnow.enabled');
    }

    /**
     * @param  array<int, string>  $urls
     */
    public static function ping(array $urls): bool
    {
        $urls = array_values(array_unique(array_filter($urls)));

        if ($urls === [] || ! self::enabled()) {
            return false;
        }

        $key = self::key();
        $host = parse_url((string) config('app.url'), PHP_URL_HOST) ?: request()->getHost();

        try {
            $response = Http::timeout(5)
                ->connectTimeout(3)
                ->acceptJson()
                ->post(self::ENDPOINT, [
                    'host' => $host,
                    'key' => $key,
                    'keyLocation' => 'https://'.$host.'/'.$key.'.txt',
                    'urlList' => array_slice($urls, 0, 10000),
                ]);
        } catch (\Throwable $e) {
            Log::warning('IndexNow bildirimi gönderilemedi', ['hata' => $e->getMessage()]);

            return false;
        }

        if (! $response->successful()) {
            Log::warning('IndexNow bildirimi reddedildi', [
                'durum' => $response->status(),
                'govde' => $response->body(),
            ]);

            return false;
        }

        return true;
    }
}
