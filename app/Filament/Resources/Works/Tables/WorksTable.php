<?php

namespace App\Filament\Resources\Works\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class WorksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('cover')->label('Kapak')->disk('public')->height(40),
                TextColumn::make('title')->label('Proje')->searchable()->weight('bold'),
                TextColumn::make('client')->label('Müşteri')->searchable()->toggleable(),
                TextColumn::make('category.name')->label('Kategori')->badge()->toggleable(),
                TextColumn::make('year')->label('Yıl')->toggleable(),
                ToggleColumn::make('is_featured')->label('Ana sayfada'),
                ToggleColumn::make('is_active')->label('Yayında'),
            ])
            ->filters([
                SelectFilter::make('category_id')->label('Kategori')->relationship('category', 'name'),
                TernaryFilter::make('is_active')->label('Yayın durumu'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ])
            ->emptyStateHeading('Henüz iş eklenmemiş')
            ->emptyStateDescription('İş eklenene kadar ana sayfadaki portfolyo bölümü gizli kalır.');
    }
}
