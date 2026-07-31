<?php

return [
    'disk' => env('REPORTES_DISK', 'local'),
    'expiration_hours' => (int) env('REPORTES_EXPIRATION_HOURS', 24),
    'failed_retention_days' => (int) env('REPORTES_FAILED_RETENTION_DAYS', 7),
];
