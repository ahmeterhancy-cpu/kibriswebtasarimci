<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Eski adresler.
 *
 * Bir kaydın adresi değiştiğinde eskisi buraya yazılıyor; o adrese gelen
 * istek 301 ile yenisine taşınıyor. Olmadığında dışarıdan verilmiş her
 * bağlantı 404'e düşer ve arama motorundaki birikim sıfırlanır.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slug_history', function (Blueprint $table) {
            $table->id();
            $table->string('model_type', 191);
            $table->unsignedBigInteger('model_id');
            $table->string('slug', 191);
            $table->timestamps();

            // Aynı tür içinde bir adres yalnızca bir kayda ait olabilir.
            $table->unique(['model_type', 'slug']);
            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slug_history');
    }
};
