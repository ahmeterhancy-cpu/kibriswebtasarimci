<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\QuoteRequests\QuoteRequestResource;
use App\Models\QuoteRequest;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestQuotesWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Son teklif talepleri')
            ->query(QuoteRequest::query()->latest()->limit(8))
            ->paginated(false)
            ->columns([
                TextColumn::make('created_at')->label('Tarih')->dateTime('d.m.Y H:i'),
                TextColumn::make('name')->label('Kişi')->weight('bold')
                    ->description(fn ($record) => $record->email),
                TextColumn::make('project_type')->label('Tür')->badge()->placeholder('—'),
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
            ->recordActions([
                Action::make('open')
                    ->label('Aç')
                    ->url(fn (QuoteRequest $record) => QuoteRequestResource::getUrl('edit', ['record' => $record])),
            ])
            ->emptyStateHeading('Henüz teklif talebi yok');
    }
}
