<?php

namespace App\Filament\Resources\TestimonialRequests\Pages;

use App\Filament\Resources\TestimonialRequests\TestimonialRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTestimonialRequest extends EditRecord
{
    protected static string $resource = TestimonialRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
