<?php

namespace Database\Seeders;

use App\Models\Addon;
use Illuminate\Database\Seeder;

/**
 * Teklif sihirbazı ek modülleri.
 *
 * Katalog modülünün 5.000 ₺'si resmi kampanya fiyat listesinden gelir;
 * diğer ücretli modüllerin fiyatları kurum içi belirlenmiştir ve panelden
 * serbestçe değiştirilebilir.
 */
class AddonSeeder extends Seeder
{
    public function run(): void
    {
        $addons = [
            ['slug' => 'katalog', 'price' => 5000, 'project_types' => ['tanitim', 'kurumsal', 'yenileme'],
                'name' => 'Katalog modülü (WhatsApp sipariş)', 'name_en' => 'Catalogue module (WhatsApp orders)',
                'note' => 'Ürünleri sergiler, siparişi WhatsApp\'tan alır.', 'note_en' => 'Displays products, orders arrive on WhatsApp.'],

            ['slug' => 'dil', 'price' => 4000, 'project_types' => ['tanitim', 'kurumsal', 'eticaret', 'yenileme'],
                'name' => 'İkinci dil (TR / EN)', 'name_en' => 'Second language (TR / EN)'],

            ['slug' => 'blog', 'price' => 3500, 'project_types' => ['tanitim', 'kurumsal', 'eticaret', 'yenileme'],
                'name' => 'Blog / haber modülü', 'name_en' => 'Blog / news module'],

            ['slug' => 'rezervasyon', 'price' => 12000, 'project_types' => ['kurumsal', 'yenileme'],
                'name' => 'Rezervasyon / randevu sistemi', 'name_en' => 'Booking / appointment system'],

            ['slug' => 'uyelik', 'price' => 15000, 'project_types' => ['kurumsal', 'eticaret', 'yenileme'],
                'name' => 'Üyelik / müşteri portalı', 'name_en' => 'Membership / customer portal'],

            ['slug' => 'seo', 'price' => 6000, 'project_types' => ['tanitim', 'kurumsal', 'eticaret', 'yenileme'],
                'name' => 'SEO içerik paketi (5 sayfa)', 'name_en' => 'SEO content package (5 pages)'],

            ['slug' => 'kimlik', 'price' => 9000, 'project_types' => ['tanitim', 'kurumsal', 'eticaret', 'yenileme'],
                'name' => 'Logo ve marka kimliği', 'name_en' => 'Logo and brand identity'],

            ['slug' => 'mobilapp', 'price' => 65000, 'project_types' => ['kurumsal', 'eticaret', 'yenileme'],
                'name' => 'iOS + Android uygulama', 'name_en' => 'iOS + Android app'],

            /* Mobil uygulama projeleri — fiyatsız kapsam maddeleri. */
            ['slug' => 'push', 'price' => null, 'project_types' => ['mobil'],
                'name' => 'Push bildirim', 'name_en' => 'Push notifications'],
            ['slug' => 'uygulama-uyelik', 'price' => null, 'project_types' => ['mobil'],
                'name' => 'Giriş ve üyelik', 'name_en' => 'Login and user accounts'],
            ['slug' => 'magaza-yayin', 'price' => null, 'project_types' => ['mobil'],
                'name' => 'App Store + Google Play yayını', 'name_en' => 'App Store + Google Play submission'],
            ['slug' => 'uygulama-odeme', 'price' => null, 'project_types' => ['mobil'],
                'name' => 'Uygulama içi ödeme', 'name_en' => 'In-app payment'],

            /* Özel yazılım projeleri — fiyatsız kapsam maddeleri. */
            ['slug' => 'rol-yetki', 'price' => null, 'project_types' => ['yazilim'],
                'name' => 'Rol bazlı yetkilendirme', 'name_en' => 'Role-based permissions'],
            ['slug' => 'raporlama', 'price' => null, 'project_types' => ['yazilim'],
                'name' => 'Raporlama ekranları', 'name_en' => 'Reporting screens'],
            ['slug' => 'entegrasyon', 'price' => null, 'project_types' => ['yazilim', 'mobil'],
                'name' => 'Mevcut sisteme entegrasyon', 'name_en' => 'Integration with an existing system'],
            ['slug' => 'api', 'price' => null, 'project_types' => ['yazilim'],
                'name' => 'Dışarıya API', 'name_en' => 'API for third parties'],
        ];

        foreach ($addons as $i => $addon) {
            Addon::query()->updateOrCreate(
                ['slug' => $addon['slug']],
                array_merge(
                    ['note' => null, 'note_en' => null, 'is_active' => true, 'sort_order' => $i + 1],
                    $addon,
                ),
            );
        }
    }
}
