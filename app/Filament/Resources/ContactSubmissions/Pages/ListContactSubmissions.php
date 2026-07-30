<?php

namespace App\Filament\Resources\ContactSubmissions\Pages;

use App\Filament\Resources\ContactSubmissions\ContactSubmissionResource;
use Filament\Resources\Pages\ListRecords;

class ListContactSubmissions extends ListRecords
{
    protected static string $resource = ContactSubmissionResource::class;

    // Mesajlar yalnızca siteden gelir; panelden elle oluşturulmaz.
    protected function getHeaderActions(): array
    {
        return [];
    }
}
