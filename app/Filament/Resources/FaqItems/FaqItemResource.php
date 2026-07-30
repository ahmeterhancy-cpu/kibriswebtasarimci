<?php

namespace App\Filament\Resources\FaqItems;

use App\Filament\Resources\FaqItems\Pages\CreateFaqItem;
use App\Filament\Resources\FaqItems\Pages\EditFaqItem;
use App\Filament\Resources\FaqItems\Pages\ListFaqItems;
use App\Models\FaqItem;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class FaqItemResource extends Resource
{
    protected static ?string $model = FaqItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static UnitEnum|string|null $navigationGroup = 'Sosyal Kanıt';

    protected static ?string $navigationLabel = 'Sıkça Sorulanlar';

    protected static ?string $modelLabel = 'soru';

    protected static ?string $pluralModelLabel = 'sorular';

    protected static ?int $navigationSort = 12;

    protected static ?string $recordTitleAttribute = 'question';

    public const PAGES = [
        'home' => 'Ana sayfa',
        'packages' => 'Paketler',
        'quote' => 'Teklif Al',
        'contact' => 'İletişim',
    ];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Türkçe')->schema([
                TextInput::make('question')->label('Soru')->required()->maxLength(190),
                Textarea::make('answer')->label('Cevap')->required()->rows(4),
            ])->columns(1),

            Section::make('English')->schema([
                TextInput::make('question_en')->label('Question')->maxLength(190),
                Textarea::make('answer_en')->label('Answer')->rows(4),
            ])->columns(1)
                ->collapsed(),

            Section::make('Yerleşim')->schema([
                Select::make('page')
                    ->label('Hangi sayfada')
                    ->options(self::PAGES)
                    ->default('home')
                    ->required()
                    ->helperText('Seçilen sayfanın SSS bölümünde ve yapısal veride (FAQPage) yer alır.'),
                TextInput::make('sort_order')->label('Sıra')->numeric()->default(0),
                Toggle::make('is_active')->label('Yayında')->default(true),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->groups(['page'])
            ->columns([
                TextColumn::make('question')->label('Soru')->searchable()->weight('bold')->limit(70)->wrap(),
                TextColumn::make('page')->label('Sayfa')->badge()
                    ->formatStateUsing(fn (string $state) => self::PAGES[$state] ?? $state),
                ToggleColumn::make('is_active')->label('Yayında'),
            ])
            ->filters([
                SelectFilter::make('page')->label('Sayfa')->options(self::PAGES),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFaqItems::route('/'),
            'create' => CreateFaqItem::route('/create'),
            'edit' => EditFaqItem::route('/{record}/edit'),
        ];
    }
}
