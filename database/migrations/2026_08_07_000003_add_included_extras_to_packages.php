<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Paketin kapsamında zaten bulunan ek modüller.
 *
 * Kurumsal paketin içinde "Blog / haber modülü", "Çoklu dil" ve "Gelişmiş SEO +
 * 5 sayfa SEO metni" varken teklif sihirbazı bunları ayrıca ücretli modül
 * olarak satıyordu — aynı işi ikinci kez faturalamak demekti.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->json('included_extras')->nullable()->after('project_types');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('included_extras');
        });
    }
};
