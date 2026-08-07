@php
    /**
     * 404 — hatalı adres, kayıp ziyaretçi.
     *
     * Bir 404'ün SEO'daki görevi ziyaretçiyi siteden atmamak: doğru dilde
     * konuşmak, doğru yere yönlendirmek. Rota eşleşmediği için `locale`
     * middleware çalışmamış olabilir; dili adresin kendisinden okuyoruz.
     */
    $isEn = str_starts_with(trim(request()->path(), '/'), 'en');
    app()->setLocale($isEn ? 'en' : 'tr');

    $prefix = $isEn ? 'en.' : '';
    $to = fn (string $name) => route($prefix.$name);

    $suggestions = [
        ['name' => 'services.index', 'label' => $isEn ? 'Services' : 'Hizmetler'],
        ['name' => 'packages', 'label' => $isEn ? 'Pricing' : 'Paketler'],
        ['name' => 'works.index', 'label' => $isEn ? 'Work' : 'İşler'],
        ['name' => 'locations.index', 'label' => $isEn ? 'Where we work' : 'Hizmet bölgeleri'],
        ['name' => 'blog.index', 'label' => $isEn ? 'Insights' : 'Blog'],
        ['name' => 'contact', 'label' => $isEn ? 'Contact' : 'İletişim'],
    ];
@endphp

<x-app-layout
    robots="noindex, follow"
    :seo-title="$isEn ? 'Page not found' : 'Sayfa bulunamadı'"
    :seo-description="$isEn
        ? 'This address does not exist. Here is where you can go instead.'
        : 'Bu adres yok. Bunun yerine gidebileceğiniz yerler burada.'">

    <section class="relative overflow-hidden bg-[#F1F1EF] px-6 pb-20 pt-36 lg:px-12 lg:pb-28 lg:pt-48" data-dots>
        <div class="relative mx-auto max-w-[1280px]">
            <p class="k-eyebrow k-reveal mb-6 text-[#0F0F0F]/50">404</p>

            <h1 class="k-display-sm text-[#0F0F0F]" data-split data-split-step="0.05">
                {{ $isEn ? 'This page' : 'Bu sayfa' }} <span class="k-hl">{{ $isEn ? 'moved on.' : 'burada değil.' }}</span>
            </h1>

            <p class="k-lead k-reveal mt-7 max-w-2xl text-[#0F0F0F]/70" data-delay="200">
                {{ $isEn
                    ? 'The address you followed does not exist — a broken link, an old URL, or a typo. Pick a direction below and carry on.'
                    : 'Girdiğiniz adres yok — kırık bir bağlantı, eski bir adres ya da bir yazım hatası. Aşağıdan devam edebilirsiniz.' }}
            </p>

            <div class="k-reveal mt-9 flex flex-wrap gap-4" data-delay="300">
                <a href="{{ $to('home') }}" data-cursor="cta" data-magnetic="0.3" class="k-btn k-btn--brand">
                    <span style="color:inherit;">{{ $isEn ? 'Back to home' : 'Ana sayfaya dön' }}</span>
                    <span class="k-btn__arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ $to('quote') }}" class="k-btn k-btn--ghost">
                    <span>{{ $isEn ? 'Get a quote' : 'Teklif al' }}</span>
                </a>
            </div>
        </div>
    </section>

    <section class="bg-white px-6 py-16 lg:px-12 lg:py-24">
        <div class="mx-auto max-w-[1280px]">
            <p class="k-eyebrow k-reveal mb-8 text-[#0F0F0F]/45">
                {{ $isEn ? 'Try one of these' : 'Şuralara bakabilirsiniz' }}
            </p>

            <ul class="grid grid-cols-1 gap-x-8 md:grid-cols-2">
                @foreach ($suggestions as $i => $item)
                    <li class="k-reveal border-t border-[#0F0F0F]/12 last:border-b md:[&:nth-last-child(2)]:border-b"
                        data-delay="{{ min(($i % 2 + 1) * 100, 200) }}">
                        <a href="{{ $to($item['name']) }}"
                           class="k-row group flex items-baseline justify-between gap-5 py-5 hover:text-[#E30613]">
                            <span class="k-display-xs">{{ $item['label'] }}</span>
                            <span class="k-row__arrow shrink-0 text-2xl" aria-hidden="true">→</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

</x-app-layout>
