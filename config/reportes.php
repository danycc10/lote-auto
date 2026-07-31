<?php

return [
    'disk' => env('REPORTES_DISK', 'local'),
    'expiration_hours' => (int) env('REPORTES_EXPIRATION_HOURS', 24),
];
