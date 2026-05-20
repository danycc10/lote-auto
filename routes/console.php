<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Libera autos cuyos apartados vencieron. Requiere cron en el servidor:
// * * * * * php /ruta/artisan schedule:run >> /dev/null 2>&1
Schedule::command('apartados:vencer')->dailyAt('01:00');
