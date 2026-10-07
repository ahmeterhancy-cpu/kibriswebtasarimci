<?php

namespace App\Filament\Concerns;

/**
 * Adres (slug) değişince düzenleme sayfasını yeni adrese taşır.
 *
 * TUZAK: Bu projedeki içerik modellerinin çoğunda getRouteKeyName() = 'slug'.
 * Düzenleme sayfasının adresi bu yüzden /admin/isler/<slug>/edit biçiminde.
 * Slug kaydederken değişirse tarayıcının adres çubuğu ESKİ slug'da kalıyor:
 * kayıt başarıyla yazılıyor ama sayfa yenilendiğinde ya da aynı sayfada
 * ikinci bir işlem yapıldığında kayıt artık bulunamıyor ve 404 dönüyor.
 * Kullanıcı tarafında bu "kaydetmiyor" gibi görünüyor.
 *
 * Yalnızca yol anahtarı gerçekten değiştiğinde yönlendiriyoruz; her kayıtta
 * tam sayfa yenilemek gereksiz olurdu.
 */
trait RedirectsWhenRouteKeyChanges
{
    protected function getRedirectUrl(): ?string
    {
        if (! $this->record->wasChanged($this->record->getRouteKeyName())) {
            return null;
        }

        return static::getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
