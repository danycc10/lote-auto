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
];
