<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Filament\Support\SlugField;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()->tabs([

                Tab::make('Türkçe')->schema([
                    TextInput::make('title')
                        ->label('Başlık')
                        ->required()
                        ->maxLength(190)
                        ->live(onBlur: true)
                        ->afterStateUpdated(SlugField::titleHook()),

                    SlugField::make(
                        'Adres (slug)',
                        'Sayfa adresi: /hizmetler/<slug>. Başlığı değiştirirseniz adres de değişir.',
                    ),
                    SlugField::lock(),

                    Textarea::make('excerpt')
                        ->label('Kısa açıklama')
                        ->rows(2)
                        ->maxLength(500)
                        ->helperText('Listelerde ve kartlarda görünür. 1-2 cümle.'),

                    TagsInput::make('features')
                        ->label('Kapsam maddeleri')
                        ->placeholder('Madde yazıp Enter\'a basın')
                        ->helperText('Hizmet kartında ve detay sayfasında listelenir.'),

                    RichEditor::make('body')
                        ->label('Detay metni')
                        ->columnSpanFull(),
                ])->columns(1),

                Tab::make('English')->schema([
                    TextInput::make('title_en')->label('Title')->maxLength(190),
                    Textarea::make('excerpt_en')->label('Short description')->rows(2)->maxLength(500),
                    TagsInput::make('features_en')->label('Scope items'),
                    RichEditor::make('body_en')->label('Body')->columnSpanFull(),
                ])->columns(1),

                Tab::make('Görsel & SEO')->schema([
                    FileUpload::make('image')
                        ->label('Görsel')
                        ->image()
                        ->disk('public')
                        ->directory('services')
                        ->visibility('public')
                        ->imageEditor()
                        ->maxSize(4096)
                        ->helperText('Önerilen: 1600×900 px. Max 4 MB.'),

                    Section::make('Arama motoru')->schema([
                        TextInput::make('seo_title')->label('SEO başlığı (TR)')->maxLength(190),
                        TextInput::make('seo_title_en')->label('SEO title (EN)')->maxLength(190),
                        Textarea::make('seo_description')->label('SEO açıklaması (TR)')->rows(2)->maxLength(500),
                        Textarea::make('seo_description_en')->label('SEO description (EN)')->rows(2)->maxLength(500),
                    ])->columns(2)
                        ->description('Boş bırakılırsa başlık ve kısa açıklama kullanılır.'),
                ])->columns(1),

                Tab::make('Yayın')->schema([
                    Toggle::make('is_active')->label('Yayında')->default(true),
                    TextInput::make('sort_order')
                        ->label('Sıra')
                        ->numeric()
                        ->default(0)
                        ->helperText('Küçük numara önce gösterilir.'),
                ]),

            ])->columnSpanFull(),
        ]);
    }
}
