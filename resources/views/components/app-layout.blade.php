@props([
    'seoTitle' => null,
    'seoDescription' => null,
    'ogImage' => null,
    'ogType' => 'website',
    'canonical' => null,
    'bodyClass' => '',
    'robots' => null,
    'paginator' => null,
])

@php
    $locale = app()->getLocale();
    $isEn = $locale === 'en';

    $siteName = $site('site_name', 'Kıbrıs Web Tasarımcı');

    $defaultDescription = $isEn
        ? 'Web design and development studio in North Cyprus. Corporate sites, e-commerce and custom web software — built fast, built to convert.'
        : 'Kuzey Kıbrıs merkezli web tasarım ve yazılım stüdyosu. Kurumsal site, e-ticaret ve özel web yazılımı — hızlı kurulur, satış getirir.';

    $pageTitle = $seoTitle ? $seoTitle.' — '.$siteName : $siteName.($isEn ? ' — Web Design in Cyprus' : ' — Kıbrıs Web Tasarım');
    $pageDescription = $seoDescription ?: $site('site_description', $defaultDescription);
    $canonicalUrl = $canonical ?: url()->current();

    $ogSetting = $site('og_image');
    $ogUrl = $ogImage
        ?: ($ogSetting ? asset('storage/'.$ogSetting) : asset('images/og-default.png'));

    $faviconSetting = $site('site_favicon');
    $faviconUrl = $faviconSetting ? asset('storage/'.$faviconSetting) : asset('favicon.svg');

    $contactEmail = $site('contact_email', 'info@kibriswebtasarimci.com');
    $contactPhone = $site('contact_phone', '+90 533 000 00 00');
    $whatsapp = preg_replace('/\D+/', '', (string) $site('contact_whatsapp', $contactPhone));
@endphp

