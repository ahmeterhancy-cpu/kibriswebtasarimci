<?php

namespace App\Filament\Resources\BlogPosts\Pages;

use App\Filament\Concerns\RedirectsWhenRouteKeyChanges;
use App\Filament\Resources\BlogPosts\BlogPostResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBlogPost extends EditRecord
{
    use RedirectsWhenRouteKeyChanges;

    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
