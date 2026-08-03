<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

#[Signature('app:backup-database {--keep= : Días de retención} {--local-only : Omite la copia remota configurada}')]
#[Description('Genera un respaldo consistente de la base de datos y elimina copias vencidas.')]
class BackupDatabaseCommand extends Command
{
    public function handle(): int
    {
        $directorio = (string) config('backup.directory');
        $retencion = max(
            1,
            (int) ($this->option('keep') ?: config('backup.keep_days', 14)),
        );
        File::ensureDirectoryExists($directorio, 0700, true);

        $conexion = DB::connection();
        $driver = $conexion->getDriverName();
        $sufijo = now()->format('Ymd-His').'-'.Str::lower(Str::random(4));

        $ruta = match ($driver) {
            'sqlite' => $this->respaldarSqlite($directorio, $sufijo),
            'mysql', 'mariadb' => $this->respaldarMysql($directorio, $sufijo),
            default => throw new RuntimeException("El driver {$driver} no tiene respaldo configurado."),
        };

        $hash = hash_file('sha256', $ruta);

        if ($hash === false) {
            throw new RuntimeException('No se pudo calcular la integridad del respaldo.');
        }

        File::put(
            $ruta.'.sha256',
            $hash.'  '.basename($ruta).PHP_EOL,
            true,
        );
        $eliminados = $this->aplicarRetencion($directorio, $retencion);
        $remotosEliminados = 0;
        $rutaRemota = null;

        if (! $this->option('local-only')) {
            [$rutaRemota, $remotosEliminados] = $this->copiarRemoto($ruta, $retencion);
        }

        $this->info('Respaldo creado: '.$ruta);
        $this->info('SHA-256: '.$hash);
        $this->info("Archivos vencidos eliminados: {$eliminados}");

        if ($rutaRemota) {
            $this->info('Copia remota creada: '.$rutaRemota);
            $this->info("Archivos remotos vencidos eliminados: {$remotosEliminados}");
        }

        return self::SUCCESS;
    }

    private function respaldarSqlite(string $directorio, string $sufijo): string
    {
        $origen = (string) DB::connection()->getConfig('database');

        if ($origen === ':memory:' || ! File::isFile($origen)) {
            throw new RuntimeException('SQLite requiere una base de datos persistente para respaldar.');
        }

        $destino = $directorio.DIRECTORY_SEPARATOR."database-{$sufijo}.sqlite";
        $pdo = DB::connection()->getPdo();
        $pdo->exec('PRAGMA wal_checkpoint(FULL)');
        $pdo->exec('BEGIN IMMEDIATE');

        try {
            if (! File::copy($origen, $destino)) {
                throw new RuntimeException('No se pudo copiar la base SQLite.');
            }
        } finally {
            $pdo->exec('ROLLBACK');
        }

        return $destino;
    }

    private function respaldarMysql(string $directorio, string $sufijo): string
    {
        $config = DB::connection()->getConfig();
        $destino = $directorio.DIRECTORY_SEPARATOR."database-{$sufijo}.sql";
        $binario = (string) config('backup.mysqldump_binary', 'mysqldump');
        $argumentos = [
            $binario,
            '--single-transaction',
            '--quick',
            '--skip-lock-tables',
            '--host='.(string) ($config['host'] ?? '127.0.0.1'),
            '--port='.(string) ($config['port'] ?? '3306'),
            '--user='.(string) ($config['username'] ?? ''),
            '--result-file='.$destino,
        ];

        if (! empty($config['unix_socket'])) {
            $argumentos[] = '--socket='.(string) $config['unix_socket'];
        }

        $argumentos[] = (string) ($config['database'] ?? '');

        $process = new Process(
            $argumentos,
            null,
            ['MYSQL_PWD' => (string) ($config['password'] ?? '')],
        );
        $process->setTimeout((int) config('backup.timeout_seconds', 600));
        $process->mustRun();

        if (! File::isFile($destino) || File::size($destino) === 0) {
            throw new RuntimeException('mysqldump finalizó sin producir un respaldo válido.');
        }

        return $destino;
    }

    private function aplicarRetencion(string $directorio, int $dias): int
    {
        $limite = now()->subDays($dias)->getTimestamp();
        $eliminados = 0;

        foreach (File::files($directorio) as $archivo) {
            if ($archivo->getMTime() >= $limite) {
                continue;
            }

            if (File::delete($archivo->getPathname())) {
                $eliminados++;
            }
        }

        return $eliminados;
    }

    /** @return array{0: string|null, 1: int} */
    private function copiarRemoto(string $ruta, int $diasRetencion): array
    {
        $diskName = config('backup.remote_disk');

        if (! is_string($diskName) || blank($diskName)) {
            return [null, 0];
        }

        $disk = Storage::disk($diskName);
        $prefix = trim((string) config('backup.remote_prefix', 'backups'), '/');
        $rutaRemota = ($prefix === '' ? '' : $prefix.'/').basename($ruta);

        $this->subirArchivo($diskName, $ruta, $rutaRemota);
        $this->subirArchivo($diskName, $ruta.'.sha256', $rutaRemota.'.sha256');

        $limite = now()->subDays($diasRetencion)->getTimestamp();
        $eliminados = 0;

        foreach ($disk->files($prefix) as $archivo) {
            if ($disk->lastModified($archivo) >= $limite) {
                continue;
            }

            if ($disk->delete($archivo)) {
                $eliminados++;
            }
        }

        return [$rutaRemota, $eliminados];
    }

    private function subirArchivo(string $diskName, string $origen, string $destino): void
    {
        $stream = fopen($origen, 'rb');

        if ($stream === false) {
            throw new RuntimeException('No se pudo abrir el respaldo para copiarlo al almacenamiento remoto.');
        }

        try {
            if (! Storage::disk($diskName)->put($destino, $stream)) {
                throw new RuntimeException("No se pudo guardar la copia remota {$destino}.");
            }
        } finally {
            fclose($stream);
        }
    }
}
