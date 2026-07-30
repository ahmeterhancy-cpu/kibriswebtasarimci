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
                'contact_email' => ['info@kibriswebtasarimci.com', null],
                'contact_phone' => ['+90 533 000 00 00', null],
                'contact_whatsapp' => ['905330000000', null],
                'contact_city' => ['Girne', 'Kyrenia'],
                'contact_address' => ['Girne, Kuzey Kıbrıs', 'Kyrenia, North Cyprus'],
                'social_instagram' => ['', null],
                'social_linkedin' => ['', null],
                'social_behance' => ['', null],
            ],
            'footer' => [
                'footer_about' => [
                    'Kuzey Kıbrıs merkezli web tasarım ve yazılım stüdyosu. Tek iş, hakkıyla.',
                    'A web design and development studio based in North Cyprus. One craft, done properly.',
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
