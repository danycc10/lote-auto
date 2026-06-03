<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Requiere cron en el servidor:
// * * * * * cd /ruta/proyecto && php artisan schedule:run >> /dev/null 2>&1

// Marca cuotas como vencidas (respeta días de gracia) y actualiza estatus de contratos
Schedule::command('cuotas:marcar-vencidas')->dailyAt('00:05');

// Libera autos cuyos apartados vencieron
Schedule::command('apartados:vencer')->dailyAt('01:00');

// Envía recordatorios 3 días antes del vencimiento y notificación el día que vence
Schedule::command('cuotas:notificar-vencimientos')->dailyAt('08:00');
