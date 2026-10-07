<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sektör sayfalarına sık sorulanlar.
 *
 * İki işi birden yapıyor: ziyaretçinin teklif istemeden önce sorduğu
 * soruları cevaplıyor ve `FAQPage` yapısal verisiyle arama sonucunda
 * açılır cevap kutusu olarak çıkma şansı veriyor.
 *
 * Biçim: [{"q": "...", "a": "..."}, ...]
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sectors', function (Blueprint $table) {
            $table->json('faq')->nullable()->after('features_en');
            $table->json('faq_en')->nullable()->after('faq');
        });
    }

    public function down(): void
    {
        Schema::table('sectors', function (Blueprint $table) {
            $table->dropColumn(['faq', 'faq_en']);
        });
    }
};
