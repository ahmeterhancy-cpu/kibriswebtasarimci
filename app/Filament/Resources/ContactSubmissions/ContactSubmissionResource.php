<?php

namespace App\Filament\Resources\ContactSubmissions;

use App\Filament\Resources\ContactSubmissions\Pages\EditContactSubmission;
use App\Filament\Resources\ContactSubmissions\Pages\ListContactSubmissions;
use App\Models\ContactSubmission;
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

class ContactSubmissionResource extends Resource
{
    protected static ?string $model = ContactSubmission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static UnitEnum|string|null $navigationGroup = 'Talepler';

    protected static ?string $navigationLabel = 'İletişim Mesajları';

    protected static ?string $modelLabel = 'mesaj';

    protected static ?string $pluralModelLabel = 'mesajlar';

    protected static ?int $navigationSort = 21;

    protected static ?string $recordTitleAttribute = 'name';

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
            Section::make('Mesaj')->schema([
                TextInput::make('name')->label('Ad Soyad')->disabled(),
                TextInput::make('email')->label('E-posta')->disabled(),
                TextInput::make('phone')->label('Telefon')->disabled(),
                TextInput::make('subject')->label('Konu')->disabled(),
                Textarea::make('message')->label('Mesaj')->disabled()->rows(6)->columnSpanFull(),
            ])->columns(2),

            Section::make('Takip')->schema([
                Select::make('status')
                    ->label('Durum')
                    ->options(ContactSubmission::STATUSES)
                    ->default('new')
                    ->required(),
            ]),
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
                TextColumn::make('subject')->label('Konu')->limit(40)->placeholder('—'),
                TextColumn::make('message')->label('Mesaj')->limit(60)->wrap()->toggleable(),
                TextColumn::make('status')->label('Durum')->badge()
                    ->formatStateUsing(fn (string $state) => ContactSubmission::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'new' => 'danger',
                        'read' => 'warning',
                        default => 'success',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')->label('Durum')->options(ContactSubmission::STATUSES),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->emptyStateHeading('Henüz mesaj yok');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContactSubmissions::route('/'),
            'edit' => EditContactSubmission::route('/{record}/edit'),
        ];
    }
}
