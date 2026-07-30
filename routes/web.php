<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WorkController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SEO — dilden bağımsız, kökten sunulur
|--------------------------------------------------------------------------
*/

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Türkçe — varsayılan dil, önek yok
|--------------------------------------------------------------------------
*/

Route::middleware('locale:tr')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/hizmetler', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/hizmetler/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

    Route::get('/isler', [WorkController::class, 'index'])->name('works.index');
    Route::get('/isler/{work:slug}', [WorkController::class, 'show'])->name('works.show');

    Route::get('/paketler', [PackageController::class, 'index'])->name('packages');

    Route::get('/teklif-al', [QuoteController::class, 'index'])->name('quote');
    Route::post('/teklif-al', [QuoteController::class, 'store'])->name('quote.store');

    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

    Route::get('/iletisim', [ContactController::class, 'index'])->name('contact');
    Route::post('/iletisim', [ContactController::class, 'store'])->name('contact.store');
});

/*
|--------------------------------------------------------------------------
| English — /en öneki
|--------------------------------------------------------------------------
*/

Route::middleware('locale:en')->prefix('en')->name('en.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');

    Route::get('/work', [WorkController::class, 'index'])->name('works.index');
    Route::get('/work/{work:slug}', [WorkController::class, 'show'])->name('works.show');

    Route::get('/pricing', [PackageController::class, 'index'])->name('packages');

    Route::get('/get-quote', [QuoteController::class, 'index'])->name('quote');
    Route::post('/get-quote', [QuoteController::class, 'store'])->name('quote.store');

    Route::get('/insights', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/insights/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
});
