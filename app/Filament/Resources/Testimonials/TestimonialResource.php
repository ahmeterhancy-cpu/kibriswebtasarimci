<?php

namespace App\Filament\Resources\Testimonials;

use App\Filament\Resources\Testimonials\Pages\CreateTestimonial;
use App\Filament\Resources\Testimonials\Pages\EditTestimonial;
use App\Filament\Resources\Testimonials\Pages\ListTestimonials;
use App\Models\Testimonial;
use BackedEnum;
use Filament\Actions\Action;
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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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
                Toggle::make('is_active')
                    ->label('Yayında')
                    ->default(true)
                    ->helperText('Müşteri formundan gelen yorumlar kapalı gelir; okuyup açtığınızda sitede görünür.'),
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
                TextColumn::make('source')
                    ->label('Kaynak')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => $state === 'form' ? 'müşteri formu' : 'elle')
                    ->color(fn (?string $state) => $state === 'form' ? 'success' : 'gray')
                    ->description(fn (Testimonial $record) => $record->consented_at?->format('d.m.Y')),
                ToggleColumn::make('is_active')->label('Yayında'),
            ])
            ->filters([
                Filter::make('pending')
                    ->label('Onay bekleyenler')
                    ->query(fn (Builder $q) => $q->where('source', 'form')->where('is_active', false)),
            ])
            ->recordActions([
                // Formdan gelen yorum, okunup onaylanana kadar yayında değil.
                Action::make('approve')
                    ->label('Onayla ve yayınla')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Yorum yayınlansın mı?')
                    ->modalDescription('Müşterinin yazdığı hâliyle yayınlanacak. Yazım hatası düzeltmek serbest; anlamı değiştiren bir düzenleme yapmayın — yayınlanan metin müşterinin onayladığı metin olmalı.')
                    ->visible(fn (Testimonial $record) => ! $record->is_active)
                    ->action(fn (Testimonial $record) => $record->update(['is_active' => true])),
                EditAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->emptyStateHeading('Henüz yorum yok')
            ->emptyStateDescription('Yorum eklenene kadar ana sayfadaki referans bölümü gizli kalır. Gerçek yorum toplamak için Yorum Davetleri\'nden bağlantı oluşturup müşterinize gönderin.');
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
