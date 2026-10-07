@php
    $isEn = app()->getLocale() === 'en';
@endphp

<x-app-layout
    :seo-title="$service->t('seo_title') ?: $service->t('title')"
    :seo-description="$service->t('seo_description') ?: $service->t('excerpt')"
    :og-image="$service->image ? asset('storage/'.$service->image) : null">

    @push('jsonld')
        <script type="application/ld+json">
            @php
                echo json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'Service',
                    'name' => $service->t('title'),
                    'description' => $service->t('excerpt'),
                    'serviceType' => $service->t('title'),
                    'provider' => ['@type' => 'ProfessionalService', 'name' => $site('site_name', 'Kıbrıs Web Tasarımcı'), 'url' => url('/')],
                    'areaServed' => ['@type' => 'Country', 'name' => 'Cyprus'],
                    'url' => url()->current(),
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            @endphp
        </script>
        <script type="application/ld+json">
            @php
                echo json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => __('site.nav.home'), 'item' => $r('home')],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => __('site.nav.services'), 'item' => $r('services.index')],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $service->t('title'), 'item' => url()->current()],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            @endphp
        </script>
    @endpush

    <x-page-hero :eyebrow="__('site.nav.services')" :lead="$service->t('excerpt')">
        {{ $service->t('title') }}
        <x-slot:actions>
            <a href="{{ $r('quote') }}" data-cursor="cta" data-magnetic="0.3" class="k-btn k-btn--brand">
                <span style="color:inherit;">{{ __('site.nav.quote') }}</span>
                <span class="k-btn__arrow" aria-hidden="true">→</span>
            </a>
            <a href="{{ $r('services.index') }}" class="k-btn k-btn--ghost">
                <span>{{ __('site.common.back_to_services') }}</span>
            </a>
        </x-slot:actions>
    </x-page-hero>

    @if ($service->image)
        <div class="bg-[#F1F1EF] px-6 lg:px-12">
            <div class="mx-auto max-w-[1280px]">
                <div data-unmask class="aspect-[21/9] overflow-hidden rounded-lg">
                    <img src="{{ asset('storage/'.$service->image) }}" alt="{{ $service->t('title') }}"
                         class="h-full w-full object-cover">
                </div>
            </div>
        </div>
    @endif

    <section class="bg-white px-6 py-16 lg:px-12 lg:py-24">
        <div class="mx-auto grid max-w-[1280px] grid-cols-1 gap-12 lg:grid-cols-12">

            <div class="lg:col-span-7">
                @if (filled($service->t('body')))
                    <div class="k-article k-reveal">{!! $service->t('body') !!}</div>
                @endif
            </div>

            <aside class="lg:col-span-4 lg:col-start-9">
                @if (filled($service->t('features')))
                    <div class="k-reveal sticky top-28 rounded-2xl border border-[#0F0F0F]/10 bg-[#F4F4F2] p-7">
                        <p class="k-eyebrow mb-5 text-[#0F0F0F]/45">{{ $isEn ? 'Included' : 'Kapsam' }}</p>
                        <ul class="space-y-3">
                            @foreach ((array) $service->t('features') as $feature)
                                <li class="flex gap-3 text-sm text-[#0F0F0F]/75">
                                    <span class="mt-[7px] block h-1 w-1 shrink-0 rounded-full bg-[#E30613]" aria-hidden="true"></span>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ $r('quote') }}" class="k-btn k-btn--ink mt-7 w-full justify-center">
                            <span style="color:inherit;">{{ __('site.nav.quote') }}</span>
                        </a>
                    </div>
                @endif
            </aside>
        </div>
    </section>

    {{-- Hizmetten sektörlere ve şehirlere. Hizmet sayfaları site içinde
         en çok bağlantı alan ama en az bağlantı VEREN sayfalardı; sektör ve
         şehir sayfaları buradan hiç bağ almıyordu. --}}
    @if ($sectors->isNotEmpty())
        <section class="bg-white px-6 py-14 lg:px-12 lg:py-20">
            <div class="mx-auto max-w-[1280px]">
                <a href="{{ $r('sectors.index') }}"
                   class="k-eyebrow k-reveal k-link k-link-in mb-6 inline-block text-[#0F0F0F]/45 transition-colors duration-300 hover:text-[#E30613]">
                    {{ $isEn ? 'This service by industry' : 'Bu hizmeti sektörlere göre görün' }} →
                </a>
                <div class="flex flex-wrap gap-x-7 gap-y-3">
                    @foreach ($sectors as $sector)
                        <a href="{{ $r('sectors.show', ['sector' => $sector->slug]) }}"
                           class="k-link k-link-in text-sm text-[#0F0F0F]/70 transition-colors duration-300 hover:text-[#E30613]">
                            {{ $sector->icon }} {{ $sector->t('name') }}
                        </a>
                    @endforeach
                </div>

                <p class="k-reveal mt-10 text-sm text-[#0F0F0F]/55" data-delay="100">
                    {{ $isEn ? 'We deliver this service across' : 'Bu hizmeti' }}
                    <a href="{{ $r('locations.index') }}" class="k-link font-semibold text-[#0F0F0F] hover:text-[#E30613]">{{ $isEn ? 'Northern Cyprus and Türkiye' : 'Kuzey Kıbrıs ve Türkiye genelinde' }}</a>
                    {{ $isEn ? '.' : 'veriyoruz.' }}
                </p>
            </div>
        </section>
    @endif

    {{-- Diğer hizmetler --}}
    @if ($others->isNotEmpty())
        <section class="bg-[#F4F4F2] px-6 py-16 lg:px-12 lg:py-24">
            <div class="mx-auto max-w-[1280px]">
                <p class="k-eyebrow k-reveal mb-8 text-[#0F0F0F]/45">{{ $isEn ? 'Other services' : 'Diğer hizmetler' }}</p>
                <ul>
                    @foreach ($others as $i => $other)
                        <li class="k-reveal border-t border-[#0F0F0F]/12 last:border-b" data-delay="{{ min(($i + 1) * 100, 400) }}">
                            <a href="{{ $r('services.show', ['service' => $other->slug]) }}"
                               class="k-row group flex items-center justify-between gap-6 py-6 hover:text-[#E30613]">
                                <span class="k-display-xs">{{ $other->t('title') }}</span>
                                <span class="k-row__arrow text-2xl" aria-hidden="true">→</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

</x-app-layout>
