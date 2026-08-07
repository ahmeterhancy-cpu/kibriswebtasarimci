<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Panel duman testi.
 *
 * Filament sayfaları sessizce patlar: bir alan adı yanlışsa ya da bir sınıf
 * taşınmışsa hata yalnız o sayfa açıldığında görünür. Burada her ayar sayfası
 * ve kaynak listesi bir kez açılıp 200 döndüğü doğrulanıyor.
 */
class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\SettingsSeeder::class);
        $this->actingAs(User::factory()->create());
    }

    public static function adminUrls(): array
    {
        return [
            'ayarlar — genel' => ['/admin/general-settings'],
            'ayarlar — marka' => ['/admin/site-branding'],
            'ayarlar — iletişim' => ['/admin/site-contact'],
            'ayarlar — footer' => ['/admin/footer-content'],
            'ayarlar — yapay zekâ' => ['/admin/ai-visibility'],
            'ayarlar — analitik' => ['/admin/analytics'],
            'kaynak — kullanıcılar' => ['/admin/users'],
            'kaynak — kullanıcı oluştur' => ['/admin/users/create'],
            'kaynak — şehirler' => ['/admin/locations'],
            'kaynak — paketler' => ['/admin/packages'],
            'kaynak — hizmetler' => ['/admin/services'],
        ];
    }

    #[DataProvider('adminUrls')]
    public function test_panel_sayfasi_acilir(string $url): void
    {
        $this->get($url)->assertOk();
    }

    public function test_giris_yapmadan_panele_erisilemez(): void
    {
        auth()->logout();

        $this->get('/admin/users')->assertRedirect('/admin/login');
        $this->get('/admin/analytics')->assertRedirect('/admin/login');
    }
}
