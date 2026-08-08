<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ofisler — adresin TEK kaynağı.
 *
 * Önceki hâlde ofis bilgisi üç ayrı yere dağılmıştı: merkez adresi ayarlarda,
 * Edirne şehir kaydında, Londra ise hiçbir yerde. Aynı adresi üç yerde
 * güncellemek er ya da geç tutarsızlık üretir — ve adres tutarsızlığı yerel
 * SEO'da doğrudan sıralama kaybı demek.
 *
 * Artık tek yer burası. Şehir sayfaları `locations.office_id` ile buraya
 * bağlanıyor; bağlıysa "buradayız", değilse "uzaktan" gösteriliyor.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');                       // Kıbrıs (Merkez)
            $table->string('name_en')->nullable();
            $table->string('city');                       // Girne
            $table->string('city_en')->nullable();
            $table->string('country');                    // Kuzey Kıbrıs
            $table->string('country_en')->nullable();
            $table->string('country_code', 2)->default('CY');

            $table->string('address');                    // Zafer Sokak No:1, Bellapais
            $table->string('address_en')->nullable();
            $table->string('phone', 60)->nullable();
            $table->string('email')->nullable();
            $table->string('maps_url')->nullable();       // "Yol tarifi" bağlantısı

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->boolean('is_primary')->default(false); // merkez
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        // Şehir sayfaları ofise bağlanıyor; dağınık alanlar kaldırılıyor.
        Schema::table('locations', function (Blueprint $table) {
            $table->foreignId('office_id')->nullable()->after('country_code')->constrained()->nullOnDelete();
            $table->dropColumn(['has_office', 'address', 'address_en', 'phone']);
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('office_id');
            $table->boolean('has_office')->default(false);
            $table->string('address')->nullable();
            $table->string('address_en')->nullable();
            $table->string('phone', 60)->nullable();
        });

        Schema::dropIfExists('offices');
    }
};
