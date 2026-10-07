<?php

namespace Tests\Feature;

use App\Models\SlugHistory;
use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Eski adreslerin korunması.
 *
 * Başlık değişince adres de değişiyor (SlugField). Eski adrese verilmiş
 * dış bağlantılar 404'e düşerse hem ziyaretçi hem arama motorundaki
 * birikim kaybedilir. Buradaki testler o zincirin nöbetçisi.
 */
class AdresGecmisiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
        Http::fake();
    }

    private function work(): Work
    {
        return Work::create([
            'title' => 'Örnek iş',
            'slug' => 'ornek-is',
            'is_active' => true,
        ]);
    }

    public function test_adres_degisince_eskisi_kaydedilir(): void
    {
        $work = $this->work();
        $work->update(['slug' => 'yeni-adres']);

        $this->assertDatabaseHas('slug_history', [
            'model_type' => Work::class,
            'model_id' => $work->id,
            'slug' => 'ornek-is',
        ]);
    }

    public function test_eski_adres_yenisine_301_ile_gider(): void
    {
        $work = $this->work();
        $work->update(['slug' => 'yeni-adres']);

        $this->get('/isler/ornek-is')
            ->assertStatus(301)
            ->assertRedirect(route('works.show', ['work' => 'yeni-adres']));
    }

    public function test_ingilizce_adres_ingilizce_hedefe_gider(): void
    {
        $work = $this->work();
        $work->update(['slug' => 'yeni-adres']);

        $this->get('/en/work/ornek-is')
            ->assertStatus(301)
            ->assertRedirect(route('en.works.show', ['work' => 'yeni-adres']));
    }

    public function test_hic_var_olmamis_adres_404_kalir(): void
    {
        $this->work();

        $this->get('/isler/boyle-bir-sey-yok')->assertNotFound();
    }

    /**
     * Kayıt silinmişse eski adresi diriltmenin anlamı yok — yönlendirecek
     * bir hedef kalmadı, 404 doğru cevap.
     */
    public function test_kayit_silinmisse_404_kalir(): void
    {
        $work = $this->work();
        $work->update(['slug' => 'yeni-adres']);
        $work->delete();

        $this->get('/isler/ornek-is')->assertNotFound();
    }

    /**
     * Kullanıcı adresi değiştirip geri alırsa, eski kayıt kendi kendine
     * yönlendiren bir döngüye dönüşür. O satır silinmeli.
     */
    public function test_adres_geri_alinirsa_dongu_olusmaz(): void
    {
        $work = $this->work();
        $work->update(['slug' => 'yeni-adres']);
        $work->update(['slug' => 'ornek-is']);

        $this->assertDatabaseMissing('slug_history', [
            'model_type' => Work::class,
            'slug' => 'ornek-is',
        ]);

        $this->get('/isler/ornek-is')->assertOk();
    }

    public function test_ayni_adres_iki_kez_degisirse_her_ikisi_de_yonlenir(): void
    {
        $work = $this->work();
        $work->update(['slug' => 'ikinci-adres']);
        $work->update(['slug' => 'ucuncu-adres']);

        $this->get('/isler/ornek-is')->assertRedirect(route('works.show', ['work' => 'ucuncu-adres']));
        $this->get('/isler/ikinci-adres')->assertRedirect(route('works.show', ['work' => 'ucuncu-adres']));
    }

    public function test_slug_gecmisi_modelle_birlikte_bulunur(): void
    {
        $work = $this->work();
        $work->update(['slug' => 'yeni-adres']);

        $kayit = SlugHistory::where('slug', 'ornek-is')->first();

        $this->assertNotNull($kayit);
        $this->assertSame(Work::class, $kayit->model_type);
        $this->assertSame($work->id, (int) $kayit->model_id);
    }
}