<!DOCTYPE html>
<html lang="{{ $isEn ? 'en' : 'tr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0F0F0F">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="{{ $robots ?: 'index, follow, max-image-preview:large, max-snippet:-1' }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    {{-- Sayfalanmış listelerde önceki/sonraki — tarayıcıya dizinin sırasını
         bildirir, 2. sayfanın tek başına değerlendirilmesini engeller. --}}
    @if ($paginator)
        @if ($paginator->currentPage() > 1)
            <link rel="prev" href="{{ $paginator->previousPageUrl() }}">
        @endif
        @if ($paginator->hasMorePages())
            <link rel="next" href="{{ $paginator->nextPageUrl() }}">
        @endif
    @endif

    {{-- hreflang — her sayfanın karşı dildeki eşi AppServiceProvider'da üretilir --}}
    @php
        $trHref = $isEn ? ($alternateUrl ?? url('/')) : $canonicalUrl;
        $enHref = $isEn ? $canonicalUrl : $alternateUrl;
    @endphp
    <link rel="alternate" hreflang="tr" href="{{ $trHref }}">
    @if ($enHref)
        <link rel="alternate" hreflang="en" href="{{ $enHref }}">
    @endif
    <link rel="alternate" hreflang="x-default" href="{{ $trHref }}">

    {{-- OpenGraph / Twitter --}}
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogUrl }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $siteName }}">
    <meta property="og:locale" content="{{ $isEn ? 'en_US' : 'tr_TR' }}">
    <meta property="og:locale:alternate" content="{{ $isEn ? 'tr_TR' : 'en_US' }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $ogUrl }}">

    <link rel="icon" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">

    {{-- JSON-LD: kuruluş + site.
         areaServed listesi Şehir Sayfaları'ndan türetilir; panelden şehir
         eklendiğinde yapısal veri de kendiliğinden güncellenir.
         Uydurma puan/yorum (aggregateRating, review) BİLEREK YOK — gerçek
         müşteri değerlendirmesi olmadan yazmak yanıltıcı ve cezalandırılan
         bir uygulama. --}}
    <script type="application/ld+json">
        @php
            $seoCities = \Illuminate\Support\Facades\Cache::remember('seo_area_served', 3600, fn () => \App\Models\Location::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(['name', 'name_en', 'country_code'])
                ->map(fn ($l) => [
                    '@type' => 'City',
                    'name' => $l->name,
                    'containedInPlace' => [
                        '@type' => 'Country',
                        'name' => $l->country_code === 'TR' ? 'Türkiye' : 'Cyprus',
                    ],
                ])->all());

            $organization = [
                '@type' => 'ProfessionalService',
                '@id' => url('/').'#organization',
                'name' => $siteName,
                'url' => url('/'),
                'image' => $ogUrl,
                'email' => $contactEmail,
                'telephone' => $contactPhone,
                'description' => $pageDescription,
                'priceRange' => '₺₺',
                'currenciesAccepted' => 'TRY',
                'areaServed' => $seoCities ?: [
                    ['@type' => 'Country', 'name' => 'Cyprus'],
                    ['@type' => 'Country', 'name' => 'Türkiye'],
                ],
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $site('contact_city', 'Girne'),
                    'addressRegion' => $isEn ? 'North Cyprus' : 'Kuzey Kıbrıs',
                    'addressCountry' => 'CY',
                ],
                'knowsLanguage' => ['tr', 'en'],
            ];

            // Tüm ofisler `location` olarak bildirilir; merkez ayrıca `address`
            // alanında zaten var. Adres tek kaynaktan geldiği için burada
            // tutarsızlık üretme ihtimali yok.
            $seoOffices = \Illuminate\Support\Facades\Cache::remember('seo_offices', 3600, fn () => \App\Models\Office::query()
                ->where('is_active', true)
                ->orderByDesc('is_primary')
                ->orderBy('sort_order')
                ->get()
                ->map(function ($o) {
                    $place = [
                        '@type' => 'Place',
                        'name' => $o->name,
                        'address' => [
                            '@type' => 'PostalAddress',
                            'streetAddress' => $o->address,
                            'addressLocality' => $o->city,
                            'addressCountry' => $o->country_code,
                        ],
                    ];

                    if ($o->latitude && $o->longitude) {
                        $place['geo'] = ['@type' => 'GeoCoordinates', 'latitude' => $o->latitude, 'longitude' => $o->longitude];
                    }

                    if ($o->phone) {
                        $place['telephone'] = $o->phone;
                    }

                    return $place;
                })->all());

            if ($seoOffices) {
                $organization['location'] = $seoOffices;
            }

            $sameAs = array_values(array_filter([
                $site('social_instagram'), $site('social_linkedin'),
                $site('social_facebook'), $site('social_x'),
                $site('social_youtube'), $site('social_tiktok'),
                $site('social_behance'), $site('social_github'),
            ]));
            if ($sameAs) {
                $organization['sameAs'] = $sameAs;
            }

            echo json_encode([
                '@context' => 'https://schema.org',
                '@graph' => [
                    $organization,
                    [
                        '@type' => 'WebSite',
                        '@id' => url('/').'#website',
                        'url' => url('/'),
                        'name' => $siteName,
                        'inLanguage' => $isEn ? 'en' : 'tr',
                        'publisher' => ['@id' => url('/').'#organization'],
                    ],
                ],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        @endphp
    </script>

    @stack('jsonld')

    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Ölçüm/pixel script'leri — çerez onayı verilene kadar yüklenmez. --}}
    @include('layouts.partials.analytics')

    {{-- Panelden girilen ham <head> kodu (doğrulama etiketi, font preload…).
         Bilinçli olarak kaçışsız: buraya HTML girilmesi bekleniyor ve alan
         yalnızca panel yöneticisine açık. --}}
    @if ($customHead = $site('custom_head'))
        {!! $customHead !!}
    @endif

    @stack('head')
</head>
<body class="{{ $bodyClass }}">

    {{-- Klavye kullanıcısı için içeriğe atla --}}
    <a href="#icerik" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[10000] focus:px-5 focus:py-3 focus:bg-[#0F0F0F] focus:rounded-full focus:text-xs focus:font-bold" style="color:#ffffff;">
        {{ $isEn ? 'Skip to content' : 'İçeriğe geç' }}
    </a>

    <div class="k-progress" id="k-progress" aria-hidden="true"></div>

    <div class="k-cursor" id="k-cursor" aria-hidden="true">
        <span class="k-cursor__dot" data-cursor-dot></span>
        <span class="k-cursor__label" data-cursor-label></span>
    </div>

    <div class="k-curtain" id="k-curtain" aria-hidden="true">
        <div class="k-curtain__mark">
            <span class="k-eyebrow" style="color:rgba(255,255,255,0.55);">{{ $siteName }}</span>
        </div>
    </div>

    @include('layouts.partials.header')
    @include('layouts.partials.menu')

    <main id="icerik">
        {{ $slot }}
    </main>

    @include('layouts.partials.footer')

    @if ($whatsapp)
        <a href="https://wa.me/{{ $whatsapp }}?text={{ rawurlencode($isEn ? 'Hello, I would like a quote for a website.' : 'Merhaba, web sitesi için teklif almak istiyorum.') }}"
           target="_blank" rel="noopener"
           data-magnetic="0.25"
           class="fixed bottom-5 right-5 z-[880] grid h-14 w-14 place-items-center rounded-full bg-[#0F0F0F] transition-colors duration-300 hover:bg-[#E30613]"
           aria-label="WhatsApp">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="#ffffff" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
            </svg>
        </a>
    @endif

    @stack('scripts')

    @if ($customBody = $site('custom_body'))
        {!! $customBody !!}
    @endif
</body>
</html>
