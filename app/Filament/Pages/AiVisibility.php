<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\SavesSettings;
use App\Support\AiCrawlers;
use BackedEnum;
use Filament\Forms\Components\CheckboxList;
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
 * GEO — Generative Engine Optimization.
 *
 * Klasik SEO "Google'da kaçıncı sıradayız" sorusuydu. Burada soru şu:
 * ChatGPT'ye "Kıbrıs'ta web tasarım yapan kim var" diye sorulduğunda cevapta
 * geçiyor muyuz, kaynak olarak gösteriliyor muyuz?
 *
 * Üç kaldıraç var ve üçü de bu sayfadan yönetiliyor:
 *  1. Tarayıcı izni — bot siteye giremezse cevapta da yer alamaz (robots.txt).
 *  2. llms.txt — modele "bu site nedir, hangi sayfalar önemli" diyen düz metin
 *     özet. Sitemap'in insan/model tarafındaki karşılığı gibi.
 *  3. Alıntılanabilir tanım — modelin markayı tarif ederken kullanacağı,
 *     tek paragraflık net cümle.
 */
class AiVisibility extends Page implements HasForms
{
    use InteractsWithForms;
    use SavesSettings;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static UnitEnum|string|null $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'Yapay Zekâ Görünürlüğü';

    protected static ?string $title = 'Yapay Zekâ Görünürlüğü (GEO) — ChatGPT, Claude, Perplexity, Gemini';

    protected static ?int $navigationSort = 34;

    protected string $view = 'filament.pages.settings-form';

    protected function settingGroup(): string
    {
        return 'geo';
    }

    protected function settingKeys(): array
    {
        return [
            'ai_crawlers',
            'llms_enabled',
            'ai_summary', 'ai_summary__en',
            'ai_entity', 'ai_entity__en',
            'ai_contact_note',
        ];
    }

    protected function castFromStorage(array $values): array
    {
        // Kayıt hiç yapılmadıysa varsayılan: tüm botlar açık.
        $values['ai_crawlers'] = $values['ai_crawlers'] === null
            ? AiCrawlers::defaults()
            : array_values(array_filter(explode(',', (string) $values['ai_crawlers'])));

        $values['llms_enabled'] = ($values['llms_enabled'] ?? '1') === '1';

        return $values;
    }

    protected function castToStorage(array $values): array
    {
        $values['ai_crawlers'] = implode(',', (array) ($values['ai_crawlers'] ?? []));
        $values['llms_enabled'] = ! empty($values['llms_enabled']) ? '1' : '0';

        return $values;
    }

    public function form(Schema $schema): Schema
    {
        $options = [];
        $descriptions = [];

        foreach (AiCrawlers::all() as $key => $bot) {
            $tag = $bot['purpose'] === 'search' ? '🔎 Arama/alıntı' : '📚 Eğitim';
            $options[$key] = $bot['label'];
            $descriptions[$key] = $tag.' — '.$bot['note'];
        }

        return $schema
            ->components([
                Section::make('Alıntılanabilir tanım')
                    ->description('Yapay zekâ bir markayı tarif ederken kısa ve net cümleleri alıntılar. Pazarlama dili değil, doğrulanabilir bilgi yazın: ne yapıyorsunuz, kime, nerede, hangi farkla.')
                    ->schema([
                        Textarea::make('ai_entity')
                            ->label('Tek cümlelik tanım (TR)')
                            ->rows(2)
                            ->maxLength(300)
                            ->placeholder('Kıbrıs Web Tasarımcı, Kuzey Kıbrıs merkezli; kurumsal web sitesi, e-ticaret ve özel web yazılımı geliştiren bir stüdyodur.')
                            ->helperText('"Şirket nedir" sorusunun cevabı. Sıfat değil, olgu.'),
                        Textarea::make('ai_entity__en')->label('One-line definition (EN)')->rows(2)->maxLength(300),

                        Textarea::make('ai_summary')
                            ->label('Genişletilmiş özet (TR)')
                            ->rows(5)
                            ->maxLength(1200)
                            ->helperText('llms.txt dosyasının giriş bölümü. Hizmetler, çalışma bölgeleri, diller, tipik teslim süresi gibi somut bilgiler verin. Boş bırakılırsa site açıklaması kullanılır.'),
                        Textarea::make('ai_summary__en')->label('Extended summary (EN)')->rows(5)->maxLength(1200),
                    ]),

                Section::make('llms.txt')
                    ->description('Yapay zekâ modelleri için düz metin site haritası — /llms.txt adresinde yayınlanır. Hizmetler, paketler, şehir sayfaları ve blog yazıları listeden otomatik üretilir; elle güncelleme gerekmez.')
                    ->schema([
                        Toggle::make('llms_enabled')
                            ->label('llms.txt yayınlansın')
                            ->helperText('Kapatılırsa /llms.txt 404 döner ve robots.txt\'teki bağlantısı kaldırılır.'),

                        TextInput::make('ai_contact_note')
                            ->label('İletişim satırı')
                            ->maxLength(200)
                            ->placeholder('Teklif için: /teklif-al')
                            ->helperText('llms.txt sonunda yer alır. Modelin kullanıcıya vereceği yönlendirme.'),
                    ]),

                Section::make('Yapay zekâ tarayıcı izinleri')
                    ->description('İşaretli botlar robots.txt ile siteye alınır. Bot giremezse o motorun cevabında hiç görünemezsiniz — özellikle 🔎 işaretli olanları kapatmadan önce iki kez düşünün.')
                    ->schema([
                        CheckboxList::make('ai_crawlers')
                            ->label('Siteye alınacak botlar')
                            ->options($options)
                            ->descriptions($descriptions)
                            ->columns(1)
                            ->bulkToggleable(),
                    ]),
            ])
            ->statePath('data');
    }
}
