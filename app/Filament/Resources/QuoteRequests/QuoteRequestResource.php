<?php

namespace App\Filament\Resources\QuoteRequests;

use App\Filament\Resources\QuoteRequests\Pages\EditQuoteRequest;
use App\Filament\Resources\QuoteRequests\Pages\ListQuoteRequests;
use App\Models\QuoteRequest;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class QuoteRequestResource extends Resource
{
    protected static ?string $model = QuoteRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static UnitEnum|string|null $navigationGroup = 'Talepler';

    protected static ?string $navigationLabel = 'Teklif Talepleri';

    protected static ?string $modelLabel = 'teklif talebi';

    protected static ?string $pluralModelLabel = 'teklif talepleri';

    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'name';

    /** Yeni talep sayısı menüde rozet olarak gösterilir. */
    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()->where('status', 'new')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Talep')
                ->description('Bu kayıt siteden geldi. Alanlar okunabilir; yalnızca durum ve not düzenlenir.')
                ->schema([
                    TextInput::make('name')->label('Ad Soyad')->disabled(),
                    TextInput::make('email')->label('E-posta')->disabled(),
                    TextInput::make('phone')->label('Telefon')->disabled(),
                    TextInput::make('company')->label('Şirket')->disabled(),
                    TextInput::make('project_type')->label('Proje türü')->disabled(),
                    TextInput::make('package')->label('Seçilen paket')->disabled(),
                    TextInput::make('timeline')->label('Süre beklentisi')->disabled(),
                    TextInput::make('budget')->label('Bütçe aralığı')->disabled(),
                    TextInput::make('quote_total')
                        ->label('Seçim toplamı')
                        ->disabled()
                        ->suffix('₺')
                        ->helperText('Sihirbazda seçilen liste fiyatlarının toplamı, KDV hariç. Liste fiyatı olmayan türlerde boştur.')
                        ->columnSpanFull(),
                    Textarea::make('message')->label('Mesaj')->disabled()->rows(4)->columnSpanFull(),
                ])->columns(2),

            Section::make('Takip')->schema([
                Select::make('status')
                    ->label('Durum')
                    ->options(QuoteRequest::STATUSES)
                    ->default('new')
                    ->required(),
                Textarea::make('admin_note')->label('İç not')->rows(3),
            ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->label('Tarih')->dateTime('d.m.Y H:i')->sortable(),
                TextColumn::make('name')->label('Kişi')->searchable()->weight('bold')
                    ->description(fn ($record) => $record->email),
                TextColumn::make('project_type')->label('Tür')->badge()->toggleable(),
                TextColumn::make('package')->label('Paket')->toggleable()->placeholder('—'),
                TextColumn::make('quote_total')->label('Tutar')->numeric(0, ',', '.')->suffix(' ₺')->placeholder('—'),
                TextColumn::make('status')->label('Durum')->badge()
                    ->formatStateUsing(fn (string $state) => QuoteRequest::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'new' => 'danger',
                        'contacted' => 'warning',
                        'quoted' => 'info',
                        'won' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')->label('Durum')->options(QuoteRequest::STATUSES),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->emptyStateHeading('Henüz teklif talebi yok');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuoteRequests::route('/'),
            'edit' => EditQuoteRequest::route('/{record}/edit'),
        ];
    }
}
