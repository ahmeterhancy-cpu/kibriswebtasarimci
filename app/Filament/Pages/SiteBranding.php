<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\SavesSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SiteBranding extends Page implements HasForms
{
    use InteractsWithForms;
    use SavesSettings;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaintBrush;

    protected static UnitEnum|string|null $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'Site Markası';

    protected static ?string $title = 'Site Markası — ad, açıklama, favicon, paylaşım görseli';

    protected static ?int $navigationSort = 30;

    protected string $view = 'filament.pages.settings-form';

    protected function settingGroup(): string
    {
        return 'branding';
    }

    protected function settingKeys(): array
    {
        return ['site_name', 'site_description', 'site_description__en', 'site_logo', 'site_logo_light', 'logo_alt', 'site_favicon', 'og_image'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kimlik')->schema([
                    TextInput::make('site_name')
                        ->label('Site adı')
                        ->maxLength(120)
                        ->placeholder('Kıbrıs Web Tasarımcı')
                        ->helperText('Sekme başlığında, footer\'da ve yapısal veride kullanılır.'),
                ]),

                Section::make('Site açıklaması')
                    ->description('Google sonuçlarında ve link paylaşımlarında görünen varsayılan metin. 150-160 karakter idealdir.')
                    ->schema([
                        Textarea::make('site_description')->label('Açıklama (TR)')->rows(3)->maxLength(300),
                        Textarea::make('site_description__en')->label('Description (EN)')->rows(3)->maxLength(300),
                    ]),

                Section::make('Logo')
                    ->description('Boş bırakılırsa header ve footer\'da tipografik logo (iki satır + kırmızı nokta) kullanılır — tasarımın varsayılanı budur. Görsel logo yüklerseniz onun yerine geçer.')
                    ->schema([
                        FileUpload::make('site_logo')
                            ->label('Logo — açık zemin için')
                            ->image()
                            ->disk('public')
                            ->directory('branding')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/png', 'image/svg+xml', 'image/webp'])
                            ->maxSize(2048)
                            ->helperText('Header\'da kullanılır. Yatay, şeffaf zeminli PNG veya SVG; yükseklik en az 80px. Max 2 MB.'),

                        FileUpload::make('site_logo_light')
                            ->label('Logo — koyu zemin için')
                            ->image()
                            ->disk('public')
                            ->directory('branding')
                            ->visibility('public')
                            ->acceptedFileTypes(['image/png', 'image/svg+xml', 'image/webp'])
                            ->maxSize(2048)
                            ->helperText('Footer ve mobil menü siyah zeminde. Boş bırakılırsa üstteki logo kullanılır — koyu renkli bir logo yüklediyseniz burada beyaz sürümünü verin, yoksa footer\'da görünmez.'),

                        TextInput::make('logo_alt')
                            ->label('Logo alt metni')
                            ->maxLength(120)
                            ->placeholder('Kıbrıs Web Tasarımcı')
                            ->helperText('Ekran okuyucular ve görsel yüklenmezse görünen metin. Boş bırakılırsa site adı kullanılır.'),
                    ]),

                Section::make('Favicon')->schema([
                    FileUpload::make('site_favicon')
                        ->label('Favicon')
                        ->image()
                        ->disk('public')
                        ->directory('branding')
                        ->visibility('public')
                        ->acceptedFileTypes(['image/png', 'image/svg+xml', 'image/x-icon'])
                        ->maxSize(512)
                        ->helperText('Önerilen: 32×32 PNG veya SVG. Max 512 KB. Boş bırakılırsa varsayılan simge kullanılır.'),
                ]),

                Section::make('Sosyal medya önizlemesi (Open Graph)')
                    ->description('Site linki WhatsApp, Facebook veya X\'te paylaşıldığında görünen kapak.')
                    ->schema([
                        FileUpload::make('og_image')
                            ->label('Paylaşım görseli')
                            ->image()
                            ->disk('public')
                            ->directory('branding')
                            ->visibility('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios(['1.91:1'])
                            ->maxSize(2048)
                            ->helperText('Önerilen: 1200×630 px. Max 2 MB.'),
                    ]),
            ])
            ->statePath('data');
    }
}
