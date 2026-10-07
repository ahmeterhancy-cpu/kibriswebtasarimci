<?php

use App\Http\Middleware\MaintenanceMode;
use App\Http\Middleware\SetLocale;
use App\Models\SlugHistory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        // Shell erişimi olmayan sunucuda migration'ı tarayıcıdan çalıştırmak
        // için. .env'de SETUP_TOKEN yoksa hiçbir rota kaydedilmez.
        then: function (): void {
            require __DIR__.'/../routes/setup.php';
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'locale' => SetLocale::class,
        ]);

        $middleware->web(append: [
            MaintenanceMode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        // Eski adrese gelen istek 404 yerine 301 ile yenisine gitsin.
        // Kaydın adresi panelden değiştirilmişse dışarıdan verilmiş
        // bağlantılar ve arama motorundaki birikim böyle korunuyor.
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return SlugHistory::redirectFor($request);
        });
    })->create();
