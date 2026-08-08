<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sektör sayfaları — içeriğin asıl ayrıştığı yer.
 *
 * Şehirler birbirinden zor ayrışır (aynı hizmet, farklı isim). Sektörler ise
 * GERÇEKTEN farklıdır: otelin rezervasyona, emlakçının ilan sistemine,
 * restoranın menü ve siparişe ihtiyacı var. Bu yüzden arama trafiğinin ve
 * yapay zekâ alıntılarının hedeflendiği yer burasıdır; şehir sayfaları bu
 * sayfalara açılan kapı olarak çalışır.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sectors', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');                     // Otel & Konaklama
            $table->string('name_en')->nullable();

            $table->string('headline')->nullable();     // H1
            $table->string('headline_en')->nullable();
            $table->string('intro', 600)->nullable();
            $table->string('intro_en', 600)->nullable();
            $table->longText('body')->nullable();
            $table->longText('body_en')->nullable();

            // "Bu sektörde site neyi çözmeli" — sayfanın en çok okunan kısmı.
            $table->json('needs')->nullable();
            $table->json('needs_en')->nullable();

            // Sektöre özel modül/özellik listesi (rezervasyon formu, ilan filtresi…)
            $table->json('features')->nullable();
            $table->json('features_en')->nullable();

            $table->string('icon', 8)->nullable();      // tek emoji, listede işaret

            $table->string('seo_title')->nullable();
            $table->string('seo_title_en')->nullable();
            $table->string('seo_description', 500)->nullable();
            $table->string('seo_description_en', 500)->nullable();

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sectors');
    }
};
