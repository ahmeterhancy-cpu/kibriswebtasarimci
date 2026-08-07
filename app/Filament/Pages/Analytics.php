<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\SavesSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Ölçüm ve pixel yönetimi.
 *
 * Kural: buraya yalnız KİMLİK girilir, kod değil. Script'lerin kendisi
 * `layouts.partials.analytics` içinde yazılıdır — böylece panele yapıştırılan
 * bir kod parçası sitenin tamamını bozamaz ve çerez onayı verilmeden hiçbiri
 * çalışmaz. İstisna, listede olmayan araçlar için açık bırakılan özel script
 * alanlarıdır; oraya girilen kod HAM olarak basılır, dikkatli kullanılmalı.
 */
class Analytics extends Page implements HasForms
{
    use InteractsWithForms;
    use SavesSettings;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static UnitEnum|string|null $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'Analitik & Tracking';

    protected static ?string $title = 'Analitik & Pixel Yönetimi';

    protected static ?int $navigationSort = 36;

    protected string $view = 'filament.pages.settings-form';

    protected function settingGroup(): string
    {
        return 'analytics';
    }

    protected function settingKeys(): array
    {
        return [
            'ga4_id', 'gtm_id', 'google_ads_id',
            'meta_pixel_id', 'linkedin_partner_id', 'tiktok_pixel_id',
            'clarity_id', 'hotjar_id', 'yandex_metrica_id',
            'custom_head', 'custom_body',
            'cookie_consent',
        ];
    }

    protected function castFromStorage(array $values): array
    {
        $values['cookie_consent'] = ($values['cookie_consent'] ?? '1') === '1';

        return $values;
    }

    protected function castToStorage(array $values): array
    {
        $values['cookie_consent'] = ! empty($values['cookie_consent']) ? '1' : '0';

        return $values;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Google Analytics & Tag Manager')
                    ->description('Site trafiği, dönüşüm takibi ve kitle oluşturma.')
                    ->schema([
                        TextInput::make('ga4_id')
                            ->label('Google Analytics 4 — Ölçüm ID')
                            ->placeholder('G-XXXXXXXXXX')
                            ->rule('regex:/^$|^G-[A-Z0-9]{6,}$/i')
                            ->helperText('analytics.google.com → Yönetici → Veri Akışları → Web → Ölçüm ID.'),

                        TextInput::make('gtm_id')
                            ->label('Google Tag Manager ID')
                            ->placeholder('GTM-XXXXXXX')
                            ->rule('regex:/^$|^GTM-[A-Z0-9]{4,}$/i')
                            ->helperText('tagmanager.google.com → çalışma alanı sağ üst. GTM kullanıyorsanız GA4\'ü de oradan yönetip buradaki GA4 alanını boş bırakın; iki yerden birden yüklerseniz ziyaretler çift sayılır.'),

                        TextInput::make('google_ads_id')
                            ->label('Google Ads dönüşüm ID')
                            ->placeholder('AW-XXXXXXXXX')
                            ->rule('regex:/^$|^AW-[0-9]{6,}$/i')
                            ->helperText('Reklam veriyorsanız dönüşüm takibi için (opsiyonel).'),
                    ]),

                Section::make('Reklam pixel\'leri')
                    ->description('Retargeting ve reklam dönüşüm takibi. Yalnızca reklam verdiğiniz platformları doldurun.')
                    ->schema([
                        TextInput::make('meta_pixel_id')
                            ->label('Meta (Facebook & Instagram) Pixel ID')
                            ->placeholder('123456789012345')
                            ->rule('regex:/^$|^[0-9]{10,20}$/')
                            ->helperText('business.facebook.com → Events Manager → Veri Kaynakları → Pixel ID.'),

                        TextInput::make('linkedin_partner_id')
                            ->label('LinkedIn Partner ID')
                            ->placeholder('1234567')
                            ->rule('regex:/^$|^[0-9]{4,12}$/')
                            ->helperText('Campaign Manager → Account Assets → Insight Tag.'),

                        TextInput::make('tiktok_pixel_id')
                            ->label('TikTok Pixel ID')
                            ->placeholder('XXXXXXXXXXXXXXXXXXXX')
                            ->rule('regex:/^$|^[A-Z0-9]{10,30}$/i')
                            ->helperText('TikTok Ads Manager → Assets → Events → Web Events.'),
                    ]),

                Section::make('Davranış analizi')
                    ->description('Ziyaretçi fareyi nereye götürüyor, nerede takılıyor, nereye kadar kaydırıyor.')
                    ->schema([
                        TextInput::make('clarity_id')
                            ->label('Microsoft Clarity Proje ID')
                            ->placeholder('abcdefghij')
                            ->rule('regex:/^$|^[a-z0-9]{6,20}$/i')
                            ->helperText('ÜCRETSİZ ve sınırsız. clarity.microsoft.com → Settings → Project ID. Isı haritası ve oturum kaydı verir.'),

                        TextInput::make('hotjar_id')
                            ->label('Hotjar Site ID')
                            ->placeholder('1234567')
                            ->rule('regex:/^$|^[0-9]{5,10}$/')
                            ->helperText('insights.hotjar.com → Sites & Organizations.'),

                        TextInput::make('yandex_metrica_id')
                            ->label('Yandex Metrica Counter ID')
                            ->placeholder('12345678')
                            ->rule('regex:/^$|^[0-9]{6,12}$/')
                            ->helperText('Rusça konuşan pazar hedefleniyorsa (Girne/Antalya emlak ve turizm için anlamlı).'),
                    ]),

                Section::make('Özel script')
                    ->description('Yukarıdaki listede olmayan araçlar için ham HTML/JS. Canlı destek widget\'ı, Calendly, doğrulama meta etiketi vb.')
                    ->schema([
                        Textarea::make('custom_head')
                            ->label('<head> içine eklenecek kod')
                            ->rows(4)
                            ->placeholder('<meta name="google-site-verification" content="...">')
                            ->helperText('DİKKAT: Buraya yazılan kod olduğu gibi basılır. Hatalı bir etiket sayfayı bozabilir; değişiklikten sonra siteyi açıp kontrol edin.'),

                        Textarea::make('custom_body')
                            ->label('</body> öncesine eklenecek kod')
                            ->rows(4)
                            ->helperText('Sayfa çizildikten sonra çalışır. Sohbet widget\'ı, popup script\'i vb.'),
                    ]),

                Section::make('KVKK / GDPR çerez onayı')
                    ->description('Yasal zorunluluk: ölçüm ve reklam çerezleri, ziyaretçi izin vermeden çalıştırılamaz.')
                    ->schema([
                        Toggle::make('cookie_consent')
                            ->label('Çerez onay bandını göster')
                            ->helperText('Açıkken: ziyaretçi "Kabul et" demeden yukarıdaki HİÇBİR script yüklenmez. Kapalıyken script\'ler ilk açılışta çalışır — yalnızca hukuki sorumluluğu üstlendiğinizde kapatın. Özel script alanları bu kontrolün dışındadır, onlar her hâlükârda yüklenir.'),
                    ]),
            ])
            ->statePath('data');
    }
}
