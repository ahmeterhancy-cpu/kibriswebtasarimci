<?php

namespace App\Filament\Resources\Packages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use App\Models\Package;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PackagesTable
{
    /**
     * Satır içi anahtar sessizce kaydediyordu; kullanıcı çalışmadığını sanıyordu.
     * Kısa bir onay bildirimi ekliyoruz.
     */
    protected static function toggle(string $name, string $label): ToggleColumn
    {
        return ToggleColumn::make($name)
            ->label($label)
            ->afterStateUpdated(fn () => Notification::make()
                ->title('Kaydedildi')
                ->success()
                ->send());
    }

    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->groups(['type'])
            ->columns([
                TextColumn::make('name')->label('Paket')->searchable()->weight('bold'),
                TextColumn::make('type')->label('Tür')->badge()
                    ->formatStateUsing(fn (string $state) => $state === Package::TYPE_CARE ? 'Bakım / aylık' : 'Proje')
                    ->color(fn (string $state) => $state === Package::TYPE_CARE ? 'info' : 'gray'),
                TextColumn::make('price')->label('Panelsiz')->numeric(0, ',', '.')->suffix(' ₺')->placeholder('—'),
                TextColumn::make('price_with_panel')->label('Panelli')->numeric(0, ',', '.')->suffix(' ₺')->placeholder('—'),
                TextColumn::make('delivery')->label('Teslim')->toggleable(),
                self::toggle('is_ecommerce', 'E-ticaret'),
                self::toggle('is_popular', 'Öne çıkan'),
                self::toggle('is_active', 'Yayında'),
            ])
            ->filters([
                SelectFilter::make('type')->label('Paket türü')->options(Package::TYPES),
                TernaryFilter::make('is_ecommerce')->label('E-ticaret paketi'),
                TernaryFilter::make('is_active')->label('Yayın durumu'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
