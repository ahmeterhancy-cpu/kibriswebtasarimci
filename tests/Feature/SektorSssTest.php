<?php

namespace Tests\Feature;

use App\Models\Sector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Sektör sayfalarındaki sık sorulanların nöbetçisi.
 *
 * En önemli iddia sonuncusu: sorular sektörler arasında TEKRAR ETMEMELİ.
 * Aynı SSS bloğunu on beş sayfaya kopyalamak hem sayfaları birbirinin
 * kopyası yapar hem de arama motoru tekrar eden işaretlemeyi görmezden
 * gelir. Buradaki test, ileride kolaylık olsun diye kopyalanmasını önlüyor.
 */
class SektorSssTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    private function jsonLd(string $html): array
    {
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $m);

        return array_map(function (string $raw) {
            $decoded = json_decode(trim($raw), true);
            $this->assertSame(JSON_ERROR_NONE, json_last_error(), 'Geçersiz JSON-LD: '.json_last_error_msg());

            return $decoded;
        }, $m[1]);
    }

    public function test_her_sektorde_iki_dilde_soru_var(): void
    {
        foreach (Sector::active()->get() as $sector) {
            $this->assertNotEmpty($sector->faq, $sector->slug.' sektöründe Türkçe SSS yok.');
            $this->assertNotEmpty($sector->faq_en, $sector->slug.' sektöründe İngilizce SSS yok.');
        }
    }

    public function test_soru_ve_cevap_alanlari_dolu(): void
    {
        foreach (Sector::active()->get() as $sector) {
            foreach ((array) $sector->faq as $i => $item) {
                $this->assertNotEmpty($item['q'] ?? null, $sector->slug.' #'.$i.' sorusuz.');
                $this->assertNotEmpty($item['a'] ?? null, $sector->slug.' #'.$i.' cevapsız.');
            }
        }
    }

    public function test_sorular_sektorler_arasinda_tekrar_etmiyor(): void
    {
        $gorulen = [];

        foreach (Sector::active()->get() as $sector) {
            foreach ((array) $sector->faq as $item) {
                $soru = mb_strtolower(trim($item['q']));

                $this->assertArrayNotHasKey(
                    $soru,
                    $gorulen,
                    '"'.$item['q'].'" sorusu hem '.($gorulen[$soru] ?? '?').' hem '.$sector->slug.' sektöründe var.',
                );

                $gorulen[$soru] = $sector->slug;
            }
        }
    }

    public function test_sayfada_faqpage_yapisal_verisi_var(): void
    {
        $sector = Sector::active()->whereNotNull('faq')->first();

        $html = $this->get('/sektorler/'.$sector->slug)->assertOk()->getContent();

        $faqPage = collect($this->jsonLd($html))->firstWhere('@type', 'FAQPage');

        $this->assertNotNull($faqPage, 'FAQPage işaretlemesi yok.');
        $this->assertCount(count($sector->faq), $faqPage['mainEntity']);
        $this->assertSame($sector->faq[0]['q'], $faqPage['mainEntity'][0]['name']);
        $this->assertSame($sector->faq[0]['a'], $faqPage['mainEntity'][0]['acceptedAnswer']['text']);
    }

    /**
     * Google, sayfada görünmeyen soruyu işaretlemeyi el ile cezalandırıyor.
     * İşaretlenen her soru sayfanın metninde de geçmeli.
     */
    public function test_isaretlenen_her_soru_sayfada_gorunuyor(): void
    {
        $sector = Sector::active()->whereNotNull('faq')->first();

        $response = $this->get('/sektorler/'.$sector->slug)->assertOk();

        foreach ($sector->faq as $item) {
            $response->assertSee($item['q'], escape: true);
            $response->assertSee($item['a'], escape: true);
        }
    }

    public function test_sorusuz_sektorde_faqpage_basilmaz(): void
    {
        $sector = Sector::active()->first();
        $sector->update(['faq' => null, 'faq_en' => null]);

        $html = $this->get('/sektorler/'.$sector->slug)->assertOk()->getContent();

        $this->assertNull(collect($this->jsonLd($html))->firstWhere('@type', 'FAQPage'));
    }
}
