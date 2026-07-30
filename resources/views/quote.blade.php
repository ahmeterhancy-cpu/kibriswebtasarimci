@php
    $isEn = app()->getLocale() === 'en';

    /* Proje türleri — 2. adımda gösterilecek paketleri süzer. */
    $types = [
        ['key' => 'tanitim',  'label' => $isEn ? 'Landing / one-page site' : 'Tanıtım / tek sayfa site',   'note' => $isEn ? 'A single page that tells your story.' : 'Hikâyenizi tek sayfada anlatan site.'],
        ['key' => 'kurumsal', 'label' => $isEn ? 'Corporate website' : 'Kurumsal web sitesi',              'note' => $isEn ? 'Multiple pages, blog, service details.' : 'Çok sayfa, blog, hizmet detayları.'],
        ['key' => 'eticaret', 'label' => $isEn ? 'Online store' : 'E-ticaret / online mağaza',             'note' => $isEn ? 'Products, orders, payments.' : 'Ürün, sipariş, ödeme.'],
        ['key' => 'mobil',    'label' => $isEn ? 'iOS / Android app' : 'iOS / Android uygulama',           'note' => $isEn ? 'One codebase, both stores.' : 'Tek kod tabanı, iki mağaza.'],
        ['key' => 'yazilim',  'label' => $isEn ? 'Custom web software' : 'Özel web yazılımı',              'note' => $isEn ? 'Booking, portal, internal panel.' : 'Rezervasyon, portal, iç panel.'],
        ['key' => 'yenileme', 'label' => $isEn ? 'Redesign an existing site' : 'Mevcut siteyi yenileme',   'note' => $isEn ? 'You have a site; it needs rebuilding.' : 'Siteniz var; yeniden kurulması gerekiyor.'],
    ];

    /* Ek modüller — fiyatlar ₺, KDV hariç. */
    $extras = [
        ['key' => 'katalog',   'label' => $isEn ? 'Catalogue module (WhatsApp orders)' : 'Katalog modülü (WhatsApp sipariş)', 'price' => 5000],
        ['key' => 'dil',       'label' => $isEn ? 'Second language (TR / EN)' : 'İkinci dil (TR / EN)',                        'price' => 4000],
        ['key' => 'blog',      'label' => $isEn ? 'Blog / news module' : 'Blog / haber modülü',                                'price' => 3500],
        ['key' => 'rezervasyon', 'label' => $isEn ? 'Booking / appointment system' : 'Rezervasyon / randevu sistemi',          'price' => 12000],
        ['key' => 'uyelik',    'label' => $isEn ? 'Membership / customer portal' : 'Üyelik / müşteri portalı',                 'price' => 15000],
        ['key' => 'seo',       'label' => $isEn ? 'SEO content package (5 pages)' : 'SEO içerik paketi (5 sayfa)',             'price' => 6000],
        ['key' => 'kimlik',    'label' => $isEn ? 'Logo and brand identity' : 'Logo ve marka kimliği',                         'price' => 9000],
        ['key' => 'mobilapp',  'label' => $isEn ? 'iOS + Android app' : 'iOS + Android uygulama',                              'price' => 65000],
    ];

    $timelines = [
        ['key' => 'acil',   'label' => $isEn ? 'As soon as possible' : 'En kısa sürede',      'factor' => 1.2, 'note' => $isEn ? 'Rush surcharge applies.' : 'Hızlandırma farkı uygulanır.'],
        ['key' => 'normal', 'label' => $isEn ? 'Within 1 month' : '1 ay içinde',              'factor' => 1.0, 'note' => ''],
        ['key' => 'esnek',  'label' => $isEn ? 'Flexible' : 'Esnek',                          'factor' => 0.95, 'note' => $isEn ? 'Small discount for flexibility.' : 'Esneklik indirimi.'],
    ];

    $budgets = $isEn
        ? ['under-10k' => 'Under 10,000 ₺', '10-25k' => '10,000 – 25,000 ₺', '25-50k' => '25,000 – 50,000 ₺', '50k-plus' => '50,000 ₺ +', 'unsure' => 'Not sure yet']
        : ['under-10k' => '10.000 ₺ altı', '10-25k' => '10.000 – 25.000 ₺', '25-50k' => '25.000 – 50.000 ₺', '50k-plus' => '50.000 ₺ üstü', 'unsure' => 'Henüz emin değilim'];

    /* JS'e geçen metinler burada hazırlanır. Doğrudan @json(...) içine çok satırlı
       dizi ya da parantez içeren dize yazmak Blade'in argüman ayrıştırıcısını
       bozar (parantezleri sayarak kapanışı bulmaya çalışır). */
    $stepTitles = [
        __('site.quote.step1'), __('site.quote.step2'), __('site.quote.step3'),
        __('site.quote.step4'), __('site.quote.step5'),
    ];

    $wizardLabels = [
        'noPackage' => $isEn ? 'Not sure yet — advise me' : 'Emin değilim — siz önerin',
        'noPackageNote' => $isEn ? 'We will scope it together on the call.' : 'Görüşmede birlikte netleştiririz.',
        'withPanel' => $isEn ? 'with panel' : 'panelli',
        'empty' => $isEn ? 'Nothing selected yet.' : 'Henüz seçim yapılmadı.',
        'required' => $isEn ? 'Please make a selection to continue.' : 'Devam etmek için bir seçim yapın.',
        'fields' => $isEn ? 'Please fill the required fields.' : 'Zorunlu alanları doldurun.',
        'onRequest' => $isEn ? 'Scoped after a call' : 'Görüşme sonrası netleşir',
    ];

    $typePackages = [
        'tanitim' => ['hizli-baslangic', 'basic-onepage'],
        'kurumsal' => ['basic-onepage', 'kurumsal'],
        'eticaret' => ['e-ticaret-baslangic', 'e-ticaret-pro'],
        'yenileme' => ['basic-onepage', 'kurumsal', 'e-ticaret-baslangic'],
        'mobil' => [],
        'yazilim' => [],
    ];

    /* Paket verisi JS'e — tür eşlemesi ve fiyat. */
    $packageData = $packages->map(fn ($p) => [
        'slug' => $p->slug,
        'name' => $p->t('name'),
        'price' => $p->price,
        'pricePanel' => $p->price_with_panel,
        'delivery' => $p->t('delivery'),
        'ecommerce' => (bool) $p->is_ecommerce,
    ])->values();
