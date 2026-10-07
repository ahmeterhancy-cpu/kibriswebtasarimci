<?php

namespace App\Models;

use App\Support\ContentRoutes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Eski adres → yeni adres eşlemesi.
 *
 * Kayıt: içerik modellerinin slug'ı değiştiğinde IndexNowObserver yazıyor.
 * Okuma: 404 anında bootstrap/app.php'deki istisna yakalayıcısı soruyor.
 */
class SlugHistory extends Model
{
    protected $table = 'slug_history';

    protected $guarded = [];

    /**
     * 404'e düşen bir istek eski bir adrese mi geldi? Geldiyse 301.
     *
     * Adres biçimi: /<onek>/<segment>/<slug> — yalnızca SON segmente
     * bakıyoruz. Dil öneki ve rota adı değişmiş olabilir (ör. TR'de
     * /sektorler, EN'de /industries); doğru adresi her zaman rota adından
     * yeniden üretiyoruz, eski yolu olduğu gibi kullanmıyoruz.
     */
    public static function redirectFor(Request $request): ?RedirectResponse
    {
        $slug = basename($request->path());

        if ($slug === '' || $slug === '/') {
            return null;
        }

        $kayit = static::where('slug', $slug)->first();

        if (! $kayit) {
            return null;
        }

        $rotalar = ContentRoutes::for($kayit->model_type);

        if ($rotalar === null) {
            return null;
        }

        /** @var Model|null $model */
        $model = $kayit->model_type::find($kayit->model_id);

        // Kayıt silinmişse eski adresi diriltmenin anlamı yok; 404 kalsın.
        if ($model === null) {
            return null;
        }

        [$show, , $param] = $rotalar;
        // Dili ADRESTEN okuyoruz, app()->getLocale()'den değil: rota
        // eşleşmediği için locale middleware'i hiç çalışmadı, uygulama
        // hâlâ varsayılan dilde. /en/... isteği aksi hâlde TR'ye düşerdi.
        $onek = $request->is('en', 'en/*') ? 'en.' : '';

        try {
            $hedef = route($onek.$show, [$param => $model->slug]);
        } catch (\Throwable) {
            return null;
        }

        // Kendine yönlendirme döngüsü olmasın.
        if ($hedef === $request->url()) {
            return null;
        }

        return redirect()->away($hedef, 301);
    }
}
