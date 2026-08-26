<?php

use App\Http\Controllers\SetupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tek seferlik kurulum rotası — SHELL ERİŞİMİ OLMAYAN SUNUCULAR İÇİN
|--------------------------------------------------------------------------
|
| `.env` içinde SETUP_TOKEN tanımlı DEĞİLSE rota hiç kaydedilmez; token'ı
| silmek adresi yok etmekle aynı şeydir.
|
| Denetleyici SINIF olarak yazıldı, closure olarak değil: `route:cache`
| closure içeren rotaları serileştiremez ve canlıda `optimize` çalıştığı
| için sitenin tamamı 500'e düşerdi.
|
| KULLANIM
|   1. .env'e ekleyin:   SETUP_TOKEN=uzun-rastgele-bir-dize
|   2. Tarayıcıda açın:  https://alanadi.com/kurulum/uzun-rastgele-bir-dize
|   3. Bitince .env'den SETUP_TOKEN satırını SİLİN.
*/

if (! config('app.setup_token')) {
    return;
}

Route::get('/kurulum/{given}', SetupController::class);
