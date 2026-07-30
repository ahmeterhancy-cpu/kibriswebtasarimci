<?php

namespace App\Filament\Widgets;

use App\Models\BlogPost;
use App\Models\ContactSubmission;
use App\Models\QuoteRequest;
use App\Models\Work;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $newQuotes = QuoteRequest::query()->where('status', 'new')->count();
        $monthQuotes = QuoteRequest::query()->where('created_at', '>=', now()->startOfMonth())->count();
        $newMessages = ContactSubmission::query()->where('status', 'new')->count();

        return [
            Stat::make('Bekleyen teklif talebi', $newQuotes)
                ->description($monthQuotes.' talep bu ay geldi')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($newQuotes > 0 ? 'danger' : 'success'),

            Stat::make('Okunmamış mesaj', $newMessages)
                ->description('İletişim formundan')
                ->color($newMessages > 0 ? 'warning' : 'success'),

            Stat::make('Yayındaki iş', Work::query()->where('is_active', true)->count())
                ->description(BlogPost::query()->where('is_published', true)->count().' yayında blog yazısı')
                ->color('gray'),
        ];
    }
}
