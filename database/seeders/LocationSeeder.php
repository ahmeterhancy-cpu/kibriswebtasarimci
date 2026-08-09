<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Office;
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
 * Koordinat ve adres notu: yalnızca FİİLEN OFİSİMİZİN OLDUĞU şehirlerde
 * (`office_id`) adres ve koordinat var. Ofisin olmadığı bir şehre adres ya da
 * koordinat basmak arama motoruna yanlış konum sinyali verir; oralarda
 * `areaServed` yeterli.
 *
 * Ofisler: Girne (merkez) ve Edirne. Ayrım bölgeye göre DEĞİL — Edirne
 * Türkiye'de ama orada da ofis var.
 */
class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // Adres tek yerde: offices tablosu. Şehir yalnız ofise BAĞLANIR.
        $offices = Office::query()->pluck('id', 'slug');

        foreach ($this->locations() as $i => $location) {
            $officeSlug = $location['office'] ?? null;
            unset($location['office']);

            Location::query()->updateOrCreate(
                ['slug' => $location['slug']],
                $location + [
                    'sort_order' => $i + 1,
                    'is_active' => true,
                    'office_id' => $officeSlug ? ($offices[$officeSlug] ?? null) : null,
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
        // Ofisimizin bulunduğu şehir. Adres burada DEĞİL — `office` anahtarı
        // offices tablosuna bağlanır. Metinde görüşme biçiminden (yüz yüze /
        // çevrimiçi) BAHSEDİLMEZ; ofis bilgisi sayfadaki ofis kartında zaten
        // duruyor, cümleye taşımak gereksiz ve satış dilini zayıflatıyor.
        $office = fn (string $name, string $nameEn) => [
            'intro' => $name.'\'deki işletmeler için kurumsal web sitesi, e-ticaret ve özel yazılım geliştiriyoruz. Kapsam sektörünüze göre belirlenir.',
            'intro_en' => 'Corporate websites, e-commerce and custom software for businesses in '.$nameEn.'. The scope follows your industry.',
            'seo_title' => $name.' Web Tasarım | Kurumsal Site, E-Ticaret ve Yazılım',
            'seo_title_en' => 'Web Design in '.$nameEn.' | Corporate Sites, E-Commerce, Software',
            'seo_description' => $name.'\'de ofisimizden web sitesi, e-ticaret ve özel yazılım. Şablon yok, sabit fiyat, yabancı dil seçenekli.',
            'seo_description_en' => 'Websites, e-commerce and custom software from our '.$nameEn.' office. No templates, fixed prices, optional foreign language.',
        ];

        $kktc = fn (string $name, string $nameEn) => [
            'intro' => $name.' ve çevresindeki işletmeler için kurumsal web sitesi, e-ticaret ve özel yazılım geliştiriyoruz. Kapsam sektörünüze göre belirlenir.',
            'intro_en' => 'Corporate websites, e-commerce and custom software for businesses in '.$nameEn.' and the surrounding area. The scope follows your industry.',
            'seo_title' => $name.' Web Tasarım | Kurumsal Site, E-Ticaret ve Yazılım',
            'seo_title_en' => 'Web Design in '.$nameEn.' | Corporate Sites, E-Commerce, Software',
            'seo_description' => $name.'\'da web sitesi, e-ticaret ve özel yazılım. Şablon yok, sabit fiyat, yabancı dil seçenekli. Sektörünüze göre kapsam.',
            'seo_description_en' => 'Websites, e-commerce and custom software in '.$nameEn.'. No templates, fixed prices, optional foreign language. Scope shaped by your industry.',
        ];

        // Ofisin bulunmadığı şehirler. "Uzaktan çalışıyoruz" ifadesi bilerek
        // KULLANILMIYOR: eksiklik gibi okunuyor. Süreç zaten çevrimiçi
        // yürüyor — bu bir kısıt değil, çalışma biçimi.
        $tr = fn (string $name) => [
            'intro' => $name.'\'daki işletmeler için kurumsal web sitesi, e-ticaret ve özel yazılım geliştiriyoruz. Kapsam sektörünüze göre belirlenir.',
            'intro_en' => 'Corporate websites, e-commerce and custom software for businesses in '.$name.'. The scope follows your industry.',
            'seo_title' => $name.' Web Tasarım | Kurumsal Site, E-Ticaret ve Yazılım',
            'seo_title_en' => 'Web Design in '.$name.' | Corporate Sites, E-Commerce, Software',
            'seo_description' => $name.'\'da kurumsal web sitesi, e-ticaret ve özel yazılım. Şablon yok, sabit fiyat, yabancı dil seçenekli. Sektörünüze göre kapsam.',
            'seo_description_en' => 'Corporate websites, e-commerce and custom software in '.$name.'. No templates, fixed prices, optional foreign language. Scope shaped by your industry.',
        ];

        return [
            /* ── Kuzey Kıbrıs — merkezimizin bulunduğu bölge ─────────────── */
            ['slug' => 'lefkosa', 'name' => 'Lefkoşa', 'name_en' => 'Nicosia', 'region' => 'kktc', 'country_code' => 'CY',
                'latitude' => 35.1856, 'longitude' => 33.3823,
                'headline' => 'Lefkoşa web tasarım', 'headline_en' => 'Web design in Nicosia'] + $kktc('Lefkoşa', 'Nicosia'),

            // Merkez ofis.
            ['slug' => 'girne', 'name' => 'Girne', 'name_en' => 'Kyrenia', 'region' => 'kktc', 'country_code' => 'CY',
                'latitude' => 35.3364, 'longitude' => 33.3192,
                'office' => 'girne',
                'headline' => 'Girne web tasarım', 'headline_en' => 'Web design in Kyrenia'] + $office('Girne', 'Kyrenia'),

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

            /* ── Türkiye ──────────────────────────────────────────────────
             | Edirne'de ofis var: adres, telefon ve koordinat gerçek.
             | Ofis olmayan şehirlerde adres ve koordinat BİLEREK boş.
             */
            ['slug' => 'edirne', 'name' => 'Edirne', 'name_en' => 'Edirne', 'region' => 'turkiye', 'country_code' => 'TR',
                // Yaklaşık şehir merkezi koordinatı. Panelden Google Haritalar'daki
                // kesin değerle güncellenmeli.
                'latitude' => 41.6771, 'longitude' => 26.5557,
                'office' => 'edirne',
                'headline' => 'Edirne web tasarım', 'headline_en' => 'Web design in Edirne'] + $office('Edirne', 'Edirne'),

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
