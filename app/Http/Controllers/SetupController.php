<?php

namespace App\Http\Controllers;

use Database\Seeders\SectorFaqSeeder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

/**
 * Tek seferlik kurulum ekranı — shell erişimi olmayan sunucular için.
 *
 * SINIF, closure DEĞİL: `php artisan route:cache` closure içeren rotaları
 * serileştiremez ve "Unable to prepare route for serialization" ile patlar.
 * Canlıda `optimize` çalıştığı için bu, sitenin tamamını 500'e düşürürdü.
 *
 * Güvenlik üç katman:
 *   1. `.env` içinde SETUP_TOKEN yoksa rota HİÇ kaydedilmez (routes/setup.php)
 *   2. Token sabit zamanlı karşılaştırılır
 *   3. Tablolar kuruluysa seed atlanır — ikinci çağrı içeriği geri döndürmez
 */
class SetupController extends Controller
{
    /**
     * Kurulu bir sitede tek başına çalıştırılabilen görevler.
     *
     * Buraya yalnızca VAR OLAN İÇERİĞİ SİLMEYEN, tekrar çalıştırıldığında
     * aynı sonucu veren seeder'lar girer. Liste bilerek dar: token sızsa
     * bile çalıştırılabilecek şeyin sınırı burası.
     *
     * @var array<string, class-string>
     */
    public const JOBS = [
        // Sektör sayfalarının sık sorulanları. Yalnızca faq sütunlarını
        // slug'a göre günceller; kayıt oluşturmaz, silmez.
        'sss' => SectorFaqSeeder::class,
    ];

    public function __invoke(string $given, ?string $job = null): Response
    {
        $token = config('app.setup_token');

        abort_unless($token && hash_equals($token, $given), 404);

        if ($job !== null) {
            return $this->runJob($job);
        }

        $steps = [];
        $alreadyInstalled = false;

        try {
            $alreadyInstalled = Schema::hasTable('settings');
        } catch (\Throwable) {
            // Bağlantı yoksa migrate zaten anlamlı hatayı verecek.
        }

        try {
            Artisan::call('migrate', ['--force' => true]);
            $steps['migrate'] = trim(Artisan::output());
        } catch (\Throwable $e) {
            return $this->render('MIGRATE HATASI'."\n\n".$e->getMessage(), 500);
        }

        if ($alreadyInstalled) {
            $steps['seed'] = 'Atlandı — veritabanı zaten kuruluydu, içerik korundu.';
        } else {
            try {
                Artisan::call('db:seed', ['--force' => true]);
                $steps['seed'] = trim(Artisan::output());
            } catch (\Throwable $e) {
                $steps['seed'] = 'HATA — '.$e->getMessage();
            }
        }

        foreach (['storage:link', 'config:cache', 'route:cache', 'view:cache'] as $command) {
            try {
                Artisan::call($command);
                $steps[$command] = trim(Artisan::output()) ?: 'tamam';
            } catch (\Throwable $e) {
                $steps[$command] = 'atlandı — '.$e->getMessage();
            }
        }

        $body = "KURULUM TAMAMLANDI\n".str_repeat('=', 60)."\n\n";

        foreach ($steps as $step => $result) {
            $body .= strtoupper($step)."\n".$result."\n\n";
        }

        $body .= str_repeat('=', 60)."\n";
        $body .= "ŞİMDİ YAPILACAKLAR\n\n";
        $body .= "1. .env dosyasından SETUP_TOKEN satırını SİLİN.\n";
        $body .= "   Silmezseniz bu adres açık kalır.\n";
        $body .= "2. /admin adresinden giriş yapıp şifreyi değiştirin.\n";

        return $this->render($body);
    }

    /** Tek bir içerik görevi: kurulu siteyi bozmadan çalıştırılır. */
    private function runJob(string $job): Response
    {
        $seeder = self::JOBS[$job] ?? null;

        if ($seeder === null) {
            return $this->render(implode(PHP_EOL, [
                'Tanımsız görev: '.$job,
                '',
                'Tanımlı olanlar: '.implode(', ', array_keys(self::JOBS)),
            ]), 404);
        }

        // Göç önce çalışıyor: seeder'ın yazacağı sütun henüz yoksa görev
        // "Unknown column" ile patlıyor. Dağıtımdaki migrate adımına
        // güvenmiyoruz — sunucuda sessizce atlanabiliyor.
        //
        // Önbellek BİLEREK tazelenmiyor: veriyle ilgisi yok ve bir web
        // isteği içinde `config:cache` o anki yapılandırmayı diske
        // sabitliyor. Testte bu, bellek içi veritabanını işaret eden bir
        // config.php bırakıp sonraki her koşuda tabloları düşürdü.
        //
        // Seeder konsol katmanından değil doğrudan çağrılıyor.
        try {
            Artisan::call('migrate', ['--force' => true]);
            $migrate = trim(Artisan::output());

            app($seeder)->setContainer(app())->__invoke();
            $output = $seeder.' çalıştırıldı.';
        } catch (\Throwable $e) {
            return $this->render(implode(PHP_EOL, ['GÖREV HATASI', '', $e->getMessage()]), 500);
        }

        return $this->render(implode(PHP_EOL, [
            'GÖREV TAMAMLANDI: '.$job,
            str_repeat('=', 60),
            '',
            'GÖÇ',
            $migrate ?: 'değişiklik yok',
            '',
            'GÖREV',
            $output ?: 'çıktı yok',
            '',
            str_repeat('=', 60),
            'Bittiğinde .env dosyasından SETUP_TOKEN satırını SİLİN.',
        ]));
    }

    private function render(string $body, int $status = 200): Response
    {
        return response(
            '<pre style="font:14px/1.6 ui-monospace,monospace;padding:2rem;white-space:pre-wrap">'.e($body).'</pre>',
            $status
        )->header('Content-Type', 'text/html; charset=UTF-8');
    }
}
