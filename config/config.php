<?php

return [
    'auth' => [
        'username' => env('KURAIMI_USERNAME'),
        'password' => env('KURAIMI_PASSWORD'),
    ],

    'url' => [
        'base' => env('KURAIMI_BASE_URL', 'https://web.krmbank.net.ye:44746'),
    ],

    'webhook_credentials' => [
        'username' => env('KURAIMI_WEBHOOK_USERNAME'),
        'password' => env('KURAIMI_WEBHOOK_PASSWORD'),
    ],

    'currency_zone' => env('KURAIMI_CURRENCY_ZONE', 'all'), // ['all', 'old', 'new']
    'webhook_url' => env('KURAIMI_WEBHOOK_URL', 'webhooks/kuraimibank/check-user'),
    'model_namespace' => env('KURAIMI_MODEL'),
    'column_name' => env('KURAIMI_COLUMN_NAME'),
];
