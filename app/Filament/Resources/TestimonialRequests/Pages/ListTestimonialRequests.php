<?php

namespace App\Filament\Resources\TestimonialRequests\Pages;

use App\Filament\Resources\TestimonialRequests\TestimonialRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTestimonialRequests extends ListRecords
{
    protected static string $resource = TestimonialRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Davet oluştur'),
        ];
    }
}
