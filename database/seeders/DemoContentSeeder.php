<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Testimonial;
use App\Models\Work;
use Illuminate\Database\Seeder;

/**
 * ÖRNEK (DEMO) VERİ — gerçek müşteri işi değildir.
 *
 * Portfolyo, referans logosu ve müşteri yorumu bölümlerinin tasarımını yerelde
 * görebilmek için vardır. `DatabaseSeeder` bunu ÇAĞIRMAZ; bilerek elle
 * çalıştırılır:
 *
 *   php artisan db:seed --class=DemoContentSeeder
 *
 * Canlıya çıkmadan önce admin panelinden tamamen silin ve yerine gerçek
 * işlerinizi girin. Uydurma müşteri adı/yorumu yayınlamayın.
 */
class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $corporate = Category::query()->where('slug', 'kurumsal-site')->value('id');
        $store = Category::query()->where('slug', 'magaza')->value('id');
        $app = Category::query()->where('slug', 'web-uygulama')->value('id');

        $works = [
            [
                'slug' => 'ornek-kurumsal-site',
                'title' => 'Örnek Kurumsal Site',
                'title_en' => 'Sample Corporate Site',
                'client' => 'Örnek Müşteri',
                'category_id' => $corporate,
                'year' => '2026',
                'summary' => 'Bu bir örnek kayıttır. Gerçek vaka çalışmanızı admin panelinden ekleyin.',
                'summary_en' => 'This is a sample record. Add your real case study from the admin panel.',
                'tags' => ['Kurumsal Web', 'UI/UX', 'SEO'],
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'ornek-e-ticaret',
                'title' => 'Örnek E-Ticaret',
                'title_en' => 'Sample Store',
                'client' => 'Örnek Müşteri',
                'category_id' => $store,
                'year' => '2026',
                'summary' => 'Bu bir örnek kayıttır. Gerçek vaka çalışmanızı admin panelinden ekleyin.',
                'summary_en' => 'This is a sample record. Add your real case study from the admin panel.',
                'tags' => ['E-Ticaret', 'Ödeme Entegrasyonu'],
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'ornek-web-uygulama',
                'title' => 'Örnek Web Uygulaması',
                'title_en' => 'Sample Web App',
                'client' => 'Örnek Müşteri',
                'category_id' => $app,
                'year' => '2026',
                'summary' => 'Bu bir örnek kayıttır. Gerçek vaka çalışmanızı admin panelinden ekleyin.',
                'summary_en' => 'This is a sample record. Add your real case study from the admin panel.',
                'tags' => ['Web Yazılım', 'Panel'],
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'slug' => 'ornek-tanitim-sitesi',
                'title' => 'Örnek Tanıtım Sitesi',
                'title_en' => 'Sample Landing Site',
                'client' => 'Örnek Müşteri',
                'category_id' => $corporate,
                'year' => '2026',
                'summary' => 'Bu bir örnek kayıttır. Gerçek vaka çalışmanızı admin panelinden ekleyin.',
                'summary_en' => 'This is a sample record. Add your real case study from the admin panel.',
                'tags' => ['Onepage', 'Kampanya'],
                'is_featured' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($works as $work) {
            Work::query()->updateOrCreate(['slug' => $work['slug']], $work + ['is_active' => true]);
        }

        foreach (range(1, 6) as $i) {
            Brand::query()->updateOrCreate(
                ['name' => "Örnek Marka {$i}"],
                ['sort_order' => $i, 'is_active' => true],
            );
        }

        $testimonials = [
            ['Örnek Referans 1', 'Kurucu', 'Founder', 'Bu bir örnek yorumdur; gerçek müşteri görüşünüzü admin panelinden girin.', 'This is a sample testimonial; enter real client feedback from the admin panel.'],
            ['Örnek Referans 2', 'Pazarlama Müdürü', 'Marketing Manager', 'Bu bir örnek yorumdur; gerçek müşteri görüşünüzü admin panelinden girin.', 'This is a sample testimonial; enter real client feedback from the admin panel.'],
            ['Örnek Referans 3', 'İşletme Sahibi', 'Business Owner', 'Bu bir örnek yorumdur; gerçek müşteri görüşünüzü admin panelinden girin.', 'This is a sample testimonial; enter real client feedback from the admin panel.'],
        ];

        foreach ($testimonials as $i => [$name, $role, $roleEn, $quote, $quoteEn]) {
            Testimonial::query()->updateOrCreate(
                ['name' => $name],
                [
                    'role' => $role,
                    'role_en' => $roleEn,
                    'company' => 'Örnek Şirket',
                    'quote' => $quote,
                    'quote_en' => $quoteEn,
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
