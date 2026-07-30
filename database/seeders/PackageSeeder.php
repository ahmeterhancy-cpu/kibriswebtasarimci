<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

/**
 * Fiyatlar Kuzey Kıbrıs (₺) kampanya listesinden alınmıştır; hepsi +KDV.
 * `price` panelsiz, `price_with_panel` panelli boyuttur.
 * E-Ticaret paketlerinde panel standarttır → yalnız `price` doludur.
 */
class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'slug' => 'hizli-baslangic',
                'name' => 'Hızlı Başlangıç',
                'name_en' => 'Quick Start',
                'tagline' => 'Tek sayfalık mini site. Yeni açılan işletmeler ve kampanya sayfaları için.',
                'tagline_en' => 'A single-page mini site. For new businesses and campaign pages.',
                'price' => 6900,
                'price_with_panel' => 9900,
                'price_regular' => null,
                'delivery' => '3-5 iş günü',
                'delivery_en' => '3-5 working days',
                'features' => ['Tek sayfa tasarım', 'Mobil uyumlu', 'İletişim formu', 'WhatsApp butonu', 'SSL + hosting + domain (1 yıl)', 'Google Analytics'],
                'features_en' => ['Single-page design', 'Mobile responsive', 'Contact form', 'WhatsApp button', 'SSL + hosting + domain (1 year)', 'Google Analytics'],
                'sort_order' => 1,
            ],
            [
                'slug' => 'basic-onepage',
                'name' => 'Basic — Onepage',
                'name_en' => 'Basic — Onepage',
                'tagline' => 'Tek sayfada tüm hikâyeniz. Hizmet, referans, iletişim tek akışta.',
                'tagline_en' => 'Your whole story on one page. Services, references and contact in one flow.',
                'price' => 8900,
                'price_with_panel' => 11900,
                'price_regular' => 15000,
                'delivery' => '4-6 iş günü',
                'delivery_en' => '4-6 working days',
                'features' => ['Genişletilmiş tek sayfa', 'Hizmet ve referans bölümleri', 'Galeri', 'İletişim formu + harita', 'Temel SEO', 'SSL + hosting + domain (1 yıl)'],
                'features_en' => ['Extended single page', 'Services and references sections', 'Gallery', 'Contact form + map', 'Baseline SEO', 'SSL + hosting + domain (1 year)'],
                'sort_order' => 2,
            ],
            [
                'slug' => 'kurumsal',
                'name' => 'Kurumsal',
                'name_en' => 'Corporate',
                'tagline' => 'Çok sayfalı kurumsal site. Blog, hizmet detayları ve kariyer sayfasıyla.',
                'tagline_en' => 'A multi-page corporate site with blog, service details and careers.',
                'price' => 17900,
                'price_with_panel' => 22900,
                'price_regular' => 30000,
                'delivery' => '8-12 iş günü',
                'delivery_en' => '8-12 working days',
                'features' => ['Sınırsız sayfa yapısı', 'Blog / haber modülü', 'Hizmet detay sayfaları', 'Çoklu dil (TR + EN)', 'Gelişmiş SEO + 5 sayfa SEO metni', 'Yönetim paneli eğitimi'],
                'features_en' => ['Unlimited page structure', 'Blog / news module', 'Service detail pages', 'Bilingual (TR + EN)', 'Advanced SEO + 5 SEO pages', 'Admin panel training'],
                'is_popular' => true,
                'sort_order' => 3,
            ],
            [
                'slug' => 'e-ticaret-baslangic',
                'name' => 'E-Ticaret — Başlangıç',
                'name_en' => 'E-Commerce — Start',
                'tagline' => 'Temel panel, yaklaşık 50 ürün. Online satışa ilk adım.',
                'tagline_en' => 'Core panel, around 50 products. Your first step into online sales.',
                'price' => 34900,
                'price_with_panel' => null,
                'price_regular' => null,
                'delivery' => '15-20 iş günü',
                'delivery_en' => '15-20 working days',
                'features' => ['Ürün ve kategori yönetimi', '~50 ürün kapasitesi', 'Sipariş takibi', 'Online ödeme entegrasyonu', 'Kargo entegrasyonu', 'Panel kullanım eğitimi'],
                'features_en' => ['Product and category management', '~50 product capacity', 'Order tracking', 'Online payment integration', 'Shipping integration', 'Panel training'],
                'is_ecommerce' => true,
                'sort_order' => 4,
            ],
            [
                'slug' => 'e-ticaret-pro',
                'name' => 'E-Ticaret — Pro',
                'name_en' => 'E-Commerce — Pro',
                'tagline' => 'Sınırsız ürün, gelişmiş panel, raporlama ve bildirimler.',
                'tagline_en' => 'Unlimited products, advanced panel, reporting and notifications.',
                'price' => 44900,
                'price_with_panel' => null,
                'price_regular' => 60000,
                'delivery' => '18-25 iş günü',
                'delivery_en' => '18-25 working days',
                'features' => ['Sınırsız ürün ve varyant', 'Gelişmiş yönetim paneli', 'SMS + e-posta bildirimleri', 'Kampanya ve kupon sistemi', 'Gelişmiş satış raporları', 'İlk 20 ürün girişi bizden'],
                'features_en' => ['Unlimited products and variants', 'Advanced admin panel', 'SMS + email notifications', 'Campaigns and coupons', 'Advanced sales reporting', 'First 20 products entered by us'],
                'is_ecommerce' => true,
                'is_popular' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($packages as $package) {
            Package::query()->updateOrCreate(
                ['slug' => $package['slug']],
                $package + ['currency' => '₺', 'is_active' => true],
            );
        }
    }
}
