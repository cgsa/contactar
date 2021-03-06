<?php

return [
    'mode'    => env('PAYPAL_MODE', 'sandbox'),

    'sandbox' => [
        'client_id'         => env('PAYPAL_SANDBOX_CLIENT_ID', ''),
        'client_secret'     => env('PAYPAL_SANDBOX_CLIENT_SECRET', ''),
        'app_id'            => 'APP-80W284485P519543T',
    ],

    'live' => [
        'client_id'         => env('PAYPAL_LIVE_CLIENT_ID', ''),
        'client_secret'     => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
        'app_id'            => '',
    ],

    'payment_action'        => env('PAYPAL_PAYMENT_ACTION', 'Sale'),
    'currency'              => env('PAYPAL_CURRENCY', 'USD'),
    'notify_url'            => env('PAYPAL_NOTIFY_URL', ''),
    'locale'                => env('PAYPAL_LOCALE', 'en_US'),
    'validate_ssl'          => env('PAYPAL_VALIDATE_SSL', true),
    'frontend_contactar'    => env('FRONTEND_CONTACTAR', 'http://127.0.0.1:8004'),
    'backend_contactar'    => env('APP_URL', 'http://127.0.0.1:8004'),
];