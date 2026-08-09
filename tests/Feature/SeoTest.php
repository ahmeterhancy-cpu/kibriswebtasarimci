<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\Office;
use App\Models\Sector;
use App\Models\Setting;
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
        $location = Location::active()->whereNotNull('office_id')->firstOrFail();

        $html = $this->get('/web-tasarim/'.$location->slug)->assertOk()->getContent();
        $blocks = $this->jsonLd($html);
        $types = array_map(fn ($b) => $b['@type'] ?? 'graph', $blocks);

        $this->assertContains('ProfessionalService', $types);
        $this->assertContains('BreadcrumbList', $types);

        $business = $blocks[array_search('ProfessionalService', $types, true)];
        $this->assertSame($location->name, $business['areaServed']['name']);
        $this->assertArrayHasKey('geo', $business, 'Ofisimizin olduğu şehirde koordinat bekleniyor.');
        $this->assertSame($location->office->address, $business['address']['streetAddress']);

        // Uydurma puan/yorum yapısal veriye asla girmemeli.
        $this->assertArrayNotHasKey('aggregateRating', $business);
        $this->assertArrayNotHasKey('review', $business);
    }

    /**
     * Ofisin olmadığı bir şehre adres ya da koordinat basmak arama motoruna
     * yanlış konum sinyali verir; orada yalnız `areaServed` olmalı.
     *
     * Ayrım BÖLGEYE göre değil, ofis varlığına göre: Edirne Türkiye'de ama
     * orada ofis var, İstanbul'da yok.
     */
    public function test_uzaktan_calisilan_sehirde_adres_ve_koordinat_yayinlanmaz(): void
    {
        $remote = Location::active()->whereNull('office_id')->firstOrFail();

        $blocks = $this->jsonLd($this->get('/web-tasarim/'.$remote->slug)->assertOk()->getContent());
        $types = array_map(fn ($b) => $b['@type'] ?? 'graph', $blocks);
        $business = $blocks[array_search('ProfessionalService', $types, true)];

        $this->assertArrayNotHasKey('geo', $business);
        $this->assertArrayNotHasKey('address', $business);
        $this->assertSame($remote->name, $business['areaServed']['name']);
    }

    /**
     * Ofis bilgisi bölgeden bağımsız olmalı. Bu test, "Türkiye = uzaktan"
     * varsayımının geri sızmasını engelliyor.
     */
    public function test_turkiyedeki_ofis_yuz_yuze_gorusme_olarak_gosterilir(): void
    {
        $edirne = Location::active()->where('slug', 'edirne')->firstOrFail();

        $this->assertTrue($edirne->hasOffice());
        $this->assertSame('turkiye', $edirne->region);

        $this->get('/web-tasarim/edirne')
            ->assertOk()
            ->assertSee('Buradayız.')
            ->assertDontSee('Süreç aynı.')
            ->assertSee($edirne->office->address);
    }

    /**
     * Adres tek kaynaktan gelmeli. Aynı ofis hem iletişim sayfasında hem bağlı
     * şehir sayfasında BİREBİR aynı yazmalı — site içi tutarsızlık, Google
     * Business Profile eşleşmesini de bozuyor.
     */
    public function test_ofis_adresi_iletisim_ve_sehir_sayfasinda_ayni(): void
    {
        $office = Office::active()->where('slug', 'edirne')->firstOrFail();

        $this->get('/iletisim')->assertOk()->assertSee($office->address);
        $this->get('/web-tasarim/edirne')->assertOk()->assertSee($office->address);
    }

    public function test_iletisim_sayfasi_tum_ofisleri_listeler(): void
    {
        $response = $this->get('/iletisim')->assertOk();

        foreach (Office::active()->get() as $office) {
            $response->assertSee($office->address);
            $response->assertSee($office->t('city'));
        }
    }

    public function test_kurulus_verisinde_tum_ofisler_yer_alir(): void
    {
        $graph = $this->jsonLd($this->get('/')->assertOk()->getContent())[0]['@graph'];

        $this->assertCount(Office::active()->count(), $graph[0]['location']);
    }

    /**
     * Şehir sayfaları BİLEREK geneldir; ayrışan içerik sektör sayfalarında.
     * Bu yüzden her şehir sayfası sektörlere bağlantı vermek zorunda — yoksa
     * genel metinli 11 sayfa birbirinin kopyası olarak kalır.
     */
    public function test_sehir_sayfasi_sektorlere_kapi_acar(): void
    {
        $location = Location::active()->firstOrFail();

        $response = $this->get('/web-tasarim/'.$location->slug)->assertOk();

        foreach (Sector::active()->get() as $sector) {
            $response->assertSee(url('/sektorler/'.$sector->slug), false);
        }
    }

    public function test_sektor_sayfasi_kendi_service_verisini_uretir(): void
    {
        $sector = Sector::active()->firstOrFail();

        $blocks = $this->jsonLd($this->get('/sektorler/'.$sector->slug)->assertOk()->getContent());
        $types = array_map(fn ($b) => $b['@type'] ?? 'graph', $blocks);

        $this->assertContains('Service', $types);
        $this->assertContains('BreadcrumbList', $types);

        $service = $blocks[array_search('Service', $types, true)];
        $this->assertSame($sector->name, $service['serviceType']);
        $this->assertArrayNotHasKey('aggregateRating', $service);
    }

    /**
     * Sektör sayfalarının tüm değeri birbirinden farklı olmalarından geliyor.
     * İki sektörün gövde metni aynıysa kopyala-yapıştır yapılmış demektir.
     */
    public function test_sektor_metinleri_birbirinin_kopyasi_degil(): void
    {
        $bodies = Sector::active()->pluck('body')->filter()->all();
        $intros = Sector::active()->pluck('intro')->filter()->all();

        $this->assertCount(count($bodies), array_unique($bodies), 'İki sektörde aynı gövde metni var.');
        $this->assertCount(count($intros), array_unique($intros), 'İki sektörde aynı giriş cümlesi var.');
    }

    public function test_yayindan_kaldirilan_sektor_sayfasi_404_verir(): void
    {
        $sector = Sector::active()->firstOrFail();
        $sector->update(['is_active' => false]);

        $this->get('/sektorler/'.$sector->slug)->assertNotFound();
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

    public function test_sitemap_sehir_ve_sektorleri_iki_dilde_listeler(): void
    {
        $response = $this->get('/sitemap.xml')->assertOk();

        foreach (Location::active()->get() as $location) {
            $response->assertSee(url('/web-tasarim/'.$location->slug), false);
            $response->assertSee(url('/en/web-design/'.$location->slug), false);
        }

        foreach (Sector::active()->get() as $sector) {
            $response->assertSee(url('/sektorler/'.$sector->slug), false);
            $response->assertSee(url('/en/industries/'.$sector->slug), false);
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

    /**
     * Laravel varsayılan olarak `public/robots.txt` ile gelir ve web sunucusu
     * statik dosyayı rotadan önce sunar — dinamik robots.txt sessizce hiç
     * çalışmaz. Bir kez bu tuzağa düşüldü, bir daha düşülmesin.
     */
    public function test_statik_robots_dosyasi_dinamik_rotayi_golgelemiyor(): void
    {
        $this->assertFileDoesNotExist(public_path('robots.txt'));
    }

    public function test_yapay_zeka_botlari_varsayilan_olarak_siteye_alinir(): void
    {
        $robots = $this->get('/robots.txt')->assertOk()->getContent();

        // Hiç ayar yapılmamışken kimse engellenmemeli — yeni kurulan bir site
        // sessizce yapay zekâ sonuçlarından silinmesin.
        foreach (['GPTBot', 'ClaudeBot', 'PerplexityBot', 'Google-Extended'] as $agent) {
            $this->assertStringNotContainsString("User-agent: {$agent}\nDisallow: /", $robots);
        }
    }

    public function test_panelden_kapatilan_bot_robotsta_engellenir(): void
    {
        // Yalnızca ChatGPT aramasına izin ver, gerisini kapat.
        Setting::set('ai_crawlers', 'openai_search', 'geo');

        $robots = $this->get('/robots.txt')->assertOk()->getContent();

        $this->assertStringContainsString("User-agent: PerplexityBot\nDisallow: /", $robots);
        $this->assertStringContainsString("User-agent: GPTBot\nDisallow: /", $robots);
        $this->assertStringNotContainsString("User-agent: OAI-SearchBot\nDisallow: /", $robots);
    }

    public function test_llms_dosyasi_hizmet_paket_ve_sehirleri_listeler(): void
    {
        $response = $this->get('/llms.txt')->assertOk();

        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('## Hizmetler', false);
        $response->assertSee('## Paketler ve fiyatlar', false);
        $response->assertSee('## Sektörler', false);
        $response->assertSee('## Hizmet verilen şehirler', false);

        foreach (Sector::active()->get() as $sector) {
            $response->assertSee(url('/sektorler/'.$sector->slug), false);
        }

        foreach (Location::active()->get() as $location) {
            $response->assertSee(url('/web-tasarim/'.$location->slug), false);
        }
    }

    public function test_llms_kapatilinca_404_doner_ve_robotstan_dusulur(): void
    {
        Setting::set('llms_enabled', '0', 'geo');

        $this->get('/llms.txt')->assertNotFound();
        $this->get('/robots.txt')->assertOk()->assertDontSee('LLM-Content');
    }

    public function test_cerez_onayi_verilmeden_olcum_scriptleri_yuklenmez(): void
    {
        Setting::set('ga4_id', 'G-TEST12345', 'analytics');
        Setting::set('cookie_consent', '1', 'analytics');

        $html = $this->get('/')->assertOk()->getContent();

        // Onay bandı var, ama Google'a giden hiçbir <script src> yok.
        $this->assertStringContainsString('k-consent', $html);
        $this->assertStringNotContainsString('<script src="https://www.googletagmanager.com', $html);
    }
}
