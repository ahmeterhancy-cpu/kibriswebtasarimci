<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Kendini silip panelden kilitlenmeyi engelle.
            DeleteAction::make()
                ->visible(fn (Model $record) => $record->getKey() !== auth()->id()),
        ];
    }

    /** Şifre alanı formda hiç dolu gelmesin; boşsa değiştirilmez. */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['password'] = null;
        $data['password_confirmation'] = null;

        return $data;
    }
}
