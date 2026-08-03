<?php

use App\Services\Operations\OperationalStatusService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Requiere cron en el servidor:
// * * * * * cd /ruta/proyecto && php artisan schedule:run >> /dev/null 2>&1

Schedule::call(function (OperationalStatusService $status): void {
    $status->success('scheduler', 'El scheduler se ejecutó correctamente.');
})
    ->name('operations:scheduler-heartbeat')
    ->everyMinute()
    ->withoutOverlapping(2)
    ->onOneServer();

Schedule::command('queue:work', [
    config('queue.default'),
    '--queue' => (string) config('hosting.queue_worker.queue', 'default'),
    '--stop-when-empty',
    '--sleep' => max(0, (int) config('hosting.queue_worker.sleep', 1)),
    '--tries' => max(1, (int) config('hosting.queue_worker.tries', 3)),
    '--timeout' => max(1, (int) config('hosting.queue_worker.timeout', 300)),
    '--max-time' => max(1, (int) config('hosting.queue_worker.max_time', 50)),
])
    ->name('operations:queue-worker')
    ->everyMinute()
    ->withoutOverlapping(10)
    ->onOneServer()
    ->when(fn (): bool => config('hosting.queue_worker.mode') === 'cron' && config('queue.default') !== 'sync')
    ->onSuccess(fn (OperationalStatusService $status) => $status->success('queue', 'El worker programado finalizó correctamente.'))
    ->onFailure(fn (OperationalStatusService $status) => $status->failure('queue', 'El worker programado terminó con error.'));

// Marca cuotas como vencidas (respeta días de gracia) y actualiza estatus de contratos
Schedule::command('cuotas:marcar-vencidas')
    ->dailyAt('00:05')
    ->timezone(config('app.timezone'))
    ->withoutOverlapping(30)
    ->onOneServer();

// Libera autos cuyos apartados vencieron
Schedule::command('apartados:vencer')
    ->dailyAt('01:00')
    ->timezone(config('app.timezone'))
    ->withoutOverlapping(30)
    ->onOneServer();

Schedule::command('app:backup-database')
    ->dailyAt('02:00')
    ->timezone(config('app.timezone'))
    ->withoutOverlapping(120)
    ->onOneServer()
    ->when(fn (): bool => (bool) config('backup.enabled'))
    ->onSuccess(fn (OperationalStatusService $status) => $status->success('backup', 'El respaldo programado finalizó correctamente.'))
    ->onFailure(fn (OperationalStatusService $status) => $status->failure('backup', 'El respaldo programado terminó con error.'));

Schedule::command('reportes:limpiar-expirados')
    ->dailyAt('02:30')
    ->timezone(config('app.timezone'))
    ->withoutOverlapping(30)
    ->onOneServer();

// Envía recordatorios 3 días antes del vencimiento y notificación el día que vence
Schedule::command('cuotas:notificar-vencimientos')
    ->dailyAt('08:00')
    ->timezone(config('app.timezone'))
    ->withoutOverlapping(60)
    ->onOneServer();
