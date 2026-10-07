<?php

namespace App\Filament\Resources\Sectors;

use App\Filament\Resources\Sectors\Pages\CreateSector;
use App\Filament\Resources\Sectors\Pages\EditSector;
use App\Filament\Resources\Sectors\Pages\ListSectors;
use App\Filament\Support\SlugField;
use App\Models\Sector;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class SectorResource extends Resource
{
    protected static ?string $model = Sector::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static UnitEnum|string|null $navigationGroup = 'İçerik';

    protected static ?string $navigationLabel = 'Sektör Sayfaları';

    protected static ?string $modelLabel = 'Sektör';

    protected static ?string $pluralModelLabel = 'Sektörler';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()->tabs([

                Tab::make('Türkçe')->schema([
                    TextInput::make('name')
                        ->label('Sektör adı')
                        ->required()
                        ->maxLength(190)
                        ->live(onBlur: true)
                        ->afterStateUpdated(SlugField::titleHook())
                        ->placeholder('Otel & Konaklama'),

                    TextInput::make('headline')
                        ->label('Sayfa başlığı (H1)')
                        ->maxLength(190)
                        ->placeholder('Otel web sitesi')
                        ->helperText('Boş bırakılırsa sektör adı kullanılır.'),

                    Textarea::make('intro')
                        ->label('Giriş cümlesi')
                        ->rows(2)
                        ->maxLength(600)
                        ->helperText('Listede ve sayfa başında görünür. Bu sektörde sitenin asıl işini bir cümlede söyleyin.'),

                    TagsInput::make('needs')
                        ->label('Sitenin çözmesi gerekenler')
                        ->helperText('Koyu bölümde numaralı liste olarak çıkar. 3-5 madde ideal. Sektörün gerçek derdi ne ise onu yazın.'),

                    TagsInput::make('features')
                        ->label('Tipik kapsam')
                        ->helperText('Yan sütunda modül listesi. Bu sektörde genelde ne yapılıyor.'),

                    RichEditor::make('body')
                        ->label('Uzun metin')
                        ->helperText('Sayfanın gövdesi. BAŞKA SEKTÖRDEN KOPYALAMAYIN — sektör sayfalarının tüm değeri birbirinden gerçekten farklı olmasından geliyor. Uydurma referans, müşteri sayısı ya da "sektör lideri" iddiası yazmayın.'),
                ])->columns(1),

                Tab::make('English')->schema([
                    TextInput::make('name_en')->label('Sector name')->maxLength(190),
                    TextInput::make('headline_en')->label('Headline (H1)')->maxLength(190),
                    Textarea::make('intro_en')->label('Intro')->rows(2)->maxLength(600),
                    TagsInput::make('needs_en')->label('What the site must solve'),
                    TagsInput::make('features_en')->label('Typical scope'),
                    RichEditor::make('body_en')->label('Body'),
                ])->columns(1),

                Tab::make('SEO & Yayın')->schema([
                    Section::make('Arama sonucu')->schema([
                        TextInput::make('seo_title')->label('SEO başlığı (TR)')->maxLength(190),
                        TextInput::make('seo_title_en')->label('SEO title (EN)')->maxLength(190),
                        Textarea::make('seo_description')->label('SEO açıklaması (TR)')->rows(2)->maxLength(500),
                        Textarea::make('seo_description_en')->label('SEO description (EN)')->rows(2)->maxLength(500),
                    ])->columns(2),

                    Section::make('Yayın')->schema([
                        SlugField::make(
                            'Adres (slug)',
                            '/sektorler/otel-ve-konaklama — sektör adını değiştirirseniz adres de değişir, yayındaki bağlantılar kırılır.',
                        ),
                        SlugField::lock(),
                        TextInput::make('icon')
                            ->label('Simge')
                            ->maxLength(8)
                            ->placeholder('🏨')
                            ->helperText('Tek emoji. Listede sektörün yanında görünür.'),
                        TextInput::make('sort_order')->label('Sıra')->numeric()->default(0),
                        Toggle::make('is_active')->label('Yayında')->default(true),
                    ])->columns(4),
                ]),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('icon')->label('')->size('lg'),
                TextColumn::make('name')->label('Sektör')->searchable()->weight('bold'),
                TextColumn::make('intro')->label('Giriş')->limit(70)->wrap()->color('gray'),
                TextColumn::make('needs')
                    ->label('Madde')
                    ->badge()
                    ->formatStateUsing(fn ($state) => is_array($state) ? count($state).' ihtiyaç' : '—')
                    ->color('gray'),
                ToggleColumn::make('is_active')->label('Yayında'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSectors::route('/'),
            'create' => CreateSector::route('/create'),
            'edit' => EditSector::route('/{record}/edit'),
        ];
    }
}
