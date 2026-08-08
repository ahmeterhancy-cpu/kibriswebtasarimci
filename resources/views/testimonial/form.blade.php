@php
    $isEn = app()->getLocale() === 'en';
    $sent = session('status') === 'testimonial-sent';
    $used = ! $sent && $request->completed_at !== null;
    $expired = ! $sent && ! $used && ! $request->isUsable();
@endphp

{{-- Tekil davet bağlantısı: arama motorlarında görünmemeli. --}}
<x-app-layout
    robots="noindex, nofollow"
    :seo-title="__('site.testimonial.title')"
    :seo-description="__('site.testimonial.lead')">

    <section class="bg-[#F1F1EF] px-6 pb-20 pt-36 lg:px-12 lg:pb-28 lg:pt-48">
        <div class="mx-auto max-w-[760px]">

            @if ($sent || $used || $expired)
                {{-- Form dışı üç durum: teşekkür, kullanılmış, süresi dolmuş. --}}
                <p class="k-eyebrow k-reveal mb-6 text-[#0F0F0F]/50">{{ __('site.testimonial.eyebrow') }}</p>

                <h1 class="k-display-sm text-[#0F0F0F]" data-split data-split-step="0.05">
                    @if ($sent)
                        {{ __('site.testimonial.thanks_title') }}
                    @elseif ($used)
                        {{ __('site.testimonial.used_title') }}
                    @else
                        {{ __('site.testimonial.expired_title') }}
                    @endif
                </h1>

                <p class="k-lead k-reveal mt-7 max-w-xl text-[#0F0F0F]/70" data-delay="200">
                    @if ($sent)
                        {{ __('site.testimonial.thanks_text') }}
                    @elseif ($used)
                        {{ __('site.testimonial.used_text') }}
                    @else
                        {{ __('site.testimonial.expired_text') }}
                    @endif
                </p>

                <div class="k-reveal mt-9 flex flex-wrap gap-4" data-delay="300">
                    <a href="{{ $r('home') }}" data-cursor="cta" data-magnetic="0.3" class="k-btn k-btn--brand">
                        <span style="color:inherit;">{{ $isEn ? 'Back to site' : 'Siteye dön' }}</span>
                        <span class="k-btn__arrow" aria-hidden="true">→</span>
                    </a>
                    <a href="{{ $r('contact') }}" class="k-btn k-btn--ghost">
                        <span>{{ __('site.nav.contact') }}</span>
                    </a>
                </div>
            @else
                <p class="k-eyebrow k-reveal mb-6 text-[#0F0F0F]/50">{{ __('site.testimonial.eyebrow') }}</p>

                <h1 class="k-display-sm text-[#0F0F0F]" data-split data-split-step="0.05">
                    {{ __('site.testimonial.title') }}
                </h1>

                <p class="k-lead k-reveal mt-7 max-w-xl text-[#0F0F0F]/70" data-delay="200">
                    {{ __('site.testimonial.lead') }}
                </p>

                @if ($request->project)
                    <p class="k-reveal mt-4 text-sm text-[#0F0F0F]/55" data-delay="250">
                        {{ $isEn ? 'Project' : 'Proje' }}: <strong class="font-bold text-[#0F0F0F]/75">{{ $request->project }}</strong>
                    </p>
                @endif

                @if ($request->note)
                    <blockquote class="k-reveal mt-8 border-l-2 border-[#E30613] pl-5 text-[#0F0F0F]/70" data-delay="300">
                        {{ $request->note }}
                    </blockquote>
                @endif

                <form method="POST" action="{{ $r('testimonial.store', ['request' => $request->token]) }}"
                      class="k-reveal mt-12 space-y-8" data-delay="300">
                    @csrf

                    {{-- Bal kabı: ekran dışında, gerçek kullanıcı görmez. --}}
                    <div class="sr-only" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div>
                        <label for="quote" class="k-field-label mb-3 block">
                            {{ __('site.testimonial.quote') }} *
                        </label>
                        <textarea name="quote" id="quote" rows="5" required minlength="40" maxlength="600"
                                  class="k-field"
                                  placeholder="{{ $isEn ? 'What we built and what changed for your business…' : 'Ne yaptırdığınız ve işinizde ne değiştiği…' }}">{{ old('quote') }}</textarea>
                        <p class="mt-2 text-xs text-[#0F0F0F]/45">{{ __('site.testimonial.quote_help') }}</p>
                        @error('quote')
                            <p class="mt-2 text-xs font-bold text-[#E30613]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                        <div>
                            <label for="name" class="k-field-label mb-3 block">{{ __('site.form.name') }} *</label>
                            <input type="text" name="name" id="name" required maxlength="150" class="k-field"
                                   value="{{ old('name', $request->client_name) }}">
                            @error('name')
                                <p class="mt-2 text-xs font-bold text-[#E30613]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="role" class="k-field-label mb-3 block">{{ __('site.testimonial.role') }}</label>
                            <input type="text" name="role" id="role" maxlength="150" class="k-field"
                                   value="{{ old('role') }}"
                                   placeholder="{{ $isEn ? 'Owner, Marketing Manager…' : 'İşletme sahibi, Pazarlama müdürü…' }}">
                        </div>

                        <div>
                            <label for="company" class="k-field-label mb-3 block">{{ __('site.testimonial.company') }}</label>
                            <input type="text" name="company" id="company" maxlength="190" class="k-field"
                                   value="{{ old('company', $request->company) }}">
                        </div>

                        <div>
                            <label for="email" class="k-field-label mb-3 block">{{ __('site.form.email') }}</label>
                            <input type="email" name="email" id="email" maxlength="190" class="k-field"
                                   value="{{ old('email', $request->email) }}">
                            <p class="mt-2 text-xs text-[#0F0F0F]/45">
                                {{ $isEn ? 'Not published — only so we can reach you.' : 'Yayınlanmaz — yalnız size ulaşabilmek için.' }}
                            </p>
                        </div>
                    </div>

                    <div class="border-t border-[#0F0F0F]/12 pt-7">
                        <label class="flex cursor-pointer items-start gap-3">
                            <input type="checkbox" name="consent" value="1" required
                                   class="mt-1 h-4 w-4 shrink-0 accent-[#E30613]" {{ old('consent') ? 'checked' : '' }}>
                            <span class="text-sm leading-relaxed text-[#0F0F0F]/75">
                                {{ __('site.testimonial.consent') }}
                                <span class="mt-1 block text-xs text-[#0F0F0F]/45">{{ __('site.testimonial.consent_note') }}</span>
                            </span>
                        </label>
                        @error('consent')
                            <p class="mt-3 text-xs font-bold text-[#E30613]">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" data-cursor="cta" data-magnetic="0.3" class="k-btn k-btn--brand">
                        <span style="color:inherit;">{{ __('site.testimonial.submit') }}</span>
                        <span class="k-btn__arrow" aria-hidden="true">→</span>
                    </button>
                </form>
            @endif
        </div>
    </section>

</x-app-layout>
