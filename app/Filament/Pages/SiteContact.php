<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\SavesSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SiteContact extends Page implements HasForms
{
    use InteractsWithForms;
    use SavesSettings;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static UnitEnum|string|null $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'İletişim & Sosyal Medya';

    protected static ?string $title = 'İletişim bilgileri ve sosyal medya';

    protected static ?int $navigationSort = 31;

    protected string $view = 'filament.pages.settings-form';

    protected function settingGroup(): string
    {
        return 'contact';
    }

    protected function settingKeys(): array
    {
        return [
            'contact_email', 'contact_phone', 'contact_whatsapp',
            'contact_city', 'contact_city__en',
            'contact_address', 'contact_address__en',
            'map_query', 'map_query__en', 'map_embed', 'map_title', 'map_title__en',
            'social_instagram', 'social_linkedin', 'social_behance', 'social_github',
            'social_facebook', 'social_x', 'social_youtube', 'social_tiktok',
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('İletişim')
                    ->description('Bu bilgiler header, footer, iletişim sayfası ve yapısal veride kullanılır.')
                    ->schema([
                        TextInput::make('contact_email')->label('E-posta')->email()->maxLength(190),
                        TextInput::make('contact_phone')->label('Telefon')->maxLength(60)->placeholder('+90 533 000 00 00'),
                        TextInput::make('contact_whatsapp')
                            ->label('WhatsApp numarası')
                            ->maxLength(30)
                            ->placeholder('905330000000')
                            ->helperText('Yalnızca rakam, ülke koduyla. Boş bırakılırsa WhatsApp düğmesi gizlenir.'),
                    ])->columns(3),

                Section::make('Konum')->schema([
                    TextInput::make('contact_city')->label('Şehir (TR)')->maxLength(120)->placeholder('Girne'),
                    TextInput::make('contact_city__en')->label('City (EN)')->maxLength(120)->placeholder('Kyrenia'),
                    TextInput::make('contact_address')->label('Adres (TR)')->maxLength(190),
                    TextInput::make('contact_address__en')->label('Address (EN)')->maxLength(190),
                ])->columns(2),

                Section::make('Harita (iletişim sayfası)')
                    ->description('İki yoldan biri yeterli. Harita yalnızca aşağıdaki alanlardan biri doluysa gösterilir; ikisi de boşsa iletişim sayfasında harita bölümü hiç çıkmaz.')
                    ->schema([
                        TextInput::make('map_query')
                            ->label('Adres araması (TR)')
                            ->maxLength(190)
                            ->placeholder('Bellapais, Girne, Kuzey Kıbrıs')
                            ->helperText('Basit yol: Google Haritalar\'da arattığınız metni yazın, gömülü harita otomatik üretilir.'),
                        TextInput::make('map_query__en')->label('Address search (EN)')->maxLength(190),

                        Textarea::make('map_embed')
                            ->label('Özel gömme adresi (opsiyonel)')
                            ->rows(2)
                            ->placeholder('https://www.google.com/maps/embed?pb=…')
                            ->helperText('Hassas yol: Google Haritalar → Paylaş → "Harita yerleştir" → iframe içindeki src adresi. Doluysa üstteki adres aramasının yerine geçer.'),

                        TextInput::make('map_title')
                            ->label('Harita başlığı (TR)')
                            ->maxLength(190)
                            ->helperText('Ekran okuyucular için iframe başlığı. Erişilebilirlik açısından gerekli.'),
                        TextInput::make('map_title__en')->label('Map title (EN)')->maxLength(190),
                    ])->columns(2),

                Section::make('Sosyal medya')
                    ->description('Boş bırakılan hesaplar footer\'da hiç gösterilmez. Dolu olanlar ayrıca yapısal veriye (sameAs) eklenir — arama motorları ve yapay zekâ, markayı bu hesaplarla ilişkilendirir.')
                    ->schema([
                        TextInput::make('social_instagram')->label('Instagram')->url()->maxLength(190)->placeholder('https://instagram.com/…'),
                        TextInput::make('social_linkedin')->label('LinkedIn')->url()->maxLength(190),
                        TextInput::make('social_facebook')->label('Facebook')->url()->maxLength(190),
                        TextInput::make('social_x')->label('X (Twitter)')->url()->maxLength(190),
                        TextInput::make('social_youtube')->label('YouTube')->url()->maxLength(190),
                        TextInput::make('social_tiktok')->label('TikTok')->url()->maxLength(190),
                        TextInput::make('social_behance')->label('Behance')->url()->maxLength(190),
                        TextInput::make('social_github')->label('GitHub')->url()->maxLength(190),
                    ])->columns(2),
            ])
            ->statePath('data');
    }
}
