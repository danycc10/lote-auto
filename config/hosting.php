<?php

return [
    'provider' => env('HOSTING_PROVIDER', 'hostgator'),
    'public_path' => env('APP_PUBLIC_PATH'),
    'minimum_php' => '8.3.0',
    'required_extensions' => [
        'bcmath',
        'ctype',
        'curl',
        'dom',
        'fileinfo',
        'gd',
        'intl',
        'mbstring',
        'openssl',
        'pdo',
        'pdo_mysql',
        'tokenizer',
        'xml',
        'zip',
    ],
    'queue_worker' => [
        'mode' => env('HOSTING_QUEUE_WORKER_MODE', 'external'),
        'queue' => env('HOSTING_QUEUE', 'default'),
        'sleep' => (int) env('HOSTING_QUEUE_SLEEP', 1),
        'tries' => (int) env('HOSTING_QUEUE_TRIES', 3),
        'timeout' => (int) env('HOSTING_QUEUE_TIMEOUT', 300),
        'max_time' => (int) env('HOSTING_QUEUE_MAX_TIME', 50),
    ],
];
