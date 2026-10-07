<?php

namespace App\Filament\Resources\Addons\Pages;

use App\Filament\Concerns\RedirectsWhenRouteKeyChanges;
use App\Filament\Resources\Addons\AddonResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAddon extends EditRecord
{
    use RedirectsWhenRouteKeyChanges;

    protected static string $resource = AddonResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
