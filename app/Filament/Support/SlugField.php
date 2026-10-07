<?php

namespace App\Filament\Support;

use Closure;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;

/**
 * Adres (slug) alanı — sekiz kaynakta aynı şekilde kuruluyor.
 *
 * Üç davranış birbirine bağlı, biri olmadan diğeri yanlış çalışır:
 *
 *   1. Adres başlıktan türer, YENİ KAYITTA DA DÜZENLEMEDE DE. Başlığı
 *      değiştirince adres de değişir; bu bilinçli bir tercih.
 *   2. Kullanıcı adres alanına elle dokunursa kilitlenir (LOCK) ve başlık
 *      bir daha üzerine yazmaz. Aksi hâlde elle yazdığı adresi geri alırdık.
 *   3. Yazılan her değer adres biçimine çevrilir. "NP-CYP-Merkezi" gibi
 *      büyük harfli bir slug Linux sunucuda 404 verir; ayrıca benzersizlik
 *      denetimi de böylece ham metni değil kaydedilecek değeri görür.
 *
 * DİKKAT: Yayındaki bir sayfanın başlığını değiştirmek adresini de
 * değiştirir ve o adrese verilmiş bağlantılar kırılır. Adresi sabit tutmak
 * için başlığı değiştirmeden ÖNCE adres alanına elle dokunmak gerekir.
 */
final class SlugField
{
    /** Adres alanına elle dokunuldu mu — gizli alanda tutuluyor. */
    public const LOCK = 'slug_locked';

    public static function normalize(?string $state): string
    {
        $state = (string) $state;

        return Str::slug($state, '-', 'tr') ?: $state;
    }

    /** Adres alanının kendisi. */
    public static function make(string $label, ?string $helperText = null): TextInput
    {
        return TextInput::make('slug')
            ->label($label)
            ->required()
            ->maxLength(190)
            ->unique(ignoreRecord: true)
            ->live(onBlur: true)
            ->afterStateUpdated(function (?string $state, callable $set): void {
                // Elle yazıldı: bundan sonra başlık buraya karışmasın.
                $set(self::LOCK, true);
                $set('slug', self::normalize($state));
            })
            // $set() ile programatik atanan değerler yukarıdaki kancayı
            // tetiklemez; dehydrate son emniyet kemeri.
            ->dehydrateStateUsing(fn (?string $state): string => self::normalize($state))
            ->helperText($helperText);
    }

    /** Başlık/ad alanına takılan kanca. */
    public static function titleHook(): Closure
    {
        return function (?string $state, callable $set, callable $get): void {
            if (blank($state) || $get(self::LOCK)) {
                return;
            }

            $set('slug', self::normalize($state));
        };
    }

    /** Kilit bayrağı. dehydrated(false): modele kaydedilmez. */
    public static function lock(): Hidden
    {
        return Hidden::make(self::LOCK)->dehydrated(false);
    }
}
