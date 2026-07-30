<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ \App\Models\Setting::get('site_name', 'Kıbrıs Web Tasarımcı') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=inter:400,700,900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="grid min-h-screen place-items-center bg-[#0F0F0F] px-6">
    <div class="max-w-xl text-center">
        <p class="k-eyebrow mb-6" style="color:rgba(255,255,255,0.4);">
            {{ app()->getLocale() === 'en' ? 'Under maintenance' : 'Yapım aşamasında' }}
        </p>
        <h1 class="k-display-sm" style="color:#ffffff;">
            {{ \App\Models\Setting::get('maintenance_title', app()->getLocale() === 'en' ? 'Back shortly.' : 'Birazdan buradayız.') }}
        </h1>
        <p class="mx-auto mt-6 max-w-md text-base leading-relaxed" style="color:rgba(255,255,255,0.55);">
            {{ \App\Models\Setting::get('maintenance_text', app()->getLocale() === 'en'
                ? 'We are shipping an update. Please check back soon.'
                : 'Kısa bir güncelleme yapıyoruz. Birazdan tekrar deneyin.') }}
        </p>
        <a href="mailto:{{ \App\Models\Setting::get('contact_email', 'info@kibriswebtasarimci.com') }}"
           class="k-btn k-btn--brand mt-10">
            <span style="color:inherit;">{{ \App\Models\Setting::get('contact_email', 'info@kibriswebtasarimci.com') }}</span>
        </a>
    </div>
</body>
</html>
