@php
    $isEn = app()->getLocale() === 'en';
@endphp

<x-app-layout
    :seo-title="$isEn ? 'Industries' : 'Sektörler'"
    :seo-description="$isEn
        ? 'What a website actually has to do differs by industry. Hotels, restaurants, real estate, e-commerce, clinics, professional services, education and industrial B2B.'
        : 'Web sitesinin ne yapması gerektiği sektöre göre değişir. Otel, restoran, emlak, e-ticaret, klinik, danışmanlık, eğitim ve sanayi.'">

    @push('jsonld')
        <script type="application/ld+json">
            @php
                $items = [];
                foreach ($sectors as $i => $item) {
                    $items[] = [
                        '@type' => 'ListItem',
                        'position' => $i + 1,
                        'name' => $item->t('name'),
                        'url' => $r('sectors.show', ['sector' => $item->slug]),
                    ];
                }

                echo json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'ItemList',
                    'name' => $isEn ? 'Industries we build for' : 'Çalıştığımız sektörler',
                    'itemListElement' => $items,
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            @endphp
        </script>
    @endpush

    <x-page-hero :eyebrow="$isEn ? 'Industries' : 'Sektörler'"
                 :lead="$isEn
                    ? 'A hotel needs bookings, an agency needs filterable listings, a factory needs a dealer login. The design language stays ours; what the site has to do comes from your industry.'
                    : 'Otel rezervasyon alır, emlakçı filtrelenebilir ilan yayınlar, fabrika bayiye şifreli fiyat verir. Tasarım dili bizim; sitenin ne yapacağı sizin sektörünüzden çıkar.'">
        {{ $isEn ? 'Every industry has' : 'Her sektörün ihtiyacı' }} <span class="k-hl">{{ $isEn ? 'different needs.' : 'farklıdır.' }}</span>
    </x-page-hero>

    <section class="bg-white px-6 py-16 lg:px-12 lg:py-24">
        <div class="mx-auto max-w-[1280px]">
            <div class="grid grid-cols-1 gap-x-8 md:grid-cols-2">
                @foreach ($sectors as $i => $sector)
                    <a href="{{ $r('sectors.show', ['sector' => $sector->slug]) }}"
                       class="k-reveal k-row group flex items-start gap-5 border-t border-[#0F0F0F]/12 py-8 hover:text-[#E30613] md:[&:nth-last-child(-n+2)]:border-b"
                       data-delay="{{ min(($i % 2 + 1) * 100, 200) }}">
                        @if ($sector->icon)
                            <span class="mt-1 shrink-0 text-2xl" aria-hidden="true">{{ $sector->icon }}</span>
                        @endif
                        <span class="min-w-0 flex-1">
                            <span class="k-display-xs block">{{ $sector->t('name') }}</span>
                            @if ($sector->t('intro'))
                                <span class="mt-2.5 block text-sm leading-relaxed text-[#0F0F0F]/55">
                                    {{ $sector->t('intro') }}
                                </span>
                            @endif
                        </span>
                        <span class="k-row__arrow mt-1 shrink-0 text-2xl" aria-hidden="true">→</span>
                    </a>
                @endforeach
            </div>

            <p class="k-reveal mt-14 max-w-2xl text-sm leading-relaxed text-[#0F0F0F]/55">
                {{ $isEn
                    ? 'Not on the list? That is normal — these are the ones we get asked about most. The approach is the same: we start from what the site has to accomplish, not from a template.'
                    : 'Listede yoksanız bu normal — bunlar en sık sorulanlar. Yaklaşım aynı: şablondan değil, sitenin neyi başarması gerektiğinden başlıyoruz.' }}
            </p>

            <div class="k-reveal mt-8 flex flex-wrap gap-4" data-delay="200">
                <a href="{{ $r('quote') }}" data-cursor="cta" data-magnetic="0.3" class="k-btn k-btn--brand">
                    <span style="color:inherit;">{{ __('site.nav.quote') }}</span>
                    <span class="k-btn__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ $r('contact') }}" class="k-btn k-btn--ghost">
                    <span>{{ $isEn ? 'Talk it through' : 'Önce konuşalım' }}</span>
                </a>
            </div>

            {{-- Sektör listesinden şehir listesine. İki liste birbirine
                 bağlı değildi; /web-tasarim sayfası neredeyse hiç bağ almıyordu. --}}
            <p class="k-reveal mt-12 text-sm text-[#0F0F0F]/55" data-delay="300">
                {{ $isEn ? 'Working outside Cyprus? See' : 'Kıbrıs dışındaysanız' }}
                <a href="{{ $r('locations.index') }}" class="k-link font-semibold text-[#0F0F0F] hover:text-[#E30613]">{{ $isEn ? 'the cities we work in' : 'hizmet verdiğimiz şehirlere' }}</a>{{ $isEn ? '.' : ' bakın.' }}
            </p>
        </div>
    </section>

</x-app-layout>
