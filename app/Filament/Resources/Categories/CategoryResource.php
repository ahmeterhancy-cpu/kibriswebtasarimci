<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Models\Category;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static UnitEnum|string|null $navigationGroup = 'İçerik';

    protected static ?string $navigationLabel = 'Kategoriler';

    protected static ?string $modelLabel = 'kategori';

    protected static ?string $pluralModelLabel = 'kategoriler';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Ad (TR)')
                ->required()
                ->maxLength(190)
                ->live(onBlur: true)
                ->afterStateUpdated(function (?string $state, callable $set, string $operation) {
                    if ($operation === 'create' && filled($state)) {
                        $set('slug', Str::slug($state, '-', 'tr'));
                    }
                }),

            TextInput::make('name_en')->label('Name (EN)')->maxLength(190),

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
                ->helperText('Filtre bağlantılarında kullanılır: ?kategori=<slug>.'),

            Select::make('type')
                ->label('Nerede kullanılacak')
                ->options(['blog' => 'Blog yazıları', 'work' => 'İşler / portfolyo'])
                ->default('blog')
                ->required(),

            TextInput::make('sort_order')->label('Sıra')->numeric()->default(0),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('name')->label('Ad')->searchable()->weight('bold'),
                TextColumn::make('name_en')->label('English')->placeholder('— çevrilmedi —')->toggleable(),
                TextColumn::make('type')->label('Tür')->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'work' ? 'İşler' : 'Blog'),
                TextColumn::make('posts_count')->label('Yazı')->counts('posts'),
                TextColumn::make('works_count')->label('İş')->counts('works'),
            ])
            ->filters([
                SelectFilter::make('type')->label('Tür')
                    ->options(['blog' => 'Blog', 'work' => 'İşler']),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
