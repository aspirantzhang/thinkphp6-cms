<?php

return [
    'middleware' => [
        app\middleware\BackendAuth::class,
    ],
    'default_header' => [
        'access-control-allow-origin' => 'http://localhost:8000',
        'access-control-allow-methods' => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
        'access-control-allow-headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With',
        'access-control-allow-credentials' => 'true',
    ],
];
