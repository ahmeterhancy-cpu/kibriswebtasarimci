@php
    $isEn = app()->getLocale() === 'en';
    $email = $site('contact_email', 'info@kibriswebtasarimci.com');
    $phone = $site('contact_phone', '+90 533 000 00 00');
    $address = $site('contact_address', $isEn ? 'Kyrenia, North Cyprus' : 'Girne, Kuzey Kıbrıs');
@endphp

<x-app-layout
    :seo-title="__('site.nav.contact')"
    :seo-description="$isEn
        ? 'Talk to a web design studio in North Cyprus. We reply within one business day.'
        : 'Kuzey Kıbrıs\'ta bir web tasarım stüdyosuyla konuşun. Bir iş günü içinde dönüş yapıyoruz.'">

    <x-page-hero :eyebrow="__('site.nav.contact')"
                 :lead="$isEn
                    ? 'Tell us what you need. If we are not the right fit we will say so — and point you somewhere better.'
                    : 'Neye ihtiyacınız olduğunu anlatın. Doğru adres biz değilsek bunu söyleriz ve daha uygun bir yer öneririz.'">
        {{ $isEn ? 'Let\'s' : 'Konuşalım' }} <span class="k-hl">{{ $isEn ? 'talk.' : 'mı?' }}</span>
    </x-page-hero>

    <section class="bg-white px-6 py-16 lg:px-12 lg:py-24">
        <div class="mx-auto grid max-w-[1280px] grid-cols-1 gap-14 lg:grid-cols-12">

            {{-- Form --}}
            <div class="lg:col-span-7">
                @if (session('status') === 'contact-sent')
                    <div class="k-reveal mb-8 rounded-xl border-l-4 border-[#E30613] bg-[#F1F1EF] px-6 py-5">
                        <p class="font-semibold">{{ __('site.form.success_contact') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-8 rounded-xl border-l-4 border-[#E30613] bg-[#FDF0F1] px-6 py-5">
                        <p class="mb-2 font-semibold">{{ __('site.form.error') }}</p>
                        <ul class="list-disc pl-5 text-sm text-[#0F0F0F]/70">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ $r('contact.store') }}" data-busy class="k-reveal grid grid-cols-1 gap-x-8 gap-y-7 sm:grid-cols-2">
                    @csrf

                    {{-- Bal kabı: ekranda görünmez, botlar doldurur. --}}
                    <div class="absolute left-[-9999px]" aria-hidden="true">
                        <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>

                    <div>
                        <label for="c-name" class="k-field-label">{{ __('site.form.name') }} *</label>
                        <input id="c-name" name="name" type="text" required maxlength="150"
                               value="{{ old('name') }}" class="k-field" autocomplete="name">
                    </div>

                    <div>
                        <label for="c-email" class="k-field-label">{{ __('site.form.email') }} *</label>
                        <input id="c-email" name="email" type="email" required maxlength="190"
                               value="{{ old('email') }}" class="k-field" autocomplete="email">
                    </div>

                    <div>
                        <label for="c-phone" class="k-field-label">{{ __('site.form.phone') }}</label>
                        <input id="c-phone" name="phone" type="tel" maxlength="60"
                               value="{{ old('phone') }}" class="k-field" autocomplete="tel">
                    </div>

                    <div>
                        <label for="c-subject" class="k-field-label">{{ __('site.form.subject') }}</label>
                        <input id="c-subject" name="subject" type="text" maxlength="190"
                               value="{{ old('subject') }}" class="k-field">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="c-message" class="k-field-label">{{ __('site.form.message') }} *</label>
                        <textarea id="c-message" name="message" required rows="5" maxlength="5000"
                                  class="k-field resize-y">{{ old('message') }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <button type="submit" data-cursor="cta" data-busy-text="{{ __('site.form.sending') }}"
                                class="k-btn k-btn--brand">
                            <span data-busy-label style="color:inherit;">{{ __('site.form.send') }}</span>
                            <span class="k-btn__arrow" aria-hidden="true">→</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- İletişim bilgileri --}}
            <aside class="lg:col-span-4 lg:col-start-9">
                <div class="k-reveal space-y-9" data-delay="200">
                    <div>
                        <p class="k-eyebrow mb-3 text-[#0F0F0F]/45">{{ __('site.form.email') }}</p>
                        <a href="mailto:{{ $email }}" class="k-link text-lg font-bold tracking-tight">{{ $email }}</a>
                    </div>
                    <div>
                        <p class="k-eyebrow mb-3 text-[#0F0F0F]/45">{{ __('site.form.phone') }}</p>
                        <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="k-link text-lg font-bold tracking-tight">{{ $phone }}</a>
                    </div>
                    @if ($offices->isEmpty())
                        <div>
                            <p class="k-eyebrow mb-3 text-[#0F0F0F]/45">{{ $isEn ? 'Where' : 'Neredeyiz' }}</p>
                            <p class="text-lg font-bold tracking-tight">{{ $address }}</p>
                        </div>
                    @endif

                    <div class="k-rule"></div>

                    <div>
                        <p class="mb-4 text-sm leading-relaxed text-[#0F0F0F]/60">
                            {{ $isEn
                                ? 'Know what you want already? Skip the form and use the quote wizard — it gives you a live budget estimate.'
                                : 'Ne istediğinizi biliyorsanız formu atlayın, teklif sihirbazını kullanın — anlık bütçe tahmini veriyor.' }}
                        </p>
                        <a href="{{ $r('quote') }}" class="k-btn k-btn--ghost">
                            <span>{{ __('site.nav.quote') }}</span>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    {{-- Ofisler.
         Adres tek kaynaktan (offices tablosu) geliyor; burada, şehir
         sayfalarında ve yapısal veride aynı kaydı okuyoruz. Adresin üç yerde
         ayrı tutulması er ya da geç tutarsızlık üretir ve adres tutarsızlığı
         yerel SEO'da doğrudan sıralama kaybı demek. --}}
    @if ($offices->isNotEmpty())
        <section class="k-dark bg-[#0F0F0F] px-6 py-16 lg:px-12 lg:py-24">
            <div class="mx-auto max-w-[1280px]">
                <p class="k-eyebrow k-reveal mb-4" style="color:rgba(255,255,255,0.45);">
                    {{ $isEn ? 'Offices' : 'Ofisler' }}
                </p>
                <h2 class="k-display-sm k-reveal mb-12 max-w-2xl" style="color:#ffffff;" data-delay="100">
                    {{ $isEn ? 'Three addresses,' : 'Üç adres,' }}
                    <span class="k-hl">{{ $isEn ? 'one team.' : 'tek ekip.' }}</span>
                </h2>

                <div class="grid grid-cols-1 gap-x-10 gap-y-12 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($offices as $i => $office)
                        <div class="k-reveal border-t pt-7" style="border-color:rgba(255,255,255,0.14);"
                             data-delay="{{ min(($i + 1) * 100, 400) }}">
                            <p class="flex items-center gap-2 text-[0.7rem] font-black uppercase tracking-[0.18em]"
                               style="color:rgba(255,255,255,0.45);">
                                @if ($office->is_primary)
                                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-[#E30613]" aria-hidden="true"></span>
                                @endif
                                {{ $office->t('name') }}
                            </p>

                            <p class="mt-4 text-lg font-bold leading-snug tracking-tight" style="color:#ffffff;">
                                {{ $office->t('city') }}
                            </p>

                            <p class="mt-2 text-sm leading-relaxed" style="color:rgba(255,255,255,0.55);">
                                {{ $office->t('address') }}<br>
                                {{ $office->t('country') }}
                            </p>

                            <div class="mt-5 space-y-1.5 text-sm">
                                @if ($office->phone)
                                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $office->phone) }}"
                                       class="k-link k-link-in block transition-colors duration-300 hover:text-[#E30613]"
                                       style="color:#ffffff;">{{ $office->phone }}</a>
                                @endif
                                @if ($office->email)
                                    <a href="mailto:{{ $office->email }}"
                                       class="k-link k-link-in block transition-colors duration-300 hover:text-[#E30613]"
                                       style="color:rgba(255,255,255,0.6);">{{ $office->email }}</a>
                                @endif
                            </div>

                            <a href="{{ $office->mapsUrl() }}" target="_blank" rel="noopener"
                               class="k-link k-link-in mt-5 inline-flex items-center gap-2 text-[0.7rem] font-bold uppercase tracking-[0.14em] transition-colors duration-300 hover:text-[#E30613]"
                               style="color:rgba(255,255,255,0.5);">
                                <span>{{ $isEn ? 'Directions' : 'Yol tarifi' }}</span>
                                <span aria-hidden="true">↗</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Harita — panelden adres araması ya da özel gömme adresi girilirse.
         iframe tembel yüklenir: haritalar ağır ve sayfanın altında, ilk
         boyamayı bekletmelerinin bir anlamı yok. --}}
    @php
        $mapEmbed = $site('map_embed');
        $mapQuery = $site('map_query');
        $mapSrc = $mapEmbed
            ?: ($mapQuery ? 'https://www.google.com/maps?q='.rawurlencode($mapQuery).'&output=embed' : null);
    @endphp

    @if ($mapSrc)
        <section class="border-t border-[#0F0F0F]/10">
            <iframe
                src="{{ $mapSrc }}"
                title="{{ $site('map_title', $site('site_name', 'Kıbrıs Web Tasarımcı')) }}"
                class="block h-[380px] w-full border-0 lg:h-[460px]"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen></iframe>
        </section>
    @endif

    @if ($faqs->isNotEmpty())
        <section class="bg-[#F1F1EF] px-6 py-16 lg:px-12 lg:py-24">
            <div class="mx-auto max-w-[900px]" data-acc="single">
                <p class="k-eyebrow k-reveal mb-8 text-[#0F0F0F]/45">{{ __('site.common.faq') }}</p>
                @foreach ($faqs as $faq)
                    <div class="k-acc k-reveal border-t border-[#0F0F0F]/12 last:border-b">
                        <h2>
                            <button type="button" data-acc-trigger aria-expanded="false"
                                    class="flex w-full items-start justify-between gap-6 py-6 text-left">
                                <span class="text-lg font-bold tracking-tight">{{ $faq->t('question') }}</span>
                                <span class="k-acc__sign mt-1 shrink-0 text-xl text-[#E30613]" aria-hidden="true">+</span>
                            </button>
                        </h2>
                        <div class="k-acc__body"><div>
                            <p class="pb-6 leading-relaxed text-[#0F0F0F]/65">{{ $faq->t('answer') }}</p>
                        </div></div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

</x-app-layout>