@endphp

<x-app-layout
    :seo-title="__('site.nav.quote')"
    :seo-description="$isEn
        ? 'Answer five short questions and get a live budget estimate. We send a scoped proposal within 24 hours.'
        : 'Beş kısa soruyu yanıtlayın, anlık bütçe tahmini alın. Kapsamı netleştiren teklifi 24 saat içinde gönderiyoruz.'">

    <x-page-hero :eyebrow="__('site.nav.quote')"
                 :lead="$isEn
                    ? 'Five short steps. The estimate updates live as you choose — nothing is binding, it just saves us both a call.'
                    : 'Beş kısa adım. Seçtikçe tahmini bütçe anlık güncellenir — bağlayıcı değil, sadece ikimizin de vaktini kazandırır.'">
        {{ $isEn ? 'Let\'s scope' : 'Projeyi' }} <span class="k-hl">{{ $isEn ? 'it.' : 'kurgulayalım.' }}</span>
    </x-page-hero>

    <section class="bg-white px-6 py-14 lg:px-12 lg:py-20">
        <div class="mx-auto max-w-[1280px]">

            @if (session('status') === 'quote-sent')
                <div class="mb-10 rounded-2xl border-l-4 border-[#E30613] bg-[#F1F1EF] px-7 py-6">
                    <p class="text-lg font-bold">{{ __('site.form.success_quote') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-10 rounded-2xl border-l-4 border-[#E30613] bg-[#FDF0F1] px-7 py-6">
                    <p class="mb-2 font-semibold">{{ __('site.form.error') }}</p>
                    <ul class="list-disc pl-5 text-sm text-[#0F0F0F]/70">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ $r('quote.store') }}" data-busy id="quote-form"
                  class="grid grid-cols-1 gap-12 lg:grid-cols-12">
                @csrf

                <div class="absolute left-[-9999px]" aria-hidden="true">
                    <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>

                {{-- ── Adımlar ─────────────────────────────────────────── --}}
                <div class="lg:col-span-7">

                    {{-- İlerleme --}}
                    <div class="mb-10">
                        <div class="mb-3 flex items-center justify-between text-[0.7rem] font-bold uppercase tracking-[0.16em] text-[#0F0F0F]/45">
                            <span>{{ __('site.quote.step') }} <span data-step-current>1</span> {{ __('site.quote.of') }} 5</span>
                            <span data-step-title>{{ __('site.quote.step1') }}</span>
                        </div>
                        <div class="h-[3px] w-full overflow-hidden rounded-full bg-[#0F0F0F]/10">
                            <div class="h-full rounded-full bg-[#E30613] transition-[width] duration-500 ease-out"
                                 style="width:20%" data-step-bar></div>
                        </div>
                    </div>

                    {{-- 1 · Proje türü --}}
                    <fieldset class="k-step is-active" data-step="1">
                        <legend class="k-display-xs mb-7">{{ __('site.quote.step1') }}</legend>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            @foreach ($types as $type)
                                <button type="button" class="k-choice" data-pick="project_type" data-value="{{ $type['key'] }}">
                                    <span class="block pr-7 font-bold tracking-tight">{{ $type['label'] }}</span>
                                    <span class="mt-1 block text-sm text-[#0F0F0F]/55">{{ $type['note'] }}</span>
                                    <span class="k-choice__check" aria-hidden="true">
                                        <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4l2.5 2.5L9 1" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </span>
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="project_type" data-field="project_type">
                    </fieldset>

                    {{-- 2 · Paket --}}
                    <fieldset class="k-step" data-step="2">
                        <legend class="k-display-xs mb-7">{{ __('site.quote.step2') }}</legend>
                        <div class="grid grid-cols-1 gap-3" data-package-list></div>
                        <input type="hidden" name="package" data-field="package">
                    </fieldset>

                    {{-- 3 · Ek modüller --}}
                    <fieldset class="k-step" data-step="3">
                        <legend class="k-display-xs mb-2">{{ __('site.quote.step3') }}</legend>
                        <p class="mb-7 text-sm text-[#0F0F0F]/55">
                            {{ $isEn ? 'Optional — pick any that apply.' : 'İsteğe bağlı — uygun olanları işaretleyin.' }}
                        </p>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            @foreach ($extras as $extra)
                                <button type="button" class="k-choice" data-toggle="extras" data-value="{{ $extra['key'] }}"
                                        data-price="{{ $extra['price'] }}" data-label="{{ $extra['label'] }}">
                                    <span class="block pr-7 font-bold tracking-tight">{{ $extra['label'] }}</span>
                                    <span class="mt-1 block text-sm text-[#E30613]">+{{ number_format($extra['price'], 0, ',', '.') }} ₺</span>
                                    <span class="k-choice__check" aria-hidden="true">
                                        <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4l2.5 2.5L9 1" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </span>
                                </button>
                            @endforeach
                        </div>
                        <div data-extras-inputs></div>
                    </fieldset>

                    {{-- 4 · Süre ve bütçe --}}
                    <fieldset class="k-step" data-step="4">
                        <legend class="k-display-xs mb-7">{{ __('site.quote.step4') }}</legend>

                        <p class="k-field-label mb-3">{{ $isEn ? 'When do you need it?' : 'Ne zaman lazım?' }}</p>
                        <div class="mb-9 grid grid-cols-1 gap-3 sm:grid-cols-3">
                            @foreach ($timelines as $timeline)
                                <button type="button" class="k-choice" data-pick="timeline"
                                        data-value="{{ $timeline['key'] }}" data-factor="{{ $timeline['factor'] }}">
                                    <span class="block pr-7 font-bold tracking-tight">{{ $timeline['label'] }}</span>
                                    @if ($timeline['note'])
                                        <span class="mt-1 block text-sm text-[#0F0F0F]/55">{{ $timeline['note'] }}</span>
                                    @endif
                                    <span class="k-choice__check" aria-hidden="true">
                                        <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4l2.5 2.5L9 1" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </span>
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="timeline" data-field="timeline">

                        <p class="k-field-label mb-3">{{ $isEn ? 'Budget in mind' : 'Aklınızdaki bütçe' }}</p>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            @foreach ($budgets as $key => $label)
                                <button type="button" class="k-choice" data-pick="budget" data-value="{{ $key }}">
                                    <span class="block pr-7 font-bold tracking-tight">{{ $label }}</span>
                                    <span class="k-choice__check" aria-hidden="true">
                                        <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4l2.5 2.5L9 1" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </span>
                                </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="budget" data-field="budget">
                    </fieldset>

                    {{-- 5 · İletişim --}}
                    <fieldset class="k-step" data-step="5">
                        <legend class="k-display-xs mb-7">{{ __('site.quote.step5') }}</legend>
                        <div class="grid grid-cols-1 gap-x-8 gap-y-7 sm:grid-cols-2">
                            <div>
                                <label for="q-name" class="k-field-label">{{ __('site.form.name') }} *</label>
                                <input id="q-name" name="name" type="text" maxlength="150" value="{{ old('name') }}"
                                       class="k-field" autocomplete="name" data-required>
                            </div>
                            <div>
                                <label for="q-email" class="k-field-label">{{ __('site.form.email') }} *</label>
                                <input id="q-email" name="email" type="email" maxlength="190" value="{{ old('email') }}"
                                       class="k-field" autocomplete="email" data-required>
                            </div>
                            <div>
                                <label for="q-phone" class="k-field-label">{{ __('site.form.phone') }}</label>
                                <input id="q-phone" name="phone" type="tel" maxlength="60" value="{{ old('phone') }}"
                                       class="k-field" autocomplete="tel">
                            </div>
                            <div>
                                <label for="q-company" class="k-field-label">{{ __('site.form.company') }}</label>
                                <input id="q-company" name="company" type="text" maxlength="190" value="{{ old('company') }}"
                                       class="k-field" autocomplete="organization">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="q-message" class="k-field-label">
                                    {{ $isEn ? 'Anything else we should know?' : 'Eklemek istediğiniz bir şey var mı?' }}
                                </label>
                                <textarea id="q-message" name="message" rows="4" maxlength="5000"
                                          class="k-field resize-y">{{ old('message') }}</textarea>
                            </div>
                        </div>

                        <input type="hidden" name="estimate_min" data-field="estimate_min">
                        <input type="hidden" name="estimate_max" data-field="estimate_max">
                    </fieldset>

                    {{-- Gezinme --}}
                    <div class="mt-10 flex flex-wrap items-center gap-4">
                        <button type="button" class="k-btn k-btn--ghost" data-nav="prev" hidden>
                            <span>← {{ __('site.quote.back') }}</span>
                        </button>
                        <button type="button" class="k-btn k-btn--ink" data-nav="next" data-cursor="cta">
                            <span style="color:inherit;">{{ __('site.quote.next') }}</span>
                            <span class="k-btn__arrow" aria-hidden="true">→</span>
                        </button>
                        <button type="submit" class="k-btn k-btn--brand" data-nav="submit" data-cursor="cta"
                                data-busy-text="{{ __('site.form.sending') }}" hidden>
                            <span data-busy-label style="color:inherit;">{{ __('site.quote.submit') }}</span>
                            <span class="k-btn__arrow" aria-hidden="true">→</span>
                        </button>
                        <p class="text-sm text-[#E30613]" data-step-error role="alert" hidden></p>
                    </div>
                </div>

                {{-- ── Canlı özet ──────────────────────────────────────── --}}
                <aside class="lg:col-span-4 lg:col-start-9">
                    <div class="sticky top-28 rounded-2xl border border-[#0F0F0F]/10 bg-[#F4F4F2] p-7">
                        <p class="k-eyebrow mb-5 text-[#0F0F0F]/45">{{ __('site.quote.estimate') }}</p>

                        <p class="text-3xl font-black leading-none tracking-tight" data-estimate>—</p>
                        <p class="mt-2 text-xs text-[#0F0F0F]/45">+ KDV</p>

                        <div class="k-rule my-6"></div>

                        <p class="k-eyebrow mb-4 text-[#0F0F0F]/45">{{ __('site.quote.summary') }}</p>
                        <ul class="space-y-2.5 text-sm" data-summary>
                            <li class="text-[#0F0F0F]/40">{{ $isEn ? 'Nothing selected yet.' : 'Henüz seçim yapılmadı.' }}</li>
                        </ul>

                        <p class="mt-6 text-xs leading-relaxed text-[#0F0F0F]/50">{{ __('site.quote.estimate_note') }}</p>
                    </div>
                </aside>
            </form>
        </div>
    </section>

    @if ($faqs->isNotEmpty())
        <section class="bg-[#F1F1EF] px-6 py-16 lg:px-12 lg:py-24">
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
    @endif

    @push('scripts')
        <script>
        /* ══════════════════════════════════════════════════════════════
           TEKLİF SİHİRBAZI — saf JS, harici kütüphane yok.
           Adım geçişi, seçim durumu, canlı bütçe tahmini ve doğrulama.
           ══════════════════════════════════════════════════════════ */
        (function () {
            const form = document.getElementById('quote-form');
            if (!form) return;

            const PACKAGES = @json($packageData);
            const STEP_TITLES = @json($stepTitles);
            const L = @json($wizardLabels);
            const TYPE_PACKAGES = @json($typePackages);

            const steps = Array.from(form.querySelectorAll('[data-step]'));
            const bar = form.querySelector('[data-step-bar]');
            const stepCurrent = form.querySelector('[data-step-current]');
            const stepTitle = form.querySelector('[data-step-title]');
            const errorEl = form.querySelector('[data-step-error]');
            const btnPrev = form.querySelector('[data-nav="prev"]');
            const btnNext = form.querySelector('[data-nav="next"]');
            const btnSubmit = form.querySelector('[data-nav="submit"]');
            const packageList = form.querySelector('[data-package-list]');
            const extrasInputs = form.querySelector('[data-extras-inputs]');
            const estimateEl = form.querySelector('[data-estimate]');
            const summaryEl = form.querySelector('[data-summary]');

            const state = {
                step: 1,
                project_type: '',
                package: '',
                packageLabel: '',
                panel: false,
                extras: [],          // [{key, label, price}]
                timeline: '',
                timelineLabel: '',
                factor: 1,
                budget: '',
                budgetLabel: '',
            };

            const money = (n) => new Intl.NumberFormat('tr-TR').format(Math.round(n / 500) * 500) + ' ₺';

            /* ── Adım gösterimi ──────────────────────────────────────── */
            function showStep(n) {
                state.step = Math.min(Math.max(n, 1), steps.length);
                steps.forEach((s) => s.classList.toggle('is-active', Number(s.dataset.step) === state.step));
                bar.style.width = (state.step / steps.length * 100) + '%';
                stepCurrent.textContent = state.step;
                stepTitle.textContent = STEP_TITLES[state.step - 1];
                btnPrev.hidden = state.step === 1;
                btnNext.hidden = state.step === steps.length;
                btnSubmit.hidden = state.step !== steps.length;
                errorEl.hidden = true;

                const box = form.getBoundingClientRect();
                if (box.top < 0) window.scrollTo({ top: window.scrollY + box.top - 110, behavior: 'smooth' });
            }

            /* ── Paket listesini türe göre kur ───────────────────────── */
            function buildPackages() {
                const allowed = TYPE_PACKAGES[state.project_type] || [];
                const list = PACKAGES.filter((p) => allowed.includes(p.slug));
                packageList.innerHTML = '';

                list.forEach((p) => {
                    const hasPanel = !!p.pricePanel;
                    const card = document.createElement('div');
                    card.className = 'k-choice';
                    card.dataset.packageCard = p.slug;
                    card.innerHTML = `
                        <button type="button" class="block w-full pr-7 text-left" data-pick="package"
                                data-value="${p.slug}" data-price="${p.price}" data-label="${p.name}">
                            <span class="block font-bold tracking-tight">${p.name}</span>
                            <span class="mt-1 block text-sm text-[#E30613]">${new Intl.NumberFormat('tr-TR').format(p.price)} ₺${p.delivery ? ' · ' + p.delivery : ''}</span>
                        </button>
                        <span class="k-choice__check" aria-hidden="true">
                            <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4l2.5 2.5L9 1" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        ${hasPanel ? `<label class="mt-3 flex items-center gap-2.5 border-t border-[#0F0F0F]/10 pt-3 text-sm text-[#0F0F0F]/70">
                            <input type="checkbox" data-panel-for="${p.slug}" data-panel-price="${p.pricePanel}" class="h-4 w-4 accent-[#E30613]">
                            <span>${L.withPanel} — ${new Intl.NumberFormat('tr-TR').format(p.pricePanel)} ₺</span>
                        </label>` : ''}
                    `;
                    packageList.appendChild(card);
                });

                // "Emin değilim" seçeneği her zaman var.
                const unsure = document.createElement('button');
                unsure.type = 'button';
                unsure.className = 'k-choice';
                unsure.dataset.pick = 'package';
                unsure.dataset.value = '';
                unsure.dataset.price = '0';
                unsure.dataset.label = L.noPackage;
                unsure.innerHTML = `
                    <span class="block pr-7 font-bold tracking-tight">${L.noPackage}</span>
                    <span class="mt-1 block text-sm text-[#0F0F0F]/55">${L.noPackageNote}</span>
                    <span class="k-choice__check" aria-hidden="true">
                        <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4l2.5 2.5L9 1" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>`;
                packageList.appendChild(unsure);
            }

            /* ── Tahmin ve özet ─────────────────────────────────────── */
            function basePrice() {
                if (!state.package) return 0;
                const p = PACKAGES.find((x) => x.slug === state.package);
                if (!p) return 0;
                return state.panel && p.pricePanel ? p.pricePanel : p.price;
            }

            function recalc() {
                const base = basePrice();
                const extrasSum = state.extras.reduce((sum, e) => sum + e.price, 0);
                const total = (base + extrasSum) * state.factor;

                const minInput = form.querySelector('[data-field="estimate_min"]');
                const maxInput = form.querySelector('[data-field="estimate_max"]');

                if (total > 0) {
                    const min = total * 0.9;
                    const max = total * 1.15;
                    estimateEl.textContent = money(min) + ' – ' + money(max);
                    minInput.value = Math.round(min);
                    maxInput.value = Math.round(max);
                } else {
                    estimateEl.textContent = L.onRequest;
                    minInput.value = '';
                    maxInput.value = '';
                }

                // Özet listesi
                const rows = [];
                if (state.project_type) {
                    const btn = form.querySelector(`[data-pick="project_type"][data-value="${state.project_type}"]`);
                    if (btn) rows.push(btn.querySelector('span').textContent.trim());
                }
                if (state.packageLabel) rows.push(state.packageLabel + (state.panel ? ' (' + L.withPanel + ')' : ''));
                state.extras.forEach((e) => rows.push('+ ' + e.label));
                if (state.timelineLabel) rows.push(state.timelineLabel);
                if (state.budgetLabel) rows.push(state.budgetLabel);

                summaryEl.innerHTML = rows.length
                    ? rows.map((r) => `<li class="flex gap-2.5 text-[#0F0F0F]/75"><span class="mt-[7px] block h-1 w-1 shrink-0 rounded-full bg-[#E30613]"></span><span>${r}</span></li>`).join('')
                    : `<li class="text-[#0F0F0F]/40">${L.empty}</li>`;
            }

            /* ── Tek seçim ──────────────────────────────────────────── */
            form.addEventListener('click', (e) => {
                const pick = e.target.closest('[data-pick]');
                if (pick) {
                    const group = pick.dataset.pick;
                    const card = pick.classList.contains('k-choice') ? pick : pick.closest('.k-choice');

                    form.querySelectorAll(`[data-pick="${group}"]`).forEach((el) => {
                        const c = el.classList.contains('k-choice') ? el : el.closest('.k-choice');
                        if (c) c.classList.remove('is-picked');
                    });
                    if (card) card.classList.add('is-picked');

                    const hidden = form.querySelector(`[data-field="${group}"]`);
                    if (hidden) hidden.value = pick.dataset.value || '';

                    if (group === 'project_type') {
                        state.project_type = pick.dataset.value;
                        state.package = ''; state.packageLabel = ''; state.panel = false;
                        buildPackages();
                    } else if (group === 'package') {
                        state.package = pick.dataset.value || '';
                        state.packageLabel = state.package ? pick.dataset.label : '';
                        state.panel = false;
                        form.querySelectorAll('[data-panel-for]').forEach((cb) => { cb.checked = false; });
                    } else if (group === 'timeline') {
                        state.timeline = pick.dataset.value;
                        state.timelineLabel = pick.querySelector('span').textContent.trim();
                        state.factor = parseFloat(pick.dataset.factor) || 1;
                    } else if (group === 'budget') {
                        state.budget = pick.dataset.value;
                        state.budgetLabel = pick.querySelector('span').textContent.trim();
                    }
                    recalc();
                    return;
                }

                /* Çoklu seçim — ek modüller */
                const toggle = e.target.closest('[data-toggle="extras"]');
                if (toggle) {
                    const key = toggle.dataset.value;
                    const idx = state.extras.findIndex((x) => x.key === key);
                    if (idx >= 0) {
                        state.extras.splice(idx, 1);
                        toggle.classList.remove('is-picked');
                    } else {
                        state.extras.push({ key, label: toggle.dataset.label, price: Number(toggle.dataset.price) });
                        toggle.classList.add('is-picked');
                    }
                    extrasInputs.innerHTML = state.extras
                        .map((x) => `<input type="hidden" name="extras[]" value="${x.key}">`).join('');
                    recalc();
                }
            });

            /* Panelli kutusu */
            form.addEventListener('change', (e) => {
                const cb = e.target.closest('[data-panel-for]');
                if (!cb) return;
                if (cb.dataset.panelFor !== state.package) {
                    // Kutu, seçili olmayan bir paketin altındaysa önce o paketi seç.
                    const pick = form.querySelector(`[data-pick="package"][data-value="${cb.dataset.panelFor}"]`);
                    if (pick) pick.click();
                }
                state.panel = cb.checked;
                recalc();
            });

            /* ── Gezinme + doğrulama ────────────────────────────────── */
            function validate(step) {
                if (step === 1 && !state.project_type) return L.required;
                if (step === 4 && !state.timeline) return L.required;
                return null;
            }

            btnNext.addEventListener('click', () => {
                const problem = validate(state.step);
                if (problem) { errorEl.textContent = problem; errorEl.hidden = false; return; }
                if (state.step === 1) buildPackages();
                showStep(state.step + 1);
            });

            btnPrev.addEventListener('click', () => showStep(state.step - 1));

            form.addEventListener('submit', (e) => {
                const missing = Array.from(form.querySelectorAll('[data-required]')).filter((el) => !el.value.trim());
                if (missing.length) {
                    e.preventDefault();
                    showStep(5);
                    errorEl.textContent = L.fields;
                    errorEl.hidden = false;
                    missing[0].focus();
                }
            });

            /* ── Paketler sayfasından gelen ön seçim (?paket=slug) ──── */
            const preset = new URLSearchParams(location.search).get('paket');
            if (preset) {
                const pkg = PACKAGES.find((p) => p.slug === preset);
                if (pkg) {
                    const type = pkg.ecommerce ? 'eticaret' : (preset === 'kurumsal' ? 'kurumsal' : 'tanitim');
                    const typeBtn = form.querySelector(`[data-pick="project_type"][data-value="${type}"]`);
                    if (typeBtn) typeBtn.click();
                    buildPackages();
                    const pkgBtn = form.querySelector(`[data-pick="package"][data-value="${preset}"]`);
                    if (pkgBtn) pkgBtn.click();
                    showStep(2);
                }
            }

            recalc();
        })();
        </script>
    @endpush

</x-app-layout>
