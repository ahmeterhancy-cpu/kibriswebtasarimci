<?php

namespace App\Filament\Resources\Works\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class WorkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()->tabs([

                Tab::make('Türkçe')->schema([
                    TextInput::make('title')
                        ->label('Proje adı')
                        ->required()
                        ->maxLength(190)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (?string $state, callable $set, string $operation) {
                            // Slug yalnızca yeni kayıtta otomatik dolar; yayındaki
                            // adresler düzenleme sırasında kendiliğinden değişmesin.
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
                        ->helperText('Sayfa adresi: /isler/<slug>.'),

                    Textarea::make('summary')->label('Özet')->rows(2)->maxLength(500),

                    RichEditor::make('body')->label('Vaka çalışması metni')->columnSpanFull(),
                ])->columns(1),

                Tab::make('English')->schema([
                    TextInput::make('title_en')->label('Project name')->maxLength(190),
                    Textarea::make('summary_en')->label('Summary')->rows(2)->maxLength(500),
                    RichEditor::make('body_en')->label('Case study body')->columnSpanFull(),
                ])->columns(1),

                Tab::make('Künye')->schema([
                    TextInput::make('client')->label('Müşteri')->maxLength(190),
                    TextInput::make('year')->label('Yıl')->maxLength(10)->placeholder('2026'),

                    Select::make('category_id')
                        ->label('Kategori')
                        ->options(fn () => Category::ofType('work')->pluck('name', 'id'))
                        ->searchable()
                        ->preload(),

                    TextInput::make('external_url')
                        ->label('Canlı site adresi')
                        ->url()
                        ->maxLength(190)
                        ->placeholder('https://…'),

                    TagsInput::make('tags')
                        ->label('Kapsam etiketleri')
                        ->placeholder('Kurumsal Web, SEO…')
                        ->columnSpanFull(),

                    Repeater::make('metrics')
                        ->label('Sonuç rakamları')
                        ->schema([
                            TextInput::make('value')->label('Değer')->placeholder('%180')->required(),
                            TextInput::make('label')->label('Etiket (TR)')->placeholder('Trafik artışı')->required(),
                            TextInput::make('label_en')->label('Label (EN)')->placeholder('Traffic growth'),
                        ])
                        ->columns(3)
                        ->defaultItems(0)
                        ->addActionLabel('Rakam ekle')
                        ->columnSpanFull()
                        ->helperText('Boş bırakılırsa proje sayfasında sonuç bölümü gösterilmez.'),
                ])->columns(2),

                Tab::make('Görseller')->schema([
                    FileUpload::make('cover')
                        ->label('Kapak görseli')
                        ->image()
                        ->disk('public')
                        ->directory('works')
                        ->visibility('public')
                        ->imageEditor()
                        ->maxSize(4096)
                        ->helperText('Önerilen: 1600×1200 px (4:3). Max 4 MB.'),

                    FileUpload::make('gallery')
                        ->label('Galeri')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->disk('public')
                        ->directory('works/gallery')
                        ->visibility('public')
                        ->maxSize(4096)
                        ->helperText('Proje detay sayfasında ızgara olarak gösterilir.'),
                ])->columns(1),

                Tab::make('Yayın')->schema([
                    Toggle::make('is_active')->label('Yayında')->default(true),
                    Toggle::make('is_featured')
                        ->label('Ana sayfada göster')
                        ->default(true)
                        ->helperText('Ana sayfadaki yatay iş şeridine girer.'),
                    TextInput::make('sort_order')->label('Sıra')->numeric()->default(0),
                ]),

            ])->columnSpanFull(),
        ]);
    }
}
