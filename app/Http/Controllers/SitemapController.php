<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Service;
use App\Models\Work;
use Illuminate\Http\Response;

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
        $add('quote', 'en.quote', [], null, 'monthly', '0.8');
        $add('blog.index', 'en.blog.index', [], null, 'weekly', '0.8');
        $add('contact', 'en.contact', [], null, 'yearly', '0.6');

        foreach (Service::active()->get() as $service) {
            $add('services.show', 'en.services.show', ['service' => $service->slug], $service->updated_at?->toAtomString(), 'monthly', '0.8');
        }

        foreach (Work::active()->get() as $work) {
            $add('works.show', 'en.works.show', ['work' => $work->slug], $work->updated_at?->toAtomString(), 'monthly', '0.7');
        }

        foreach (BlogPost::published()->get() as $post) {
            $add('blog.show', 'en.blog.show', ['post' => $post->slug], $post->updated_at?->toAtomString(), 'monthly', '0.7');
        }

        return response()
            ->view('sitemap', ['entries' => $entries])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /livewire',
            '',
            'Sitemap: '.route('sitemap'),
            '',
        ];

        return response(implode("\n", $lines))
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
