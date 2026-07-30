<?php

namespace App\Filament\Pages\Concerns;

use App\Models\Setting;
use Filament\Notifications\Notification;

/**
 * Ayar sayfaları için ortak doldur/kaydet davranışı.
 *
 * Alan adlandırması: `contact_email` Türkçe değeri, `contact_email__en`
 * İngilizce karşılığını taşır. Kaydederken `__en` ekli alanlar aynı anahtarın
 * `value_en` sütununa yazılır.
 *
 * Sayfalar tip dönüşümü gerekiyorsa `castFromStorage()` / `castToStorage()`
 * kancalarını ezer (örn. bakım modunun '1'/'0' metnini Toggle boole'sine çevirmek).
 */
trait SavesSettings
{
    public ?array $data = [];

    /** @return array<int, string> */
    abstract protected function settingKeys(): array;

    abstract protected function settingGroup(): string;

    public function mount(): void
    {
        $values = [];

        foreach ($this->settingKeys() as $field) {
            $values[$field] = str_ends_with($field, '__en')
                ? Setting::getRaw(substr($field, 0, -4), 'en')
                : Setting::getRaw($field, 'tr');
        }

        $this->form->fill($this->castFromStorage($values));
    }

    public function save(): void
    {
        $data = $this->castToStorage($this->form->getState());
        $group = $this->settingGroup();

        // Önce Türkçe değerler, sonra aynı satıra İngilizce karşılıkları.
        foreach ($data as $field => $value) {
            if (str_ends_with($field, '__en')) {
                continue;
            }

            Setting::query()->updateOrCreate(
                ['key' => $field],
                ['value' => $value, 'group' => $group],
            );
        }

        foreach ($data as $field => $value) {
            if (! str_ends_with($field, '__en')) {
                continue;
            }

            Setting::query()->updateOrCreate(
                ['key' => substr($field, 0, -4)],
                ['value_en' => $value, 'group' => $group],
            );
        }

        foreach ($this->settingKeys() as $field) {
            Setting::forget(str_ends_with($field, '__en') ? substr($field, 0, -4) : $field);
        }

        Notification::make()
            ->title('Ayarlar kaydedildi')
            ->body('Değişiklikler sitede hemen geçerli.')
            ->success()
            ->send();
    }

    /** Veritabanından forma. */
    protected function castFromStorage(array $values): array
    {
        return $values;
    }

    /** Formdan veritabanına. */
    protected function castToStorage(array $values): array
    {
        return $values;
    }
}
