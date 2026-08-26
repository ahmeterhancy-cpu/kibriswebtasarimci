<?php

namespace App\Http\Controllers;

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
    public function __invoke(string $given): Response
    {
        $token = config('app.setup_token');

        abort_unless($token && hash_equals($token, $given), 404);

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

    private function render(string $body, int $status = 200): Response
    {
        return response(
            '<pre style="font:14px/1.6 ui-monospace,monospace;padding:2rem;white-space:pre-wrap">'.e($body).'</pre>',
            $status
        )->header('Content-Type', 'text/html; charset=UTF-8');
    }
}
