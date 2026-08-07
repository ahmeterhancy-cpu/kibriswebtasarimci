<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pakette sunulacak ek modüller.
 *
 * Önce modülün kendisi "hangi proje türlerinde çıkayım" diyordu; oysa müşteri
 * 2. adımda bir PAKET seçiyor. Doğru ilişki paket → modül. Modüldeki
 * `project_types` artık yalnız paket seçilmeyen durumlar için yedek kalıyor
 * (mobil uygulama, özel yazılım, "emin değilim").
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->json('available_addons')->nullable()->after('included_extras');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('available_addons');
        });
    }
};
