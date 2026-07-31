<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Form kayıtları ve site ayarları. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('project_type')->nullable();   // tanitim | kurumsal | eticaret | yenileme
            $table->string('package')->nullable();        // seçilen paket slug'ı
            $table->json('extras')->nullable();           // ek modüller
            $table->string('budget')->nullable();
            $table->string('timeline')->nullable();
            // Sihirbazda seçilen liste fiyatlarının kesin toplamı (KDV hariç).
            // Liste fiyatı olmayan türlerde (mobil uygulama, özel yazılım) boş kalır.
            $table->unsignedInteger('quote_total')->nullable();
            $table->text('message')->nullable();
            $table->string('locale', 5)->default('tr');
            $table->string('status')->default('new');     // new | contacted | quoted | won | lost
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('locale', 5)->default('tr');
            $table->string('status')->default('new');     // new | read | replied
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->longText('value_en')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('contact_submissions');
        Schema::dropIfExists('quote_requests');
    }
};
