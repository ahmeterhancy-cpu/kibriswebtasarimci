@php
    $isEn = app()->getLocale() === 'en';
@endphp

<x-app-layout
    :seo-title="$isEn ? 'Services' : 'Hizmetler'"
    :seo-description="$isEn
        ? 'Corporate web design, e-commerce, custom web software, SEO, UI/UX and ongoing care — the full service list of our Cyprus web studio.'
        : 'Kurumsal web tasarımı, e-ticaret, özel web yazılımı, SEO, UI/UX ve bakım. Kıbrıs web tasarım stüdyomuzun tüm hizmetleri.'">

    <x-page-hero :eyebrow="__('site.nav.services')"
                 :lead="$isEn
                    ? 'One craft with several shapes. Whatever the project, the approach is the same: understand the business first, then build.'
                    : 'Tek uzmanlık, birkaç biçim. Proje ne olursa olsun yaklaşım aynı: önce işi anlıyoruz, sonra kuruyoruz.'">
        {{ $isEn ? 'What we' : 'Ne' }} <span class="k-hl">{{ $isEn ? 'build.' : 'yapıyoruz.' }}</span>
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

    {{-- Hizmet kartları --}}
    <section class="bg-white px-6 py-16 lg:px-12 lg:py-24">
        <div class="mx-auto max-w-[1280px]">
            @if ($services->isEmpty())
                <p class="text-[#0F0F0F]/50">{{ __('site.common.empty') }}</p>
            @else
                <div class="grid grid-cols-1 gap-x-7 gap-y-12 md:grid-cols-2">
                    @foreach ($services as $i => $service)
                        <article class="k-reveal group flex flex-col border-t border-[#0F0F0F]/12 pt-7"
                                 data-delay="{{ min(($i % 2 + 1) * 100, 200) }}">
                            <div class="mb-4 flex items-baseline gap-4">
                                <span class="text-[0.7rem] font-bold tracking-[0.16em] text-[#0F0F0F]/30">
                                    {{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <h2 class="k-display-xs">
                                    <a href="{{ $r('services.show', ['service' => $service->slug]) }}"
                                       class="transition-colors duration-300 group-hover:text-[#E30613]">
                                        {{ $service->t('title') }}
                                    </a>
                                </h2>
                            </div>

                            <p class="mb-6 max-w-lg leading-relaxed text-[#0F0F0F]/60">{{ $service->t('excerpt') }}</p>

                            @if (filled($service->t('features')))
                                <ul class="mb-7 grid flex-1 grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-2">
                                    @foreach ((array) $service->t('features') as $feature)
                                        <li class="flex gap-2.5 text-sm text-[#0F0F0F]/70">
                                            <span class="mt-[7px] block h-1 w-1 shrink-0 rounded-full bg-[#E30613]" aria-hidden="true"></span>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <a href="{{ $r('services.show', ['service' => $service->slug]) }}"
                               class="k-link mt-auto inline-flex w-fit items-center gap-3 text-sm font-bold uppercase tracking-[0.12em]">
                                {{ __('site.common.view_service') }} <span aria-hidden="true">→</span>
                            </a>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Paket önizlemesi --}}
    @if ($packages->isNotEmpty())
        <section class="bg-[#F4F4F2] px-6 py-16 lg:px-12 lg:py-24">
            <div class="mx-auto max-w-[1280px]">
                <div class="mb-10 flex flex-wrap items-end justify-between gap-5">
                    <h2 class="k-display-xs k-reveal">
                        {{ $isEn ? 'Every service has a written price.' : 'Her hizmetin yazılı bir fiyatı var.' }}
                    </h2>
                    <a href="{{ $r('packages') }}" class="k-reveal k-btn k-btn--ghost" data-delay="100">
                        <span>{{ $isEn ? 'All packages' : 'Tüm paketler' }}</span>
                    </a>
                </div>

                <ul class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-5">
                    @foreach ($packages as $package)
                        <li class="k-reveal rounded-xl border border-[#0F0F0F]/10 bg-white p-5">
                            <p class="text-sm font-black tracking-tight">{{ $package->t('name') }}</p>
                            <p class="mt-2 text-lg font-black text-[#E30613]">{{ $package->formatPrice($package->price) }}</p>
                            <p class="mt-1 text-[0.7rem] text-[#0F0F0F]/45">{{ $package->t('delivery') }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- Hizmet listesinden sektör ve şehir listelerine. --}}
    <section class="bg-white px-6 py-14 lg:px-12 lg:py-20">
        <div class="mx-auto max-w-[1280px]">
            <p class="k-reveal max-w-2xl text-sm leading-relaxed text-[#0F0F0F]/55">
                {{ $isEn ? 'The same service looks different depending on the business. See it' : 'Aynı hizmet, işe göre farklı görünür.' }}
                <a href="{{ $r('sectors.index') }}" class="k-link font-semibold text-[#0F0F0F] hover:text-[#E30613]">{{ $isEn ? 'by industry' : 'Sektörlere göre' }}</a>
                {{ $isEn ? ', or see the' : 've' }}
                <a href="{{ $r('locations.index') }}" class="k-link font-semibold text-[#0F0F0F] hover:text-[#E30613]">{{ $isEn ? 'cities we work in' : 'hizmet verdiğimiz şehirlere' }}</a>{{ $isEn ? '.' : ' bakın.' }}
            </p>
        </div>
    </section>

</x-app-layout>
