<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('image')->label('Görsel')->disk('public')->height(40),
                TextColumn::make('title')->label('Başlık')->searchable()->weight('bold'),
                TextColumn::make('title_en')->label('English')->searchable()->toggleable()
                    ->placeholder('— çevrilmedi —'),
                TextColumn::make('slug')->label('Adres')->searchable()->color('gray')->toggleable(isToggledHiddenByDefault: true),
                ToggleColumn::make('is_active')->label('Yayında'),
                TextColumn::make('updated_at')->label('Güncellendi')->since()->sortable()->toggleable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Yayın durumu'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ])
            ->emptyStateHeading('Henüz hizmet yok')
            ->emptyStateDescription('İlk hizmeti ekleyin; ana sayfadaki hizmet listesi buradan beslenir.');
    }
}
