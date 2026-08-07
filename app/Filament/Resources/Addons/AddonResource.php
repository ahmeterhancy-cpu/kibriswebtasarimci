<?php

namespace App\Filament\Resources\Addons;

use App\Filament\Resources\Addons\Pages\CreateAddon;
use App\Filament\Resources\Addons\Pages\EditAddon;
use App\Filament\Resources\Addons\Pages\ListAddons;
use App\Models\Addon;
use App\Models\Package;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class AddonResource extends Resource
{
    protected static ?string $model = Addon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPuzzlePiece;

    protected static UnitEnum|string|null $navigationGroup = 'İçerik';

    protected static ?string $navigationLabel = 'Ek Modüller';

    protected static ?string $modelLabel = 'ek modül';

    protected static ?string $pluralModelLabel = 'ek modüller';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Modül')
                ->description('Teklif sihirbazının 3. adımında gösterilir ve seçilirse toplama eklenir.')
                ->schema([
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
                        ->label('Kod (slug)')
                        ->required()
                        ->maxLength(190)
                        ->unique(ignoreRecord: true)
                        ->helperText('Gelen teklif taleplerinde bu kod görünür. Yayındaysa değiştirmeyin.'),

                    TextInput::make('price')
                        ->label('Fiyat')
                        ->numeric()
                        ->minValue(0)
                        ->suffix('₺')
                        ->helperText('Boş bırakılırsa modül ücretsiz sayılır: seçilebilir ama toplama girmez. Liste fiyatı olmayan türlerde (mobil uygulama, özel yazılım) kapsamı anlatmak için kullanılır.'),

                    Textarea::make('note')->label('Açıklama (TR)')->rows(2)->maxLength(500),
                    Textarea::make('note_en')->label('Description (EN)')->rows(2)->maxLength(500),
                ])->columns(2),

            Section::make('Nerede görünsün')->schema([
                CheckboxList::make('project_types')
                    ->label('Hangi proje türlerinde gösterilsin')
                    ->options(Package::PROJECT_TYPES)
                    ->columns(2)
                    ->columnSpanFull()
                    ->helperText('Örnek: katalog modülü e-ticaret projesinde gösterilmez, paketle çelişir.'),

                Toggle::make('is_active')->label('Yayında')->default(true),
                TextInput::make('sort_order')->label('Sıra')->numeric()->default(0),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('name')->label('Modül')->searchable()->weight('bold')
                    ->description(fn (Addon $record) => $record->note),
                TextColumn::make('price')->label('Fiyat')->numeric(0, ',', '.')->suffix(' ₺')
                    ->placeholder('ücretsiz'),
                TextColumn::make('project_types')->label('Proje türleri')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Package::PROJECT_TYPES[$state] ?? $state)
                    ->placeholder('— hiçbiri —'),
                ToggleColumn::make('is_active')->label('Yayında'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->emptyStateHeading('Henüz ek modül yok')
            ->emptyStateDescription('Modül eklenmezse teklif sihirbazının 3. adımı boş görünür.');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAddons::route('/'),
            'create' => CreateAddon::route('/create'),
            'edit' => EditAddon::route('/{record}/edit'),
        ];
    }
}
