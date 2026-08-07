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

    /* Ek modüller. `types` modülün hangi proje türlerinde anlamlı olduğunu söyler;
       3. adım buna göre süzülür (mobil uygulama projesinde "katalog modülü" ya da
       "iOS + Android uygulama" göstermek saçma olurdu).
       `price` null olanlar liste fiyatı olmayan türlere aittir: seçilebilir ama
       tutara girmez, kapsamı anlatmaya yarar. Fiyatlar ₺ ve KDV hariçtir. */
    $extras = [
        ['key' => 'katalog',     'price' => 5000,  'types' => ['tanitim', 'kurumsal', 'yenileme'],
            'label' => $isEn ? 'Catalogue module (WhatsApp orders)' : 'Katalog modülü (WhatsApp sipariş)'],
        ['key' => 'dil',         'price' => 4000,  'types' => ['tanitim', 'kurumsal', 'eticaret', 'yenileme'],
            'label' => $isEn ? 'Second language (TR / EN)' : 'İkinci dil (TR / EN)'],
        ['key' => 'blog',        'price' => 3500,  'types' => ['tanitim', 'kurumsal', 'eticaret', 'yenileme'],
            'label' => $isEn ? 'Blog / news module' : 'Blog / haber modülü'],
        ['key' => 'rezervasyon', 'price' => 12000, 'types' => ['kurumsal', 'yenileme'],
            'label' => $isEn ? 'Booking / appointment system' : 'Rezervasyon / randevu sistemi'],
        ['key' => 'uyelik',      'price' => 15000, 'types' => ['kurumsal', 'eticaret', 'yenileme'],
            'label' => $isEn ? 'Membership / customer portal' : 'Üyelik / müşteri portalı'],
        ['key' => 'seo',         'price' => 6000,  'types' => ['tanitim', 'kurumsal', 'eticaret', 'yenileme'],
            'label' => $isEn ? 'SEO content package (5 pages)' : 'SEO içerik paketi (5 sayfa)'],
        ['key' => 'kimlik',      'price' => 9000,  'types' => ['tanitim', 'kurumsal', 'eticaret', 'yenileme'],
            'label' => $isEn ? 'Logo and brand identity' : 'Logo ve marka kimliği'],
        ['key' => 'mobilapp',    'price' => 65000, 'types' => ['kurumsal', 'eticaret', 'yenileme'],
            'label' => $isEn ? 'iOS + Android app' : 'iOS + Android uygulama'],

        /* Mobil uygulama projeleri — fiyatsız kapsam maddeleri. */
        ['key' => 'push',        'price' => null, 'types' => ['mobil'],
            'label' => $isEn ? 'Push notifications' : 'Push bildirim'],
        ['key' => 'uygulama-uyelik', 'price' => null, 'types' => ['mobil'],
            'label' => $isEn ? 'Login and user accounts' : 'Giriş ve üyelik'],
        ['key' => 'magaza-yayin', 'price' => null, 'types' => ['mobil'],
            'label' => $isEn ? 'App Store + Google Play submission' : 'App Store + Google Play yayını'],
        ['key' => 'uygulama-odeme', 'price' => null, 'types' => ['mobil'],
            'label' => $isEn ? 'In-app payment' : 'Uygulama içi ödeme'],

        /* Özel yazılım projeleri — fiyatsız kapsam maddeleri. */
        ['key' => 'rol-yetki',   'price' => null, 'types' => ['yazilim'],
            'label' => $isEn ? 'Role-based permissions' : 'Rol bazlı yetkilendirme'],
        ['key' => 'raporlama',   'price' => null, 'types' => ['yazilim'],
            'label' => $isEn ? 'Reporting screens' : 'Raporlama ekranları'],
        ['key' => 'entegrasyon', 'price' => null, 'types' => ['yazilim', 'mobil'],
            'label' => $isEn ? 'Integration with an existing system' : 'Mevcut sisteme entegrasyon'],
        ['key' => 'api',         'price' => null, 'types' => ['yazilim'],
            'label' => $isEn ? 'API for third parties' : 'Dışarıya API'],
    ];

    /* Süre yalnızca planlama bilgisidir; fiyata etki etmez. Liste fiyatları sabit,
       uydurma bir "hızlandırma farkı" çarpanı toplamı belirsizleştirirdi. */
    $timelines = [
        ['key' => 'acil',   'label' => $isEn ? 'As soon as possible' : 'En kısa sürede', 'note' => $isEn ? 'We check capacity for you.' : 'Takvimde yer var mı bakarız.'],
        ['key' => 'normal', 'label' => $isEn ? 'Within 1 month' : '1 ay içinde',         'note' => ''],
        ['key' => 'esnek',  'label' => $isEn ? 'Flexible' : 'Esnek',                     'note' => $isEn ? 'No date pressure.' : 'Tarih baskısı yok.'],
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
        'noteFixed' => $isEn
            ? 'Sum of listed campaign prices, VAT excluded. Hosting, domain and SSL are included for the first year.'
            : 'Kampanya liste fiyatlarının toplamıdır, KDV hariç. Hosting, domain ve SSL ilk yıl dahildir.',
        'noteOpen' => $isEn
            ? 'This type has no list price. We scope it on a call and send a fixed proposal within 24 hours.'
            : 'Bu tür için liste fiyatı yok. Kapsamı görüşmede netleştirip 24 saat içinde sabit fiyatlı teklif gönderiyoruz.',
    ];

    /* 2. ve 3. adımın içeriği 1. adımdaki seçime göre değişir.
       `packages` aşağıda veritabanından doldurulur (paketin `project_types`
       alanına göre) — burada slug listesi GÖMÜLÜ DEĞİL, yoksa panelden eklenen
       yeni bir paket sihirbazda hiç görünmezdi.
       `options` ise liste fiyatı olmayan türlerde (mobil uygulama, özel yazılım)
       kapsam sorusunun şıklarını taşır; böylece hiçbir türde adım boş kalmaz. */
    $typeSteps = [
        'tanitim' => [
            'step2' => $isEn ? 'Which package?' : 'Hangi paket?',
            'step3' => $isEn ? 'Add-on modules' : 'Ek modüller',
            'options' => [],
        ],
        'kurumsal' => [
            'step2' => $isEn ? 'Which package?' : 'Hangi paket?',
            'step3' => $isEn ? 'Add-on modules' : 'Ek modüller',
            'options' => [],
        ],
        'eticaret' => [
            'step2' => $isEn ? 'Which store package?' : 'Hangi mağaza paketi?',
            'step3' => $isEn ? 'Add-on modules' : 'Ek modüller',
            'options' => [],
        ],
        'yenileme' => [
            'step2' => $isEn ? 'What scale is the new site?' : 'Yenilenen site hangi ölçekte olacak?',
            'step3' => $isEn ? 'Add-on modules' : 'Ek modüller',
            'options' => [],
        ],
        'mobil' => [
            'step2' => $isEn ? 'What will the app do?' : 'Uygulama ne yapacak?',
            'step3' => $isEn ? 'What should it include?' : 'Neler olsun?',
            'options' => [
                ['key' => 'mobil-vitrin', 'label' => $isEn ? 'Showcase / catalogue app' : 'Tanıtım / katalog uygulaması',
                    'note' => $isEn ? 'Shows content, takes no orders.' : 'İçerik gösterir, sipariş almaz.'],
                ['key' => 'mobil-siparis', 'label' => $isEn ? 'Ordering / booking app' : 'Sipariş / rezervasyon uygulaması',
                    'note' => $isEn ? 'Users transact inside the app.' : 'Kullanıcı uygulamadan işlem yapar.'],
                ['key' => 'mobil-uyelik', 'label' => $isEn ? 'Membership app' : 'Üyelik uygulaması',
                    'note' => $isEn ? 'Login, profile, notifications.' : 'Giriş, profil, bildirim.'],
                ['key' => 'mobil-entegrasyon', 'label' => $isEn ? 'Connects to our existing system' : 'Mevcut sistemimize bağlanacak',
                    'note' => $isEn ? 'You already have a site or panel.' : 'Sitemiz ya da panelimiz var.'],
            ],
        ],
        'yazilim' => [
            'step2' => $isEn ? 'What kind of system?' : 'Ne tür bir sistem?',
            'step3' => $isEn ? 'What should it include?' : 'Neler olsun?',
            'options' => [
                ['key' => 'yazilim-rezervasyon', 'label' => $isEn ? 'Booking / appointment system' : 'Rezervasyon / randevu sistemi',
                    'note' => $isEn ? 'Calendar, slots, confirmations.' : 'Takvim, slot, onay akışı.'],
                ['key' => 'yazilim-portal', 'label' => $isEn ? 'Dealer / customer portal' : 'Bayi / müşteri portalı',
                    'note' => $isEn ? 'Outside users log in and self-serve.' : 'Dışarıdaki kullanıcılar giriş yapar.'],
                ['key' => 'yazilim-operasyon', 'label' => $isEn ? 'Internal operations panel' : 'İç operasyon paneli',
                    'note' => $isEn ? 'Your team runs daily work on it.' : 'Ekibiniz günlük işi buradan yürütür.'],
                ['key' => 'yazilim-api', 'label' => $isEn ? 'API for an existing system' : 'Mevcut sisteme API bağlantısı',
                    'note' => $isEn ? 'Two systems need to talk.' : 'İki sistem birbiriyle konuşacak.'],
            ],
        ],
    ];

    /* Her türün paket listesi veritabanından: paketin `project_types` alanı.
       Panelden yeni paket eklendiğinde ya da eşleme değiştiğinde sihirbaz
       kendiliğinden güncellenir — kodda dokunulacak yer yok. */
    foreach ($typeSteps as $typeKey => $step) {
        $typeSteps[$typeKey]['packages'] = $packages
            ->filter(fn ($p) => in_array($typeKey, (array) $p->project_types, true))
            ->pluck('slug')
            ->values()
            ->all();
    }

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
        ? 'Answer five short questions and see the exact total from our listed prices. We send a scoped proposal within 24 hours.'
        : 'Beş kısa soruyu yanıtlayın, liste fiyatlarından kesin toplamı görün. Kapsamı netleştiren teklifi 24 saat içinde gönderiyoruz.'">

    {{-- Dikkat: bileşen attribute'u çift tırnakla sınırlıdır; metin içinde düz "
         kullanma, attribute'u erken kapatıp tüm sayfayı bozar. Tipografik “…” güvenli. --}}
    <x-page-hero :eyebrow="__('site.nav.quote')"
                 :lead="$isEn
                    ? 'Five short steps. Every package and module has a listed price, so the total adds up as you choose — no guesswork, no “contact us for pricing”.'
                    : 'Beş kısa adım. Her paketin ve modülün fiyatı listede yazılı; seçtikçe toplam kendiliğinden çıkıyor — tahmin yok, “fiyat için arayın” yok.'">
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

                    {{-- 2 · Paket / kapsam — içerik 1. adımdaki türe göre kurulur --}}
                    <fieldset class="k-step" data-step="2">
                        <legend class="k-display-xs mb-7" data-step2-title>{{ __('site.quote.step2') }}</legend>
                        <div class="grid grid-cols-1 gap-3" data-package-list></div>
                        <input type="hidden" name="package" data-field="package">
                    </fieldset>

                    {{-- 3 · Ek modüller — türe uymayanlar gizlenir --}}
                    <fieldset class="k-step" data-step="3">
                        <legend class="k-display-xs mb-2" data-step3-title>{{ __('site.quote.step3') }}</legend>
                        <p class="mb-7 text-sm text-[#0F0F0F]/55">
                            {{ $isEn ? 'Optional — pick any that apply.' : 'İsteğe bağlı — uygun olanları işaretleyin.' }}
                        </p>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            @foreach ($extras as $extra)
                                <button type="button" class="k-choice" data-toggle="extras" data-value="{{ $extra['key'] }}"
                                        data-types="{{ implode(',', $extra['types']) }}"
                                        data-price="{{ $extra['price'] ?? '' }}" data-label="{{ $extra['label'] }}" hidden>
                                    <span class="block pr-7 font-bold tracking-tight">{{ $extra['label'] }}</span>
                                    @if ($extra['price'])
                                        <span class="mt-1 block text-sm text-[#E30613]">+{{ number_format($extra['price'], 0, ',', '.') }} ₺</span>
                                    @endif
                                    <span class="k-choice__check" aria-hidden="true">
                                        <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4l2.5 2.5L9 1" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </span>
                                </button>
                            @endforeach
                        </div>
                        <p class="mt-5 text-sm text-[#0F0F0F]/45" data-extras-empty hidden>
                            {{ $isEn ? 'Nothing to add for this type — continue.' : 'Bu tür için ek modül yok, devam edebilirsiniz.' }}
                        </p>
                        <div data-extras-inputs></div>
                    </fieldset>

                    {{-- 4 · Süre ve bütçe --}}
                    <fieldset class="k-step" data-step="4">
                        <legend class="k-display-xs mb-7">{{ __('site.quote.step4') }}</legend>

                        <p class="k-field-label mb-3">{{ $isEn ? 'When do you need it?' : 'Ne zaman lazım?' }}</p>
                        <div class="mb-9 grid grid-cols-1 gap-3 sm:grid-cols-3">
                            @foreach ($timelines as $timeline)
                                <button type="button" class="k-choice" data-pick="timeline"
                                        data-value="{{ $timeline['key'] }}">
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

                        <input type="hidden" name="quote_total" data-field="quote_total">
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

                {{-- ── Canlı özet — kalem kalem tutar, altında kesin toplam ── --}}
                <aside class="lg:col-span-4 lg:col-start-9">
                    <div class="sticky top-28 rounded-2xl border border-[#0F0F0F]/10 bg-[#F4F4F2] p-7">
                        <p class="k-eyebrow mb-5 text-[#0F0F0F]/45">{{ __('site.quote.summary') }}</p>

                        <ul class="space-y-3 text-sm" data-summary>
                            <li class="text-[#0F0F0F]/40">{{ $isEn ? 'Nothing selected yet.' : 'Henüz seçim yapılmadı.' }}</li>
                        </ul>

                        <div class="k-rule my-6"></div>

                        <div class="flex items-baseline justify-between gap-4">
                            <p class="k-eyebrow text-[#0F0F0F]/45">{{ __('site.quote.total') }}</p>
                            <p class="text-right text-2xl font-black leading-none tracking-tight" data-total>—</p>
                        </div>
                        <p class="mt-1.5 text-right text-xs text-[#0F0F0F]/45" data-total-vat hidden>+ KDV</p>

                        <p class="mt-6 text-xs leading-relaxed text-[#0F0F0F]/50" data-total-note>
                            {{ __('site.quote.total_note_open') }}
                        </p>
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
            const TYPE_STEPS = @json($typeSteps);

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
            const extraButtons = Array.from(form.querySelectorAll('[data-toggle="extras"]'));
            const extrasEmptyEl = form.querySelector('[data-extras-empty]');
            const step2TitleEl = form.querySelector('[data-step2-title]');
            const step3TitleEl = form.querySelector('[data-step3-title]');
            const totalEl = form.querySelector('[data-total]');
            const totalVatEl = form.querySelector('[data-total-vat]');
            const totalNoteEl = form.querySelector('[data-total-note]');
            const summaryEl = form.querySelector('[data-summary]');

            const state = {
                step: 1,
                project_type: '',
                projectTypeLabel: '',
                package: '',
                packageLabel: '',
                panel: false,
                extras: [],          // [{key, label, price}]
                timeline: '',
                timelineLabel: '',
                budget: '',
                budgetLabel: '',
            };

            // Liste fiyatları sabit — yuvarlama YOK, gösterilen tutar birebir toplam.
            const money = (n) => new Intl.NumberFormat('tr-TR').format(n) + ' ₺';

            /* ── Adım gösterimi ──────────────────────────────────────── */

            /** İlerleme çubuğundaki etiket, 2. ve 3. adımda türe göre değişir. */
            function stepLabel(n) {
                const cfg = TYPE_STEPS[state.project_type];
                if (cfg && n === 2 && cfg.step2) return cfg.step2;
                if (cfg && n === 3 && cfg.step3) return cfg.step3;
                return STEP_TITLES[n - 1];
            }

            function showStep(n) {
                state.step = Math.min(Math.max(n, 1), steps.length);
                steps.forEach((s) => s.classList.toggle('is-active', Number(s.dataset.step) === state.step));
                bar.style.width = (state.step / steps.length * 100) + '%';
                stepCurrent.textContent = state.step;
                stepTitle.textContent = stepLabel(state.step);
                btnPrev.hidden = state.step === 1;
                btnNext.hidden = state.step === steps.length;
                btnSubmit.hidden = state.step !== steps.length;
                errorEl.hidden = true;

                const box = form.getBoundingClientRect();
                if (box.top < 0) window.scrollTo({ top: window.scrollY + box.top - 110, behavior: 'smooth' });
            }

            /* ── 2. adımı seçilen türe göre kur ─────────────────────────
               Liste fiyatı olan türlerde paketler, olmayanlarda (mobil uygulama,
               özel yazılım) fiyatsız kapsam şıkları gösterilir. */
            function buildStep2() {
                const cfg = TYPE_STEPS[state.project_type] || { packages: [], options: [] };
                const list = PACKAGES.filter((p) => cfg.packages.includes(p.slug));
                packageList.innerHTML = '';

                if (cfg.step2) step2TitleEl.textContent = cfg.step2;
                if (cfg.step3) step3TitleEl.textContent = cfg.step3;

                // Fiyatsız kapsam şıkları (mobil / özel yazılım)
                (cfg.options || []).forEach((o) => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'k-choice';
                    btn.dataset.pick = 'package';
                    btn.dataset.value = o.key;
                    btn.dataset.label = o.label;
                    btn.innerHTML = `
                        <span class="block pr-7 font-bold tracking-tight">${escape(o.label)}</span>
                        <span class="mt-1 block text-sm text-[#0F0F0F]/55">${escape(o.note || '')}</span>
                        <span class="k-choice__check" aria-hidden="true">
                            <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4l2.5 2.5L9 1" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>`;
                    packageList.appendChild(btn);
                });

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

                // "Emin değilim" her türde geçerli bir cevap.
                const unsure = document.createElement('button');
                unsure.type = 'button';
                unsure.className = 'k-choice';
                unsure.dataset.pick = 'package';
                unsure.dataset.value = 'belirsiz';
                unsure.dataset.label = L.noPackage;
                unsure.innerHTML = `
                    <span class="block pr-7 font-bold tracking-tight">${escape(L.noPackage)}</span>
                    <span class="mt-1 block text-sm text-[#0F0F0F]/55">${escape(L.noPackageNote)}</span>
                    <span class="k-choice__check" aria-hidden="true">
                        <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4l2.5 2.5L9 1" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>`;
                packageList.appendChild(unsure);
            }

            /* ── 3. adımı türe göre süz ─────────────────────────────── */
            function buildExtras() {
                let visible = 0;

                extraButtons.forEach((btn) => {
                    const types = (btn.dataset.types || '').split(',');
                    const show = types.includes(state.project_type);
                    btn.hidden = !show;
                    if (show) {
                        visible++;
                    } else if (btn.classList.contains('is-picked')) {
                        // Tür değişince artık geçerli olmayan modülleri seçimden düşür.
                        btn.classList.remove('is-picked');
                        const i = state.extras.findIndex((x) => x.key === btn.dataset.value);
                        if (i >= 0) state.extras.splice(i, 1);
                    }
                });

                extrasEmptyEl.hidden = visible > 0;
                syncExtrasInputs();
            }

            function syncExtrasInputs() {
                extrasInputs.innerHTML = state.extras
                    .map((x) => `<input type="hidden" name="extras[]" value="${escape(x.key)}">`).join('');
            }

            /* ── Toplam ve özet ─────────────────────────────────────── */

            /** Seçili paketin liste fiyatı (panelli seçildiyse panelli tutar). */
            function packagePrice() {
                const p = PACKAGES.find((x) => x.slug === state.package);
                if (!p) return null;
                return state.panel && p.pricePanel ? p.pricePanel : p.price;
            }

            const escape = (s) => String(s).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));

            function row(label, amount, muted = false) {
                const right = amount === null
                    ? ''
                    : `<span class="shrink-0 whitespace-nowrap font-semibold">${escape(money(amount))}</span>`;
                return `<li class="flex items-baseline justify-between gap-4 ${muted ? 'text-[#0F0F0F]/45' : 'text-[#0F0F0F]/80'}">
                    <span>${escape(label)}</span>${right}
                </li>`;
            }

            function recalc() {
                const base = packagePrice();
                const extrasSum = state.extras.reduce((sum, e) => sum + e.price, 0);
                const totalInput = form.querySelector('[data-field="quote_total"]');

                /* Paket seçilmediyse (ya da "emin değilim" / mobil / özel yazılım gibi
                   liste fiyatı olmayan bir tür seçildiyse) tutar yazılmaz. Yalnız ek
                   modülleri toplayıp fiyat vermek yanıltıcı olurdu. */
                if (base === null) {
                    totalEl.textContent = L.onRequest;
                    totalEl.classList.remove('text-2xl');
                    totalEl.classList.add('text-lg');
                    totalVatEl.hidden = true;
                    totalNoteEl.textContent = L.noteOpen;
                    totalInput.value = '';
                } else {
                    const total = base + extrasSum;
                    totalEl.textContent = money(total);
                    totalEl.classList.remove('text-lg');
                    totalEl.classList.add('text-2xl');
                    totalVatEl.hidden = false;
                    totalNoteEl.textContent = L.noteFixed;
                    totalInput.value = total;
                }

                /* Özet: önce tutarı olan kalemler, sonra bilgi amaçlı seçimler.
                   Fiyatsız kapsam maddeleri "0 ₺" olarak değil, soluk satır olarak. */
                const priced = [];
                if (base !== null) {
                    priced.push(row(state.packageLabel + (state.panel ? ' — ' + L.withPanel : ''), base));
                }
                state.extras.filter((e) => e.price > 0).forEach((e) => priced.push(row(e.label, e.price)));

                const context = [];
                if (state.projectTypeLabel) context.push(row(state.projectTypeLabel, null, true));
                if (base === null && state.packageLabel) context.push(row(state.packageLabel, null, true));
                state.extras.filter((e) => !e.price).forEach((e) => context.push(row(e.label, null, true)));
                if (state.timelineLabel) context.push(row(state.timelineLabel, null, true));
                if (state.budgetLabel) context.push(row(state.budgetLabel, null, true));

                const all = [...priced, ...context];
                summaryEl.innerHTML = all.length ? all.join('') : `<li class="text-[#0F0F0F]/40">${L.empty}</li>`;
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
                        state.projectTypeLabel = pick.querySelector('span').textContent.trim();
                        // Tür değişti: 2. adım baştan kurulur, 3. adım yeniden süzülür.
                        state.package = ''; state.packageLabel = ''; state.panel = false;
                        form.querySelector('[data-field="package"]').value = '';
                        buildStep2();
                        buildExtras();
                    } else if (group === 'package') {
                        state.package = pick.dataset.value || '';
                        state.packageLabel = pick.dataset.label || '';
                        state.panel = false;
                        form.querySelectorAll('[data-panel-for]').forEach((cb) => { cb.checked = false; });
                    } else if (group === 'timeline') {
                        state.timeline = pick.dataset.value;
                        state.timelineLabel = pick.querySelector('span').textContent.trim();
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
                        // Fiyatsız kapsam maddelerinde data-price boştur → 0, tutara girmez.
                        state.extras.push({
                            key,
                            label: toggle.dataset.label,
                            price: Number(toggle.dataset.price) || 0,
                        });
                        toggle.classList.add('is-picked');
                    }
                    syncExtrasInputs();
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
                if (step === 2 && !state.package) return L.required;
                if (step === 4 && !state.timeline) return L.required;
                return null;
            }

            btnNext.addEventListener('click', () => {
                const problem = validate(state.step);
                if (problem) { errorEl.textContent = problem; errorEl.hidden = false; return; }
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
                    if (typeBtn) typeBtn.click();   // buildStep2 + buildExtras'ı da tetikler
                    const pkgBtn = form.querySelector(`[data-pick="package"][data-value="${preset}"]`);
                    if (pkgBtn) pkgBtn.click();
                    showStep(2);
                }
            }

            buildExtras();   // tür seçilmeden hiçbir modül görünmesin
            recalc();
        })();
        </script>
    @endpush

</x-app-layout>
