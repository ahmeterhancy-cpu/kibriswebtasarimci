<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\TestimonialRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Müşterinin kendi yorumunu yazdığı sayfa.
 *
 * Giriş yok, tekil token var: bağlantı yalnız davet edilen kişiye gönderilir.
 * Gelen yorum DOĞRUDAN YAYINLANMAZ — `is_active = false` ile bekler, panelde
 * onaylanınca çıkar. Böylece hem müşterinin rızası hem de yayın kararı kayıt
 * altında oluyor.
 */
class TestimonialController extends Controller
{
    /**
     * Sayfa dört hâlden birini gösterir: form, teşekkür, "zaten dolduruldu",
     * "süresi doldu". Kullanılmış bağlantıda 410 hata sayfası göstermek yerine
     * anlaşılır bir mesaj veriyoruz — karşıdaki müşteri, hata ayıklayan biri
     * değil.
     */
    public function form(TestimonialRequest $request): View
    {
        return view('testimonial.form', ['request' => $request]);
    }

    public function store(Request $httpRequest, TestimonialRequest $request): RedirectResponse
    {
        abort_unless($request->isUsable(), 410);

        $data = $httpRequest->validate([
            'name' => ['required', 'string', 'max:150'],
            'role' => ['nullable', 'string', 'max:150'],
            'company' => ['nullable', 'string', 'max:190'],
            'email' => ['nullable', 'email', 'max:190'],
            'quote' => ['required', 'string', 'min:40', 'max:600'],
            // Rıza kutusu işaretlenmeden yorum kaydedilmez.
            'consent' => ['accepted'],
            // Bal kabı: gerçek kullanıcı bu alanı görmez.
            'website' => ['prohibited'],
        ], [
            'consent.accepted' => __('site.testimonial.consent_required'),
            'quote.min' => __('site.testimonial.quote_min'),
        ], [
            'name' => __('site.form.name'),
            'quote' => __('site.testimonial.quote'),
        ]);

        $testimonial = Testimonial::create([
            'name' => $data['name'],
            'role' => $data['role'] ?? null,
            'company' => $data['company'] ?? null,
            'email' => $data['email'] ?? null,
            'quote' => $data['quote'],
            'source' => 'form',
            'consented_at' => now(),
            'submitted_at' => now(),
            // Onaya kadar yayında değil.
            'is_active' => false,
            'sort_order' => 0,
        ]);

        $request->update([
            'completed_at' => now(),
            'testimonial_id' => $testimonial->getKey(),
        ]);

        return redirect()
            ->route('testimonial.form', ['request' => $request->token])
            ->with('status', 'testimonial-sent');
    }
}
