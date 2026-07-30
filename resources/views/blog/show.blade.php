@php
    $isEn = app()->getLocale() === 'en';
@endphp

<x-app-layout
    :seo-title="$post->t('seo_title') ?: $post->t('title')"
    :seo-description="$post->t('seo_description') ?: $post->t('excerpt')"
    og-type="article"
    :og-image="$post->cover ? asset('storage/'.$post->cover) : null">

    @push('jsonld')
        <script type="application/ld+json">
            @php
                echo json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'BlogPosting',
                    'headline' => $post->t('title'),
                    'description' => $post->t('excerpt'),
                    'image' => $post->cover ? asset('storage/'.$post->cover) : null,
                    'datePublished' => $post->published_at?->toAtomString(),
                    'dateModified' => $post->updated_at?->toAtomString(),
                    'inLanguage' => $isEn ? 'en' : 'tr',
                    'author' => ['@type' => 'Organization', 'name' => $post->author ?: $site('site_name', 'Kıbrıs Web Tasarımcı')],
                    'publisher' => ['@type' => 'Organization', 'name' => $site('site_name', 'Kıbrıs Web Tasarımcı'), 'url' => url('/')],
                    'mainEntityOfPage' => url()->current(),
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            @endphp
        </script>
        <script type="application/ld+json">
            @php
                echo json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => __('site.nav.home'), 'item' => $r('home')],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => __('site.nav.blog'), 'item' => $r('blog.index')],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $post->t('title'), 'item' => url()->current()],
                    ],
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            @endphp
        </script>
    @endpush

    <article>
        <header class="bg-[#F1F1EF] px-6 pb-14 pt-36 lg:px-12 lg:pb-20 lg:pt-48">
            <div class="mx-auto max-w-[820px]">
                <div class="k-reveal mb-6 flex flex-wrap items-center gap-3">
                    @if ($post->category)
                        <span class="k-tag">{{ $post->category->t('name') }}</span>
                    @endif
                    @if ($post->published_at)
                        <time datetime="{{ $post->published_at->toDateString() }}" class="text-[0.72rem] text-[#0F0F0F]/45">
                            {{ $post->published_at->locale($isEn ? 'en' : 'tr')->isoFormat('D MMMM YYYY') }}
                        </time>
                    @endif
                    @if ($post->reading_minutes)
                        <span class="text-[0.72rem] text-[#0F0F0F]/45">· {{ $post->reading_minutes }} {{ __('site.common.minutes') }}</span>
                    @endif
                </div>

                <h1 class="k-display-sm" data-split data-split-step="0.04">{{ $post->t('title') }}</h1>

                @if ($post->t('excerpt'))
                    <p class="k-lead k-reveal mt-6" data-delay="200">{{ $post->t('excerpt') }}</p>
                @endif
            </div>
        </header>

        @if ($post->cover)
            <div class="bg-[#F1F1EF] px-6 lg:px-12">
                <div class="mx-auto max-w-[1080px]">
                    <div data-unmask class="aspect-[16/9] overflow-hidden rounded-lg">
                        <img src="{{ asset('storage/'.$post->cover) }}" alt="{{ $post->t('title') }}"
                             class="h-full w-full object-cover">
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white px-6 py-16 lg:px-12 lg:py-24">
            <div class="mx-auto max-w-[760px]">
                <div class="k-article">{!! $post->t('body') !!}</div>

                <div class="k-rule my-12"></div>

                <div class="flex flex-wrap items-center justify-between gap-5">
                    <a href="{{ $r('blog.index') }}" class="k-btn k-btn--ghost">
                        <span>{{ __('site.common.back_to_blog') }}</span>
                    </a>
                    <a href="{{ $r('quote') }}" data-cursor="cta" class="k-btn k-btn--brand">
                        <span style="color:inherit;">{{ __('site.nav.quote') }}</span>
                        <span class="k-btn__arrow" aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="bg-[#F4F4F2] px-6 py-16 lg:px-12 lg:py-24">
            <div class="mx-auto max-w-[1280px]">
                <p class="k-eyebrow k-reveal mb-10 text-[#0F0F0F]/45">{{ $isEn ? 'Keep reading' : 'Bunlar da ilginizi çekebilir' }}</p>
                <div class="grid grid-cols-1 gap-x-7 gap-y-10 md:grid-cols-3">
                    @foreach ($related as $i => $item)
                        <a href="{{ $r('blog.show', ['post' => $item->slug]) }}"
                           class="k-reveal group block" data-delay="{{ min(($i + 1) * 100, 300) }}">
                            <div class="k-img-hover mb-4 aspect-[16/10] overflow-hidden rounded-lg bg-white">
                                @if ($item->cover)
                                    <img src="{{ asset('storage/'.$item->cover) }}" alt="{{ $item->t('title') }}"
                                         loading="lazy" class="h-full w-full object-cover">
                                @endif
                            </div>
                            <h3 class="text-lg font-black leading-tight tracking-tight transition-colors duration-300 group-hover:text-[#E30613]">
                                {{ $item->t('title') }}
                            </h3>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-app-layout>
