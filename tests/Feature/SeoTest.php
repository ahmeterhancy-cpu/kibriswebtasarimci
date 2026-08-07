<?php

namespace Tests\Feature;

use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * SEO iskeletinin nöbetçisi.
 *
 * Buradaki iddialar "güzel görünsün" testleri değil; her biri sitenin arama
 * sonuçlarındaki davranışını belirleyen ve elle gözden kaçması çok kolay olan
 * ayrıntılar: kanonik adres, dizine alma kuralı, hreflang eşleşmesi, yapısal
 * verinin geçerli JSON olması ve sitemap kapsamı.
 */
class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    /** Sayfadaki tüm JSON-LD bloklarını çözümlenmiş olarak döndürür. */
    private function jsonLd(string $html): array
    {
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

        return array_map(function (string $raw) {
            $decoded = json_decode(trim($raw), true);

            $this->assertSame(JSON_ERROR_NONE, json_last_error(), 'Geçersiz JSON-LD: '.json_last_error_msg());

            return $decoded;
        }, $matches[1]);
    }

    public function test_ana_sayfa_acilir_ve_kanonik_adresi_vardir(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.url('/').'">', false)
            ->assertSee('name="robots" content="index, follow', false);
    }

    public function test_hreflang_cifti_karsilikli_dogru_kurulur(): void
    {
        $this->get('/paketler')
            ->assertOk()
            ->assertSee('hreflang="tr" href="'.url('/paketler').'"', false)
            ->assertSee('hreflang="en" href="'.url('/en/pricing').'"', false)
            ->assertSee('hreflang="x-default" href="'.url('/paketler').'"', false);

        $this->get('/en/pricing')
            ->assertOk()
            ->assertSee('hreflang="tr" href="'.url('/paketler').'"', false)
            ->assertSee('hreflang="en" href="'.url('/en/pricing').'"', false);
    }

    public function test_filtreli_liste_dizine_girmez_ve_kanonik_filtresiz_adrestir(): void
    {
        $this->get('/isler?kategori=kurumsal')
            ->assertOk()
            ->assertSee('name="robots" content="noindex, follow"', false)
            ->assertSee('<link rel="canonical" href="'.url('/isler').'">', false);

        // Filtresiz hâli dizine girmeli.
        $this->get('/isler')
            ->assertOk()
            ->assertSee('name="robots" content="index, follow', false);
    }

    public function test_sehir_sayfasi_kendi_local_business_verisini_uretir(): void
    {
        $location = Location::active()->firstOrFail();

        $html = $this->get('/web-tasarim/'.$location->slug)->assertOk()->getContent();
        $blocks = $this->jsonLd($html);
        $types = array_map(fn ($b) => $b['@type'] ?? 'graph', $blocks);

        $this->assertContains('ProfessionalService', $types);
        $this->assertContains('BreadcrumbList', $types);

        $business = $blocks[array_search('ProfessionalService', $types, true)];
        $this->assertSame($location->name, $business['areaServed']['name']);
        $this->assertArrayHasKey('geo', $business, 'Şehir sayfasında koordinat bekleniyor.');

        // Uydurma puan/yorum yapısal veriye asla girmemeli.
        $this->assertArrayNotHasKey('aggregateRating', $business);
        $this->assertArrayNotHasKey('review', $business);
    }

    public function test_site_geneli_area_served_yayindaki_sehirlerden_turer(): void
    {
        $expected = Location::active()->count();

        $graph = $this->jsonLd($this->get('/')->assertOk()->getContent())[0]['@graph'];

        $this->assertCount($expected, $graph[0]['areaServed']);
    }

    public function test_yayindan_kaldirilan_sehir_sayfasi_404_verir(): void
    {
        $location = Location::active()->firstOrFail();
        $location->update(['is_active' => false]);

        $this->get('/web-tasarim/'.$location->slug)->assertNotFound();
    }

    public function test_sitemap_her_sehri_iki_dilde_listeler(): void
    {
        $response = $this->get('/sitemap.xml')->assertOk();

        foreach (Location::active()->get() as $location) {
            $response->assertSee(url('/web-tasarim/'.$location->slug), false);
            $response->assertSee(url('/en/web-design/'.$location->slug), false);
        }
    }

    public function test_404_sayfasi_dogru_dilde_ve_dizin_disidir(): void
    {
        $this->get('/olmayan-adres')
            ->assertNotFound()
            ->assertSee('Sayfa bulunamadı')
            ->assertSee('name="robots" content="noindex, follow"', false);

        $this->get('/en/no-such-page')
            ->assertNotFound()
            ->assertSee('Page not found');
    }

    public function test_robots_dosyasi_paneli_kapatir_ve_sitemapi_bildirir(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /admin')
            ->assertSee('Sitemap: '.url('/sitemap.xml'));
    }
}
