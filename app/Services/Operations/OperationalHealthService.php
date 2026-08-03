<?php

namespace App\Services\Operations;

use App\Models\Configuracion;
use App\Models\EstadoOperacion;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Throwable;

class OperationalHealthService
{
    /**
     * @return array<string, mixed>
     */
    public function snapshot(): array
    {
        $checks = [
            $this->heartbeatCheck('scheduler', 'Tareas programadas', 5),
            $this->queueCheck(),
            $this->backupCheck(),
            $this->remoteBackupCheck(),
            $this->storageCheck(),
        ];

        $failedJobs = $this->tableCount((string) config('queue.failed.table', 'failed_jobs'));
        $pendingJobs = config('queue.default') === 'database'
            ? $this->tableCount((string) config('queue.connections.database.table', 'jobs'))
            : null;

        if ($failedJobs !== null && $failedJobs > 0) {
            $checks[] = $this->check(
                $failedJobs >= 10 ? 'error' : 'warning',
                'Trabajos fallidos',
                "Hay {$failedJobs} trabajo(s) fallido(s) pendientes de revisión.",
            );
        } else {
            $checks[] = $this->check('ok', 'Trabajos fallidos', 'No hay trabajos fallidos registrados.');
        }

        return [
            'overall' => $this->overallStatus($checks),
            'checks' => $checks,
            'installation' => [
                'name' => (string) Configuracion::obtener('instalacion.nombre', config('app.name')),
                'slug' => (string) Configuracion::obtener('instalacion.slug', 'Sin aprovisionar'),
                'uuid' => (string) Configuracion::obtener('instalacion.uuid', 'Sin aprovisionar'),
                'version' => (string) Configuracion::obtener('instalacion.version', config('app.version', 'Sin versión')),
            ],
            'environment' => [
                'app' => (string) config('app.env'),
                'php' => PHP_VERSION,
                'database' => (string) config('database.default'),
                'queue' => (string) config('queue.default'),
                'worker_mode' => (string) config('hosting.queue_worker.mode'),
                'mail' => (string) config('mail.default'),
            ],
            'queues' => [
                'pending' => $pendingJobs,
                'failed' => $failedJobs,
            ],
            'generated_at' => now()->format('d/m/Y H:i:s'),
        ];
    }

    /**
     * @return array{status: string, label: string, detail: string, checked_at: string|null}
     */
    private function heartbeatCheck(string $key, string $label, int $maximumAgeMinutes): array
    {
        try {
            $heartbeat = EstadoOperacion::query()->where('clave', $key)->first();
        } catch (Throwable) {
            return $this->check('error', $label, 'No fue posible consultar el estado operativo.');
        }

        if ($heartbeat === null) {
            return $this->check('warning', $label, 'Aún no se ha registrado una ejecución. Verifica el cron de cPanel.');
        }

        $executedAt = Carbon::parse($heartbeat->ejecutado_at);
        $checkedAt = $executedAt->format('d/m/Y H:i:s');

        if ($heartbeat->estado === 'error') {
            return $this->check('error', $label, $heartbeat->mensaje ?? 'La última ejecución terminó con error.', $checkedAt);
        }

        if ($executedAt->lt(now()->subMinutes($maximumAgeMinutes))) {
            return $this->check('warning', $label, "Sin actividad en los últimos {$maximumAgeMinutes} minutos.", $checkedAt);
        }

        return $this->check('ok', $label, $heartbeat->mensaje ?? 'Funcionando correctamente.', $checkedAt);
    }

    /**
     * @return array{status: string, label: string, detail: string, checked_at: string|null}
     */
    private function queueCheck(): array
    {
        if (config('queue.default') === 'sync') {
            return $this->check('error', 'Procesamiento en segundo plano', 'La cola usa sync; los reportes pueden bloquear la solicitud web.');
        }

        if (config('hosting.queue_worker.mode') !== 'cron') {
            return $this->check('warning', 'Procesamiento en segundo plano', 'El worker se administra externamente y esta instalación no recibe su latido.');
        }

        return $this->heartbeatCheck('queue', 'Procesamiento en segundo plano', 5);
    }

