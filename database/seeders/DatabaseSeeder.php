<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Gerçek site içeriği. Örnek portfolyo/referans verisi burada DEĞİL —
     * onlar `DemoContentSeeder` içinde ve varsayılan olarak çalışmaz.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@kibriswebtasarimci.com'],
            ['name' => 'Yönetici', 'password' => Hash::make('kwt2026!')],
        );

        $this->call([
            SettingsSeeder::class,
            ServiceSeeder::class,
            PackageSeeder::class,
            AddonSeeder::class,
            SectorSeeder::class,
            LocationSeeder::class,
            BlogSeeder::class,
            FaqSeeder::class,
        ]);
    }
}
