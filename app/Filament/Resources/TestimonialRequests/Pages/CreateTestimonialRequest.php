<?php

namespace App\Filament\Resources\TestimonialRequests\Pages;

use App\Filament\Resources\TestimonialRequests\TestimonialRequestResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateTestimonialRequest extends CreateRecord
{
    protected static string $resource = TestimonialRequestResource::class;

    /** Davet açılır açılmaz bağlantı gösterilsin; asıl iş onu göndermek. */
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Davet hazır')
            ->body('Bağlantı: '.$this->record->url())
            ->success()
            ->persistent();
    }
}
