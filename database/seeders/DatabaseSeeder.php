<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Gerçek site içeriği. Örnek portfolyo/referans verisi burada DEĞİL —
     * onlar `DemoContentSeeder` içinde ve varsayılan olarak çalışmaz.
     */
    public function run(): void
    {
        $this->createAdmin();

        $this->call([
            SettingsSeeder::class,
            ServiceSeeder::class,
            PackageSeeder::class,
            AddonSeeder::class,
            SectorSeeder::class,
            // Ofisler şehirlerden ÖNCE: şehir kayıtları ofise bağlanıyor.
            OfficeSeeder::class,
            LocationSeeder::class,
            BlogSeeder::class,
            FaqSeeder::class,
        ]);
    }

    /**
     * İlk yönetici hesabı.
     *
     * Şifre BU DOSYADA YAZILI DEĞİL. Depo herkese açıksa sabit kodlanmış bir
     * şifre, panele davetiye demektir. Sıra şu:
     *   1. .env içindeki ADMIN_PASSWORD kullanılır,
     *   2. yoksa rastgele üretilir ve çıktıda BİR KEZ gösterilir.
     *
     * `firstOrCreate` bilinçli: tohum verisi ikinci kez çalıştırılırsa
     * panelden değiştirdiğiniz şifreyi sıfırlamaz.
     */
    private function createAdmin(): void
    {
        $email = config('app.admin_email');

        if (User::query()->where('email', $email)->exists()) {
            $this->command?->info("Yönetici hesabı zaten var: {$email} — şifre değiştirilmedi.");

            return;
        }

        $password = config('app.admin_password');
        $generated = ! $password;

        if ($generated) {
            $password = Str::password(16, symbols: false);
        }

        User::query()->create([
            'name' => 'Yönetici',
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->command?->newLine();
        $this->command?->info('Yönetici hesabı oluşturuldu');
        $this->command?->line("  E-posta: {$email}");

        if ($generated) {
            $this->command?->line("  Şifre  : {$password}");
            $this->command?->warn('  Bu şifre BİR KEZ gösterilir. Kaydedin ve panelden değiştirin.');
        } else {
            $this->command?->line('  Şifre  : .env içindeki ADMIN_PASSWORD değeri');
        }

        $this->command?->newLine();
    }
}
