@php
    $isEn = app()->getLocale() === 'en';
    $heading = $sector->t('headline') ?: $sector->t('name');
    $needs = (array) $sector->t('needs');
    $features = (array) $sector->t('features');
@endphp

<x-app-layout
    :seo-title="$sector->t('seo_title') ?: $heading"
    :seo-description="$sector->t('seo_description') ?: $sector->t('intro')">

    @push('jsonld')
        {{-- Sektöre özel Service: kimin için, hangi kapsamda.
             Uydurma puan/yorum yok. --}}
        <script type="application/ld+json">
            @php
                $service = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Service',
                    '@id' => url()->current().'#service',
                    'name' => $heading,
                    'url' => url()->current(),
                    'serviceType' => $sector->t('name'),
                    'description' => $sector->t('seo_description') ?: $sector->t('intro'),
                    'provider' => ['@id' => url('/').'#organization'],
                    'audience' => ['@type' => 'BusinessAudience', 'name' => $sector->t('name')],
                    'areaServed' => [
                        ['@type' => 'Country', 'name' => 'Cyprus'],
                        ['@type' => 'Country', 'name' => 'Türkiye'],
                    ],
                ];

                if ($features) {
                    $service['hasOfferCatalog'] = [
                        '@type' => 'OfferCatalog',
                        'name' => $isEn ? 'Included' : 'Kapsam',
                        'itemListElement' => array_map(fn ($f) => [
                            '@type' => 'Offer',
                            'itemOffered' => ['@type' => 'Service', 'name' => $f],
                        ], $features),
                    ];
                }

                echo json_encode($service, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            @endphp
        </script>
        <script type="application/ld+json">
            @php
                echo json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => __('site.nav.home'), 'item' => $r('home')],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => $isEn ? 'Industries' : 'Sektörler', 'item' => $r('sectors.index')],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $sector->t('name'), 'item' => url()->current()],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            @endphp
        </script>
    @endpush

    <x-page-hero :eyebrow="trim(($sector->icon ? $sector->icon.'  ' : '').$sector->t('name'))"
                 :lead="$sector->t('intro')">
        {{ $heading }}
        <x-slot:actions>
            <a href="{{ $r('quote') }}" data-cursor="cta" data-magnetic="0.3" class="k-btn k-btn--brand">
                <span style="color:inherit;">{{ __('site.nav.quote') }}</span>
                <span class="k-btn__arrow" aria-hidden="true">→</span>
            </a>
            <a href="{{ $r('packages') }}" class="k-btn k-btn--ghost">
                <span>{{ $isEn ? 'See pricing' : 'Fiyatları gör' }}</span>
            </a>
        </x-slot:actions>
    </x-page-hero>

    {{-- Bu sektörde site neyi çözmeli --}}
    @if ($needs)
        <section class="k-dark bg-[#0F0F0F] px-6 py-16 lg:px-12 lg:py-24">
            <div class="mx-auto max-w-[1280px]">
                <p class="k-eyebrow k-reveal mb-10" style="color:rgba(255,255,255,0.45);">
                    {{ $isEn ? 'What the site has to solve' : 'Sitenin çözmesi gerekenler' }}
                </p>
                <ol class="grid grid-cols-1 gap-x-10 gap-y-8 md:grid-cols-2">
                    @foreach ($needs as $i => $need)
                        <li class="k-reveal flex gap-5" data-delay="{{ min(($i % 2 + 1) * 100, 200) }}">
                            <span class="shrink-0 text-[0.7rem] font-black tracking-[0.2em] text-[#E30613]" aria-hidden="true">
                                {{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="text-lg leading-snug tracking-tight" style="color:#ffffff;">{{ $need }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    {{-- Uzun metin + kapsam listesi --}}
    <section class="bg-white px-6 py-16 lg:px-12 lg:py-24">
        <div class="mx-auto grid max-w-[1280px] grid-cols-1 gap-12 lg:grid-cols-12">
            <div class="lg:col-span-7">
                @if (filled($sector->t('body')))
                    <div class="k-article k-reveal">{!! $sector->t('body') !!}</div>
                @endif
            </div>

            <aside class="lg:col-span-4 lg:col-start-9">
                <div class="k-reveal sticky top-28 space-y-8">
                    @if ($features)
                        <div class="rounded-2xl border border-[#0F0F0F]/10 bg-[#F4F4F2] p-7">
                            <p class="k-eyebrow mb-5 text-[#0F0F0F]/45">{{ $isEn ? 'Typical scope' : 'Tipik kapsam' }}</p>
                            <ul class="space-y-3">
                                @foreach ($features as $feature)
                                    <li class="flex gap-3 text-sm leading-relaxed text-[#0F0F0F]/75">
                                        <span class="mt-[9px] block h-1 w-1 shrink-0 rounded-full bg-[#E30613]" aria-hidden="true"></span>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($packages->isNotEmpty())
                        <div>
                            <p class="k-eyebrow mb-4 text-[#0F0F0F]/45">{{ $isEn ? 'Packages' : 'Paketler' }}</p>
                            <ul class="space-y-3">
                                @foreach ($packages as $package)
                                    <li class="flex items-baseline justify-between gap-4 text-sm">
                                        <span class="text-[#0F0F0F]/80">{{ $package->t('name') }}</span>
                                        <span class="shrink-0 font-bold">{{ $package->formatPrice($package->price) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ $r('packages') }}" class="k-btn k-btn--ink mt-6 w-full justify-center">
                                <span style="color:inherit;">{{ $isEn ? 'All packages' : 'Tüm paketler' }}</span>
                            </a>
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </section>

    {{-- Sektör × şehir: bu işi nerelerde yapıyoruz --}}
    @if ($locations->isNotEmpty())
        <section class="bg-[#F4F4F2] px-6 py-14 lg:px-12 lg:py-20">
            <div class="mx-auto max-w-[1280px]">
                {{-- Baslik, tum sehirler sayfasina baglantidir: /web-tasarim sayfasina
                     baska hicbir yerden bag gelmiyordu, Google oraya ulasmakta
                     zorlaniyordu. --}}
                <a href="{{ $r('locations.index') }}"
                   class="k-eyebrow k-reveal k-link k-link-in mb-6 inline-block text-[#0F0F0F]/45 transition-colors duration-300 hover:text-[#E30613]">
                    {{ $isEn ? 'Where we work' : 'Nerelerde çalışıyoruz' }} →
                </a>
                <div class="flex flex-wrap gap-2.5">
                    @foreach ($locations as $location)
                        <a href="{{ $r('locations.show', ['location' => $location->slug]) }}"
                           class="k-btn k-btn--ghost !px-5 !py-2.5 !text-[0.66rem]">
                            <span>{{ $location->t('name') }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Sektörden hizmetlere: bu sayfaya gelen ziyaretçi "ne yapıyorsunuz"
         sorusunun cevabını hizmet sayfalarında buluyor. Bu bağlantı yoktu. --}}
    @if ($services->isNotEmpty())
        <section class="bg-white px-6 py-14 lg:px-12 lg:py-20">
            <div class="mx-auto max-w-[1280px]">
                <a href="{{ $r('services.index') }}"
                   class="k-eyebrow k-reveal k-link k-link-in mb-6 inline-block text-[#0F0F0F]/45 transition-colors duration-300 hover:text-[#E30613]">
                    {{ $isEn ? 'Services we provide' : 'Verdiğimiz hizmetler' }} →
                </a>
                <div class="flex flex-wrap gap-x-7 gap-y-3">
                    @foreach ($services as $service)
                        <a href="{{ $r('services.show', ['service' => $service->slug]) }}"
                           class="k-link k-link-in text-sm text-[#0F0F0F]/70 transition-colors duration-300 hover:text-[#E30613]">
                            {{ $service->t('title') }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Diğer sektörler --}}
    @if ($others->isNotEmpty())
        <section class="bg-white px-6 py-14 lg:px-12 lg:py-20">
            <div class="mx-auto max-w-[1280px]">
                <p class="k-eyebrow k-reveal mb-6 text-[#0F0F0F]/45">{{ $isEn ? 'Other industries' : 'Diğer sektörler' }}</p>
                <div class="flex flex-wrap gap-x-7 gap-y-3">
                    @foreach ($others as $other)
                        <a href="{{ $r('sectors.show', ['sector' => $other->slug]) }}"
                           class="k-link k-link-in text-sm text-[#0F0F0F]/70 transition-colors duration-300 hover:text-[#E30613]">
                            {{ $other->icon }} {{ $other->t('name') }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-app-layout>
