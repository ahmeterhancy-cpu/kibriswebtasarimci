@props(['tone' => 'ink'])

@php
    $color = $tone === 'light' ? '#ffffff' : '#0F0F0F';
@endphp

{{-- Tipografik logo: iki satır, sıkı harf aralığı, sonda brand noktası. --}}
<a href="{{ $r('home') }}" class="inline-block leading-none" aria-label="Kıbrıs Web Tasarımcı" data-no-transition>
    <span class="block text-[0.62rem] font-black uppercase tracking-[0.26em]" style="color:{{ $color }};">Kıbrıs Web</span>
    <span class="mt-[3px] block text-[0.62rem] font-black uppercase tracking-[0.26em]" style="color:{{ $color }};">
        Tasarımcı<span class="text-[#E30613]">.</span>
    </span>
</a>
