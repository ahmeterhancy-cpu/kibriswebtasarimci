<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Yorum davetleri.
 *
 * Amaç tek: sitedeki her yorumun GERÇEK bir müşteriden, kendi rızasıyla
 * geldiğini kanıtlayabilmek. Uydurma referans hem aldatıcı reklam (TR Reklam
 * Kurulu), hem UK'de doğrudan yasak (DMCC Act 2024), hem de Google tarafında
 * manuel işlem sebebi.
 *
 * Akış: panelden davet oluşturulur → tekil bağlantı müşteriye gönderilir →
 * müşteri kendi yazar ve yayın onayı verir → panelde onaylanınca yayına girer.
 * Token tek kullanımlıktır ve süresi dolar.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonial_requests', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();

            // Kimden isteniyor — daveti gönderirken biliniyor.
            $table->string('client_name');
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('project')->nullable();     // "Girne Otel sitesi" gibi hatırlatma
            $table->text('note')->nullable();          // müşteriye gösterilecek kişisel not

            $table->timestamp('expires_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('testimonial_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();

            $table->index(['completed_at', 'expires_at']);
        });

        Schema::table('testimonials', function (Blueprint $table) {
            // Kanıt alanları: yorumun nereden geldiği ve rızanın ne zaman alındığı.
            $table->string('source')->default('panel')->after('rating');   // panel | form
            $table->string('email')->nullable()->after('source');
            $table->timestamp('consented_at')->nullable()->after('email');
            $table->timestamp('submitted_at')->nullable()->after('consented_at');
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn(['source', 'email', 'consented_at', 'submitted_at']);
        });

        Schema::dropIfExists('testimonial_requests');
    }
};
