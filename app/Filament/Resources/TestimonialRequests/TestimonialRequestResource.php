<?php

namespace App\Filament\Resources\TestimonialRequests;

use App\Filament\Resources\TestimonialRequests\Pages\CreateTestimonialRequest;
use App\Filament\Resources\TestimonialRequests\Pages\EditTestimonialRequest;
use App\Filament\Resources\TestimonialRequests\Pages\ListTestimonialRequests;
use App\Models\TestimonialRequest;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

/**
 * Yorum davetleri.
 *
 * Sitedeki her yorumun gerçek bir müşteriden geldiğini kanıtlayabilmek için
 * tek yol bu: davet oluştur, bağlantıyı gönder, müşteri kendi yazsın.
 * Uydurma referans TR'de aldatıcı reklam, UK'de (DMCC Act 2024) doğrudan
 * yasak ve Google tarafında manuel işlem sebebi.
 */
class TestimonialRequestResource extends Resource
{
    protected static ?string $model = TestimonialRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelopeOpen;

    protected static UnitEnum|string|null $navigationGroup = 'Sosyal Kanıt';

    protected static ?string $navigationLabel = 'Yorum Davetleri';

    protected static ?string $modelLabel = 'Davet';

    protected static ?string $pluralModelLabel = 'Yorum Davetleri';

    protected static ?int $navigationSort = 11;

    protected static ?string $recordTitleAttribute = 'client_name';

    /** Cevap bekleyen davet sayısı rozette görünsün. */
    public static function getNavigationBadge(): ?string
    {
        $waiting = static::getModel()::query()
            ->whereNull('completed_at')
            ->where(fn (Builder $q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->count();

        return $waiting > 0 ? (string) $waiting : null;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Kime gönderiliyor')
                ->description('Bu bilgiler forma ön-doldurulmuş gelir; müşteri isterse değiştirebilir.')
                ->schema([
                    TextInput::make('client_name')->label('Ad soyad')->required()->maxLength(190),
                    TextInput::make('company')->label('Firma')->maxLength(190),
                    TextInput::make('email')->label('E-posta')->email()->maxLength(190)
                        ->helperText('Bağlantıyı bu adrese siz gönderirsiniz — sistem otomatik e-posta atmıyor.'),
                    TextInput::make('project')->label('Hangi proje')->maxLength(190)
                        ->placeholder('Girne otel sitesi')
                        ->helperText('Formda hatırlatma olarak görünür. Müşteri neyi değerlendireceğini bilsin.'),
                ])->columns(2),

            Section::make('Kişisel not')->schema([
                Textarea::make('note')
                    ->label('Formda görünecek not')
                    ->rows(3)
                    ->maxLength(500)
                    ->helperText('İsteğe bağlı. "Merhaba Ahmet Bey, siteyi yayına aldıktan sonra…" gibi bir cümle dönüş oranını belirgin artırır.'),
            ]),

            Section::make('Geçerlilik')->schema([
                DateTimePicker::make('expires_at')
                    ->label('Son geçerlilik')
                    ->seconds(false)
                    ->helperText('Boş bırakılırsa 30 gün. Süre dolunca bağlantı kapanır.'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('client_name')->label('Kime')->searchable()->weight('bold')
                    ->description(fn (TestimonialRequest $r) => $r->company),

                TextColumn::make('project')->label('Proje')->limit(30)->placeholder('—')->color('gray'),

                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->state(fn (TestimonialRequest $r) => $r->status())
                    ->color(fn (string $state) => match ($state) {
                        'dolduruldu' => 'success',
                        'süresi doldu' => 'danger',
                        default => 'warning',
                    }),

                TextColumn::make('expires_at')->label('Son gün')->date('d M Y')->placeholder('—')->color('gray'),
                TextColumn::make('created_at')->label('Oluşturuldu')->date('d M Y')->sortable(),
            ])
            ->filters([
                Filter::make('waiting')
                    ->label('Yalnız cevap bekleyenler')
                    ->query(fn (Builder $q) => $q->whereNull('completed_at')
                        ->where(fn (Builder $s) => $s->whereNull('expires_at')->orWhere('expires_at', '>', now()))),
            ])
            ->recordActions([
                // Bağlantıyı kopyalamak bu ekranın asıl işi.
                Action::make('copy')
                    ->label('Bağlantıyı kopyala')
                    ->icon(Heroicon::OutlinedLink)
                    ->color('gray')
                    ->visible(fn (TestimonialRequest $r) => $r->isUsable())
                    ->action(fn () => null)
                    ->extraAttributes(fn (TestimonialRequest $r) => [
                        'x-on:click.prevent' => "navigator.clipboard.writeText('{$r->url()}'); \$tooltip('Kopyalandı', { timeout: 1500 })",
                    ]),

                Action::make('open')
                    ->label('Aç')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->color('gray')
                    ->url(fn (TestimonialRequest $r) => $r->url())
                    ->openUrlInNewTab()
                    ->visible(fn (TestimonialRequest $r) => $r->isUsable()),

                EditAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTestimonialRequests::route('/'),
            'create' => CreateTestimonialRequest::route('/create'),
            'edit' => EditTestimonialRequest::route('/{record}/edit'),
        ];
    }
}
