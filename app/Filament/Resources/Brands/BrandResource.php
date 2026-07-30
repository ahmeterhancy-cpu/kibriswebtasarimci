<?php

namespace App\Filament\Resources\Brands;

use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Filament\Resources\Brands\Pages\EditBrand;
use App\Filament\Resources\Brands\Pages\ListBrands;
use App\Models\Brand;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static UnitEnum|string|null $navigationGroup = 'Sosyal Kanıt';

    protected static ?string $navigationLabel = 'Referans Logoları';

    protected static ?string $modelLabel = 'marka';

    protected static ?string $pluralModelLabel = 'markalar';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Marka adı')->required()->maxLength(190)
                ->helperText('Logo yüklenmezse şeritte yazı olarak görünür.'),

            TextInput::make('url')->label('Web sitesi')->url()->maxLength(190)->placeholder('https://…'),

            FileUpload::make('logo')
                ->label('Logo')
                ->image()
                ->disk('public')
                ->directory('brands')
                ->visibility('public')
                ->maxSize(1024)
                ->acceptedFileTypes(['image/png', 'image/svg+xml', 'image/webp'])
                ->helperText('Tercihen şeffaf zeminli PNG veya SVG. Max 1 MB.')
                ->columnSpanFull(),

            Toggle::make('is_active')->label('Yayında')->default(true),
            TextInput::make('sort_order')->label('Sıra')->numeric()->default(0),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('logo')->label('Logo')->disk('public')->height(32),
                TextColumn::make('name')->label('Marka')->searchable()->weight('bold'),
                TextColumn::make('url')->label('Site')->url(fn ($record) => $record->url)->openUrlInNewTab()->toggleable(),
                ToggleColumn::make('is_active')->label('Yayında'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->emptyStateHeading('Henüz referans logosu yok')
            ->emptyStateDescription('Logo eklenene kadar ana sayfadaki logo şeridi gizli kalır.');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBrands::route('/'),
            'create' => CreateBrand::route('/create'),
            'edit' => EditBrand::route('/{record}/edit'),
        ];
    }
}
