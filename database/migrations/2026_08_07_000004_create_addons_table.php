<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Teklif sihirbazının ek modülleri.
 *
 * Önce Blade dosyasında, sonra config'de sabit duruyordu; müşteriye fiyat
 * gösteren bir liste olduğu için panelden yönetilmesi gerekiyor. Yeni modül
 * eklemek artık kod değişikliği istemiyor.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('note', 500)->nullable();
            $table->string('note_en', 500)->nullable();
            // Boş bırakılırsa modül ücretsizdir: seçilebilir ama toplama girmez.
            // Liste fiyatı olmayan proje türlerinde kapsamı anlatmaya yarar.
            $table->unsignedInteger('price')->nullable();
            $table->json('project_types')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addons');
    }
};
