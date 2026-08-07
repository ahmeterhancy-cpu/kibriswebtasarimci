@php
    $isEn = app()->getLocale() === 'en';

    $panelNotes = [
        'off' => $isEn
            ? 'Content is fixed; we handle updates (two free revisions a year).'
            : 'İçerik sabittir; güncellemeleri biz yaparız (yılda iki ücretsiz revizyon).',
        'on' => $isEn
            ? 'You manage text, images, blog and products yourself. Training included.'
            : 'Metin, görsel, blog ve ürünleri kendiniz yönetirsiniz. Eğitim dahil.',
    ];

    $included = $isEn
        ? ['SSL + hosting + domain (1 year)', 'Mobile responsive design', 'Baseline SEO setup', 'WhatsApp integration', 'Google Analytics', 'First month of care free']
        : ['SSL + hosting + domain (1 yıl)', 'Mobil uyumlu tasarım', 'Temel SEO kurulumu', 'WhatsApp entegrasyonu', 'Google Analytics', 'İlk ay ücretsiz bakım'];
@endphp

<x-app-layout
    :seo-title="__('site.nav.packages')"
    :seo-description="$isEn
        ? 'Website packages and prices in North Cyprus: quick start, onepage, corporate and e-commerce. Written prices, no hidden extras.'
        : 'Kuzey Kıbrıs web sitesi paketleri ve fiyatları: hızlı başlangıç, onepage, kurumsal ve e-ticaret. Fiyatlar yazılı, gizli kalem yok.'">

    {{-- Eyebrow "Paketler" değil "Fiyat listesi": başlıkta zaten "Paket" geçiyor. --}}
    <x-page-hero :eyebrow="$isEn ? 'Price list' : 'Fiyat listesi'"
                 :lead="$isEn
                    ? 'Hosting, domain and SSL are included for the first year, and the first month of care is on us.'
                    : 'Hosting, domain ve SSL ilk yıl her pakete dahil; ilk ay bakım bizden.'">
        {{ $isEn ? 'Package' : 'Paket' }} <span class="k-hl">{{ $isEn ? 'Prices' : 'Fiyatlar' }}</span>
    </x-page-hero>

    {{-- ── Tüm paketler tek ızgarada ───────────────────────────────────── --}}
    @if ($packages->isNotEmpty())
        <section class="bg-white px-6 py-14 lg:px-12 lg:py-20">
            <div class="mx-auto max-w-[1280px]">

                {{-- Panelsiz / panelli seçici --}}
                <div class="k-reveal mb-12 flex flex-wrap items-center gap-5">
                    <div class="inline-flex rounded-full border border-[#0F0F0F]/15 p-1" role="group" data-panel-toggle>
                        <button type="button" data-panel-mode="off" aria-pressed="true"
                                class="rounded-full px-5 py-2.5 text-[0.68rem] font-bold uppercase tracking-[0.1em] transition-colors duration-300">
                            {{ $isEn ? 'Without panel' : 'Panelsiz' }}
                        </button>
                        <button type="button" data-panel-mode="on" aria-pressed="false"
                                class="rounded-full px-5 py-2.5 text-[0.68rem] font-bold uppercase tracking-[0.1em] transition-colors duration-300">
                            {{ $isEn ? 'With panel' : 'Panelli' }}
                        </button>
                    </div>
                    {{-- max-w yok: masaüstünde tek satırda kalsın, mobilde doğal sarsın. --}}
                    <p class="text-sm text-[#0F0F0F]/55" data-panel-note>
                        {{ $isEn
                            ? 'Content is fixed; we handle updates (two free revisions a year).'
                            : 'İçerik sabittir; güncellemeleri biz yaparız (yılda iki ücretsiz revizyon).' }}
                    </p>
                </div>

                {{-- Sınıflar LİTERAL — Tailwind kaynağı tarar, birleştirilmiş
                     sınıf adı üretmez. --}}
                @php
                    $cols = match (true) {
                        $packages->count() >= 4 => 'md:grid-cols-2 xl:grid-cols-4',
                        $packages->count() === 3 => 'md:grid-cols-3',
                        $packages->count() === 2 => 'md:grid-cols-2',
                        default => 'max-w-md',
                    };
                @endphp
                <div class="grid grid-cols-1 gap-5 {{ $cols }}">
                    @foreach ($packages as $i => $package)
                        <article class="k-reveal relative flex flex-col rounded-2xl border-2 bg-white p-7 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_14px_44px_rgba(0,0,0,0.07)] lg:p-8 {{ $package->is_popular ? 'border-[#E30613]' : 'border-[#0F0F0F]/10 hover:border-[#0F0F0F]/25' }}"
                                 data-delay="{{ min(($i + 1) * 100, 300) }}">
                            @if ($package->is_popular)
                                <span class="absolute -top-3 left-7 rounded-full bg-[#E30613] px-3 py-1 text-[0.6rem] font-bold uppercase tracking-[0.14em]" style="color:#ffffff;">
                                    {{ $isEn ? 'Most chosen' : 'En çok tercih edilen' }}
                                </span>
                            @endif

                            <h2 class="text-xl font-black tracking-tight">{{ $package->t('name') }}</h2>
                            <p class="mt-2.5 min-h-[3.5rem] text-sm leading-relaxed text-[#0F0F0F]/60">{{ $package->t('tagline') }}</p>

                            <div class="mt-6 border-t border-[#0F0F0F]/10 pt-6">
                                @if ($package->price_regular)
                                    <span class="text-sm text-[#0F0F0F]/35 line-through">{{ $package->formatPrice($package->price_regular) }}</span>
                                @endif
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-black tracking-tight"
                                          data-price-off="{{ $package->formatPrice($package->price) }}"
                                          data-price-on="{{ $package->formatPrice($package->price_with_panel ?? $package->price) }}">
                                        {{ $package->formatPrice($package->price) }}
                                    </span>
                                    <span class="text-xs text-[#0F0F0F]/45">+ KDV</span>
                                </div>
                                {{-- E-ticarette panel zaten standart; panelsiz/panelli
                                     seçicisi bu kartta fiyatı değiştirmiyor, sebebini yaz. --}}
                                @if ($package->is_ecommerce && ! $package->price_with_panel)
                                    <p class="mt-1.5 text-xs text-[#0F0F0F]/50">
                                        {{ $isEn ? 'Admin panel included as standard' : 'Yönetim paneli standart olarak dahil' }}
                                    </p>
                                @endif
                                @if ($package->t('delivery'))
                                    <p class="mt-2.5 text-xs font-semibold uppercase tracking-[0.1em] text-[#E30613]">{{ $package->t('delivery') }}</p>
                                @endif
                            </div>

                            @if (filled($package->t('features')))
                                <ul class="mt-6 flex-1 space-y-2.5">
                                    @foreach ((array) $package->t('features') as $feature)
                                        <li class="flex gap-2.5 text-sm text-[#0F0F0F]/70">
                                            <span class="mt-[7px] block h-1 w-1 shrink-0 rounded-full bg-[#E30613]" aria-hidden="true"></span>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                    @if ($package->price_with_panel)
                                        <li class="flex gap-2.5 text-sm text-[#0F0F0F]/70" data-panel-only hidden>
                                            <span class="mt-[7px] block h-1 w-1 shrink-0 rounded-full bg-[#E30613]" aria-hidden="true"></span>
                                            <span><strong>{{ $isEn ? 'Admin panel + training' : 'Yönetim paneli + eğitim' }}</strong></span>
                                        </li>
                                    @endif
                                </ul>
                            @endif

                            <a href="{{ $r('quote') }}?paket={{ $package->slug }}"
                               class="k-btn {{ $package->is_popular ? 'k-btn--brand' : 'k-btn--ghost' }} mt-7 justify-center">
                                <span style="color:inherit;">{{ __('site.nav.quote') }}</span>
                            </a>
                        </article>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    {{-- ── E-Ticaret açıklama bandı ────────────────────────────────────────
         Paket kartı yukarıdaki ortak ızgarada; burası yalnızca "panel neden
         dahil" mesajını taşır. Kart burada tekrarlanmaz — ayrı bölüme alınınca
         sayfanın altında kalıp bulunamıyordu. --}}
    @if ($hasEcommerce)
        <section class="k-dark bg-[#0F0F0F] px-6 py-16 lg:px-12 lg:py-20">
            <div class="mx-auto grid max-w-[1280px] grid-cols-1 gap-10 lg:grid-cols-12 lg:items-end">
                <div class="lg:col-span-7">
                    <p class="k-eyebrow k-reveal mb-5" style="color:rgba(255,255,255,0.4);">E-Ticaret</p>
                    <h2 class="k-display-sm" style="color:#ffffff;" data-split data-split-step="0.05">
                        {{ $isEn ? 'Panel always' : 'Panel her zaman' }} {{ $isEn ? 'included.' : 'dahil.' }}
                    </h2>
                    <p class="k-reveal mt-5 max-w-xl" style="color:rgba(255,255,255,0.6);" data-delay="200">
                        {{ $isEn
                            ? 'You manage products, prices, stock and orders yourself instead of emailing us for every change. No tiers to upgrade to — everything is in the one package above.'
                            : 'Ürünü, fiyatı, stoğu ve siparişi kendiniz yönetirsiniz; her değişiklik için bize dönmek zorunda kalmazsınız. Kademeli paket yok — her şey yukarıdaki tek pakette.' }}
                    </p>
                </div>

                <div class="k-reveal lg:col-span-4 lg:col-start-9 lg:text-right" data-delay="300">
                    <a href="{{ $r('services.show', ['service' => 'e-ticaret']) }}" class="k-btn k-btn--light">
                        <span style="color:inherit;">{{ $isEn ? 'How it works' : 'Nasıl çalışıyor' }}</span>
                        <span class="k-btn__arrow" aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ── Her pakete dahil ────────────────────────────────────────────── --}}
    <section class="bg-[#F1F1EF] px-6 py-16 lg:px-12 lg:py-24">
        <div class="mx-auto grid max-w-[1280px] grid-cols-1 gap-12 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <p class="k-eyebrow k-reveal mb-5 text-[#0F0F0F]/45">{{ $isEn ? 'In every package' : 'Her pakete dahil' }}</p>
                <h2 class="k-display-xs k-reveal" data-delay="100">
                    {{ $isEn ? 'No hidden line items.' : 'Gizli kalem yok.' }}
                </h2>
            </div>
            <ul class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2 lg:col-span-7 lg:col-start-6">
                @foreach ($included as $i => $item)
                    <li class="k-reveal flex gap-3 border-b border-[#0F0F0F]/10 pb-4 text-[#0F0F0F]/75"
                        data-delay="{{ min(($i % 3 + 1) * 100, 300) }}">
                        <span class="mt-[9px] block h-1 w-1 shrink-0 rounded-full bg-[#E30613]" aria-hidden="true"></span>
                        <span>{{ $item }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- ── Bakım paketleri — admin'den yönetilir (Paketler › tür: bakım) ── --}}
    @if ($carePlans->isNotEmpty())
        <section class="bg-white px-6 py-16 lg:px-12 lg:py-24">
            <div class="mx-auto max-w-[1280px]">
                <div class="mb-10 max-w-xl">
                    <p class="k-eyebrow k-reveal mb-5 text-[#0F0F0F]/45">{{ $isEn ? 'Care plans' : 'Bakım paketleri' }}</p>
                    <h2 class="k-display-xs k-reveal" data-delay="100">
                        {{ $isEn ? 'After launch, monthly.' : 'Yayından sonrası, aylık.' }}
                    </h2>
                </div>

                @php
                    $careCols = match (true) {
                        $carePlans->count() >= 4 => 'md:grid-cols-2 xl:grid-cols-4',
                        $carePlans->count() === 3 => 'md:grid-cols-3',
                        $carePlans->count() === 2 => 'md:grid-cols-2',
                        default => 'max-w-md',
                    };
                @endphp
                <div class="grid grid-cols-1 gap-5 {{ $careCols }}">
                    @foreach ($carePlans as $i => $plan)
                        <div class="k-reveal flex flex-col rounded-2xl border p-7 {{ $plan->is_popular ? 'border-[#E30613]' : 'border-[#0F0F0F]/10' }}"
                             data-delay="{{ min(($i + 1) * 100, 400) }}">
                            <p class="k-eyebrow mb-4 text-[#0F0F0F]/45">{{ $plan->t('name') }}</p>
                            <p class="text-2xl font-black tracking-tight">
                                {{ $plan->formatPrice($plan->price) }}<span class="text-sm font-medium text-[#0F0F0F]/45"> / {{ $isEn ? 'month' : 'ay' }}</span>
                            </p>
                            @if ($plan->t('tagline'))
                                <p class="mt-3 text-sm leading-relaxed text-[#0F0F0F]/60">{{ $plan->t('tagline') }}</p>
                            @endif
                            @if (filled($plan->t('features')))
                                <ul class="mt-5 flex-1 space-y-2">
                                    @foreach ((array) $plan->t('features') as $feature)
                                        <li class="flex gap-2.5 text-sm text-[#0F0F0F]/70">
                                            <span class="mt-[7px] block h-1 w-1 shrink-0 rounded-full bg-[#E30613]" aria-hidden="true"></span>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ── Kampanya koşulları ──────────────────────────────────────────── --}}
    <section class="border-y border-[#0F0F0F]/10 bg-[#F4F4F2] px-6 py-14 lg:px-12 lg:py-20">
        <div class="mx-auto max-w-[1280px]">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="k-reveal">
                    <p class="k-eyebrow mb-3 text-[#E30613]">{{ $isEn ? 'Campaign' : 'Kampanya' }}</p>
                    <p class="text-sm leading-relaxed text-[#0F0F0F]/70">
                        {{ $isEn ? '30 days or the first 20 clients, whichever comes first. Standard pricing applies after that.'
                                 : '30 gün veya ilk 20 müşteri ile sınırlıdır; sonrasında normal fiyatlar geçerli olur.' }}
                    </p>
                </div>
                <div class="k-reveal" data-delay="100">
                    <p class="k-eyebrow mb-3 text-[#E30613]">{{ $isEn ? 'Payment' : 'Ödeme' }}</p>
                    <p class="text-sm leading-relaxed text-[#0F0F0F]/70">
                        {{ $isEn ? '50% up front, 50% before launch. E-commerce projects are split into three instalments.'
                                 : '%50 başlangıçta, %50 yayın öncesi. E-ticaret projelerinde üç taksit uygulanır.' }}
                    </p>
                </div>
                <div class="k-reveal" data-delay="200">
                    <p class="k-eyebrow mb-3 text-[#E30613]">{{ $isEn ? 'Bonuses' : 'Bonuslar' }}</p>
                    <p class="text-sm leading-relaxed text-[#0F0F0F]/70">
                        {{ $isEn ? 'First month of care free · 5 SEO pages with Corporate and above · first 20 products entered by us on E-Commerce Pro.'
                                 : 'İlk ay ücretsiz bakım · Kurumsal ve üzerinde 5 sayfa SEO metni · E-Ticaret Pro\'da ilk 20 ürün girişi bizden.' }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── SSS ─────────────────────────────────────────────────────────── --}}
    @if ($faqs->isNotEmpty())
        <section class="bg-white px-6 py-16 lg:px-12 lg:py-24">
            <div class="mx-auto max-w-[900px]" data-acc="single">
                <p class="k-eyebrow k-reveal mb-8 text-[#0F0F0F]/45">{{ __('site.common.faq') }}</p>
                @foreach ($faqs as $faq)
                    <div class="k-acc k-reveal border-t border-[#0F0F0F]/12 last:border-b">
                        <h2>
                            <button type="button" data-acc-trigger aria-expanded="false"
                                    class="flex w-full items-start justify-between gap-6 py-6 text-left">
                                <span class="text-lg font-bold tracking-tight">{{ $faq->t('question') }}</span>
                                <span class="k-acc__sign mt-1 shrink-0 text-xl text-[#E30613]" aria-hidden="true">+</span>
                            </button>
                        </h2>
                        <div class="k-acc__body"><div>
                            <p class="pb-6 leading-relaxed text-[#0F0F0F]/65">{{ $faq->t('answer') }}</p>
                        </div></div>
                    </div>
                @endforeach
            </div>
        </section>

        @push('jsonld')
            <script type="application/ld+json">
                @php
                    echo json_encode([
                        '@context' => 'https://schema.org',
                        '@type' => 'FAQPage',
                        'mainEntity' => $faqs->map(fn ($faq) => [
                            '@type' => 'Question',
                            'name' => $faq->t('question'),
                            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->t('answer')],
                        ])->all(),
                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                @endphp
            </script>
        @endpush
    @endif

    @push('scripts')
        <script>
            /* Panelsiz / panelli seçici — fiyatlar ve kapsam satırı yer değiştirir. */
            (function () {
                const group = document.querySelector('[data-panel-toggle]');
                if (!group) return;

                const buttons = group.querySelectorAll('[data-panel-mode]');
                const prices = document.querySelectorAll('[data-price-off]');
                const extras = document.querySelectorAll('[data-panel-only]');
                const note = document.querySelector('[data-panel-note]');

                // Metinler yukarıdaki PHP bloğunda hazırlanıyor: parantez içeren bir
                // dizeyi doğrudan JSON direktifine yazmak Blade'in argüman
                // ayrıştırıcısını bozuyor (parantezleri sayarak kapanış arıyor).
                const NOTES = @json($panelNotes);

                const apply = (mode) => {
                    buttons.forEach((b) => {
                        const active = b.dataset.panelMode === mode;
                        b.setAttribute('aria-pressed', active ? 'true' : 'false');
                        b.style.background = active ? '#0F0F0F' : 'transparent';
                        b.style.color = active ? '#ffffff' : 'rgba(15,15,15,0.55)';
                    });
                    prices.forEach((p) => { p.textContent = mode === 'on' ? p.dataset.priceOn : p.dataset.priceOff; });
                    extras.forEach((e) => { e.hidden = mode !== 'on'; });
                    if (note) note.textContent = NOTES[mode];
                };

                buttons.forEach((b) => b.addEventListener('click', () => apply(b.dataset.panelMode)));
                apply('off');
            })();
        </script>
    @endpush

</x-app-layout>
