<?php

namespace App\Filament\Resources\Locations;

use App\Filament\Resources\Locations\Pages\CreateLocation;
use App\Filament\Resources\Locations\Pages\EditLocation;
use App\Filament\Resources\Locations\Pages\ListLocations;
use App\Models\Location;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static UnitEnum|string|null $navigationGroup = 'İçerik';

    protected static ?string $navigationLabel = 'Şehir Sayfaları';

    protected static ?string $modelLabel = 'şehir';

    protected static ?string $pluralModelLabel = 'şehirler';

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()->tabs([

                Tab::make('Türkçe')->schema([
                    TextInput::make('name')
                        ->label('Şehir adı')
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
                        ->helperText('Sayfa adresi: /web-tasarim/<slug>.'),

                    TextInput::make('headline')
                        ->label('Sayfa başlığı (H1)')
                        ->maxLength(190)
                        ->placeholder('Girne web tasarım'),

                    Textarea::make('intro')->label('Giriş paragrafı')->rows(3)->maxLength(600),

                    TagsInput::make('highlights')
                        ->label('Pazar maddeleri')
                        ->placeholder('Madde yazıp Enter\'a basın')
                        ->columnSpanFull(),

                    RichEditor::make('body')
                        ->label('Şehre özel metin')
                        ->columnSpanFull()
                        ->helperText('DİKKAT: Diğer şehirlerin metnini kopyalayıp yalnızca şehir adını değiştirmeyin. Arama motorları bunu kapı sayfası sayar ve iki sayfayı da düşürür. Her şehir için o pazara özgü yazın.'),
                ])->columns(2),

                Tab::make('English')->schema([
                    TextInput::make('name_en')->label('City name')->maxLength(190),
                    TextInput::make('headline_en')->label('Page heading (H1)')->maxLength(190),
                    Textarea::make('intro_en')->label('Intro paragraph')->rows(3)->maxLength(600),
                    TagsInput::make('highlights_en')->label('Market points')->columnSpanFull(),
                    RichEditor::make('body_en')->label('City-specific body')->columnSpanFull(),
                ])->columns(2),

                Tab::make('Konum & SEO')->schema([
                    Section::make('Konum')->schema([
                        Select::make('region')
                            ->label('Bölge')
                            ->options(Location::REGIONS)
                            ->default('kktc')
                            ->required(),

                        TextInput::make('country_code')
                            ->label('Ülke kodu')
                            ->maxLength(2)
                            ->default('CY')
                            ->helperText('CY veya TR. Yapısal veride kullanılır.'),

                        TextInput::make('latitude')
                            ->label('Enlem')
                            ->numeric()
                            ->helperText('Yapısal veriye (LocalBusiness geo) yazılır. Boş bırakılabilir.'),

                        TextInput::make('longitude')->label('Boylam')->numeric(),
                    ])->columns(2),

                    Section::make('Arama motoru')->schema([
                        TextInput::make('seo_title')->label('SEO başlığı (TR)')->maxLength(190),
                        TextInput::make('seo_title_en')->label('SEO title (EN)')->maxLength(190),
                        Textarea::make('seo_description')->label('SEO açıklaması (TR)')->rows(2)->maxLength(500),
                        Textarea::make('seo_description_en')->label('SEO description (EN)')->rows(2)->maxLength(500),
                    ])->columns(2)
                        ->description('Boş bırakılırsa sayfa başlığı ve giriş paragrafı kullanılır.'),
                ]),

                Tab::make('Yayın')->schema([
                    Toggle::make('is_active')->label('Yayında')->default(true),
                    TextInput::make('sort_order')->label('Sıra')->numeric()->default(0),
                ])->columns(2),

            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->groups(['region'])
            ->columns([
                TextColumn::make('name')->label('Şehir')->searchable()->weight('bold'),
                TextColumn::make('region')->label('Bölge')->badge()
                    ->formatStateUsing(fn (string $state) => Location::REGIONS[$state] ?? $state)
                    ->color(fn (string $state) => $state === 'turkiye' ? 'info' : 'success'),
                TextColumn::make('headline')->label('Başlık')->limit(38)->toggleable(),
                TextColumn::make('slug')->label('Adres')->prefix('/web-tasarim/')->color('gray')->toggleable(),
                ToggleColumn::make('is_active')->label('Yayında'),
            ])
            ->filters([
                SelectFilter::make('region')->label('Bölge')->options(Location::REGIONS),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->emptyStateHeading('Henüz şehir sayfası yok');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLocations::route('/'),
            'create' => CreateLocation::route('/create'),
            'edit' => EditLocation::route('/{record}/edit'),
        ];
    }
}
