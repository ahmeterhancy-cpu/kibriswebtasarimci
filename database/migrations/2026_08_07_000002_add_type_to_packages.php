<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Paket türü: tek seferlik proje paketi mi, aylık bakım paketi mi.
 *
 * Bakım paketleri önceden `packages.blade.php` içinde sabit dizi olarak
 * duruyordu; panelden fiyat değiştirmek mümkün değildi.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->string('type')->default('project')->after('slug');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn('type');
        });
    }
};
