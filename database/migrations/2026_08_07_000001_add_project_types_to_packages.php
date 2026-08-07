<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Paketin teklif sihirbazında hangi proje türlerinde çıkacağı.
 *
 * Önceden bu eşleme `quote.blade.php` içinde slug listesi olarak gömülüydü:
 * panelden yeni paket eklendiğinde sihirbazda hiçbir yerde görünmüyordu.
 * Artık paketin kendi alanı; eşleme tamamen admin'den yönetiliyor.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->json('project_types')->nullable()->after('is_ecommerce');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('project_types');
        });
    }
};
