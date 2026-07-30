@props([
    'eyebrow' => null,
    'lead' => null,
    'tone' => 'paper',
])

{{-- İç sayfa başlığı: eyebrow + dev başlık + isteğe bağlı giriş metni. --}}
<section class="{{ $tone === 'dark' ? 'k-dark bg-[#0F0F0F]' : 'bg-[#F1F1EF]' }} px-6 pb-16 pt-36 lg:px-12 lg:pb-24 lg:pt-48">
    <div class="mx-auto max-w-[1280px]">
        @if ($eyebrow)
            <p class="k-eyebrow k-reveal mb-6" style="color:{{ $tone === 'dark' ? 'rgba(255,255,255,0.45)' : 'rgba(15,15,15,0.5)' }};">
                {{ $eyebrow }}
            </p>
        @endif

        <h1 class="k-display-sm" style="color:{{ $tone === 'dark' ? '#ffffff' : '#0F0F0F' }};" data-split data-split-step="0.05">
            {{ $slot }}
        </h1>

        @if ($lead)
            <p class="k-lead k-reveal mt-7 max-w-2xl" data-delay="200"
               style="color:{{ $tone === 'dark' ? 'rgba(255,255,255,0.6)' : 'rgba(15,15,15,0.7)' }};">
                {{ $lead }}
            </p>
        @endif

        @isset($actions)
            <div class="k-reveal mt-9 flex flex-wrap gap-4" data-delay="300">
                {{ $actions }}
            </div>
        @endisset
    </div>
</section>
