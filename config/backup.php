<?php

return [
    'enabled' => (bool) env('DB_BACKUP_ENABLED', true),
    'directory' => env('DB_BACKUP_DIRECTORY', storage_path('app/private/backups')),
    'keep_days' => (int) env('DB_BACKUP_KEEP_DAYS', 14),
    'mysqldump_binary' => env('MYSQLDUMP_BINARY', 'mysqldump'),
    'timeout_seconds' => (int) env('DB_BACKUP_TIMEOUT', 600),
    'remote_disk' => env('DB_BACKUP_REMOTE_DISK'),
    'remote_prefix' => env('DB_BACKUP_REMOTE_PREFIX', 'backups'),
];
