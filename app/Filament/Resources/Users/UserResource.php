<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static UnitEnum|string|null $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'Kullanıcılar';

    // Filament etiketi olduğu gibi basar; "kullanıcı Oluştur" görünmesin diye
    // baş harf burada büyük yazılıyor.
    protected static ?string $modelLabel = 'Kullanıcı';

    protected static ?string $pluralModelLabel = 'Kullanıcılar';

    protected static ?int $navigationSort = 28;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hesap')->schema([
                TextInput::make('name')
                    ->label('Ad soyad')
                    ->required()
                    ->maxLength(190),

                TextInput::make('email')
                    ->label('E-posta')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(190)
                    ->helperText('Panele giriş için kullanılır.'),
            ])->columns(2),

            Section::make('Şifre')
                ->description('Mevcut bir kullanıcıyı düzenlerken boş bırakırsanız şifre değişmez.')
                ->schema([
                    TextInput::make('password')
                        ->label('Şifre')
                        ->password()
                        ->revealable()
                        ->minLength(8)
                        // Yalnız oluştururken zorunlu; düzenlemede boş = değiştirme.
                        ->required(fn (string $operation) => $operation === 'create')
                        ->dehydrated(fn (?string $state) => filled($state))
                        ->dehydrateStateUsing(fn (string $state) => Hash::make($state))
                        ->helperText('En az 8 karakter.'),

                    TextInput::make('password_confirmation')
                        ->label('Şifre (tekrar)')
                        ->password()
                        ->revealable()
                        ->same('password')
                        ->required(fn (string $operation) => $operation === 'create')
                        ->dehydrated(false),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')->label('Ad soyad')->searchable()->weight('bold'),
                TextColumn::make('email')->label('E-posta')->searchable()->icon(Heroicon::OutlinedEnvelope)->copyable(),
                TextColumn::make('email_verified_at')->label('Doğrulandı')->dateTime('d M Y H:i')->placeholder('—'),
                TextColumn::make('created_at')->label('Kayıt')->date('d M Y')->sortable(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
