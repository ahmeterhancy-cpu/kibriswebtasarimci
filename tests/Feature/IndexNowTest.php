<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Work;
use App\Support\IndexNow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * IndexNow bildirimlerinin nöbetçisi.
 *
 * Buradaki en önemli iddia sonuncusu: arama motoru ulaşılmaz olduğunda
 * paneldeki kaydetme işlemi BOZULMAMALI. Bildirim bir yan etki, kaydın
 * başarısı ona bağlı değil.
 */
class IndexNowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());

        config([
            'services.indexnow.key' => 'test-anahtari',
            'services.indexnow.enabled' => true,
        ]);
    }

    private function work(): Work
    {
        return Work::create([
            'title' => 'Örnek iş',
            'slug' => 'ornek-is',
            'is_active' => true,
        ]);
    }

    /** @return array<int, string> Son isteğin bildirdiği adresler */
    private function sonBildirilenler(): array
    {
        $urls = [];

        Http::assertSent(function (Request $request) use (&$urls) {
            $urls = $request->data()['urlList'] ?? [];

            return true;
        });

        return $urls;
    }

    public function test_anahtar_dosyasi_depoda_duruyor(): void
    {
        $key = 'b5afd8046cce653332f1b76d243e300d';
        $path = public_path($key.'.txt');

        $this->assertFileExists($path, 'IndexNow anahtar dosyası silinmiş — bildirimler reddedilir.');
        $this->assertSame($key, trim(file_get_contents($path)));
    }

    public function test_kapaliyken_istek_gitmez(): void
    {
        config(['services.indexnow.enabled' => false]);
        Http::fake();

        $this->work();

        Http::assertNothingSent();
    }

    public function test_kayit_olusunca_iki_dildeki_adresler_bildirilir(): void
    {
        Http::fake();

        $this->work();

        $urls = $this->sonBildirilenler();

        $this->assertContains(route('works.show', ['work' => 'ornek-is']), $urls);
        $this->assertContains(route('en.works.show', ['work' => 'ornek-is']), $urls);
        $this->assertContains(route('works.index'), $urls);
        $this->assertContains(route('en.works.index'), $urls);
        $this->assertContains(route('home'), $urls);
    }

    public function test_slug_degisince_eski_adres_de_bildirilir(): void
    {
        $work = $this->work();

        Http::fake();
        $work->update(['slug' => 'yeni-adres']);

        $urls = $this->sonBildirilenler();

        $this->assertContains(route('works.show', ['work' => 'yeni-adres']), $urls, 'Yeni adres bildirilmedi.');
        $this->assertContains(route('works.show', ['work' => 'ornek-is']), $urls, 'Eski adres bildirilmedi — 404 sayfa dizinde kalır.');
    }

    public function test_hicbir_sey_degismediyse_bildirim_gitmez(): void
    {
        $this->work();

        // Taze örnek: paneldeki "aç, hiçbir şeyi değiştirme, kaydet" akışı.
        $work = Work::firstWhere('slug', 'ornek-is');

        Http::fake();
        $work->save();

        Http::assertNothingSent();
    }

    public function test_arama_motoru_ulasilmazsa_kaydetme_bozulmaz(): void
    {
        Http::fake(fn () => throw new \RuntimeException('cURL error 60'));

        $work = $this->work();

        $this->assertTrue($work->exists);
        $this->assertDatabaseHas('works', ['slug' => 'ornek-is']);
    }

    public function test_gonderilen_govde_indexnow_bicimine_uyar(): void
    {
        Http::fake();

        IndexNow::ping(['https://ornek.test/a']);

        Http::assertSent(function (Request $request) {
            $body = $request->data();

            return $request->url() === 'https://api.indexnow.org/IndexNow'
                && $body['key'] === 'test-anahtari'
                && str_ends_with($body['keyLocation'], '/test-anahtari.txt')
                && $body['urlList'] === ['https://ornek.test/a']
                && filled($body['host']);
        });
    }
}
