<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Şehir / bölge sayfaları — yerel SEO'nun bel kemiği.
 *
 * "kıbrıs web tasarım" gibi tek bir genel kelime yerine "girne web tasarım",
 * "lefkoşa e-ticaret" gibi şehir bazlı aramalarda görünmek için her şehrin
 * kendi sayfası var. İçerik şehre göre GERÇEKTEN farklı olmalı; aynı metnin
 * şehir adı değiştirilmiş kopyaları arama motorlarınca kapı sayfası sayılır.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');                        // Girne
            $table->string('name_en')->nullable();         // Kyrenia
            $table->string('region')->default('kktc');     // kktc | turkiye
            $table->string('country_code', 2)->default('CY');

            $table->string('headline')->nullable();        // sayfa başlığı (H1)
            $table->string('headline_en')->nullable();
            $table->string('intro', 600)->nullable();      // giriş paragrafı
            $table->string('intro_en', 600)->nullable();
            $table->longText('body')->nullable();          // şehre özel uzun metin
            $table->longText('body_en')->nullable();

            $table->json('highlights')->nullable();        // o pazara dair maddeler
            $table->json('highlights_en')->nullable();

            $table->string('seo_title')->nullable();
            $table->string('seo_title_en')->nullable();
            $table->string('seo_description', 500)->nullable();
            $table->string('seo_description_en', 500)->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'region', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
