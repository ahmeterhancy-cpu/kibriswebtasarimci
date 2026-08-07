@php
    $isEn = app()->getLocale() === 'en';
    $email = $site('contact_email', 'info@kibriswebtasarimci.com');
    $phone = $site('contact_phone', '+90 533 000 00 00');
    $address = $site('contact_address', $isEn ? 'Kyrenia, North Cyprus' : 'Girne, Kuzey Kıbrıs');

    $socials = array_filter([
        'Instagram' => $site('social_instagram'),
        'LinkedIn' => $site('social_linkedin'),
        'Facebook' => $site('social_facebook'),
        'X' => $site('social_x'),
        'YouTube' => $site('social_youtube'),
        'TikTok' => $site('social_tiktok'),
        'Behance' => $site('social_behance'),
        'GitHub' => $site('social_github'),
    ]);

    $columns = [
        __('site.nav.services') => [
            ['route' => 'services.index', 'label' => $isEn ? 'All services' : 'Tüm hizmetler'],
            ['route' => 'packages', 'label' => __('site.nav.packages')],
            ['route' => 'quote', 'label' => __('site.nav.quote')],
        ],
        __('site.nav.works') => [
            ['route' => 'works.index', 'label' => $isEn ? 'Case studies' : 'Vaka çalışmaları'],
            ['route' => 'blog.index', 'label' => __('site.nav.blog')],
            ['route' => 'contact', 'label' => __('site.nav.contact')],
        ],
    ];

    // Şehir sayfalarına footer bağlantısı — her sayfadan erişilebilir olması
    // yerel sonuçlarda taranma ve iç bağlantı gücü açısından belirleyici.
    //
    // Önbelleğe DÜZ DİZİ yazıyoruz, Eloquent koleksiyonu değil: dosya/veritabanı
    // sürücüsü modeli serileştirip geri okurken "incomplete object" hatası verir.
    $footerLocations = \Illuminate\Support\Facades\Cache::remember(
        'footer_locations',
        3600,
        fn () => \App\Models\Location::query()
            ->where('is_active', true)
            ->orderBy('region')
            ->orderBy('sort_order')
            ->get(['slug', 'name', 'name_en'])
            ->map(fn ($l) => ['slug' => $l->slug, 'name' => $l->name, 'name_en' => $l->name_en])
            ->all()
    );
@endphp

