<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tek seferlik kurulum rotası — SHELL ERİŞİMİ OLMAYAN SUNUCULAR İÇİN
|--------------------------------------------------------------------------
|
| Paylaşımlı hostta SSH ya da cPanel Git deploy görevleri kapalıysa
| `php artisan migrate` çalıştırmanın yolu kalmıyor. Bu rota o boşluğu
| dolduruyor: tarayıcıdan bir kez çağrılır, tabloları kurar ve içeriği yükler.
|
| GÜVENLİK — üç katman:
|   1. `.env` içinde SETUP_TOKEN tanımlı DEĞİLSE rota hiç kaydedilmez.
|      Token'ı silmek rotayı yok etmekle aynı şey.
|   2. Adresteki token ile .env'deki token sabit zamanlı karşılaştırılır.
|   3. Tablolar zaten kuruluysa seed ÇALIŞMAZ — yanlışlıkla ikinci kez
|      çağrılırsa panelden girilen içerik geri dönmez.
|
| KULLANIM
|   1. .env'e rastgele bir değer ekleyin:  SETUP_TOKEN=uzun-rastgele-bir-dize
|   2. Tarayıcıda açın:  https://alanadi.com/kurulum/uzun-rastgele-bir-dize
|   3. İşlem bitince .env'den SETUP_TOKEN satırını SİLİN.
*/

$token = config('app.setup_token');

if (! $token) {
    return;
}

Route::get('/kurulum/{given}', function (string $given) use ($token) {
    abort_unless(hash_equals($token, $given), 404);

    $output = [];
    $alreadyInstalled = false;

    try {
        // Zaten kurulu mu? `settings` tablosu varsa şema kurulmuş demektir.
        $alreadyInstalled = \Illuminate\Support\Facades\Schema::hasTable('settings');
    } catch (\Throwable $e) {
        // Bağlantı yoksa aşağıdaki migrate zaten anlamlı hatayı verecek.
    }

    try {
        Artisan::call('migrate', ['--force' => true]);
        $output['migrate'] = trim(Artisan::output());
    } catch (\Throwable $e) {
        return response('<pre>MIGRATE HATASI:'."\n\n".e($e->getMessage()).'</pre>', 500)
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    // Seed YALNIZCA ilk kurulumda. İkinci çağrıda panelden yapılan
    // düzenlemeleri tohum verisine geri döndürmemeli.
    if ($alreadyInstalled) {
        $output['seed'] = 'Atlandı — veritabanı zaten kuruluydu, içerik korundu.';
    } else {
        try {
            Artisan::call('db:seed', ['--force' => true]);
            $output['seed'] = trim(Artisan::output());
        } catch (\Throwable $e) {
            $output['seed'] = 'HATA: '.$e->getMessage();
        }
    }

    foreach (['storage:link', 'config:cache', 'route:cache', 'view:cache'] as $command) {
        try {
            Artisan::call($command);
            $output[$command] = trim(Artisan::output()) ?: 'tamam';
        } catch (\Throwable $e) {
            $output[$command] = 'atlandı: '.$e->getMessage();
        }
    }

    $body = "KURULUM TAMAMLANDI\n".str_repeat('=', 60)."\n\n";

    foreach ($output as $step => $result) {
        $body .= strtoupper($step)."\n".$result."\n\n";
    }

    $body .= str_repeat('=', 60)."\n";
    $body .= "ŞİMDİ YAPILACAKLAR\n\n";
    $body .= "1. .env dosyasından SETUP_TOKEN satırını SİLİN.\n";
    $body .= "   Silmezseniz bu adres açık kalır.\n";
    $body .= "2. /admin adresinden giriş yapıp şifreyi değiştirin.\n";

    return response('<pre style="font:14px/1.6 ui-monospace,monospace;padding:2rem">'.e($body).'</pre>')
        ->header('Content-Type', 'text/html; charset=UTF-8');
});
