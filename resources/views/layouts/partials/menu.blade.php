@php
    $menu = [
        ['route' => 'home', 'label' => __('site.nav.home')],
        ['route' => 'services.index', 'label' => __('site.nav.services')],
        ['route' => 'works.index', 'label' => __('site.nav.works')],
        ['route' => 'packages', 'label' => __('site.nav.packages')],
        ['route' => 'blog.index', 'label' => __('site.nav.blog')],
        ['route' => 'contact', 'label' => __('site.nav.contact')],
        ['route' => 'quote', 'label' => __('site.nav.quote')],
    ];
@endphp

{{-- Tam ekran mobil menü: perde clip-path ile aşağıdan açılır, satırlar sırayla gelir. --}}
<div class="k-menu lg:hidden" id="k-menu">
    {{-- Menünün kendi üst çubuğu: header koyu perdenin altında gizlenir,
         dolayısıyla açık marka ve kapatma düğmesi burada tekrar edilir. --}}
    <div class="absolute inset-x-0 top-0 flex items-center justify-between px-6 py-5">
        @include('layouts.partials.brand', ['tone' => 'light'])

        <button type="button" data-menu-toggle aria-expanded="false" aria-controls="k-menu"
                class="k-burger is-open -mr-1 p-2" style="color:#ffffff;"
                aria-label="{{ __('site.nav.close') }}">
            <span></span><span></span><span></span>
        </button>
    </div>

    <div class="flex min-h-full flex-col justify-between px-6 pb-10 pt-28">
        <nav class="flex flex-col gap-1" aria-label="{{ __('site.nav.menu') }}">
            @foreach ($menu as $i => $item)
                <a href="{{ $r($item['route']) }}"
                   class="k-menu__item k-display-xs py-2 transition-colors duration-300 hover:text-[#E30613]"
                   style="color:#ffffff;">
                    <span class="mr-3 align-middle text-[0.6rem] font-bold tracking-[0.2em]" style="color:rgba(255,255,255,0.3);">
                        {{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}
                    </span>{{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="k-menu__item mt-12">
            <div class="k-rule mb-6"></div>
            <div class="flex items-end justify-between gap-6">
                <div>
                    <p class="k-eyebrow mb-2" style="color:rgba(255,255,255,0.4);">{{ __('site.nav.contact') }}</p>
                    <a href="mailto:{{ $site('contact_email', 'info@kibriswebtasarimci.com') }}"
                       class="k-link text-sm" style="color:#ffffff;">{{ $site('contact_email', 'info@kibriswebtasarimci.com') }}</a>
                </div>
                <div class="flex items-center gap-1.5 text-[0.7rem] font-bold uppercase tracking-[0.14em]">
                    <a href="{{ app()->getLocale() === 'tr' ? url()->current() : ($alternateUrl ?? url('/')) }}"
                       style="color:{{ app()->getLocale() === 'tr' ? '#ffffff' : 'rgba(255,255,255,0.4)' }};">TR</a>
                    <span style="color:rgba(255,255,255,0.25);" aria-hidden="true">/</span>
                    <a href="{{ app()->getLocale() === 'en' ? url()->current() : ($alternateUrl ?? url('/en')) }}"
                       style="color:{{ app()->getLocale() === 'en' ? '#ffffff' : 'rgba(255,255,255,0.4)' }};">EN</a>
                </div>
            </div>
        </div>
    </div>
</div>
