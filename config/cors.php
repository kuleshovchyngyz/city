<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [
        'https://test.vinz.ru',
        'https://www.test.vinz.ru',
        'http://localhost:5173'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'X-CSRF-TOKEN',
        'Accept',
        'Origin'
    ],

    'exposed_headers' => [
        'Authorization',
        'X-CSRF-TOKEN',
        'Access-Control-Allow-Origin',
        'Access-Control-Allow-Credentials'
    ],

    'max_age' => 0,

    'supports_credentials' => true,
];
