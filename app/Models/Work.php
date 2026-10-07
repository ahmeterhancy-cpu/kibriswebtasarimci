<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Work extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'tags' => 'array',
            // Sütun duruyor ama hiçbir yerde gösterilmiyor: proje
            // sayfasındaki "Sonuçlar" bölümü ve paneldeki alan kaldırıldı.
            // Eski kayıtların verisi kaybolmasın diye cast bırakıldı.
            'metrics' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
