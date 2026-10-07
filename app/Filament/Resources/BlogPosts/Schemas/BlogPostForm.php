<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use App\Models\Category;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogPostForm
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
                        ->afterStateUpdated(function (?string $state, callable $set, string $operation) {
                            if ($operation === 'create' && filled($state)) {
                                $set('slug', Str::slug($state, '-', 'tr'));
                            }
                        }),

                    TextInput::make('slug')
                        ->label('Adres (slug)')
                        ->required()
                        ->maxLength(190)
                        ->unique(ignoreRecord: true)
                        // Alandan çıkınca adres biçimine çevir: "NP-CYP" gibi büyük harfli
                        // bir slug sunucuda 404 verir. Benzersizlik denetimi de böylece ham
                        // metni değil kaydedilecek değeri görür; dehydrate emniyet kemeri.
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (?string $state, callable $set) => $set('slug', Str::slug((string) $state, '-', 'tr') ?: $state))
                        ->dehydrateStateUsing(fn (?string $state): string => Str::slug((string) $state, '-', 'tr') ?: (string) $state)
                        ->helperText('Sayfa adresi: /blog/<slug>. Yayındaysa değiştirmeyin — bağlantılar kırılır.'),

                    Textarea::make('excerpt')
                        ->label('Özet')
                        ->rows(2)
                        ->maxLength(500)
                        ->helperText('Liste kartlarında ve arama sonuçlarında görünür.'),

                    RichEditor::make('body')->label('Yazı')->columnSpanFull(),
                ])->columns(1),

                Tab::make('English')->schema([
                    TextInput::make('title_en')->label('Title')->maxLength(190),
                    Textarea::make('excerpt_en')->label('Excerpt')->rows(2)->maxLength(500),
                    RichEditor::make('body_en')->label('Body')->columnSpanFull(),
                ])->columns(1),

                Tab::make('Görsel & SEO')->schema([
                    FileUpload::make('cover')
                        ->label('Kapak görseli')
                        ->image()
                        ->disk('public')
                        ->directory('blog')
                        ->visibility('public')
                        ->imageEditor()
                        ->imageEditorAspectRatios(['16:10', '16:9'])
                        ->maxSize(4096)
                        ->helperText('Önerilen: 1600×1000 px. Max 4 MB.'),

                    Section::make('Arama motoru')->schema([
                        TextInput::make('seo_title')->label('SEO başlığı (TR)')->maxLength(190),
                        TextInput::make('seo_title_en')->label('SEO title (EN)')->maxLength(190),
                        Textarea::make('seo_description')->label('SEO açıklaması (TR)')->rows(2)->maxLength(500),
                        Textarea::make('seo_description_en')->label('SEO description (EN)')->rows(2)->maxLength(500),
                    ])->columns(2)
                        ->description('Boş bırakılırsa başlık ve özet kullanılır.'),
                ])->columns(1),

                Tab::make('Yayın')->schema([
                    Select::make('category_id')
                        ->label('Kategori')
                        ->options(fn () => Category::ofType('blog')->pluck('name', 'id'))
                        ->searchable()
                        ->preload(),

                    TextInput::make('author')->label('Yazar')->maxLength(190),

                    TextInput::make('reading_minutes')
                        ->label('Okuma süresi (dk)')
                        ->numeric()
                        ->minValue(1)
                        ->helperText('Kabaca: kelime sayısı ÷ 200.'),

                    DateTimePicker::make('published_at')
                        ->label('Yayın tarihi')
                        ->seconds(false)
                        ->default(now())
                        ->helperText('İleri bir tarih girilirse o ana kadar sitede görünmez.'),

                    Toggle::make('is_published')->label('Yayında')->default(false),
                ])->columns(2),

            ])->columnSpanFull(),
        ]);
    }
}
