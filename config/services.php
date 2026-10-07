<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | IndexNow
    |--------------------------------------------------------------------------
    |
    | İçerik değişince Bing/Yandex'e anında haber veren bildirim. Anahtar
    | sitenin kökünde `<anahtar>.txt` dosyasında DURMALI (public/ içinde,
    | depoya dahil) — dosya olmadan bildirim reddedilir.
    |
    | Varsayılan olarak yalnızca üretimde açık: yerelde çalışırken localhost
    | adreslerini arama motoruna bildirmenin anlamı yok.
    |
    */

    'indexnow' => [
        'key' => env('INDEXNOW_KEY', 'b5afd8046cce653332f1b76d243e300d'),
        'enabled' => env('INDEXNOW_ENABLED', env('APP_ENV') === 'production'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
