<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Paylaşımlı MySQL/MariaDB'de indeks 1000 bayt ile sınırlı.
        // 191 × 4 (utf8mb4) = 764 bayt < 1000 → `#1071` hatası çıkmaz.
        Builder::defaultStringLength(191);

        // @setting('contact_email', 'info@…')
        Blade::directive('setting', fn ($expression) => "<?php echo e(\\App\\Models\\Setting::get({$expression})); ?>");

        View::composer('*', function ($view) {
            // $site('key') → dile duyarlı, önbellekli ayar
            $view->with('site', fn (string $key, $default = null) => Setting::get($key, $default));

            // $r('services.index') → TR'de 'services.index', EN'de 'en.services.index'
            $prefix = app()->getLocale() === 'en' ? 'en.' : '';
            $view->with('r', fn (string $name, array $params = []) => route($prefix.$name, $params));
            $view->with('localePrefix', $prefix);

            // hreflang ve dil değiştirici için karşı dilin adresi
            $view->with('alternateUrl', $this->alternateUrl());
        });
    }

    /** Aynı rotanın diğer dildeki adresi; üretilemiyorsa null. */
    protected function alternateUrl(): ?string
    {
        $route = Route::current();
        $name = $route?->getName();

        if (! $name) {
            return null;
        }

        $target = app()->getLocale() === 'en'
            ? (str_starts_with($name, 'en.') ? substr($name, 3) : $name)
            : (str_starts_with($name, 'en.') ? $name : 'en.'.$name);

        try {
            return route($target, $route->parameters());
        } catch (\Throwable) {
            return null;
        }
    }
}