{{-- ── Kapanış CTA bandı ───────────────────────────────────────────────── --}}
<section class="k-dark relative overflow-hidden bg-[#0F0F0F] px-6 py-20 lg:px-12 lg:py-32" data-drift-host>

    {{-- Scroll'la yatay süzülen dev arka plan yazısı --}}
    <div class="pointer-events-none absolute inset-0 flex items-center justify-center opacity-[0.045]" aria-hidden="true">
        <span class="k-display whitespace-nowrap" style="color:#ffffff;" data-drift="420">
            {{ $isEn ? 'LET US BUILD IT' : 'HADİ BAŞLAYALIM' }}
        </span>
    </div>

    <div class="relative mx-auto grid max-w-[1280px] grid-cols-1 items-end gap-10 lg:grid-cols-12">
        <div class="lg:col-span-8">
            <p class="k-eyebrow k-reveal mb-5" style="color:rgba(255,255,255,0.45);">
                {{ $isEn ? 'Next project' : 'Sıradaki proje' }}
            </p>
            <h2 class="k-display-sm" style="color:#ffffff;" data-split data-split-step="0.05">
                {{ $isEn ? 'Your site should' : 'Siteniz sadece' }}<br>
                {{ $isEn ? 'sell, not just' : 'güzel değil,' }} <span class="k-hl">{{ $isEn ? 'sit there.' : 'satmalı.' }}</span>
            </h2>
        </div>

        <div class="k-reveal lg:col-span-4 lg:text-right" data-delay="200">
            <a href="{{ $r('quote') }}" data-cursor="cta" data-cursor-label="{{ $isEn ? 'Start' : 'Başla' }}"
               data-magnetic="0.3" class="k-btn k-btn--brand">
                <span style="color:inherit;">{{ __('site.nav.quote') }}</span>
                <span class="k-btn__arrow" aria-hidden="true">→</span>
            </a>
            <p class="mt-5 text-sm" style="color:rgba(255,255,255,0.5);">
                {{ $isEn ? 'Reply within one business day.' : 'Bir iş günü içinde dönüş yapıyoruz.' }}
            </p>
        </div>
    </div>
</section>

{{-- ── Footer ──────────────────────────────────────────────────────────── --}}
<footer class="k-dark bg-[#0F0F0F] px-6 pb-8 lg:px-12">
    <div class="mx-auto max-w-[1440px]">
        <div class="k-rule"></div>

        <div class="grid grid-cols-2 gap-x-8 gap-y-12 py-14 md:grid-cols-4 lg:py-20">

            <div class="col-span-2 md:col-span-1">
                @include('layouts.partials.brand', ['tone' => 'light'])
                <p class="mt-6 max-w-xs text-sm leading-relaxed" style="color:rgba(255,255,255,0.55);">
                    {{ $site('footer_about', $isEn
                        ? 'A web design and development studio based in North Cyprus. One craft, done properly.'
                        : 'Kuzey Kıbrıs merkezli web tasarım ve yazılım stüdyosu. Tek iş, hakkıyla.') }}
                </p>

                <div class="mt-7 flex items-center gap-2 text-[0.7rem] font-bold uppercase tracking-[0.16em]" style="color:rgba(255,255,255,0.4);">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-[#E30613]" aria-hidden="true"></span>
                    <span>{{ __('site.common.local_time') }}</span>
                    <span data-clock style="color:#ffffff;">--:--:--</span>
                </div>
            </div>

            @foreach ($columns as $heading => $links)
                <div>
                    <p class="k-eyebrow mb-5" style="color:rgba(255,255,255,0.35);">{{ $heading }}</p>
                    <ul class="space-y-3">
                        @foreach ($links as $link)
                            <li>
                                <a href="{{ $r($link['route']) }}" class="k-link k-link-in text-sm transition-colors duration-300 hover:text-[#E30613]" style="color:rgba(255,255,255,0.75);">
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <div>
                <p class="k-eyebrow mb-5" style="color:rgba(255,255,255,0.35);">{{ __('site.nav.contact') }}</p>
                <ul class="space-y-3 text-sm">
                    <li>
                        <a href="mailto:{{ $email }}" class="k-link k-link-in transition-colors duration-300 hover:text-[#E30613]" style="color:rgba(255,255,255,0.75);">{{ $email }}</a>
                    </li>
                    <li>
                        <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="k-link k-link-in transition-colors duration-300 hover:text-[#E30613]" style="color:rgba(255,255,255,0.75);">{{ $phone }}</a>
                    </li>
                    <li style="color:rgba(255,255,255,0.5);">{{ $address }}</li>
                </ul>

                @if ($socials)
                    <div class="mt-6 flex flex-wrap gap-x-5 gap-y-2">
                        @foreach ($socials as $label => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener"
                               class="k-link k-link-in text-[0.7rem] font-bold uppercase tracking-[0.14em] transition-colors duration-300 hover:text-[#E30613]"
                               style="color:rgba(255,255,255,0.6);">{{ $label }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        @if ($footerLocations)
            <div class="k-rule"></div>

            <div class="py-8">
                <p class="k-eyebrow mb-4" style="color:rgba(255,255,255,0.35);">
                    <a href="{{ $r('locations.index') }}" class="k-link k-link-in transition-colors duration-300 hover:text-[#E30613]" style="color:inherit;">
                        {{ $isEn ? 'Where we work' : 'Hizmet bölgeleri' }}
                    </a>
                </p>
                <div class="flex flex-wrap gap-x-5 gap-y-2.5">
                    @foreach ($footerLocations as $footerLocation)
                        <a href="{{ $r('locations.show', ['location' => $footerLocation['slug']]) }}"
                           class="k-link k-link-in text-sm transition-colors duration-300 hover:text-[#E30613]"
                           style="color:rgba(255,255,255,0.6);">
                            {{ ($isEn && $footerLocation['name_en']) ? $footerLocation['name_en'] : $footerLocation['name'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="k-rule"></div>

        <div class="flex flex-col items-start justify-between gap-3 py-7 text-[0.7rem] sm:flex-row sm:items-center" style="color:rgba(255,255,255,0.4);">
            <p>© {{ now()->year }} {{ $site('site_name', 'Kıbrıs Web Tasarımcı') }}. {{ $isEn ? 'All rights reserved.' : 'Tüm hakları saklıdır.' }}</p>
            <p>
                {{ $isEn ? 'A' : '' }}
                <a href="https://www.amesisdijital.com" target="_blank" rel="noopener"
                   class="k-link k-link-in transition-colors duration-300 hover:text-[#E30613]" style="color:rgba(255,255,255,0.6);">Amesis 360°</a>
                {{ $isEn ? 'studio.' : 'kuruluşudur.' }}
            </p>
        </div>
    </div>
</footer>
