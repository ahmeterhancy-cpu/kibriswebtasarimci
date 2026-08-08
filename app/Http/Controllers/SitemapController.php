<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Location;
use App\Models\Package;
use App\Models\Sector;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Work;
use App\Support\AiCrawlers;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class SitemapController extends Controller
{
    /**
     * Dinamik sitemap. Her adres iki dilde üretilir ve birbirine
     * `xhtml:link rel="alternate"` ile bağlanır.
     */
    public function index(): Response
    {
        $entries = [];

        $add = function (string $trName, string $enName, array $params = [], ?string $lastmod = null, string $freq = 'monthly', string $priority = '0.7') use (&$entries) {
            $entries[] = [
                'tr' => route($trName, $params),
                'en' => route($enName, $params),
                'lastmod' => $lastmod,
                'changefreq' => $freq,
                'priority' => $priority,
            ];
        };

        $add('home', 'en.home', [], null, 'weekly', '1.0');
        $add('services.index', 'en.services.index', [], null, 'monthly', '0.9');
        $add('works.index', 'en.works.index', [], null, 'weekly', '0.9');
        $add('packages', 'en.packages', [], null, 'monthly', '0.9');
        $add('sectors.index', 'en.sectors.index', [], null, 'monthly', '0.9');
        $add('locations.index', 'en.locations.index', [], null, 'monthly', '0.8');
        $add('quote', 'en.quote', [], null, 'monthly', '0.8');
        $add('blog.index', 'en.blog.index', [], null, 'weekly', '0.8');
        $add('contact', 'en.contact', [], null, 'yearly', '0.6');

        foreach (Service::active()->get() as $service) {
            $add('services.show', 'en.services.show', ['service' => $service->slug], $service->updated_at?->toAtomString(), 'monthly', '0.8');
        }

        foreach (Work::active()->get() as $work) {
            $add('works.show', 'en.works.show', ['work' => $work->slug], $work->updated_at?->toAtomString(), 'monthly', '0.7');
        }

        // Sektör sayfaları — içeriğin gerçekten ayrıştığı yer, en yüksek öncelik.
        foreach (Sector::active()->get() as $sector) {
            $add('sectors.show', 'en.sectors.show', ['sector' => $sector->slug], $sector->updated_at?->toAtomString(), 'monthly', '0.9');
        }

        // Şehir sayfaları — yerel aramaların giriş kapısı.
        foreach (Location::active()->get() as $location) {
            $add('locations.show', 'en.locations.show', ['location' => $location->slug], $location->updated_at?->toAtomString(), 'monthly', '0.7');
        }

        foreach (BlogPost::published()->get() as $post) {
            $add('blog.show', 'en.blog.show', ['post' => $post->slug], $post->updated_at?->toAtomString(), 'monthly', '0.7');
        }

        return response()
            ->view('sitemap', ['entries' => $entries])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * Panelde işaretli AI botları. Hiç kayıt yoksa hepsi açık kabul edilir —
     * yeni kurulan bir sitenin sessizce yapay zekâ sonuçlarından silinmemesi
     * için varsayılan "görünür" olmalı.
     *
     * @return array<int, string>
     */
    public static function allowedAiCrawlers(): array
    {
        $raw = Setting::get('ai_crawlers');

        if ($raw === null) {
            return AiCrawlers::defaults();
        }

        return array_values(array_filter(explode(',', (string) $raw)));
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /livewire',
            'Disallow: /*?kategori=',
        ];

        // Yapay zekâ tarayıcıları — panelden (Yapay Zekâ Görünürlüğü) yönetilir.
        // İzin verilenler için ayrı bir blok yazmıyoruz; `*` zaten kapsıyor.
        // Yalnızca KAPATILANLAR için açık bir Disallow gerekiyor.
        $allowed = static::allowedAiCrawlers();
        $blocked = [];

        foreach (AiCrawlers::all() as $key => $bot) {
            if (! in_array($key, $allowed, true)) {
                $blocked = array_merge($blocked, $bot['agents']);
            }
        }

        foreach ($blocked as $agent) {
            $lines[] = '';
            $lines[] = 'User-agent: '.$agent;
            $lines[] = 'Disallow: /';
        }

        $lines[] = '';
        $lines[] = 'Sitemap: '.route('sitemap');

        if (Setting::get('llms_enabled', '1') === '1') {
            $lines[] = 'LLM-Content: '.route('llms');
        }

        $lines[] = '';

        return response(implode("\n", $lines))
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * Yapay zekâ modelleri için düz metin site özeti.
     *
     * llms.txt, markdown başlıkları ve bağlantı listesiyle modele "bu site
     * nedir, hangi sayfalar önemli" der. Sitemap'ten farkı: sıralama yerine
     * ANLAM taşır — model hangi sayfanın neye cevap verdiğini okuyabilir.
     */
    public function llms(): Response
    {
        abort_unless(Setting::get('llms_enabled', '1') === '1', 404);

        $name = Setting::get('site_name', 'Kıbrıs Web Tasarımcı');
        $out = ['# '.$name, ''];

        if ($entity = Setting::get('ai_entity')) {
            $out[] = '> '.$entity;
            $out[] = '';
        }

        $summary = Setting::get('ai_summary') ?: Setting::get('site_description');

        if ($summary) {
            $out[] = trim($summary);
            $out[] = '';
        }

        $section = function (string $title, array $rows) use (&$out) {
            if (! $rows) {
                return;
            }

            $out[] = '## '.$title;
            $out[] = '';

            foreach ($rows as $row) {
                $out[] = '- ['.$row[0].']('.$row[1].')'.($row[2] ? ': '.$row[2] : '');
            }

            $out[] = '';
        };

        $section('Hizmetler', Service::active()->get()
            ->map(fn ($s) => [$s->title, route('services.show', $s->slug), Str::limit(strip_tags((string) $s->excerpt), 140)])
            ->all());

        $section('Paketler ve fiyatlar', Package::active()->projects()->get()
            ->map(fn ($p) => [$p->name, route('packages'), trim($p->formatPrice($p->price).' — '.Str::limit(strip_tags((string) $p->tagline), 120), ' —')])
            ->all());

        // Sektörler hizmetlerden hemen sonra: modelin "bu iş kimler için"
        // sorusuna en net cevabı bu listede.
        $section('Sektörler', Sector::active()->get()
            ->map(fn ($s) => [$s->name, route('sectors.show', $s->slug), Str::limit(strip_tags((string) $s->intro), 160)])
            ->all());

        $section('Hizmet verilen şehirler', Location::active()->get()
            ->map(fn ($l) => [$l->name, route('locations.show', $l->slug), Str::limit(strip_tags((string) $l->intro), 140)])
            ->all());

        $section('Yazılar', BlogPost::published()->take(20)->get()
            ->map(fn ($p) => [$p->title, route('blog.show', $p->slug), Str::limit(strip_tags((string) $p->excerpt), 140)])
            ->all());

        $section('İletişim', array_values(array_filter([
            ['Teklif al', route('quote'), 'Adım adım kapsam seçip anında fiyat görün'],
            ['İletişim', route('contact'), Setting::get('contact_email')],
            ['English version', route('en.home'), 'Same content in English'],
        ])));

        if ($note = Setting::get('ai_contact_note')) {
            $out[] = '---';
            $out[] = '';
            $out[] = $note;
            $out[] = '';
        }

        return response(implode("\n", $out))
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
