@php
    $isEn = app()->getLocale() === 'en';
@endphp

<x-app-layout
    :seo-title="$isEn ? 'Work' : 'İşler'"
    :seo-description="$isEn
        ? 'Selected websites, stores and web applications we designed and built.'
        : 'Tasarlayıp geliştirdiğimiz seçili web siteleri, mağazalar ve web uygulamaları.'">

    <x-page-hero :eyebrow="__('site.nav.works')"
                 :lead="$isEn
                    ? 'A short list, on purpose. Each one is a real business problem solved with a website.'
                    : 'Bilerek kısa bir liste. Her biri, bir web sitesiyle çözülmüş gerçek bir iş problemi.'">
        {{ $isEn ? 'Selected' : 'Seçili' }} <span class="italic font-light">{{ $isEn ? 'work.' : 'işler.' }}</span>
    </x-page-hero>

    <section class="bg-white px-6 py-14 lg:px-12 lg:py-20">
        <div class="mx-auto max-w-[1280px]">

            @if ($categories->isNotEmpty())
                <div class="k-noscroll mb-12 flex gap-2 overflow-x-auto pb-1">
                    <a href="{{ $r('works.index') }}"
                       class="k-btn shrink-0 !px-5 !py-2.5 !text-[0.66rem] {{ $activeCategory ? 'k-btn--ghost' : 'k-btn--ink' }}">
                        <span style="color:inherit;">{{ __('site.common.all') }}</span>
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ $r('works.index') }}?kategori={{ $category->slug }}"
                           class="k-btn shrink-0 !px-5 !py-2.5 !text-[0.66rem] {{ $activeCategory === $category->slug ? 'k-btn--ink' : 'k-btn--ghost' }}">
                            <span style="color:inherit;">{{ $category->t('name') }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($works->isEmpty())
                <p class="py-10 text-[#0F0F0F]/50">{{ __('site.common.empty') }}</p>
            @else
                {{-- Kademeli ızgara: tek numaralı kartlar aşağı kaydırılır --}}
                <div class="grid grid-cols-1 gap-x-8 gap-y-14 md:grid-cols-2">
                    @foreach ($works as $i => $work)
                        <a href="{{ $r('works.show', ['work' => $work->slug]) }}"
                           data-cursor="cta" data-cursor-label="{{ __('site.common.view_project') }}"
                           class="k-reveal group block {{ $i % 2 ? 'md:mt-20' : '' }}"
                           data-delay="{{ min(($i % 2 + 1) * 100, 200) }}">
                            <div class="k-img-hover relative aspect-[4/3] overflow-hidden rounded-lg bg-[#F1F1EF]">
                                @if ($work->cover)
                                    <img src="{{ asset('storage/'.$work->cover) }}" alt="{{ $work->t('title') }}"
                                         loading="lazy" class="h-full w-full object-cover">
                                @else
                                    <span class="absolute inset-0 grid place-items-center px-6 text-center k-display-xs text-[#0F0F0F]/20">
                                        {{ $work->t('title') }}
                                    </span>
                                @endif
                            </div>

                            <div class="mt-5 flex items-start justify-between gap-5">
                                <div class="min-w-0">
                                    <p class="k-eyebrow mb-2 text-[#0F0F0F]/45">
                                        {{ collect([$work->category?->t('name'), $work->client])->filter()->join(' · ') }}
                                    </p>
                                    <h2 class="text-2xl font-black tracking-tight transition-colors duration-300 group-hover:text-[#E30613]">
                                        {{ $work->t('title') }}
                                    </h2>
                                    @if ($work->t('summary'))
                                        <p class="mt-2 max-w-md text-sm leading-relaxed text-[#0F0F0F]/55">{{ $work->t('summary') }}</p>
                                    @endif
                                </div>
                                @if ($work->year)
                                    <span class="shrink-0 text-[0.7rem] font-bold tracking-[0.12em] text-[#0F0F0F]/35">{{ $work->year }}</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

</x-app-layout>
