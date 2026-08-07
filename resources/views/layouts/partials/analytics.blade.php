@php
    /**
     * Ölçüm ve pixel script'leri.
     *
     * Tasarım kararı: hiçbir üçüncü taraf script'i doğrudan <head>'e yazılmaz.
     * Hepsi burada bir JS nesnesinde toplanır ve YALNIZCA onay verildiğinde
     * enjekte edilir. Bunun iki faydası var:
     *
     *  1. KVKK/GDPR: onay öncesi çerez yazan hiçbir istek gitmez.
     *  2. Hız: ilk boyamada tek bir harici istek bile yok; script'ler onaydan
     *     sonra, sayfa çizildikten sonra yüklenir. Sıfır kütüphane ilkesiyle
     *     kurulmuş bir sitede 200 kB analytics yüklemek anlamsız olurdu.
     */
    $tags = array_filter([
        'ga4' => $site('ga4_id'),
        'gtm' => $site('gtm_id'),
        'ads' => $site('google_ads_id'),
        'meta' => $site('meta_pixel_id'),
        'linkedin' => $site('linkedin_partner_id'),
        'tiktok' => $site('tiktok_pixel_id'),
        'clarity' => $site('clarity_id'),
        'hotjar' => $site('hotjar_id'),
        'yandex' => $site('yandex_metrica_id'),
    ]);

    $needsConsent = $site('cookie_consent', '1') === '1';
    $isEn = app()->getLocale() === 'en';
@endphp

@if ($tags)
    <script>
        (() => {
            const TAGS = @json($tags);
            const NEEDS_CONSENT = @json($needsConsent);
            const KEY = 'kwt-consent';

            const script = (src, attrs = {}) => {
                const el = document.createElement('script');
                el.async = true;
                el.src = src;
                Object.entries(attrs).forEach(([k, v]) => el.setAttribute(k, v));
                document.head.appendChild(el);
                return el;
            };

            const inline = (code) => {
                const el = document.createElement('script');
                el.textContent = code;
                document.head.appendChild(el);
            };

            let loaded = false;

            function load() {
                if (loaded) return;
                loaded = true;

                // Google (GA4 + Ads tek gtag.js üzerinden)
                const google = [TAGS.ga4, TAGS.ads].filter(Boolean);
                if (google.length) {
                    window.dataLayer = window.dataLayer || [];
                    window.gtag = function () { window.dataLayer.push(arguments); };
                    script('https://www.googletagmanager.com/gtag/js?id=' + google[0]);
                    gtag('js', new Date());
                    google.forEach((id) => gtag('config', id));
                }

                if (TAGS.gtm) {
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({ 'gtm.start': Date.now(), event: 'gtm.js' });
                    script('https://www.googletagmanager.com/gtm.js?id=' + TAGS.gtm);
                }

                if (TAGS.meta) {
                    inline(`!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;
n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init','${TAGS.meta}');fbq('track','PageView');`);
                }

                if (TAGS.linkedin) {
                    inline(`_linkedin_partner_id='${TAGS.linkedin}';window._linkedin_data_partner_ids=window._linkedin_data_partner_ids||[];window._linkedin_data_partner_ids.push(_linkedin_partner_id);`);
                    script('https://snap.licdn.com/li.lms-analytics/insight.min.js');
                }

                if (TAGS.tiktok) {
                    inline(`!function(w,d,t){w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"];ttq.setAndDefer=function(e,n){e[n]=function(){e.push([n].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.load=function(e){var n="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{};ttq._i[e]=[];ttq._i[e]._u=n;ttq._t=ttq._t||{};ttq._t[e]=+new Date;ttq._o=ttq._o||{};ttq._o[e]={};var o=d.createElement("script");o.type="text/javascript";o.async=!0;o.src=n+"?sdkid="+e;var a=d.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};ttq.load('${TAGS.tiktok}');ttq.page()}(window,document,'ttq');`);
                }

                if (TAGS.clarity) {
                    inline(`(function(c,l,a,r,i,t,y){c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y)})(window,document,'clarity','script','${TAGS.clarity}');`);
                }

                if (TAGS.hotjar) {
                    inline(`(function(h,o,t,j,a,r){h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
h._hjSettings={hjid:${TAGS.hotjar},hjsv:6};a=o.getElementsByTagName('head')[0];
r=o.createElement('script');r.async=1;r.src=t+h._hjSettings.hjid+j;a.appendChild(r)})(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');`);
                }

                if (TAGS.yandex) {
                    inline(`(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
(window,document,'script','https://mc.yandex.ru/metrika/tag.js','ym');
ym(${TAGS.yandex},'init',{clickmap:true,trackLinks:true,accurateTrackBounce:true});`);
                }
            }

            if (!NEEDS_CONSENT || localStorage.getItem(KEY) === 'yes') {
                // Onay gerekmiyor ya da daha önce verilmiş: sayfayı bekletmeden yükle.
                window.addEventListener('load', load, { once: true });
                return;
            }

            if (localStorage.getItem(KEY) === 'no') return;

            window.kwtConsent = { load, key: KEY };
        })();
    </script>
@endif

@if ($tags && $needsConsent)
    <div class="k-consent" id="k-consent" hidden>
        <p class="k-consent__text">
            {{ $isEn
                ? 'We use cookies to measure how the site is used. Nothing loads until you choose.'
                : 'Sitenin nasıl kullanıldığını ölçmek için çerez kullanıyoruz. Siz seçim yapana kadar hiçbir şey yüklenmiyor.' }}
        </p>
        <div class="k-consent__actions">
            <button type="button" class="k-btn k-btn--ghost !px-5 !py-2.5 !text-[0.62rem]" data-consent="no">
                <span>{{ $isEn ? 'Decline' : 'Reddet' }}</span>
            </button>
            <button type="button" class="k-btn k-btn--brand !px-5 !py-2.5 !text-[0.62rem]" data-consent="yes">
                <span style="color:inherit;">{{ $isEn ? 'Accept' : 'Kabul et' }}</span>
            </button>
        </div>
    </div>

    <script>
        (() => {
            const bar = document.getElementById('k-consent');
            const consent = window.kwtConsent;
            if (!bar || !consent) return;

            bar.hidden = false;
            requestAnimationFrame(() => bar.classList.add('is-in'));

            bar.addEventListener('click', (e) => {
                const btn = e.target.closest('[data-consent]');
                if (!btn) return;

                localStorage.setItem(consent.key, btn.dataset.consent);
                if (btn.dataset.consent === 'yes') consent.load();

                bar.classList.remove('is-in');
                setTimeout(() => bar.remove(), 400);
            });
        })();
    </script>
@endif
