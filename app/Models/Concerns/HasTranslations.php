<?php

namespace App\Models\Concerns;

/**
 * İki dilli alan yardımcısı.
 *
 * Alanlar `baslik` / `baslik_en` çifti olarak tutulur. `t('baslik')` aktif dile
 * göre değeri döner; İngilizce karşılığı boşsa Türkçesine düşer — böylece admin
 * her içeriği çevirmek zorunda kalmadan site tutarlı kalır.
 */
trait HasTranslations
{
    public function t(string $field, mixed $default = null): mixed
    {
        if (app()->getLocale() === 'en') {
            $translated = $this->{$field.'_en'} ?? null;

            if (filled($translated)) {
                return $translated;
            }
        }

        return $this->{$field} ?? $default;
    }
}
