<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Rota grubundan gelen dili uygular: `->middleware('locale:en')`.
 * Türkçe önekaiz kök, İngilizce /en öneki altında yayınlanır.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next, string $locale = 'tr'): Response
    {
        if (in_array($locale, ['tr', 'en'], true)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
