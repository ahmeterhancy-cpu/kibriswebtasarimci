<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach ($entries as $entry)
@foreach (['tr', 'en'] as $lang)
    <url>
        <loc>{{ $entry[$lang] }}</loc>
@if ($entry['lastmod'])
        <lastmod>{{ $entry['lastmod'] }}</lastmod>
@endif
        <changefreq>{{ $entry['changefreq'] }}</changefreq>
        <priority>{{ $entry['priority'] }}</priority>
        <xhtml:link rel="alternate" hreflang="tr" href="{{ $entry['tr'] }}"/>
        <xhtml:link rel="alternate" hreflang="en" href="{{ $entry['en'] }}"/>
        <xhtml:link rel="alternate" hreflang="x-default" href="{{ $entry['tr'] }}"/>
    </url>
@endforeach
@endforeach
</urlset>
