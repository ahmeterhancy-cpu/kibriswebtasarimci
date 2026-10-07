@php
    $nav = [
        ['route' => 'home', 'label' => __('site.nav.home')],
        ['route' => 'services.index', 'label' => __('site.nav.services')],
        ['route' => 'sectors.index', 'label' => __('site.nav.sectors')],
        ['route' => 'works.index', 'label' => __('site.nav.works')],
        ['route' => 'packages', 'label' => __('site.nav.packages')],
        ['route' => 'blog.index', 'label' => __('site.nav.blog')],
        ['route' => 'contact', 'label' => __('site.nav.contact')],
    ];
    $current = request()->route()?->getName();
@endphp

<header class="k-header" id="k-header" data-menu-open="0">
    <div class="mx-auto flex max-w-[1600px] items-center justify-between px-6 py-5 lg:px-12">

        @include('layouts.partials.brand')

        {{-- Ana Sayfa ile birlikte 7 bağlantı var. Marka adı uzun olduğu için
             bu menü 1024px'de artık sığmıyordu: logo ile ilk bağlantı arasında
             7 piksel kalıyordu. Masaüstü menüsü bu yüzden xl'den (1280px)
             itibaren gösteriliyor; altında tam ekran menü devreye giriyor —
             orada zaten Ana Sayfa ve Teklif Al da var. --}}
        <nav class="hidden items-center gap-9 xl:flex" aria-label="{{ __('site.nav.menu') }}">
            @foreach ($nav as $item)
                @php
                    $href = $r($item['route']);
                    $isActive = $current === $localePrefix.$item['route'];
                @endphp
                <a href="{{ $href }}"
                   class="k-link k-link-in text-[0.78rem] font-semibold uppercase tracking-[0.14em] transition-colors duration-300 hover:text-[#E30613] {{ $isActive ? 'text-[#E30613]' : 'text-[#0F0F0F]' }}"
                   @if ($isActive) aria-current="page" @endif>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-4">
            {{-- Dil değiştirici --}}
            <div class="hidden items-center gap-1.5 text-[0.7rem] font-bold uppercase tracking-[0.14em] sm:flex">
                <a href="{{ app()->getLocale() === 'tr' ? url()->current() : ($alternateUrl ?? url('/')) }}"
                   class="transition-colors duration-300 {{ app()->getLocale() === 'tr' ? 'text-[#0F0F0F]' : 'text-[#0F0F0F]/35 hover:text-[#0F0F0F]' }}">TR</a>
                <span class="text-[#0F0F0F]/20" aria-hidden="true">/</span>
                <a href="{{ app()->getLocale() === 'en' ? url()->current() : ($alternateUrl ?? url('/en')) }}"
                   class="transition-colors duration-300 {{ app()->getLocale() === 'en' ? 'text-[#0F0F0F]' : 'text-[#0F0F0F]/35 hover:text-[#0F0F0F]' }}">EN</a>
            </div>

            <a href="{{ $r('quote') }}" data-cursor="cta"
               class="k-btn k-btn--ink hidden !px-6 !py-3 !text-[0.68rem] md:inline-flex">
                <span style="color:inherit;">{{ __('site.nav.quote') }}</span>
            </a>

            <button type="button" data-menu-toggle aria-expanded="false" aria-controls="k-menu"
                    class="k-burger relative z-[901] -mr-1 p-2 text-[#0F0F0F] xl:hidden"
                    aria-label="{{ __('site.nav.menu') }}">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>
