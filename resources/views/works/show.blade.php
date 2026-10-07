@php
    $isEn = app()->getLocale() === 'en';
    $gallery = collect($work->gallery ?? [])->filter();
@endphp

<x-app-layout
    :seo-title="$work->t('title')"
    :seo-description="$work->t('summary')"
    og-type="article"
    :og-image="$work->cover ? asset('storage/'.$work->cover) : null">

    @push('jsonld')
        <script type="application/ld+json">
            @php
                echo json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => __('site.nav.home'), 'item' => $r('home')],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => __('site.nav.works'), 'item' => $r('works.index')],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $work->t('title'), 'item' => url()->current()],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            @endphp
        </script>
    @endpush

    <x-page-hero :eyebrow="collect([$work->category?->t('name'), $work->year])->filter()->join(' · ')"
                 :lead="$work->t('summary')">
        {{ $work->t('title') }}
    </x-page-hero>

    {{-- Kapak — scroll'la maskesi açılır --}}
    @if ($work->cover)
        <div class="bg-[#F1F1EF] px-6 lg:px-12">
            <div class="mx-auto max-w-[1280px]">
                <div data-unmask class="aspect-[16/9] overflow-hidden rounded-lg">
                    <img src="{{ asset('storage/'.$work->cover) }}" alt="{{ $work->t('title') }}"
                         class="h-full w-full object-cover">
                </div>
            </div>
        </div>
    @endif

    {{-- Künye --}}
    <section class="bg-[#F1F1EF] px-6 pb-16 pt-14 lg:px-12 lg:pb-24">
        <div class="mx-auto max-w-[1280px]">
            <dl class="grid grid-cols-2 gap-x-8 gap-y-8 md:grid-cols-4">
                @if ($work->client)
                    <div class="k-reveal">
                        <dt class="k-eyebrow mb-2 text-[#0F0F0F]/45">{{ __('site.common.client') }}</dt>
                        <dd class="font-bold">{{ $work->client }}</dd>
                    </div>
                @endif
                @if ($work->year)
                    <div class="k-reveal" data-delay="100">
                        <dt class="k-eyebrow mb-2 text-[#0F0F0F]/45">{{ __('site.common.year') }}</dt>
                        <dd class="font-bold">{{ $work->year }}</dd>
                    </div>
                @endif
                @if (filled($work->tags))
                    <div class="k-reveal col-span-2" data-delay="200">
                        <dt class="k-eyebrow mb-2 text-[#0F0F0F]/45">{{ __('site.common.services_used') }}</dt>
                        <dd class="flex flex-wrap gap-2">
                            @foreach ((array) $work->tags as $tag)
                                <span class="k-tag">{{ $tag }}</span>
                            @endforeach
                        </dd>
                    </div>
                @endif
            </dl>

            @if ($work->external_url)
                <a href="{{ $work->external_url }}" target="_blank" rel="noopener"
                   class="k-reveal k-btn k-btn--ink mt-10" data-delay="300">
                    <span style="color:inherit;">{{ __('site.common.visit_site') }}</span>
                    <span class="k-btn__arrow" aria-hidden="true">↗</span>
                </a>
            @endif
        </div>
    </section>

    {{-- Vaka metni --}}
    @if (filled($work->t('body')))
        <section class="bg-white px-6 py-16 lg:px-12 lg:py-24">
            <div class="mx-auto max-w-[760px]">
                <div class="k-article k-reveal">{!! $work->t('body') !!}</div>
            </div>
        </section>
    @endif

    {{-- Galeri --}}
    @if ($gallery->isNotEmpty())
        <section class="bg-white px-6 pb-16 lg:px-12 lg:pb-24">
            <div class="mx-auto grid max-w-[1280px] grid-cols-1 gap-6 md:grid-cols-2">
                @foreach ($gallery as $image)
                    <div data-unmask class="aspect-[4/3] overflow-hidden rounded-lg bg-[#F1F1EF]">
                        <img src="{{ asset('storage/'.$image) }}" alt="" loading="lazy" class="h-full w-full object-cover">
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Sıradaki proje --}}
    @if ($next)
        <section class="border-t border-[#0F0F0F]/10 bg-[#F4F4F2] px-6 py-16 lg:px-12 lg:py-24">
            <div class="mx-auto max-w-[1280px]">
                <p class="k-eyebrow k-reveal mb-6 text-[#0F0F0F]/45">{{ __('site.common.next_project') }}</p>
                <a href="{{ $r('works.show', ['work' => $next->slug]) }}"
                   data-cursor="cta" data-cursor-label="{{ __('site.common.view_project') }}"
                   class="k-row group flex flex-wrap items-center justify-between gap-6 hover:text-[#E30613]">
                    <h2 class="k-display-sm">{{ $next->t('title') }}</h2>
                    <span class="k-row__arrow text-4xl" aria-hidden="true">→</span>
                </a>

                <div class="mt-10">
                    <a href="{{ $r('works.index') }}" class="k-btn k-btn--ghost">
                        <span>{{ __('site.common.back_to_works') }}</span>
                    </a>
                </div>
            </div>
        </section>
    @endif

</x-app-layout>
