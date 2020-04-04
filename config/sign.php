<?php

return [
    'default' => 'web',
    'entries' => [
        'web' => [
            'guard' => 'token',
            'redirect' => '/sign-in'
        ],
        'api' => [
            'guard' => 'token',
        ],
    ],
    'guards' => [
        'session' => [],
        'token' => [
            'input_name' => 'sign-token',
            'storage_prefix' => 'sign_',
            'storage' => 'redis:sign',
            'stamps' => ['ip', 'userAgent']
        ],
        'jwt' => []
    ]
];
