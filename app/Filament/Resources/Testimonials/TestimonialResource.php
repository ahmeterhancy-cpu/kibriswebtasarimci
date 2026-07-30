<?php

namespace App\Filament\Resources\Testimonials;

use App\Filament\Resources\Testimonials\Pages\CreateTestimonial;
use App\Filament\Resources\Testimonials\Pages\EditTestimonial;
use App\Filament\Resources\Testimonials\Pages\ListTestimonials;
use App\Models\Testimonial;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;

    protected static UnitEnum|string|null $navigationGroup = 'Sosyal Kanıt';

    protected static ?string $navigationLabel = 'Müşteri Yorumları';

    protected static ?string $modelLabel = 'yorum';

    protected static ?string $pluralModelLabel = 'yorumlar';

    protected static ?int $navigationSort = 11;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Kim söyledi')->schema([
                TextInput::make('name')->label('Ad Soyad')->required()->maxLength(190),
                TextInput::make('company')->label('Şirket')->maxLength(190),
                TextInput::make('role')->label('Ünvan (TR)')->maxLength(190),
                TextInput::make('role_en')->label('Role (EN)')->maxLength(190),
                FileUpload::make('avatar')
                    ->label('Fotoğraf')
                    ->image()
                    ->avatar()
                    ->disk('public')
                    ->directory('testimonials')
                    ->visibility('public')
                    ->maxSize(1024),
            ])->columns(2),

            Section::make('Ne söyledi')
                ->description('Gerçek müşteri görüşü girin. Uydurma yorum yayınlamak hem yanıltıcı hem risklidir.')
                ->schema([
                    Textarea::make('quote')->label('Yorum (TR)')->required()->rows(3),
                    Textarea::make('quote_en')->label('Quote (EN)')->rows(3),
                ])->columns(1),

            Section::make('Yayın')->schema([
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
                ImageColumn::make('avatar')->label('')->disk('public')->circular(),
                TextColumn::make('name')->label('Kişi')->searchable()->weight('bold')
                    ->description(fn ($record) => collect([$record->role, $record->company])->filter()->join(' · ')),
                TextColumn::make('quote')->label('Yorum')->limit(70)->wrap(),
                ToggleColumn::make('is_active')->label('Yayında'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->emptyStateHeading('Henüz yorum yok')
            ->emptyStateDescription('Yorum eklenene kadar ana sayfadaki referans bölümü gizli kalır.');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTestimonials::route('/'),
            'create' => CreateTestimonial::route('/create'),
            'edit' => EditTestimonial::route('/{record}/edit'),
        ];
    }
}
