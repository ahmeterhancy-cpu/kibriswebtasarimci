@php
    $isEn = app()->getLocale() === 'en';
    $trailWorks = $works->filter(fn ($w) => filled($w->cover))->take(6);

    $process = [
        ['01', $isEn ? 'Understand' : 'Anlıyoruz', $isEn
            ? 'What you sell, who buys it and why they pick you. The design answers this, not the other way round.'
            : 'Ne sattığınızı, kimin aldığını ve neden sizi seçtiğini konuşuyoruz. Tasarım bu cevabın üzerine kuruluyor.'],
        ['02', $isEn ? 'Draw' : 'Çiziyoruz', $isEn
            ? 'Flow map, then wireframe, then interface. You see every step — no surprise handovers.'
            : 'Önce akış, sonra tel kafes, sonra arayüz. Her adımı görüyorsunuz; sürpriz teslim yok.'],
        ['03', $isEn ? 'Build' : 'Kuruyoruz', $isEn
            ? 'Hand-written code, no page builders. Fast on mobile, indexable by Google, editable by you.'
            : 'Elle yazılan kod, hazır kurucu yok. Mobilde hızlı, Google\'da görünür, sizin yönetebildiğiniz.'],
        ['04', $isEn ? 'Launch & stay' : 'Yayınlıyoruz', $isEn
            ? 'Panel training at handover, then care: backups, updates, monitoring. First month free.'
            : 'Teslimde panel eğitimi, sonrasında bakım: yedek, güncelleme, izleme. İlk ay ücretsiz.'],
    ];

    $stats = [
        ['3', $isEn ? 'working days' : 'iş gününde', $isEn ? 'fastest launch' : 'en hızlı yayın'],
        ['2', $isEn ? 'languages' : 'dil', $isEn ? 'TR + EN, more optional' : 'TR + EN, dahası opsiyonel'],
        ['0', $isEn ? 'templates' : 'şablon', $isEn ? 'every design custom' : 'her tasarım özel'],
    ];
@endphp

