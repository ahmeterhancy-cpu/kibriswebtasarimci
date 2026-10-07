<?php

namespace App\Console\Commands;

use App\Http\Controllers\SitemapController;
use App\Support\IndexNow;
use Illuminate\Console\Command;

/**
 * Sitemap'teki tüm adresleri IndexNow'a bildirir.
 *
 * Günlük akışta gözlemci yetiyor; bu komut toplu durumlar için:
 * ilk kurulum, büyük bir içerik aktarımı ya da uzun süren bir kesinti
 * sonrası. `--url=` ile tek tek adres de bildirilebilir.
 */
class IndexNowPing extends Command
{
    protected $signature = 'indexnow:ping
                            {--url=* : Yalnızca bu adresleri bildir}
                            {--dry : Göndermeden yalnızca listeyi yazdır}';

    protected $description = 'Site haritasindaki adresleri IndexNow ile Bing/Yandex e bildirir';

    public function handle(): int
    {
        $urls = $this->option('url') ?: $this->sitemapUrls();

        if ($urls === []) {
            $this->error('Bildirilecek adres bulunamadı.');

            return self::FAILURE;
        }

        $this->line(count($urls).' adres hazır.');

        if ($this->option('dry')) {
            foreach ($urls as $url) {
                $this->line('  '.$url);
            }

            return self::SUCCESS;
        }

        if (! IndexNow::enabled()) {
            $this->warn('IndexNow kapalı. services.indexnow.enabled ve INDEXNOW_KEY değerlerini kontrol edin.');

            return self::FAILURE;
        }

        if (! IndexNow::ping($urls)) {
            $this->error('Bildirim gönderilemedi. Ayrıntı için storage/logs/laravel.log.');

            return self::FAILURE;
        }

        $this->info('Gönderildi.');

        return self::SUCCESS;
    }

    /**
     * Adresleri sitemap'ten okuyoruz: tek doğru kaynak orası, burada
     * ikinci bir liste tutmak ikisinin ayrışmasına davetiye olurdu.
     *
     * @return array<int, string>
     */
    private function sitemapUrls(): array
    {
        $xml = app(SitemapController::class)->index()->getContent();

        preg_match_all('#<loc>([^<]+)</loc>#', $xml, $matches);

        return array_values(array_unique($matches[1] ?? []));
    }
}
