<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Panele kim girebilir.
     *
     * Bu arayüz uygulanmazsa Filament yalnızca `local` ortamda giriş verir ve
     * canlıda (APP_ENV=production) herkes 403 alır — sessiz, ama sitenin
     * yönetilemez hâle gelmesi demek.
     *
     * Bu projede `users` tablosundaki HERKES yönetici: kullanıcı hesabı yalnız
     * panelden açılıyor, sitenin ziyaretçi tarafında kayıt yok. Rol ayrımı
     * gerekirse burada kontrol edilecek yer burası.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
