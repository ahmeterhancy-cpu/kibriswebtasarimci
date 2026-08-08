<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Şehirde fiilen ofis var mı?
 *
 * Önceden "yüz yüze görüşebiliriz" ayrımı bölgeye bakıyordu (kktc = buradayız,
 * turkiye = uzaktan). Bu yanlıştı: Edirne'de gerçek bir ofis var. Ayrım artık
 * bölgeye değil, ofis varlığına bağlı.
 *
 * `address` dolu olan şehirlerde yapısal veriye gerçek PostalAddress basılır;
 * boş olanlarda yalnız `areaServed` kalır. Olmayan bir adresi yazmak arama
 * motoruna yanlış konum sinyali verir.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->boolean('has_office')->default(false)->after('country_code');
            $table->string('address')->nullable()->after('has_office');
            $table->string('address_en')->nullable()->after('address');
            $table->string('phone', 60)->nullable()->after('address_en');
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['has_office', 'address', 'address_en', 'phone']);
        });
    }
};
