<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;

/**
 * Ofisler.
 *
 * Bilgiler maysila.com'daki iletişim sayfasından alındı. Koordinatlar şehir
 * merkezi yaklaşığıdır — panelden Google Haritalar'daki kesin değerle
 * güncellenmeli, çünkü yerel sonuçlarda konum doğruluğu önemli.
 */
class OfficeSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->offices() as $i => $office) {
            Office::query()->updateOrCreate(
                ['slug' => $office['slug']],
                $office + ['sort_order' => $i + 1, 'is_active' => true],
            );
        }
    }

    private function offices(): array
    {
        return [
            [
                'slug' => 'girne',
                'name' => 'Kıbrıs — Merkez',
                'name_en' => 'Cyprus — Head office',
                'city' => 'Girne',
                'city_en' => 'Kyrenia',
                'country' => 'Kuzey Kıbrıs',
                'country_en' => 'North Cyprus',
                'country_code' => 'CY',
                'address' => 'Zafer Sokak No:1, Bellapais',
                'address_en' => 'Zafer Sokak No:1, Bellapais',
                'phone' => '+90 548 840 4000',
                'email' => 'info@kibriswebtasarimci.com',
                'latitude' => 35.3178,
                'longitude' => 33.3536,
                'is_primary' => true,
            ],
            [
                'slug' => 'edirne',
                'name' => 'Türkiye',
                'name_en' => 'Türkiye',
                'city' => 'Edirne',
                'city_en' => 'Edirne',
                'country' => 'Türkiye',
                'country_en' => 'Türkiye',
                'country_code' => 'TR',
                'address' => 'Hakim Çağlar Işık Cd. Özen Plaza No:1 D.31, Merkez',
                'address_en' => 'Hakim Caglar Isik Cd. Ozen Plaza No:1 D.31, Merkez',
                'phone' => '+90 541 392 77 05',
                'email' => 'info@maysila.com',
                'latitude' => 41.6771,
                'longitude' => 26.5557,
                'is_primary' => false,
            ],
            [
                'slug' => 'londra',
                'name' => 'İngiltere',
                'name_en' => 'United Kingdom',
                'city' => 'Londra',
                'city_en' => 'London',
                'country' => 'Birleşik Krallık',
                'country_en' => 'United Kingdom',
                'country_code' => 'GB',
                'address' => '71–75 Shelton Street, Covent Garden, WC2H 9JQ',
                'address_en' => '71–75 Shelton Street, Covent Garden, WC2H 9JQ',
                'phone' => '+44 789 911 86 74',
                'email' => 'info@creafinity.co.uk',
                'latitude' => 51.5145,
                'longitude' => -0.1247,
                'is_primary' => false,
            ],
        ];
    }
}
