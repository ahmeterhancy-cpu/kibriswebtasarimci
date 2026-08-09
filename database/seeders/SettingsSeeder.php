<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // group => [key => [tr, en]]
            'branding' => [
                'site_name' => ['Kıbrıs Web Tasarımcı', 'Kıbrıs Web Tasarımcı'],
                'site_description' => [
                    'Kuzey Kıbrıs merkezli web tasarım ve yazılım stüdyosu. Kurumsal site, e-ticaret ve özel web yazılımı — hızlı kurulur, satış getirir.',
                    'Web design and development studio in North Cyprus. Corporate sites, e-commerce and custom web software — built fast, built to convert.',
                ],
            ],
            'contact' => [
                // Merkez ofis. İkinci ofis (Edirne) Şehir Sayfaları'nda tutuluyor:
                // adres bir şehre ait olduğu için orası doğru yer.
                'contact_email' => ['info@kibriswebtasarimci.com', null],
                'contact_phone' => ['+90 548 840 4000', null],
                'contact_whatsapp' => ['905488404000', null],
                'contact_city' => ['Girne', 'Kyrenia'],
                'contact_address' => ['Zafer Sokak No:1, Bellapais, Girne, Kuzey Kıbrıs', 'Zafer Sokak No:1, Bellapais, Kyrenia, North Cyprus'],
                'social_instagram' => ['', null],
                'social_linkedin' => ['', null],
                'social_behance' => ['', null],
                'social_facebook' => ['', null],
                'social_x' => ['', null],
                'social_youtube' => ['', null],
                'social_tiktok' => ['', null],
                'map_query' => ['Bellapais, Girne, Kıbrıs', 'Bellapais, Kyrenia, Cyprus'],
                'map_title' => ['Kıbrıs Web Tasarımcı — Bellapais, Girne ofisi', 'Kıbrıs Web Tasarımcı — Bellapais, Kyrenia office'],
            ],

            // GEO — yapay zekâ motorlarının markayı tarif ederken alıntıladığı
            // metinler. Pazarlama sıfatı değil, doğrulanabilir olgu yazılır.
            'geo' => [
                'ai_entity' => [
                    'Kıbrıs Web Tasarımcı, Kuzey Kıbrıs merkezli bir web tasarım ve yazılım stüdyosudur; kurumsal web siteleri, e-ticaret mağazaları, özel web panelleri ve iOS/Android uygulamaları geliştirir.',
                    'Kıbrıs Web Tasarımcı is a web design and software studio based in North Cyprus, building corporate websites, e-commerce stores, custom web panels and iOS/Android apps.',
                ],
                'ai_summary' => [
                    "Kuzey Kıbrıs merkezli, Kıbrıs ve Türkiye genelinde çalışan bir web tasarım ve yazılım stüdyosu.\n\nHizmetler: kurumsal web sitesi, e-ticaret, özel web yazılımı ve panel, iOS/Android uygulama, SEO ve içerik, UI/UX tasarım, bakım ve destek.\n\nPaketler sabit fiyatlıdır ve fiyatlar sitede açıkça yazar; teklif sihirbazından kapsam seçilerek kesin tutar anında görülebilir. Projeler şablon kullanılmadan sıfırdan tasarlanır. Siteler varsayılan olarak Türkçe ve İngilizce yayınlanır, ek dil opsiyoneldir.\n\nHizmet bölgeleri: Lefkoşa, Girne, Gazimağusa, Güzelyurt, İskele, Lefke; Türkiye'de İstanbul, Ankara, İzmir, Antalya, Bursa (uzaktan).",
                    "A web design and software studio based in North Cyprus, working across Cyprus and Türkiye.\n\nServices: corporate websites, e-commerce, custom web software and panels, iOS/Android apps, SEO and content, UI/UX design, maintenance and support.\n\nPackages are fixed price and published openly; the quote wizard gives an exact total once the scope is selected. Every project is designed from scratch, no templates. Sites ship in Turkish and English by default, further languages optional.\n\nCoverage: Nicosia, Kyrenia, Famagusta, Guzelyurt, Iskele, Lefke; and remotely Istanbul, Ankara, Izmir, Antalya, Bursa.",
                ],
                'ai_contact_note' => [
                    'Teklif ve kesin fiyat için: /teklif-al — bir iş günü içinde dönüş yapılır.',
                    'For a quote and exact pricing: /en/get-quote — reply within one business day.',
                ],
                'llms_enabled' => ['1', null],
            ],

            'footer' => [
                'footer_about' => [
                    'Kuzey Kıbrıs ve Türkiye genelinde kurumsal web sitesi, e-ticaret ve özel yazılım geliştiren web tasarım stüdyosu.',
                    'A web design studio building corporate websites, e-commerce and custom software across North Cyprus and Türkiye.',
                ],
            ],
            'general' => [
                'maintenance_mode' => ['0', null],
                'maintenance_title' => ['Birazdan buradayız.', 'Back shortly.'],
                'maintenance_text' => [
                    'Kısa bir güncelleme yapıyoruz. Birazdan tekrar deneyin.',
                    'We are shipping an update. Please check back soon.',
                ],
            ],
        ];

        foreach ($settings as $group => $items) {
            foreach ($items as $key => [$tr, $en]) {
                Setting::query()->updateOrCreate(
                    ['key' => $key],
                    ['value' => $tr, 'value_en' => $en, 'group' => $group],
                );
            }
        }

        Setting::query()->get()->each(fn (Setting $s) => Setting::forget($s->key));
    }
}