    /**
     * @return array{status: string, label: string, detail: string, checked_at: string|null}
     */
    private function backupCheck(): array
    {
        if (! config('backup.enabled')) {
            return $this->check('warning', 'Respaldo de base de datos', 'Los respaldos automáticos están desactivados.');
        }

        try {
            $lastRun = EstadoOperacion::query()->where('clave', 'backup')->first();
        } catch (Throwable) {
            $lastRun = null;
        }

        if ($lastRun?->estado === 'error') {
            return $this->check(
                'error',
                'Respaldo de base de datos',
                $lastRun->mensaje ?? 'El último respaldo programado terminó con error.',
                $lastRun->ejecutado_at->format('d/m/Y H:i:s'),
            );
        }

        $directory = (string) config('backup.directory');
        $files = File::glob($directory.DIRECTORY_SEPARATOR.'*.sql*');
        $backups = array_values(array_filter($files, fn (string $file): bool => ! str_ends_with($file, '.sha256')));

        if ($backups === []) {
            return $this->check('warning', 'Respaldo de base de datos', 'Todavía no existe un respaldo local verificable.');
        }

        usort($backups, fn (string $a, string $b): int => File::lastModified($b) <=> File::lastModified($a));
        $latestAt = Carbon::createFromTimestamp(File::lastModified($backups[0]));
        $checkedAt = $latestAt->format('d/m/Y H:i:s');

        if ($latestAt->lt(now()->subHours(30))) {
            return $this->check('warning', 'Respaldo de base de datos', 'El respaldo local más reciente tiene más de 30 horas.', $checkedAt);
        }

        return $this->check('ok', 'Respaldo de base de datos', 'Existe un respaldo local reciente.', $checkedAt);
    }

    /**
     * @return array{status: string, label: string, detail: string, checked_at: string|null}
     */
    private function remoteBackupCheck(): array
    {
        if (! config('backup.remote_disk')) {
            return $this->check('warning', 'Copia externa', 'No hay almacenamiento remoto configurado para los respaldos.');
        }

        return $this->check('ok', 'Copia externa', 'La replicación remota está configurada. Comprueba periódicamente una restauración.');
    }

    /**
     * @return array{status: string, label: string, detail: string, checked_at: string|null}
     */
    private function storageCheck(): array
    {
        $total = @disk_total_space(storage_path());
        $free = @disk_free_space(storage_path());

        if ($total === false || $free === false || $total <= 0) {
            return $this->check('warning', 'Almacenamiento', 'El servidor no permitió calcular el espacio disponible.');
        }

        $usedPercent = round((1 - ($free / $total)) * 100, 1);
        $freeGigabytes = round($free / 1024 / 1024 / 1024, 1);
        $detail = "{$usedPercent}% utilizado; {$freeGigabytes} GB disponibles.";

        if ($usedPercent >= 90) {
            return $this->check('error', 'Almacenamiento', $detail);
        }

        if ($usedPercent >= 80) {
            return $this->check('warning', 'Almacenamiento', $detail);
        }

        return $this->check('ok', 'Almacenamiento', $detail);
    }

    private function tableCount(string $table): ?int
    {
        try {
            return Schema::hasTable($table) ? DB::table($table)->count() : null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * @param  array<int, array{status: string, label: string, detail: string, checked_at: string|null}>  $checks
     */
    private function overallStatus(array $checks): string
    {
        if (collect($checks)->contains('status', 'error')) {
            return 'error';
        }

        return collect($checks)->contains('status', 'warning') ? 'warning' : 'ok';
    }

    /**
     * @return array{status: string, label: string, detail: string, checked_at: string|null}
     */
    private function check(string $status, string $label, string $detail, ?string $checkedAt = null): array
    {
        return [
            'status' => $status,
            'label' => $label,
            'detail' => $detail,
            'checked_at' => $checkedAt,
        ];
    }
}
