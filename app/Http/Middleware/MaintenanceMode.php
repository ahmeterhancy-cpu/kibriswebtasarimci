<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bakım modu — Sistem › Genel Ayarlar'dan açılır.
 * Admin paneli, giriş ekranı ve Livewire istekleri kapsam dışıdır ki
 * mod açıkken panele girip kapatabilelim.
 */
class MaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin', 'admin/*', 'livewire/*', 'filament/*', 'storage/*', 'up')) {
            return $next($request);
        }

        if (Setting::get('maintenance_mode') !== '1') {
            return $next($request);
        }

        return response()->view('maintenance', [], 503);
    }
}
