<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * İçerik tabloları.
 *
 * İki dilli alanlar `_en` sonekiyle ikizlenir; boşsa TR değerine düşülür
 * (bkz. App\Models\Concerns\HasTranslations).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('slug')->unique();
            $table->string('type')->default('blog'); // blog | work
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['type', 'sort_order']);
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->string('slug')->unique();
            $table->string('excerpt', 500)->nullable();
            $table->string('excerpt_en', 500)->nullable();
            $table->longText('body')->nullable();
            $table->longText('body_en')->nullable();
            $table->string('image')->nullable();
            $table->json('features')->nullable();
            $table->json('features_en')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_title_en')->nullable();
            $table->string('seo_description', 500)->nullable();
            $table->string('seo_description_en', 500)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->string('slug')->unique();
            $table->string('client')->nullable();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('summary', 500)->nullable();
            $table->string('summary_en', 500)->nullable();
            $table->longText('body')->nullable();
            $table->longText('body_en')->nullable();
            $table->string('cover')->nullable();
            $table->json('gallery')->nullable();
            $table->string('external_url')->nullable();
            $table->string('year', 10)->nullable();
            $table->json('tags')->nullable();       // uygulanan hizmetler
            $table->json('metrics')->nullable();    // [{label, label_en, value}]
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('slug')->unique();
            $table->string('tagline', 500)->nullable();
            $table->string('tagline_en', 500)->nullable();
            // Fiyat: panelsiz / panelli iki boyut. E-ticarette yalnız `price` dolu.
            $table->unsignedInteger('price')->nullable();
            $table->unsignedInteger('price_with_panel')->nullable();
            $table->unsignedInteger('price_regular')->nullable(); // üstü çizili normal fiyat
            $table->string('currency', 8)->default('₺');
            $table->string('delivery')->nullable();
            $table->string('delivery_en')->nullable();
            $table->json('features')->nullable();
            $table->json('features_en')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_ecommerce')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('excerpt', 500)->nullable();
            $table->string('excerpt_en', 500)->nullable();
            $table->longText('body')->nullable();
            $table->longText('body_en')->nullable();
            $table->string('cover')->nullable();
            $table->string('author')->nullable();
            $table->unsignedInteger('reading_minutes')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_title_en')->nullable();
            $table->string('seo_description', 500)->nullable();
            $table->string('seo_description_en', 500)->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->index(['is_published', 'published_at']);
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('role_en')->nullable();
            $table->string('company')->nullable();
            $table->text('quote');
            $table->text('quote_en')->nullable();
            $table->string('avatar')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('faq_items', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->string('question_en')->nullable();
            $table->text('answer');
            $table->text('answer_en')->nullable();
            $table->string('page')->default('home'); // home | packages | quote
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['page', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faq_items');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('works');
        Schema::dropIfExists('services');
        Schema::dropIfExists('categories');
    }
};
