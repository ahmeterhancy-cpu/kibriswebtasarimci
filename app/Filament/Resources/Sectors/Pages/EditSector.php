<?php

namespace App\Filament\Resources\Sectors\Pages;

use App\Filament\Concerns\RedirectsWhenRouteKeyChanges;
use App\Filament\Resources\Sectors\SectorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSector extends EditRecord
{
    use RedirectsWhenRouteKeyChanges;

    protected static string $resource = SectorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
