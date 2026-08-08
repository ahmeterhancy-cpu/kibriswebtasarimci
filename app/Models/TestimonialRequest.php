<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TestimonialRequest extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        // Token ve süre panelde elle girilmesin; davet oluşturulurken üretilir.
        static::creating(function (self $request): void {
            $request->token ??= Str::random(48);
            $request->expires_at ??= now()->addDays(30);
        });
    }

    public function testimonial(): BelongsTo
    {
        return $this->belongsTo(Testimonial::class);
    }

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    public function isUsable(): bool
    {
        return $this->completed_at === null
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function url(): string
    {
        return route('testimonial.form', ['request' => $this->token]);
    }

    /** Panelde tek bakışta durum. */
    public function status(): string
    {
        if ($this->completed_at) {
            return 'dolduruldu';
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'süresi doldu';
        }

        return 'bekliyor';
    }
}
