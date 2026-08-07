@php
    $isEn = app()->getLocale() === 'en';
    $featured = $posts->getCollection()->first();
    $rest = $posts->getCollection()->skip(1);

    // Filtreli görünüm dizine girmesin; sayfa 2+ kendi kanoniğini korur ki
    // içerik kaybolmasın, ama başlık "(sayfa N)" ile ayrışsın.
    $page = $posts->currentPage();
    $canonical = $activeCategory
        ? ($page > 1 ? $r('blog.index').'?page='.$page : $r('blog.index'))
        : ($page > 1 ? $r('blog.index').'?page='.$page : null);
    $robots = $activeCategory ? 'noindex, follow' : null;
    $pageSuffix = $page > 1 ? ($isEn ? ' — page '.$page : ' — sayfa '.$page) : '';
@endphp

<x-app-layout
    :canonical="$canonical"
    :robots="$robots"
    :paginator="$posts"
    :seo-title="($isEn ? 'Insights' : 'Blog').$pageSuffix"
    :seo-description="$isEn
        ? 'Notes on web design, e-commerce and SEO for businesses in Cyprus.'
        : 'Kıbrıs\'taki işletmeler için web tasarım, e-ticaret ve SEO üzerine notlar.'">

    <x-page-hero :eyebrow="__('site.nav.blog')"
                 :lead="$isEn
                    ? 'What we learn while building sites, written down. No filler, no listicles for the sake of it.'
                    : 'Site kurarken öğrendiklerimizi yazıyoruz. Dolgu içerik yok, sırf olsun diye liste yok.'">
        {{ $isEn ? 'Notes on' : 'Web üzerine' }} <span class="italic font-light">{{ $isEn ? 'the web.' : 'notlar.' }}</span>
    </x-page-hero>

    <section class="bg-white px-6 py-14 lg:px-12 lg:py-20">
        <div class="mx-auto max-w-[1280px]">

            @if ($categories->isNotEmpty())
                <div class="k-noscroll mb-12 flex gap-2 overflow-x-auto pb-1">
                    <a href="{{ $r('blog.index') }}"
                       class="k-btn shrink-0 !px-5 !py-2.5 !text-[0.66rem] {{ $activeCategory ? 'k-btn--ghost' : 'k-btn--ink' }}">
                        <span style="color:inherit;">{{ __('site.common.all') }}</span>
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ $r('blog.index') }}?kategori={{ $category->slug }}"
                           class="k-btn shrink-0 !px-5 !py-2.5 !text-[0.66rem] {{ $activeCategory === $category->slug ? 'k-btn--ink' : 'k-btn--ghost' }}">
                            <span style="color:inherit;">{{ $category->t('name') }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($posts->isEmpty())
                <p class="py-10 text-[#0F0F0F]/50">{{ __('site.common.empty') }}</p>
            @else
                {{-- Öne çıkan yazı --}}
                <a href="{{ $r('blog.show', ['post' => $featured->slug]) }}"
                   class="k-reveal group mb-16 grid grid-cols-1 gap-8 border-b border-[#0F0F0F]/12 pb-14 lg:grid-cols-12">
                    <div class="k-img-hover aspect-[16/10] overflow-hidden rounded-lg bg-[#F1F1EF] lg:col-span-7">
                        @if ($featured->cover)
                            <img src="{{ asset('storage/'.$featured->cover) }}" alt="{{ $featured->t('title') }}"
                                 class="h-full w-full object-cover">
                        @endif
                    </div>
                    <div class="flex flex-col justify-center lg:col-span-5">
                        <div class="mb-4 flex items-center gap-3">
                            @if ($featured->category)
                                <span class="k-tag">{{ $featured->category->t('name') }}</span>
                            @endif
                            @if ($featured->reading_minutes)
                                <span class="text-[0.7rem] text-[#0F0F0F]/40">{{ $featured->reading_minutes }} {{ __('site.common.minutes') }}</span>
                            @endif
                        </div>
                        <h2 class="k-display-xs transition-colors duration-300 group-hover:text-[#E30613]">{{ $featured->t('title') }}</h2>
                        <p class="mt-4 leading-relaxed text-[#0F0F0F]/60">{{ $featured->t('excerpt') }}</p>
                        <span class="k-link mt-6 w-fit text-sm font-bold uppercase tracking-[0.12em]">
                            {{ __('site.common.read_more') }} →
                        </span>
                    </div>
                </a>

                @if ($rest->isNotEmpty())
                    <div class="grid grid-cols-1 gap-x-7 gap-y-12 md:grid-cols-3">
                        @foreach ($rest as $i => $post)
                            <a href="{{ $r('blog.show', ['post' => $post->slug]) }}"
                               class="k-reveal group block" data-delay="{{ min(($i % 3 + 1) * 100, 300) }}">
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
                                <h2 class="text-xl font-black leading-tight tracking-tight transition-colors duration-300 group-hover:text-[#E30613]">
                                    {{ $post->t('title') }}
                                </h2>
                                <p class="mt-2.5 line-clamp-3 text-sm leading-relaxed text-[#0F0F0F]/55">{{ $post->t('excerpt') }}</p>
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="mt-14">{{ $posts->links() }}</div>
            @endif
        </div>
    </section>

</x-app-layout>
