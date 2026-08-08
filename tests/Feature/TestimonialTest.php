<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use App\Models\TestimonialRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Yorum toplama akışı.
 *
 * Buradaki iddiaların hepsi tek bir amaca hizmet ediyor: sitede yayınlanan
 * her yorumun gerçek bir müşteriden, kendi rızasıyla geldiğini kanıtlayabilmek.
 * Uydurma referans TR'de aldatıcı reklam, UK'de (DMCC Act 2024) doğrudan yasak
 * ve Google tarafında manuel işlem sebebi.
 */
class TestimonialTest extends TestCase
{
    use RefreshDatabase;

    private function invite(array $attributes = []): TestimonialRequest
    {
        return TestimonialRequest::create($attributes + [
            'client_name' => 'Ayşe Yılmaz',
            'company' => 'Örnek Otel',
        ]);
    }

    private function payload(array $overrides = []): array
    {
        return $overrides + [
            'name' => 'Ayşe Yılmaz',
            'role' => 'İşletme sahibi',
            'company' => 'Örnek Otel',
            'quote' => 'Siteyi zamanında yayına aldılar ve rezervasyon talepleri doğrudan siteden gelmeye başladı.',
            'consent' => '1',
        ];
    }

    public function test_davet_baglantisi_formu_acar_ve_bilgileri_on_doldurur(): void
    {
        $invite = $this->invite();

        $this->get('/gorus/'.$invite->token)
            ->assertOk()
            ->assertSee('Ayşe Yılmaz', false)
            ->assertSee('Örnek Otel', false)
            // Tekil bağlantı arama motorlarına girmemeli.
            ->assertSee('name="robots" content="noindex, nofollow"', false);
    }

    public function test_gonderilen_yorum_onaya_kadar_yayinlanmaz(): void
    {
        $invite = $this->invite();

        $this->post('/gorus/'.$invite->token, $this->payload())->assertRedirect();

        $testimonial = Testimonial::firstOrFail();

        $this->assertFalse($testimonial->is_active, 'Yorum onaydan önce yayında olmamalı.');
        $this->assertSame('form', $testimonial->source);
        $this->assertNotNull($testimonial->consented_at, 'Rıza zamanı kaydedilmeli.');
        $this->assertTrue($testimonial->isVerified());

        // Ana sayfada henüz görünmemeli.
        $this->get('/')->assertOk()->assertDontSee($testimonial->quote, false);
    }

    public function test_yayin_onayi_isaretlenmeden_yorum_kaydedilmez(): void
    {
        $invite = $this->invite();

        $this->post('/gorus/'.$invite->token, $this->payload(['consent' => '']))
            ->assertSessionHasErrors('consent');

        $this->assertSame(0, Testimonial::count());
    }

    public function test_davet_baglantisi_tek_kullanimlik(): void
    {
        $invite = $this->invite();

        $this->post('/gorus/'.$invite->token, $this->payload());
        $this->post('/gorus/'.$invite->token, $this->payload())->assertStatus(410);

        $this->assertSame(1, Testimonial::count());
    }

    public function test_suresi_dolmus_davet_form_gostermez(): void
    {
        $invite = $this->invite(['expires_at' => now()->subDay()]);

        $this->get('/gorus/'.$invite->token)->assertOk()->assertSee('süresi dolmuş', false);
        $this->post('/gorus/'.$invite->token, $this->payload())->assertStatus(410);
    }

    public function test_gecersiz_token_404_verir(): void
    {
        $this->get('/gorus/gecersiz-token')->assertNotFound();
    }

    public function test_bal_kabi_dolduruldugunda_kayit_yapilmaz(): void
    {
        $invite = $this->invite();

        $this->post('/gorus/'.$invite->token, $this->payload(['website' => 'https://spam.example']))
            ->assertSessionHasErrors('website');

        $this->assertSame(0, Testimonial::count());
    }

    /**
     * Yapısal veriye yalnız DOĞRULANMIŞ yorum yazılır. Panelden elle girilen
     * bir metin doğru olabilir ama kanıtı yoktur.
     */
    public function test_yalnizca_dogrulanmis_yorum_yapisal_veriye_yazilir(): void
    {
        $manual = Testimonial::create([
            'name' => 'Elle Girilen',
            'quote' => 'Panelden elle girilmiş bir yorum.',
            'source' => 'panel',
            'is_active' => true,
        ]);

        $invite = $this->invite();
        $this->post('/gorus/'.$invite->token, $this->payload());
        $verified = Testimonial::where('source', 'form')->firstOrFail();
        $verified->update(['is_active' => true]);

        $html = $this->get('/')->assertOk()->getContent();

        // İkisi de sayfada görünür…
        $this->assertStringContainsString($manual->quote, $html);
        $this->assertStringContainsString($verified->quote, $html);

        // …ama Review verisinde yalnız doğrulanmış olan var.
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);
        $reviews = [];

        foreach ($matches[1] as $raw) {
            $decoded = json_decode(trim($raw), true);
            $reviews = array_merge($reviews, $decoded['review'] ?? []);
        }

        $this->assertCount(1, $reviews);
        $this->assertSame($verified->quote, $reviews[0]['reviewBody']);
    }

    public function test_uydurma_puan_yapisal_veriye_girmez(): void
    {
        $invite = $this->invite();
        $this->post('/gorus/'.$invite->token, $this->payload());
        Testimonial::firstOrFail()->update(['is_active' => true]);

        $this->get('/')->assertOk()->assertDontSee('aggregateRating', false);
    }
}
