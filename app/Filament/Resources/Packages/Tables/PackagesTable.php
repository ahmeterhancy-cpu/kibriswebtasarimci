<?php

namespace App\Filament\Resources\Packages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('name')->label('Paket')->searchable()->weight('bold'),
                TextColumn::make('price')->label('Panelsiz')->numeric(0, ',', '.')->suffix(' ₺')->placeholder('—'),
                TextColumn::make('price_with_panel')->label('Panelli')->numeric(0, ',', '.')->suffix(' ₺')->placeholder('—'),
                TextColumn::make('delivery')->label('Teslim')->toggleable(),
                ToggleColumn::make('is_ecommerce')->label('E-ticaret'),
                ToggleColumn::make('is_popular')->label('Öne çıkan'),
                ToggleColumn::make('is_active')->label('Yayında'),
            ])
            ->filters([
                TernaryFilter::make('is_ecommerce')->label('E-ticaret paketi'),
                TernaryFilter::make('is_active')->label('Yayın durumu'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
