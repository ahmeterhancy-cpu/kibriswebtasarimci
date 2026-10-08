<?php

namespace Tests\Feature;

use App\Http\Controllers\SetupController;
use App\Models\Sector;
use Database\Seeders\SectorFaqSeeder;
use Database\Seeders\SectorSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Token korumalı kurulum görevlerinin nöbetçisi.
 *
 * Bu rota, shell erişimi olmayan sunucuda içerik yüklemenin tek yolu.
 * Açık bırakıldığında ya da geniş tutulduğunda doğrudan bir güvenlik
 * sorunu olduğu için sınırları test ediliyor.
 */
class KurulumGoreviTest extends TestCase
{
    use RefreshDatabase;

    private const TOKEN = 'test-kurulum-anahtari';

    /**
     * Token phpunit.xml'den geliyor: rota, uygulama açılırken token varsa
     * kaydediliyor. setUp içinde config() ile vermek geç kalıyor — o anda
     * rotalar çoktan yüklenmiş oluyor ve istek 404 dönüyor.
     */
    public function test_tokensiz_cagri_404(): void
    {
        $this->get('/kurulum/yanlis-anahtar')->assertNotFound();
    }

    public function test_yanlis_token_404(): void
    {
        $this->get('/kurulum/yanlis-anahtar/sss')->assertNotFound();
    }

    public function test_tanimsiz_gorev_404(): void
    {
        $this->get('/kurulum/'.self::TOKEN.'/rastgele-bir-sey')
            ->assertStatus(404)
            ->assertSee('Tanımsız görev', escape: false);
    }

    public function test_sss_gorevi_sektor_sorularini_yukler(): void
    {
        $this->seed(SectorSeeder::class);

        Sector::query()->update(['faq' => null, 'faq_en' => null]);

        $this->get('/kurulum/'.self::TOKEN.'/sss')
            ->assertOk()
            ->assertSee('GÖREV TAMAMLANDI', escape: false);

        $sector = Sector::where('slug', 'otel-ve-konaklama')->first();

        $this->assertNotEmpty($sector->faq);
        $this->assertNotEmpty($sector->faq_en);
    }

    /**
     * Görev listesi bilerek dar: token sızsa bile çalıştırılabilecek şeyin
     * sınırı burası. Yeni görev eklenirken bu test bilinçli karar olmasını
     * zorunlu kılıyor.
     */
    public function test_gorev_listesi_dar_tutuluyor(): void
    {
        $this->assertSame(['sss'], array_keys(SetupController::JOBS));
        $this->assertSame(SectorFaqSeeder::class, SetupController::JOBS['sss']);
    }
}