<x-app-layout
    :seo-description="$site('site_description')"
    body-class="bg-white">

    {{-- ══════════════════════════════════════════════════════════════════
         01 · HERO — dev tipografi + imleç izinde açılan iş görselleri
         ══════════════════════════════════════════════════════════════ --}}
    <section class="relative flex min-h-[100svh] flex-col justify-between overflow-hidden bg-[#F1F1EF] px-6 pb-8 pt-32 lg:px-12"
             data-dots
             @if ($trailWorks->isNotEmpty()) data-trail data-trail-gap="170" @endif>

        {{-- İz için kaynak görseller (görünmez klonlanır) --}}
        @if ($trailWorks->isNotEmpty())
            <div class="hidden" aria-hidden="true">
                @foreach ($trailWorks as $w)
                    <img data-trail-src src="{{ asset('storage/'.$w->cover) }}" alt="">
                @endforeach
            </div>
        @endif

        <div class="relative z-10 mx-auto w-full max-w-[1600px]">
            <p class="k-eyebrow k-reveal mb-7 text-[#0F0F0F]/60">
                {{ $isEn ? 'Web design studio' : 'Web tasarım stüdyosu' }}
            </p>

            <h1 class="k-display text-[#0F0F0F]" data-split data-split-step="0.06">
                {{ $isEn ? 'Corporate web' : 'Kurumsal web' }}<br>
                {{ $isEn ? 'and e-commerce' : 've e-ticaret' }}<br>
                <span class="k-hl">{{ $isEn ? 'Solutions' : 'Çözümleri' }}</span>
            </h1>

            <div class="mt-10 grid grid-cols-1 gap-8 lg:mt-14 lg:grid-cols-12">
                <p class="k-lead k-reveal max-w-xl lg:col-span-5" data-delay="300">
                    {{ $isEn
                        ? 'Corporate websites, online stores, custom web software and mobile apps. No templates: every project is designed from a blank page and written by hand.'
                        : 'Kurumsal web sitesi, e-ticaret, özel web yazılımı ve mobil uygulama. Şablon yok: her proje sıfırdan tasarlanır, elle yazılır.' }}
                </p>

                {{-- flex-nowrap: dar ekranda alt alta düşmesin, yan yana kalsın. --}}
                <div class="k-reveal flex flex-nowrap items-center gap-3 lg:col-span-4 lg:col-start-9 lg:justify-end" data-delay="400">
                    <a href="{{ $r('quote') }}" data-cursor="cta" data-cursor-label="{{ $isEn ? 'Start' : 'Başla' }}"
                       data-magnetic="0.3" class="k-btn k-btn--brand">
                        <span style="color:inherit;">{{ __('site.nav.quote') }}</span>
                        <span class="k-btn__arrow" aria-hidden="true">→</span>
                    </a>
                    <a href="{{ $r('packages') }}" class="k-btn k-btn--ghost">
                        <span>{{ $isEn ? 'See pricing' : 'Fiyatları gör' }}</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Alt şerit: küçük istatistikler + kaydırma ipucu --}}
        <div class="relative z-10 mx-auto w-full max-w-[1600px]">
            <div class="k-rule mb-6"></div>
            <div class="flex flex-wrap items-end justify-between gap-6">
                <dl class="flex flex-wrap gap-x-10 gap-y-4">
                    @foreach ($stats as $i => [$value, $unit, $label])
                        <div class="k-reveal" data-delay="{{ ($i + 1) * 100 }}">
                            <dt class="sr-only">{{ $label }}</dt>
                            <dd>
                                <span class="text-3xl font-black tracking-tight" data-count="{{ $value }}">0</span>
                                <span class="ml-1 text-xs font-bold uppercase tracking-[0.14em] text-[#0F0F0F]/50">{{ $unit }}</span>
                                <span class="mt-0.5 block text-[0.7rem] text-[#0F0F0F]/45">{{ $label }}</span>
                            </dd>
                        </div>
                    @endforeach
                </dl>

                {{-- Sağ kenarda sabit WhatsApp düğmesi var; ipucu onun altına girmesin. --}}
                <div class="k-reveal mr-16 flex items-center gap-3 text-[#0F0F0F]/40 lg:mr-20" data-delay="500">
                    <span class="k-eyebrow">{{ __('site.common.scroll') }}</span>
                    <span class="block h-8 w-px bg-[#0F0F0F]/20" aria-hidden="true"></span>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════
         02 · KAYAN ŞERİT — scroll hızına tepki verir, yön scroll yönüyle döner
         ══════════════════════════════════════════════════════════════ --}}
    <section class="border-y border-[#0F0F0F]/10 bg-white py-6 lg:py-8" aria-hidden="true">
        <div class="k-marquee" data-marquee="0.55">
            <div class="k-marquee__rail" data-marquee-rail>
                <span class="flex shrink-0 items-center gap-7 pr-7 text-2xl font-black tracking-tight md:text-4xl">
                    @foreach (($isEn
                        ? ['Corporate Sites', 'E-Commerce', 'Web Software', 'UI / UX', 'SEO', 'Care & Support']
                        : ['Kurumsal Site', 'E-Ticaret', 'Web Yazılım', 'UI / UX', 'SEO', 'Bakım & Destek']) as $word)
                        <span>{{ $word }}</span>
                        <span class="text-[#E30613]">●</span>
                    @endforeach
                </span>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════
         03 · MANİFESTO — büyük ifade, arkada süzülen dev yazı
         ══════════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden bg-white px-6 py-24 lg:px-12 lg:py-40" data-drift-host>
        <div class="pointer-events-none absolute inset-0 flex items-center justify-center opacity-[0.035]" aria-hidden="true">
            <span class="k-display whitespace-nowrap text-[#0F0F0F]" data-drift="500">
                {{ $isEn ? 'CRAFT OVER TEMPLATES' : 'ŞABLON DEĞİL ZANAAT' }}
            </span>
        </div>

        <div class="relative mx-auto max-w-[1280px]">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-12">
                <div class="lg:col-span-3">
                    <p class="k-eyebrow k-reveal text-[#0F0F0F]/45">{{ $isEn ? 'Why us' : 'Neden biz' }}</p>
                </div>

                <div class="lg:col-span-9">
                    <h2 class="k-display-sm text-[#0F0F0F]" data-split data-split-step="0.045">
                        {{ $isEn ? 'One craft,' : 'Tek uzmanlık,' }}
                        <span class="italic font-light">{{ $isEn ? 'done' : 'tam' }}</span>
                        {{ $isEn ? 'properly.' : 'odak.' }}
                    </h2>

                    <div class="mt-10 grid grid-cols-1 gap-x-14 gap-y-6 md:grid-cols-2">
                        <p class="k-reveal text-lg leading-relaxed text-[#0F0F0F]/70" data-delay="100">
                            {{ $isEn
                                ? 'We are not a 360° agency doing twelve things at once. We build websites — that is the whole business. Every project is designed from a blank page and written by hand, without page builders or bought themes.'
                                : 'On iki iş birden yapan 360° bir ajans değiliz. Web sitesi kuruyoruz; işin tamamı bu. Her proje boş bir sayfadan tasarlanır ve elle yazılır — hazır kurucu ya da satın alınmış tema yok.' }}
                        </p>
                        <p class="k-reveal text-lg leading-relaxed text-[#0F0F0F]/70" data-delay="200">
                            {{ $isEn
                                ? 'That focus is why we ship in days, not months, and why the sites stay fast a year later. You get the domain in your name, a panel you actually control, and someone who picks up the phone after launch.'
                                : 'Bu odak sayesinde aylar değil günler içinde teslim ediyoruz ve siteler bir yıl sonra da hızlı kalıyor. Domain sizin adınıza, panel gerçekten sizin kontrolünüzde ve yayından sonra telefonu açan biri var.' }}
                        </p>
                    </div>

                    <div class="k-reveal mt-10" data-delay="300">
                        <a href="{{ $r('services.index') }}" class="k-link inline-flex items-center gap-3 text-sm font-bold uppercase tracking-[0.12em] text-[#0F0F0F]">
                            {{ $isEn ? 'What we do' : 'Ne yapıyoruz' }}
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════
         04 · HİZMETLER — satır listesi, imleci takip eden önizleme kartı
         ══════════════════════════════════════════════════════════════ --}}
    @if ($services->isNotEmpty())
        <section class="bg-[#F4F4F2] px-6 py-20 lg:px-12 lg:py-32" data-follower>

            {{-- İmleci takip eden görsel kart (yalnız fare ile) --}}
            <div class="k-follower" data-follower-card aria-hidden="true">
                @foreach ($services as $service)
                    <div class="k-follower__slide" data-follower-slide>
                        @if ($service->image)
                            <img src="{{ asset('storage/'.$service->image) }}" alt="" loading="lazy">
                        @else
                            <div class="grid h-full w-full place-items-center bg-[#E30613] px-6 text-center">
                                <span class="k-display-xs" style="color:#ffffff;">{{ $service->t('title') }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mx-auto max-w-[1280px]">
                <div class="mb-12 flex flex-wrap items-end justify-between gap-6 lg:mb-16">
                    <div>
                        <p class="k-eyebrow k-reveal mb-5 text-[#0F0F0F]/45">{{ __('site.nav.services') }}</p>
                        <h2 class="k-display-sm text-[#0F0F0F]" data-split data-split-step="0.05">
                            {{ $isEn ? 'What we' : 'Ne' }} <span class="k-hl">{{ $isEn ? 'build.' : 'yapıyoruz.' }}</span>
                        </h2>
                    </div>
                    <a href="{{ $r('services.index') }}" class="k-reveal k-btn k-btn--ghost" data-delay="200">
                        <span>{{ __('site.common.view_all') }}</span>
                    </a>
                </div>

                <ul>
                    @foreach ($services as $i => $service)
                        <li class="k-reveal border-t border-[#0F0F0F]/10 last:border-b" data-delay="{{ min(($i + 1) * 100, 500) }}">
                            <a href="{{ $r('services.show', ['service' => $service->slug]) }}"
                               data-follower-row
                               class="k-row group flex items-baseline gap-5 py-7 hover:text-[#E30613] lg:gap-10 lg:py-9">
                                <span class="w-8 shrink-0 text-[0.7rem] font-bold tracking-[0.14em] text-[#0F0F0F]/35">
                                    {{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="k-display-xs block">{{ $service->t('title') }}</span>
                                    <span class="mt-2 block max-w-2xl text-sm leading-relaxed text-[#0F0F0F]/55 group-hover:text-[#0F0F0F]/70 lg:text-base">
                                        {{ $service->t('excerpt') }}
                                    </span>
                                </span>
                                <span class="k-row__arrow hidden shrink-0 text-2xl sm:block" aria-hidden="true">→</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════
         05 · İŞLER — dikey scroll'la sürülen yapışkan yatay şerit
         ══════════════════════════════════════════════════════════════ --}}
    @if ($works->isNotEmpty())
        {{-- Sarmalayıcının yüksekliğini motion.js şeridin gerçek genişliğinden
             hesaplar; buradaki değer yalnızca JS öncesi/mobil için makul bir taban. --}}
        <section class="relative bg-white" data-hscroll style="height: auto;">
            <div class="sticky top-0 flex flex-col justify-center overflow-hidden py-16 md:h-[100svh] md:py-0" data-hscroll-pane>

                <div class="mx-auto mb-8 w-full max-w-[1600px] px-6 lg:px-12">
                    <div class="flex flex-wrap items-end justify-between gap-5">
                        <div>
                            <p class="k-eyebrow mb-4 text-[#0F0F0F]/45">{{ __('site.nav.works') }}</p>
                            <h2 class="k-display-sm text-[#0F0F0F]">
                                {{ $isEn ? 'Selected' : 'Seçili' }} <span class="italic font-light">{{ $isEn ? 'work.' : 'işler.' }}</span>
                            </h2>
                        </div>
                        <p class="hidden text-[0.7rem] font-bold uppercase tracking-[0.16em] text-[#0F0F0F]/35 lg:block">
                            {{ $isEn ? 'Scroll to browse' : 'Gezmek için kaydırın' }} ↓
                        </p>
                    </div>
                </div>

                {{-- Masaüstünde transform ile akar; mobilde doğal yatay kaydırma --}}
                <div class="k-noscroll overflow-x-auto md:overflow-visible" data-drag-scroll>
                    <div class="flex gap-5 px-6 lg:gap-8 lg:px-12" data-hscroll-rail>
                        @foreach ($works as $work)
                            <a href="{{ $r('works.show', ['work' => $work->slug]) }}"
                               data-cursor="drag" data-cursor-label="{{ __('site.common.view_project') }}"
                               class="group block w-[78vw] shrink-0 sm:w-[52vw] lg:w-[34vw]">
                                <div class="k-img-hover relative aspect-[4/3] overflow-hidden rounded-lg bg-[#EDEDEB]">
                                    @if ($work->cover)
                                        <img src="{{ asset('storage/'.$work->cover) }}" alt="{{ $work->t('title') }}"
                                             loading="lazy" class="h-full w-full object-cover">
                                    @else
                                        <span class="absolute inset-0 grid place-items-center px-6 text-center k-display-xs text-[#0F0F0F]/20">
                                            {{ $work->t('title') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-5 flex items-baseline justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="k-eyebrow mb-1.5 text-[#0F0F0F]/45">
                                            {{ $work->category?->t('name') ?? $work->client }}
                                        </p>
                                        <h3 class="truncate text-xl font-black tracking-tight transition-colors duration-300 group-hover:text-[#E30613]">
                                            {{ $work->t('title') }}
                                        </h3>
                                    </div>
                                    @if ($work->year)
                                        <span class="shrink-0 text-[0.7rem] font-bold tracking-[0.12em] text-[#0F0F0F]/35">{{ $work->year }}</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach

                        {{-- Şeridin sonunda "hepsini gör" kartı --}}
                        <a href="{{ $r('works.index') }}"
                           class="group grid w-[78vw] shrink-0 place-items-center rounded-lg border border-[#0F0F0F]/12 sm:w-[42vw] lg:w-[24vw]">
                            <span class="flex items-center gap-3 text-sm font-bold uppercase tracking-[0.12em] transition-colors duration-300 group-hover:text-[#E30613]">
                                {{ __('site.common.view_all') }} <span aria-hidden="true">→</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════
         06 · SÜREÇ — yapışkan, üst üste yığılan kartlar
         ══════════════════════════════════════════════════════════════ --}}
    <section class="k-dark bg-[#0F0F0F] px-6 py-20 lg:px-12 lg:py-32">
        <div class="mx-auto max-w-[1280px]">
            <div class="mb-14 max-w-2xl">
                <p class="k-eyebrow k-reveal mb-5" style="color:rgba(255,255,255,0.4);">{{ $isEn ? 'Process' : 'Süreç' }}</p>
                <h2 class="k-display-sm" style="color:#ffffff;" data-split data-split-step="0.05">
                    {{ $isEn ? 'Four steps,' : 'Dört adım,' }} {{ $isEn ? 'no surprises.' : 'sürpriz yok.' }}
                </h2>
            </div>

            {{-- Kartlar gerçekten yapışkan: her biri bir öncekinin üstüne biner.
                 JS ayrıca alttakileri hafifçe küçültüp soldurur (derinlik). --}}
            <div data-stack class="space-y-[38vh] md:space-y-[42vh]">
                @foreach ($process as $i => [$no, $title, $text])
                    <article data-stack-item
                             class="k-reveal rounded-2xl border border-white/12 bg-[#141414] p-7 md:sticky lg:p-10"
                             style="top: calc(16vh + {{ $i * 16 }}px);"
                             data-delay="{{ min(($i + 1) * 100, 400) }}">
                        <div class="flex flex-col gap-5 md:flex-row md:items-start md:gap-10">
                            <span class="k-display-xs shrink-0 text-[#E30613]">{{ $no }}</span>
                            <div class="min-w-0">
                                <h3 class="text-2xl font-black tracking-tight lg:text-3xl" style="color:#ffffff;">{{ $title }}</h3>
                                <p class="mt-3 max-w-2xl leading-relaxed" style="color:rgba(255,255,255,0.6);">{{ $text }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════════════════════════════════
         07 · PAKETLER — fiyat önizlemesi
         ══════════════════════════════════════════════════════════════ --}}
    @if ($packages->isNotEmpty())
        <section class="bg-[#F1F1EF] px-6 py-20 lg:px-12 lg:py-32">
            <div class="mx-auto max-w-[1280px]">
                <div class="mb-12 flex flex-wrap items-end justify-between gap-6 lg:mb-16">
                    <div class="max-w-xl">
                        <p class="k-eyebrow k-reveal mb-5 text-[#0F0F0F]/45">{{ __('site.nav.packages') }}</p>
                        <h2 class="k-display-sm text-[#0F0F0F]" data-split data-split-step="0.05">
                            {{ $isEn ? 'Prices,' : 'Fiyatlar' }} <span class="k-hl">{{ $isEn ? 'in the open.' : 'açıkça yazılı.' }}</span>
                        </h2>
                        <p class="k-reveal mt-5 text-[#0F0F0F]/60" data-delay="200">
                            {{ $isEn
                                ? 'No "contact us for pricing". Every package, what it includes and what it costs — written down.'
                                : '"Fiyat için arayın" yok. Her paketin kapsamı ve fiyatı burada yazıyor.' }}
                        </p>
                    </div>
                    <a href="{{ $r('packages') }}" class="k-reveal k-btn k-btn--ghost" data-delay="300">
                        <span>{{ $isEn ? 'All packages' : 'Tüm paketler' }}</span>
                    </a>
                </div>

                {{-- Izgara paket sayısına uyar: 4 paket 3'lü ızgarada tek başına
                     kalan bir kart bırakıyordu. Sınıflar LİTERAL yazılmak zorunda —
                     Tailwind kaynağı tarar, birleştirilmiş sınıf adı üretilmez. --}}
                @php
                    $packageCols = match (true) {
                        $packages->count() >= 4 => 'md:grid-cols-2 xl:grid-cols-4',
                        $packages->count() === 3 => 'md:grid-cols-3',
                        $packages->count() === 2 => 'md:grid-cols-2',
                        default => 'max-w-md',
                    };
                @endphp
                <div class="grid grid-cols-1 gap-5 {{ $packageCols }}">
                    @foreach ($packages as $i => $package)
                        <article class="k-reveal relative flex flex-col rounded-2xl border-2 bg-white p-7 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_14px_44px_rgba(0,0,0,0.07)] lg:p-8 {{ $package->is_popular ? 'border-[#E30613]' : 'border-[#0F0F0F]/10 hover:border-[#0F0F0F]/25' }}"
                                 data-delay="{{ min(($i + 1) * 100, 300) }}">
                            @if ($package->is_popular)
                                <span class="absolute -top-3 left-7 rounded-full bg-[#E30613] px-3 py-1 text-[0.6rem] font-bold uppercase tracking-[0.14em]" style="color:#ffffff;">
                                    {{ $isEn ? 'Most chosen' : 'En çok tercih edilen' }}
                                </span>
                            @endif

                            <h3 class="text-xl font-black tracking-tight">{{ $package->t('name') }}</h3>
                            <p class="mt-2.5 min-h-[3.25rem] text-sm leading-relaxed text-[#0F0F0F]/60">{{ $package->t('tagline') }}</p>

                            <div class="mt-6 border-t border-[#0F0F0F]/10 pt-6">
                                @if ($package->price_regular)
                                    <span class="mr-2 text-sm text-[#0F0F0F]/35 line-through">{{ $package->formatPrice($package->price_regular) }}</span>
                                @endif
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-black tracking-tight">{{ $package->formatPrice($package->price) }}</span>
                                    <span class="text-xs text-[#0F0F0F]/45">+ KDV</span>
                                </div>
                                @if ($package->price_with_panel)
                                    <p class="mt-1.5 text-xs text-[#0F0F0F]/50">
                                        {{ $isEn ? 'With admin panel' : 'Panelli' }}: <strong class="text-[#0F0F0F]/75">{{ $package->formatPrice($package->price_with_panel) }}</strong>
                                    </p>
                                @endif
                                @if ($package->t('delivery'))
                                    <p class="mt-2.5 text-xs font-semibold uppercase tracking-[0.1em] text-[#E30613]">{{ $package->t('delivery') }}</p>
                                @endif
                            </div>

                            @if (filled($package->t('features')))
                                <ul class="mt-6 flex-1 space-y-2.5">
                                    @foreach (array_slice((array) $package->t('features'), 0, 4) as $feature)
                                        <li class="flex gap-2.5 text-sm text-[#0F0F0F]/70">
                                            <span class="mt-[7px] block h-1 w-1 shrink-0 rounded-full bg-[#E30613]" aria-hidden="true"></span>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <a href="{{ $r('quote') }}" class="k-btn {{ $package->is_popular ? 'k-btn--brand' : 'k-btn--ghost' }} mt-7 justify-center">
                                <span style="color:inherit;">{{ __('site.nav.quote') }}</span>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════
         08 · REFERANSLAR
         ══════════════════════════════════════════════════════════════ --}}
    @if ($testimonials->isNotEmpty())
        <section class="bg-white px-6 py-20 lg:px-12 lg:py-32">
            <div class="mx-auto max-w-[1280px]">
                <p class="k-eyebrow k-reveal mb-12 text-[#0F0F0F]/45">{{ $isEn ? 'Clients' : 'Müşteriler' }}</p>

                <div class="grid grid-cols-1 gap-x-8 gap-y-12 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($testimonials as $i => $testimonial)
                        <figure class="k-reveal" data-delay="{{ min(($i % 3 + 1) * 100, 300) }}">
                            <blockquote class="text-lg font-semibold leading-snug tracking-tight text-[#0F0F0F]">
                                “{{ $testimonial->t('quote') }}”
                            </blockquote>
                            <figcaption class="mt-5 flex items-center gap-3">
                                @if ($testimonial->avatar)
                                    <img src="{{ asset('storage/'.$testimonial->avatar) }}" alt=""
                                         loading="lazy" class="h-10 w-10 rounded-full object-cover">
                                @endif
                                <div>
                                    <p class="text-sm font-bold">{{ $testimonial->name }}</p>
                                    <p class="text-xs text-[#0F0F0F]/50">
                                        {{ collect([$testimonial->t('role'), $testimonial->company])->filter()->join(' · ') }}
                                    </p>
                                </div>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════
         09 · MARKA LOGOLARI — ters yönde kayan şerit
         ══════════════════════════════════════════════════════════════ --}}
    @if ($brands->isNotEmpty())
        <section class="border-y border-[#0F0F0F]/10 bg-[#F4F4F2] py-10 lg:py-14">
            <div class="k-marquee" data-marquee="-0.35">
                <div class="k-marquee__rail" data-marquee-rail>
                    <span class="flex shrink-0 items-center gap-12 pr-12 lg:gap-20 lg:pr-20">
                        @foreach ($brands as $brand)
                            <span class="shrink-0">
                                @if ($brand->logo)
                                    <img src="{{ asset('storage/'.$brand->logo) }}" alt="{{ $brand->name }}"
                                         loading="lazy" class="h-7 w-auto opacity-45 lg:h-9">
                                @else
                                    <span class="whitespace-nowrap text-lg font-black tracking-tight text-[#0F0F0F]/30 lg:text-2xl">{{ $brand->name }}</span>
                                @endif
                            </span>
                        @endforeach
                    </span>
                </div>
            </div>
        </section>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════
         10 · BLOG
         ══════════════════════════════════════════════════════════════ --}}
    @if ($posts->isNotEmpty())
        <section class="bg-white px-6 py-20 lg:px-12 lg:py-32">
            <div class="mx-auto max-w-[1280px]">
                <div class="mb-12 flex flex-wrap items-end justify-between gap-6">
                    <div>
                        <p class="k-eyebrow k-reveal mb-5 text-[#0F0F0F]/45">{{ __('site.nav.blog') }}</p>
                        <h2 class="k-display-sm text-[#0F0F0F]" data-split data-split-step="0.05">
                            {{ $isEn ? 'Notes on' : 'Web üzerine' }} <span class="italic font-light">{{ $isEn ? 'the web.' : 'notlar.' }}</span>
                        </h2>
                    </div>
                    <a href="{{ $r('blog.index') }}" class="k-reveal k-btn k-btn--ghost" data-delay="200">
                        <span>{{ __('site.common.view_all') }}</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-x-7 gap-y-12 md:grid-cols-3">
                    @foreach ($posts as $i => $post)
                        <a href="{{ $r('blog.show', ['post' => $post->slug]) }}"
                           class="k-reveal group block" data-delay="{{ min(($i + 1) * 100, 300) }}">
                            <div class="k-img-hover mb-5 aspect-[16/10] overflow-hidden rounded-lg bg-[#F1F1EF]">
                                @if ($post->cover)
                                    <img src="{{ asset('storage/'.$post->cover) }}" alt="{{ $post->t('title') }}"
                                         loading="lazy" class="h-full w-full object-cover">
                                @endif
                            </div>
                            <div class="mb-2.5 flex items-center gap-3">
                                @if ($post->category)
                                    <span class="k-tag">{{ $post->category->t('name') }}</span>
                                @endif
                                @if ($post->reading_minutes)
                                    <span class="text-[0.7rem] text-[#0F0F0F]/40">{{ $post->reading_minutes }} {{ __('site.common.minutes') }}</span>
                                @endif
                            </div>
                            <h3 class="text-xl font-black leading-tight tracking-tight transition-colors duration-300 group-hover:text-[#E30613]">
                                {{ $post->t('title') }}
                            </h3>
                            <p class="mt-2.5 line-clamp-3 text-sm leading-relaxed text-[#0F0F0F]/55">{{ $post->t('excerpt') }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════
         11 · SSS — FAQPage yapısal verisiyle
         ══════════════════════════════════════════════════════════════ --}}
    @if ($faqs->isNotEmpty())
        <section class="bg-[#F1F1EF] px-6 py-20 lg:px-12 lg:py-32">
            <div class="mx-auto grid max-w-[1280px] grid-cols-1 gap-12 lg:grid-cols-12">
                <div class="lg:col-span-4">
                    <p class="k-eyebrow k-reveal mb-5 text-[#0F0F0F]/45">{{ __('site.common.faq') }}</p>
                    <h2 class="k-display-sm text-[#0F0F0F]" data-split data-split-step="0.05">
                        {{ $isEn ? 'Asked' : 'Sık sorulan' }}<br>{{ $isEn ? 'often.' : 'sorular.' }}
                    </h2>
                </div>

                <div class="lg:col-span-8" data-acc="single">
                    @foreach ($faqs as $i => $faq)
                        <div class="k-acc k-reveal border-t border-[#0F0F0F]/12 last:border-b" data-delay="{{ min(($i + 1) * 100, 400) }}">
                            <h3>
                                <button type="button" data-acc-trigger aria-expanded="false"
                                        class="flex w-full items-start justify-between gap-6 py-6 text-left">
                                    <span class="text-lg font-bold tracking-tight">{{ $faq->t('question') }}</span>
                                    <span class="k-acc__sign mt-1 shrink-0 text-xl text-[#E30613]" aria-hidden="true">+</span>
                                </button>
                            </h3>
                            <div class="k-acc__body">
                                <div>
                                    <p class="max-w-2xl pb-6 leading-relaxed text-[#0F0F0F]/65">{{ $faq->t('answer') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
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

</x-app-layout>
