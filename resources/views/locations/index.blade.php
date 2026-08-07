@php
    $isEn = app()->getLocale() === 'en';
    $regionTitles = $isEn
        ? ['kktc' => 'North Cyprus', 'turkiye' => 'Türkiye']
        : ['kktc' => 'Kuzey Kıbrıs', 'turkiye' => 'Türkiye'];
@endphp

<x-app-layout
    :seo-title="$isEn ? 'Where we work' : 'Hizmet verdiğimiz şehirler'"
    :seo-description="$isEn
        ? 'Web design, e-commerce and custom software across North Cyprus and Türkiye. Nicosia, Kyrenia, Famagusta, Istanbul, Ankara, Izmir, Antalya and more.'
        : 'Kuzey Kıbrıs ve Türkiye genelinde web tasarım, e-ticaret ve özel yazılım. Lefkoşa, Girne, Gazimağusa, İstanbul, Ankara, İzmir, Antalya ve daha fazlası.'">

    @push('jsonld')
        {{-- Şehir sayfalarını tek bir liste olarak bildiriyoruz; tarayıcı
             sayfalar arasındaki ilişkiyi ve sırayı böyle görüyor. --}}
        <script type="application/ld+json">
            @php
                $items = [];
                $position = 0;
                foreach ($locations as $group) {
                    foreach ($group as $item) {
                        $items[] = [
                            '@type' => 'ListItem',
                            'position' => ++$position,
                            'name' => $item->t('name'),
                            'url' => $r('locations.show', ['location' => $item->slug]),
                        ];
                    }
                }

                echo json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'ItemList',
                    'name' => $isEn ? 'Cities we work in' : 'Hizmet verdiğimiz şehirler',
                    'itemListElement' => $items,
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            @endphp
        </script>
    @endpush

    <x-page-hero :eyebrow="$isEn ? 'Coverage' : 'Hizmet bölgeleri'"
                 :lead="$isEn
                    ? 'We are based in North Cyprus and work remotely across Türkiye. Every market has its own habits — pick your city to see what usually matters there.'
                    : 'Merkezimiz Kuzey Kıbrıs; Türkiye genelinde uzaktan çalışıyoruz. Her pazarın kendi alışkanlığı var — şehrinizi seçin, orada neyin işe yaradığını yazdık.'">
        {{ $isEn ? 'Where we' : 'Nerelerde' }} <span class="k-hl">{{ $isEn ? 'work.' : 'çalışıyoruz.' }}</span>
    </x-page-hero>

    @foreach ($locations as $region => $group)
        <section class="{{ $loop->even ? 'bg-[#F4F4F2]' : 'bg-white' }} px-6 py-16 lg:px-12 lg:py-24">
            <div class="mx-auto max-w-[1280px]">
                <p class="k-eyebrow k-reveal mb-10 text-[#0F0F0F]/45">{{ $regionTitles[$region] ?? $region }}</p>

                <ul class="grid grid-cols-1 gap-x-8 gap-y-0 md:grid-cols-2">
                    @foreach ($group as $i => $location)
                        <li class="k-reveal border-t border-[#0F0F0F]/12 last:border-b md:[&:nth-last-child(2)]:border-b"
                            data-delay="{{ min(($i % 2 + 1) * 100, 200) }}">
                            <a href="{{ $r('locations.show', ['location' => $location->slug]) }}"
                               class="k-row group flex items-baseline justify-between gap-5 py-6 hover:text-[#E30613]">
                                <span class="min-w-0">
                                    <span class="k-display-xs block">{{ $location->t('name') }}</span>
                                    @if ($location->t('intro'))
                                        <span class="mt-2 block max-w-lg text-sm leading-relaxed text-[#0F0F0F]/55">
                                            {{ Str::limit($location->t('intro'), 110) }}
                                        </span>
                                    @endif
                                </span>
                                <span class="k-row__arrow shrink-0 text-2xl" aria-hidden="true">→</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endforeach

</x-app-layout>
