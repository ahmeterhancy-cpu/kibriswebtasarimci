<?php

namespace Tests\Feature;

use App\Filament\Resources\Works\Pages\EditWork;
use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Slug'ı yol anahtarı olan kayıtların düzenleme davranışı.
 *
 * Bu modellerde getRouteKeyName() = 'slug'. İki tuzak var ve ikisi de
 * kullanıcıya "kaydetmiyor" gibi görünüyor:
 *   1. Adres değişince sayfa eski slug'lı URL'de kalıyordu; yenileyince 404.
 *   2. Büyük harfli bir slug olduğu gibi kaydedilirse yayındaki sayfa
 *      sunucuda bulunamıyor.
 */
class SlugKaydetmeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    private function work(): Work
    {
        return Work::create([
            'title' => 'Örnek Kurumsal Site',
            'slug' => 'ornek-kurumsal-site',
            'is_active' => true,
        ]);
    }

    public function test_buyuk_harfli_slug_adres_bicimine_cevrilir(): void
    {
        $work = $this->work();

        Livewire::test(EditWork::class, ['record' => $work->getRouteKey()])
            ->fillForm([
                'title' => 'NP CYP Psikiyatri Merkezi',
                'slug' => 'NP-CYP-Psikiyatri-Merkezi',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('np-cyp-psikiyatri-merkezi', $work->fresh()->slug);
    }

    public function test_slug_degisince_yeni_adrese_yonlendirilir(): void
    {
        $work = $this->work();

        Livewire::test(EditWork::class, ['record' => $work->getRouteKey()])
            ->fillForm(['slug' => 'yeni-adres'])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect('/admin/works/yeni-adres/edit');
    }

    public function test_slug_degismediginde_yonlendirme_yapilmaz(): void
    {
        $work = $this->work();

        Livewire::test(EditWork::class, ['record' => $work->getRouteKey()])
            ->fillForm(['summary' => 'Yalnızca özet değişti'])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNoRedirect();
    }

    public function test_baslik_degisince_adres_de_degisir(): void
    {
        $work = $this->work();

        Livewire::test(EditWork::class, ['record' => $work->getRouteKey()])
            ->fillForm(['title' => 'NPCYP Psikiyatri Merkezi'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('npcyp-psikiyatri-merkezi', $work->fresh()->slug);
    }

    public function test_adres_elle_yazilmissa_baslik_uzerine_yazmaz(): void
    {
        $work = $this->work();

        Livewire::test(EditWork::class, ['record' => $work->getRouteKey()])
            // Önce adres elle yazılıyor: alan kilitleniyor.
            ->fillForm(['slug' => 'elle-yazilmis-adres'])
            ->fillForm(['title' => 'Bambaşka bir başlık'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('elle-yazilmis-adres', $work->fresh()->slug);
    }

    public function test_farkli_yazilmis_ayni_slug_benzersizlige_takilir(): void
    {
        $this->work();

        $digeri = Work::create([
            'title' => 'İkinci iş',
            'slug' => 'ikinci-is',
            'is_active' => true,
        ]);

        Livewire::test(EditWork::class, ['record' => $digeri->getRouteKey()])
            ->fillForm(['slug' => 'Ornek-Kurumsal-Site'])
            ->call('save')
            ->assertHasFormErrors(['slug']);

        $this->assertSame('ikinci-is', $digeri->fresh()->slug);
    }
}
