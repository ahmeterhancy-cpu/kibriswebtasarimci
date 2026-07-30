<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['extras' => 'array'];
    }

    public const STATUSES = [
        'new' => 'Yeni',
        'contacted' => 'İletişime geçildi',
        'quoted' => 'Teklif gönderildi',
        'won' => 'Kazanıldı',
        'lost' => 'Kaybedildi',
    ];
}
