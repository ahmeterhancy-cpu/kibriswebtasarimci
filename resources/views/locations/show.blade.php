@php
    $isEn = app()->getLocale() === 'en';
    $city = $location->t('name');
    $heading = $location->t('headline') ?: $city;
    // "Buradayız" ayrımı BÖLGEYE değil ofis varlığına bağlı: Edirne Türkiye'de
    // ama orada da yüz yüze görüşülebiliyor. Adres tek kaynaktan (offices).
    $office = $location->office;
    $hasOffice = $office !== null;
@endphp

<x-app-layout
    :seo-title="$location->t('seo_title') ?: $heading"
    :seo-description="$location->t('seo_description') ?: $location->t('intro')">

    @push('jsonld')
        {{-- Şehre özel ProfessionalService.
             `address` ve `geo` YALNIZCA ofisimizin bulunduğu şehirlerde basılır
             (Girne, Edirne). Ofisin olmadığı bir şehre adres ya da koordinat
             yazmak arama motoruna yanlış konum sinyali verir ve yerel
             sonuçlarda ters teper. Uydurma puan ya da yorum yok. --}}
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
                    'serviceType' => $sectors->map(fn ($s) => $s->t('name'))->all(),
                ];

                if ($office) {
                    if ($office->phone) {
                        $business['telephone'] = $office->phone;
                    }

                    $business['address'] = [
                        '@type' => 'PostalAddress',
                        'streetAddress' => $office->t('address'),
                        'addressLocality' => $office->t('city'),
                        'addressCountry' => $office->country_code,
                    ];

                    if ($office->latitude && $office->longitude) {
                        $business['geo'] = [
                            '@type' => 'GeoCoordinates',
                            'latitude' => $office->latitude,
                            'longitude' => $office->longitude,
                        ];
                    }
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

    {{-- Sektör seçimi — bu sayfanın asıl işi.
         Şehir sayfaları kasten geneldir; içerik sektör sayfalarında ayrışır.
         Ziyaretçiyi oraya taşıyan blok burasıdır. --}}
    @if ($sectors->isNotEmpty())
        <section class="bg-white px-6 py-16 lg:px-12 lg:py-24">
            <div class="mx-auto max-w-[1280px]">
                <p class="k-eyebrow k-reveal mb-4 text-[#0F0F0F]/45">
                    {{ $isEn ? 'Start with your industry' : 'Sektörünüzden başlayın' }}
                </p>
                <h2 class="k-display-sm k-reveal mb-10 max-w-3xl" data-delay="100">
                    {{ $isEn ? 'What the site has to do' : 'Sitenin ne yapacağı' }}
                    <span class="k-hl">{{ $isEn ? 'depends on the work.' : 'işinize göre değişir.' }}</span>
                </h2>

                <div class="grid grid-cols-1 gap-x-8 md:grid-cols-2">
                    @foreach ($sectors as $i => $sector)
                        <a href="{{ $r('sectors.show', ['sector' => $sector->slug]) }}"
                           class="k-reveal k-row group flex items-start gap-4 border-t border-[#0F0F0F]/12 py-6 hover:text-[#E30613] md:[&:nth-last-child(-n+2)]:border-b"
                           data-delay="{{ min(($i % 2 + 1) * 100, 200) }}">
                            @if ($sector->icon)
                                <span class="mt-0.5 shrink-0 text-xl" aria-hidden="true">{{ $sector->icon }}</span>
                            @endif
                            <span class="min-w-0 flex-1">
                                <span class="block text-lg font-bold tracking-tight">{{ $sector->t('name') }}</span>
                                @if ($sector->t('intro'))
                                    <span class="mt-1.5 block text-sm leading-relaxed text-[#0F0F0F]/55">
                                        {{ Str::limit($sector->t('intro'), 95) }}
                                    </span>
                                @endif
                            </span>
                            <span class="k-row__arrow mt-1 shrink-0 text-xl" aria-hidden="true">→</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Nasıl çalışıyoruz — bölgeye göre iki dürüst varyant. --}}
    <section class="k-dark bg-[#0F0F0F] px-6 py-16 lg:px-12 lg:py-24">
        <div class="mx-auto grid max-w-[1280px] grid-cols-1 gap-12 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <p class="k-eyebrow k-reveal mb-5" style="color:rgba(255,255,255,0.45);">
                    {{ $isEn ? 'How it works' : 'Nasıl çalışıyor' }}
                </p>
                {{-- Ofis olmayan şehirlerde "uzaktan çalışıyoruz" denmez:
                     eksiklik gibi okunuyor. Vurgu sürecin her şehirde aynı
                     işlediğinde. Ofis iddiası yine yok — adres ve koordinat
                     yalnız gerçek ofislerde basılıyor. --}}
                <h2 class="k-display-sm k-reveal" style="color:#ffffff;" data-delay="100">
                    {{ $hasOffice
                        ? ($isEn ? 'We are here.' : 'Buradayız.')
                        : ($isEn ? 'The same process.' : 'Süreç aynı.') }}
                </h2>
                <p class="k-reveal mt-6 max-w-md leading-relaxed" style="color:rgba(255,255,255,0.6);" data-delay="200">
                    {{ $hasOffice
                        ? ($isEn
                            ? 'We have an office in this city, so meeting in person is an option. Everything after that — approvals, revisions, launch — follows the same process as every other project.'
                            : 'Bu şehirde ofisimiz var, dolayısıyla yüz yüze görüşmek mümkün. Sonrası — onaylar, revizyonlar, yayın — diğer tüm projelerle aynı süreçte ilerliyor.')
                        : ($isEn
                            ? 'Briefing, design approval, revisions and launch run online — exactly as they do for clients in the cities where we have an office. The scope, the timeline and the person you talk to do not change.'
                            : 'Brief, tasarım onayı, revizyon ve yayın çevrimiçi yürütülür — ofisimizin bulunduğu şehirlerdeki projelerle birebir aynı şekilde. Kapsam, takvim ve muhatabınız değişmez.') }}
                </p>

                {{-- Ofis kartı. Adres offices tablosundan; tek kaynak. --}}
                @if ($office)
                    <div class="k-reveal mt-8 border-t pt-7" style="border-color:rgba(255,255,255,0.14);" data-delay="300">
                        <p class="k-eyebrow mb-3" style="color:rgba(255,255,255,0.35);">
                            {{ $isEn ? 'Office' : 'Ofis' }}
                        </p>
                        <p class="text-sm leading-relaxed" style="color:#ffffff;">{{ $office->fullAddress() }}</p>
                        <div class="mt-3 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm">
                            @if ($office->phone)
                                <a href="tel:{{ preg_replace('/[^\d+]/', '', $office->phone) }}"
                                   class="k-link k-link-in transition-colors duration-300 hover:text-[#E30613]"
                                   style="color:rgba(255,255,255,0.7);">{{ $office->phone }}</a>
                            @endif
                            <a href="{{ $office->mapsUrl() }}" target="_blank" rel="noopener"
                               class="k-link k-link-in text-[0.7rem] font-bold uppercase tracking-[0.14em] transition-colors duration-300 hover:text-[#E30613]"
                               style="color:rgba(255,255,255,0.5);">{{ $isEn ? 'Directions ↗' : 'Yol tarifi ↗' }}</a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-6 lg:col-start-7">
                <ol class="space-y-7">
                    @php
                        $steps = $isEn
                            ? [
                                ['Scope', 'You pick the scope in the quote wizard and see the exact total. No estimate ranges.'],
                                ['Design', 'Designed from scratch for your business. No templates, no bought themes.'],
                                ['Build', 'Hand-written front end, admin panel where you need one, TR + EN as standard.'],
                                ['Launch', 'Domain, hosting, SSL, search console. Handed over working, not "almost ready".'],
                            ]
                            : [
                                ['Kapsam', 'Teklif sihirbazından kapsamı seçiyor, kesin tutarı görüyorsunuz. Tahmini aralık yok.'],
                                ['Tasarım', 'İşinize göre sıfırdan tasarlanıyor. Şablon yok, satın alınmış tema yok.'],
                                ['Geliştirme', 'Elle yazılan arayüz, gerekiyorsa yönetim paneli, standart olarak TR + EN.'],
                                ['Yayın', 'Alan adı, hosting, SSL, arama konsolu. Çalışır hâlde teslim, "neredeyse hazır" değil.'],
                            ];
                    @endphp
                    @foreach ($steps as $i => [$title, $text])
                        <li class="k-reveal flex gap-6" data-delay="{{ min(($i + 1) * 100, 400) }}">
                            <span class="shrink-0 pt-1 text-[0.7rem] font-black tracking-[0.2em] text-[#E30613]" aria-hidden="true">
                                {{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <span>
                                <span class="block text-lg font-bold tracking-tight" style="color:#ffffff;">{{ $title }}</span>
                                <span class="mt-1.5 block text-sm leading-relaxed" style="color:rgba(255,255,255,0.55);">{{ $text }}</span>
                            </span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    {{-- Panelden bu şehre GERÇEK bir metin yazıldıysa burada çıkar.
         Varsayılan olarak boş: şehir adı değiştirilmiş kopya metin üretmemek
         için seeder bu alanı doldurmuyor. --}}
    @if (filled($location->t('body')))
        <section class="bg-white px-6 py-16 lg:px-12 lg:py-24">
            <div class="mx-auto grid max-w-[1280px] grid-cols-1 gap-12 lg:grid-cols-12">
                <div class="lg:col-span-7">
                    <div class="k-article k-reveal">{!! $location->t('body') !!}</div>
                </div>
                <aside class="lg:col-span-4 lg:col-start-9">
                    <div class="k-reveal sticky top-28">
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
                    </div>
                </aside>
            </div>
        </section>
    @endif

    {{-- Hizmetler + fiyat şeridi --}}
    <section class="bg-[#F1F1EF] px-6 py-14 lg:px-12 lg:py-20">
        <div class="mx-auto grid max-w-[1280px] grid-cols-1 gap-10 lg:grid-cols-12">
            @if ($services->isNotEmpty())
                <div class="lg:col-span-7">
                    <p class="k-eyebrow k-reveal mb-5 text-[#0F0F0F]/45">
                        {{ $isEn ? 'Services in '.$city : $city.'\'de hizmetlerimiz' }}
                    </p>
                    <ul class="flex flex-wrap gap-x-7 gap-y-3">
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

            @if ($packages->isNotEmpty())
                <div class="lg:col-span-4 lg:col-start-9">
                    <p class="k-eyebrow k-reveal mb-5 text-[#0F0F0F]/45">{{ $isEn ? 'Packages' : 'Paketler' }}</p>
                    <ul class="k-reveal space-y-3" data-delay="100">
                        @foreach ($packages as $package)
                            <li class="flex items-baseline justify-between gap-4 border-b border-[#0F0F0F]/10 pb-3 text-sm">
                                <span class="text-[#0F0F0F]/80">{{ $package->t('name') }}</span>
                                <span class="shrink-0 font-bold">{{ $package->formatPrice($package->price) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>

    {{-- Aynı bölgedeki diğer şehirler — iç bağlantı --}}
    @if ($siblings->isNotEmpty())
        <section class="bg-white px-6 py-14 lg:px-12 lg:py-20">
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
