@php
    $isEn = app()->getLocale() === 'en';
    $city = $location->t('name');
    $heading = $location->t('headline') ?: $city;
@endphp

<x-app-layout
    :seo-title="$location->t('seo_title') ?: $heading"
    :seo-description="$location->t('seo_description') ?: $location->t('intro')">

    @push('jsonld')
        {{-- Şehre özel LocalBusiness: hizmet verilen bölge ve koordinat.
             Uydurma adres, puan ya da yorum YOK — elimizde olmayan veriyi
             yapısal veriye yazmak hem yanıltıcı hem cezalandırılan bir şey. --}}
        <script type="application/ld+json">
            @php
                $business = [
                    '@context' => 'https://schema.org',
                    '@type' => 'ProfessionalService',
                    '@id' => url()->current().'#business',
                    'name' => $site('site_name', 'Kıbrıs Web Tasarımcı').' — '.$city,
                    'url' => url()->current(),
                    'parentOrganization' => ['@type' => 'Organization', 'name' => $site('site_name', 'Kıbrıs Web Tasarımcı'), 'url' => url('/')],
                    'description' => $location->t('seo_description') ?: $location->t('intro'),
                    'email' => $site('contact_email', 'info@kibriswebtasarimci.com'),
                    'telephone' => $site('contact_phone'),
                    'priceRange' => '₺₺',
                    'knowsLanguage' => ['tr', 'en'],
                    'areaServed' => [
                        '@type' => 'City',
                        'name' => $city,
                        'containedInPlace' => [
                            '@type' => 'Country',
                            'name' => $location->country_code === 'TR' ? 'Türkiye' : 'Cyprus',
                        ],
                    ],
                    'serviceType' => $services->map(fn ($s) => $s->t('title'))->all(),
                ];

                if ($location->latitude && $location->longitude) {
                    $business['geo'] = [
                        '@type' => 'GeoCoordinates',
                        'latitude' => $location->latitude,
                        'longitude' => $location->longitude,
                    ];
                }

                echo json_encode($business, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            @endphp
        </script>
        <script type="application/ld+json">
            @php
                echo json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => __('site.nav.home'), 'item' => $r('home')],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => $isEn ? 'Where we work' : 'Hizmet bölgeleri', 'item' => $r('locations.index')],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $city, 'item' => url()->current()],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            @endphp
        </script>
    @endpush

    <x-page-hero :eyebrow="$location->regionLabel()" :lead="$location->t('intro')">
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

    {{-- Pazar maddeleri --}}
    @if (filled($location->t('highlights')))
        <section class="border-b border-[#0F0F0F]/10 bg-white px-6 py-12 lg:px-12 lg:py-16">
            <div class="mx-auto max-w-[1280px]">
                <ul class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ((array) $location->t('highlights') as $i => $item)
                        <li class="k-reveal flex gap-3 text-sm text-[#0F0F0F]/75" data-delay="{{ min(($i + 1) * 100, 400) }}">
                            <span class="mt-[9px] block h-1 w-1 shrink-0 rounded-full bg-[#E30613]" aria-hidden="true"></span>
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- Şehre özel metin + yan sütun --}}
    <section class="bg-white px-6 py-16 lg:px-12 lg:py-24">
        <div class="mx-auto grid max-w-[1280px] grid-cols-1 gap-12 lg:grid-cols-12">
            <div class="lg:col-span-7">
                @if (filled($location->t('body')))
                    <div class="k-article k-reveal">{!! $location->t('body') !!}</div>
                @endif
            </div>

            <aside class="lg:col-span-4 lg:col-start-9">
                <div class="k-reveal sticky top-28 space-y-8">
                    @if ($packages->isNotEmpty())
                        <div class="rounded-2xl border border-[#0F0F0F]/10 bg-[#F4F4F2] p-7">
                            <p class="k-eyebrow mb-5 text-[#0F0F0F]/45">{{ $isEn ? 'Packages' : 'Paketler' }}</p>
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

                    @if ($services->isNotEmpty())
                        <div>
                            <p class="k-eyebrow mb-4 text-[#0F0F0F]/45">
                                {{ $isEn ? 'Services in '.$city : $city.'\'de hizmetlerimiz' }}
                            </p>
                            <ul class="space-y-2">
                                @foreach ($services as $service)
                                    <li>
                                        <a href="{{ $r('services.show', ['service' => $service->slug]) }}"
                                           class="k-link k-link-in text-sm text-[#0F0F0F]/75 transition-colors duration-300 hover:text-[#E30613]">
                                            {{ $service->t('title') }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </section>

    {{-- Aynı bölgedeki diğer şehirler — iç bağlantı --}}
    @if ($siblings->isNotEmpty())
        <section class="bg-[#F4F4F2] px-6 py-14 lg:px-12 lg:py-20">
            <div class="mx-auto max-w-[1280px]">
                <p class="k-eyebrow k-reveal mb-6 text-[#0F0F0F]/45">
                    {{ $isEn ? 'Other cities in '.$location->regionLabel() : $location->regionLabel().'\'ta diğer şehirler' }}
                </p>
                <div class="flex flex-wrap gap-2.5">
                    @foreach ($siblings as $sibling)
                        <a href="{{ $r('locations.show', ['location' => $sibling->slug]) }}"
                           class="k-btn k-btn--ghost !px-5 !py-2.5 !text-[0.66rem]">
                            <span>{{ $sibling->t('name') }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-app-layout>
