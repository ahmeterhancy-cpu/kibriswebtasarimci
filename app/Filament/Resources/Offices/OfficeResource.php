<?php

namespace App\Filament\Resources\Offices;

use App\Filament\Resources\Offices\Pages\CreateOffice;
use App\Filament\Resources\Offices\Pages\EditOffice;
use App\Filament\Resources\Offices\Pages\ListOffices;
use App\Models\Office;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

/**
 * Ofisler — adresin tek kaynağı.
 *
 * Buradaki kayıt üç yerde birden okunuyor: iletişim sayfasındaki ofis listesi,
 * bağlı şehir sayfasının "buradayız" bloğu ve yapısal veri (PostalAddress).
 * Adresi tek yerde tutmanın sebebi sadece düzen değil: sitedeki adres ile
 * Google Business Profile kaydı arasındaki tutarsızlık yerel sıralamayı
 * doğrudan düşürüyor.
 */
class OfficeResource extends Resource
{
    protected static ?string $model = Office::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static UnitEnum|string|null $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'Ofisler';

    protected static ?string $modelLabel = 'Ofis';

    protected static ?string $pluralModelLabel = 'Ofisler';

    protected static ?int $navigationSort = 32;

    protected static ?string $recordTitleAttribute = 'city';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Ofis')->schema([
                TextInput::make('name')
                    ->label('Etiket (TR)')
                    ->required()
                    ->maxLength(190)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set, $operation, $get) => $operation === 'create' ? $set('slug', Str::slug($get('city') ?: $state)) : null)
                    ->placeholder('Kıbrıs — Merkez')
                    ->helperText('İletişim sayfasında şehrin üstünde görünen başlık.'),
                TextInput::make('name_en')->label('Label (EN)')->maxLength(190),

                TextInput::make('city')->label('Şehir (TR)')->required()->maxLength(190)->placeholder('Girne'),
                TextInput::make('city_en')->label('City (EN)')->maxLength(190)->placeholder('Kyrenia'),

                TextInput::make('country')->label('Ülke (TR)')->required()->maxLength(190)->placeholder('Kuzey Kıbrıs'),
                TextInput::make('country_en')->label('Country (EN)')->maxLength(190),
            ])->columns(2),

            Section::make('Adres ve iletişim')
                ->description('Buradaki adres Google Business Profile kaydınızla BİREBİR aynı olmalı; tutarsızlık yerel aramada sıralama kaybettirir.')
                ->schema([
                    TextInput::make('address')
                        ->label('Açık adres (TR)')
                        ->required()
                        ->maxLength(190)
                        ->placeholder('Zafer Sokak No:1, Bellapais')
                        ->helperText('Şehir ve ülkeyi tekrar yazmayın; onlar üstteki alanlardan ekleniyor.'),
                    TextInput::make('address_en')->label('Address (EN)')->maxLength(190),

                    TextInput::make('phone')->label('Telefon')->maxLength(60)->placeholder('+90 548 840 4000'),
                    TextInput::make('email')->label('E-posta')->email()->maxLength(190),

                    TextInput::make('country_code')
                        ->label('Ülke kodu')
                        ->required()
                        ->maxLength(2)
                        ->default('CY')
                        ->helperText('CY, TR, GB… Yapısal veride kullanılır.'),

                    TextInput::make('maps_url')
                        ->label('Harita bağlantısı')
                        ->url()
                        ->maxLength(500)
                        ->helperText('Boş bırakılırsa adresten otomatik üretilir. Kesin konum için Google Haritalar bağlantısını yapıştırın.'),

                    TextInput::make('latitude')
                        ->label('Enlem')
                        ->numeric()
                        ->helperText('Google Haritalar\'da ofise sağ tıklayın; ilk değer enlem, ikincisi boylam.'),
                    TextInput::make('longitude')->label('Boylam')->numeric(),
                ])->columns(2),

            Section::make('Yayın')->schema([
                TextInput::make('slug')->label('Kısa ad')->required()->maxLength(190)->unique(ignoreRecord: true),
                Toggle::make('is_primary')
                    ->label('Merkez ofis')
                    ->helperText('Listede ilk sırada ve kırmızı işaretle çıkar.'),
                TextInput::make('sort_order')->label('Sıra')->numeric()->default(0),
                Toggle::make('is_active')->label('Yayında')->default(true),
            ])->columns(4),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                IconColumn::make('is_primary')->label('Merkez')->boolean(),
                TextColumn::make('city')->label('Şehir')->searchable()->weight('bold'),
                TextColumn::make('address')->label('Adres')->limit(45)->color('gray'),
                TextColumn::make('phone')->label('Telefon')->placeholder('—'),
                TextColumn::make('locations_count')->label('Bağlı şehir sayfası')->counts('locations')->badge()->color('gray'),
                ToggleColumn::make('is_active')->label('Yayında'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOffices::route('/'),
            'create' => CreateOffice::route('/create'),
            'edit' => EditOffice::route('/{record}/edit'),
        ];
    }
}
