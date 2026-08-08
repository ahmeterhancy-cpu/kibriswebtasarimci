<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'consented_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Formdan gelmiş ve müşterinin yayın izni verdiği yorumlar.
     *
     * Yalnız bunlar yapısal veriye (`Review`) yazılır: elle panele girilen bir
     * metin doğru olabilir ama kanıtı yoktur, arama motoruna "bu bir müşteri
     * değerlendirmesidir" demek için kanıt gerekir.
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('source', 'form')->whereNotNull('consented_at');
    }

    public function isVerified(): bool
    {
        return $this->source === 'form' && $this->consented_at !== null;
    }
}
