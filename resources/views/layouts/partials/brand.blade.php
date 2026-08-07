@props(['tone' => 'ink'])

@php
    $color = $tone === 'light' ? '#ffffff' : '#0F0F0F';
    $name = $site('site_name', 'Kıbrıs Web Tasarımcı');
    $alt = $site('logo_alt', $name);

    // Koyu zeminde önce açık renkli sürüm aranır; yoksa ana logoya düşülür.
    $logo = $tone === 'light'
        ? ($site('site_logo_light') ?: $site('site_logo'))
        : $site('site_logo');
@endphp

<a href="{{ $r('home') }}" class="inline-block leading-none" aria-label="{{ $alt }}" data-no-transition>
    @if ($logo)
        {{-- Yüklenmiş görsel logo. Yükseklik sabit, genişlik oranla gelir. --}}
        <img src="{{ asset('storage/'.$logo) }}" alt="{{ $alt }}"
             class="block h-7 w-auto lg:h-8" loading="eager" decoding="async">
    @else
        {{-- Varsayılan tipografik logo: iki satır, sıkı harf aralığı, sonda brand noktası. --}}
        <span class="block text-[0.62rem] font-black uppercase tracking-[0.26em]" style="color:{{ $color }};">Kıbrıs Web</span>
        <span class="mt-[3px] block text-[0.62rem] font-black uppercase tracking-[0.26em]" style="color:{{ $color }};">
            Tasarımcı<span class="text-[#E30613]">.</span>
        </span>
    @endif
</a>
