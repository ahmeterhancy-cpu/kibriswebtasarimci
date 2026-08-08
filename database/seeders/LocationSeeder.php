<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

/**
 * Şehir sayfaları — GENEL içerik.
 *
 * Önceki sürümde her şehre o pazara dair iddialar yazılmıştı (Girne'de turizm,
 * Magosa'da öğrenci, Güzelyurt'ta narenciye…). Bunlar makul tahminlerdi ama
 * doğrulanmamıştı; müşteri adına söylenmemesi gereken şeylerdi.
 *
 * Yeni kurgu: şehir sayfaları genel ve dürüst kalır, asıl ayrışan içerik
 * SEKTÖR sayfalarındadır (bkz. SectorSeeder). Şehir sayfasının işi, o şehirden
 * gelen ziyaretçiyi doğru sektör sayfasına yönlendirmek ve hizmet verilen
 * bölgeyi netleştirmek.
 *
 * Bu yüzden burada yalnızca DOĞRULANABİLİR alanlar dolduruluyor: şehrin adı,
 * bölgesi, koordinatı ve tek cümlelik genel giriş. Uzun metin görünümde
 * bölgeye göre üretiliyor — 11 satırda aynı metnin kopyasını tutmuyoruz.
 * `body` alanı panelde açık: o şehre dair GERÇEK bir şey yazılacaksa oraya.
 *
 * Koordinat notu: yalnızca fiilen bulunduğumuz KKTC şehirlerinde koordinat
 * var. Ofisin olmadığı bir şehre koordinat basmak arama motoruna yanlış konum
 * sinyali verir; Türkiye şehirlerinde `areaServed` yeterli.
 */
class LocationSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->locations() as $i => $location) {
            Location::query()->updateOrCreate(
                ['slug' => $location['slug']],
                $location + [
                    'sort_order' => $i + 1,
                    'is_active' => true,
                    // Sektöre özgü eski metinler temizleniyor.
                    'body' => null,
                    'body_en' => null,
                    'highlights' => null,
                    'highlights_en' => null,
                ],
            );
        }
    }

    private function locations(): array
    {
        $kktc = fn (string $name, string $nameEn) => [
            'intro' => $name.' ve çevresinde kurumsal web sitesi, e-ticaret ve özel yazılım. Merkezimiz Kuzey Kıbrıs\'ta; isterseniz yüz yüze görüşüyoruz.',
            'intro_en' => 'Corporate websites, e-commerce and custom software in '.$nameEn.' and the surrounding area. We are based in North Cyprus and can meet in person.',
            'seo_title' => $name.' Web Tasarım | Kurumsal Site, E-Ticaret ve Yazılım',
            'seo_title_en' => 'Web Design in '.$nameEn.' | Corporate Sites, E-Commerce, Software',
            'seo_description' => $name.'\'da web sitesi, e-ticaret ve özel yazılım. Şablon yok, sabit fiyat, TR + EN yayın. Sektörünüze göre kapsam.',
            'seo_description_en' => 'Websites, e-commerce and custom software in '.$nameEn.'. No templates, fixed prices, TR + EN. Scope shaped by your industry.',
        ];

        $tr = fn (string $name) => [
            'intro' => $name.'\'daki işletmelerle uzaktan çalışıyoruz. Görüşme, tasarım onayı ve teslim süreci tamamen çevrimiçi yürüyor.',
            'intro_en' => 'We work remotely with businesses in '.$name.'. Meetings, design approval and delivery all run online.',
            'seo_title' => $name.' Web Tasarım | Kurumsal Site, E-Ticaret ve Yazılım',
            'seo_title_en' => 'Web Design in '.$name.' | Corporate Sites, E-Commerce, Software',
            'seo_description' => $name.'\'da web sitesi, e-ticaret ve özel yazılım. Uzaktan çalışıyoruz; şablon yok, sabit fiyat, TR + EN yayın.',
            'seo_description_en' => 'Websites, e-commerce and custom software in '.$name.'. Remote delivery, no templates, fixed prices, TR + EN.',
        ];

        return [
            /* ── Kuzey Kıbrıs — merkezimizin bulunduğu bölge ─────────────── */
            ['slug' => 'lefkosa', 'name' => 'Lefkoşa', 'name_en' => 'Nicosia', 'region' => 'kktc', 'country_code' => 'CY',
                'latitude' => 35.1856, 'longitude' => 33.3823,
                'headline' => 'Lefkoşa web tasarım', 'headline_en' => 'Web design in Nicosia'] + $kktc('Lefkoşa', 'Nicosia'),

            ['slug' => 'girne', 'name' => 'Girne', 'name_en' => 'Kyrenia', 'region' => 'kktc', 'country_code' => 'CY',
                'latitude' => 35.3364, 'longitude' => 33.3192,
                'headline' => 'Girne web tasarım', 'headline_en' => 'Web design in Kyrenia'] + $kktc('Girne', 'Kyrenia'),

            ['slug' => 'gazimagusa', 'name' => 'Gazimağusa', 'name_en' => 'Famagusta', 'region' => 'kktc', 'country_code' => 'CY',
                'latitude' => 35.1250, 'longitude' => 33.9500,
                'headline' => 'Gazimağusa web tasarım', 'headline_en' => 'Web design in Famagusta'] + $kktc('Gazimağusa', 'Famagusta'),

            ['slug' => 'guzelyurt', 'name' => 'Güzelyurt', 'name_en' => 'Guzelyurt', 'region' => 'kktc', 'country_code' => 'CY',
                'latitude' => 35.1989, 'longitude' => 32.9925,
                'headline' => 'Güzelyurt web tasarım', 'headline_en' => 'Web design in Guzelyurt'] + $kktc('Güzelyurt', 'Guzelyurt'),

            ['slug' => 'iskele', 'name' => 'İskele', 'name_en' => 'Iskele', 'region' => 'kktc', 'country_code' => 'CY',
                'latitude' => 35.2889, 'longitude' => 33.8917,
                'headline' => 'İskele web tasarım', 'headline_en' => 'Web design in Iskele'] + $kktc('İskele', 'Iskele'),

            ['slug' => 'lefke', 'name' => 'Lefke', 'name_en' => 'Lefke', 'region' => 'kktc', 'country_code' => 'CY',
                'latitude' => 35.1103, 'longitude' => 32.8464,
                'headline' => 'Lefke web tasarım', 'headline_en' => 'Web design in Lefke'] + $kktc('Lefke', 'Lefke'),

            /* ── Türkiye — uzaktan. Koordinat YOK, ofisimiz orada değil. ── */
            ['slug' => 'istanbul', 'name' => 'İstanbul', 'name_en' => 'Istanbul', 'region' => 'turkiye', 'country_code' => 'TR',
                'latitude' => null, 'longitude' => null,
                'headline' => 'İstanbul web tasarım', 'headline_en' => 'Web design in Istanbul'] + $tr('İstanbul'),

            ['slug' => 'ankara', 'name' => 'Ankara', 'name_en' => 'Ankara', 'region' => 'turkiye', 'country_code' => 'TR',
                'latitude' => null, 'longitude' => null,
                'headline' => 'Ankara web tasarım', 'headline_en' => 'Web design in Ankara'] + $tr('Ankara'),

            ['slug' => 'izmir', 'name' => 'İzmir', 'name_en' => 'Izmir', 'region' => 'turkiye', 'country_code' => 'TR',
                'latitude' => null, 'longitude' => null,
                'headline' => 'İzmir web tasarım', 'headline_en' => 'Web design in Izmir'] + $tr('İzmir'),

            ['slug' => 'antalya', 'name' => 'Antalya', 'name_en' => 'Antalya', 'region' => 'turkiye', 'country_code' => 'TR',
                'latitude' => null, 'longitude' => null,
                'headline' => 'Antalya web tasarım', 'headline_en' => 'Web design in Antalya'] + $tr('Antalya'),

            ['slug' => 'bursa', 'name' => 'Bursa', 'name_en' => 'Bursa', 'region' => 'turkiye', 'country_code' => 'TR',
                'latitude' => null, 'longitude' => null,
                'headline' => 'Bursa web tasarım', 'headline_en' => 'Web design in Bursa'] + $tr('Bursa'),
        ];
    }
}
