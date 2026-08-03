<?php

namespace App\Services\Operations;

use App\Models\Configuracion;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Throwable;

class HostingReadinessService
{
    public const ERROR = 'error';

    public const OK = 'ok';

    public const WARNING = 'warning';

    public function __construct(private Application $app) {}

    /**
     * @return list<array{status: string, check: string, detail: string}>
     */
    public function inspect(): array
    {
        return [
            ...$this->inspectRuntime(),
            ...$this->inspectPaths(),
            ...$this->inspectServices(),
            ...$this->inspectProductionConfiguration(),
        ];
    }

    /** @return list<array{status: string, check: string, detail: string}> */
    private function inspectRuntime(): array
    {
        $minimumPhp = (string) config('hosting.minimum_php', '8.3.0');
        $requiredExtensions = array_values((array) config('hosting.required_extensions', []));
        $missingExtensions = array_values(array_filter(
            $requiredExtensions,
            fn (mixed $extension): bool => is_string($extension) && ! extension_loaded($extension),
        ));

        return [
            $this->result(
                version_compare(PHP_VERSION, $minimumPhp, '>='),
                'Versión de PHP',
                'Actual: '.PHP_VERSION.'; mínima: '.$minimumPhp,
            ),
            $this->result(
                $missingExtensions === [],
                'Extensiones de PHP',
                $missingExtensions === []
                    ? 'Todas las extensiones requeridas están disponibles.'
                    : 'Faltan: '.implode(', ', $missingExtensions),
            ),
        ];
    }

    /** @return list<array{status: string, check: string, detail: string}> */
    private function inspectPaths(): array
    {
        $publicPath = public_path();
        $sensitiveFiles = collect(['.env', 'artisan', 'composer.json'])
            ->filter(fn (string $file): bool => File::exists($publicPath.DIRECTORY_SEPARATOR.$file))
            ->values()
            ->all();

        return [
            $this->result(
                File::isDirectory($publicPath) && realpath($publicPath) !== realpath(base_path()),
                'Raíz pública',
                $publicPath,
            ),
            $this->result(
                $sensitiveFiles === [],
                'Archivos públicos sensibles',
                $sensitiveFiles === []
                    ? 'No se detectaron archivos internos en la raíz pública.'
                    : 'Expuestos: '.implode(', ', $sensitiveFiles),
            ),
            $this->result(
                File::isWritable(storage_path()) && File::isWritable(base_path('bootstrap/cache')),
                'Directorios de escritura',
                'storage y bootstrap/cache deben ser escribibles.',
            ),
            $this->result(
                File::exists(public_path('build/manifest.json')),
                'Assets de producción',
                'Se requiere public/build/manifest.json.',
                self::WARNING,
            ),
            $this->result(
                File::exists(public_path('storage')),
                'Enlace de almacenamiento público',
                'Ejecuta php artisan storage:link si se publican imágenes del inventario.',
                self::WARNING,
            ),
        ];
    }

    /** @return list<array{status: string, check: string, detail: string}> */
    private function inspectServices(): array
    {
        $results = [];

        try {
            DB::select('select 1');
            $results[] = $this->result(true, 'Base de datos', 'Conexión disponible: '.DB::connection()->getDriverName());
        } catch (Throwable $exception) {
            $results[] = $this->result(false, 'Base de datos', $exception->getMessage());
        }

        try {
            $key = 'hosting-readiness:'.Str::uuid();
            Cache::put($key, 'ok', 10);
            $available = Cache::pull($key) === 'ok';
            $results[] = $this->result($available, 'Cache', $available ? 'Lectura y escritura disponibles.' : 'El valor de prueba no pudo recuperarse.');
        } catch (Throwable $exception) {
            $results[] = $this->result(false, 'Cache', $exception->getMessage());
        }

        return $results;
    }

    /** @return list<array{status: string, check: string, detail: string}> */
    private function inspectProductionConfiguration(): array
    {
        if (! $this->app->environment('production')) {
            return [];
        }

        $appUrl = (string) config('app.url');
        $databaseDriver = DB::connection()->getDriverName();
        $workerMode = (string) config('hosting.queue_worker.mode', 'external');
        $instanceUuid = Configuracion::obtener('instalacion.uuid');
        $instanceName = Configuracion::obtener('instalacion.nombre');

        return [
            $this->result(filled(config('app.key')), 'Clave de aplicación', 'APP_KEY debe ser única y persistente.'),
            $this->result(filled($instanceUuid) && filled($instanceName), 'Identidad de la instalación', 'Ejecuta php artisan lote:aprovisionar antes de publicar.'),
            $this->result(! (bool) config('app.debug'), 'Modo de depuración', 'APP_DEBUG debe ser false.'),
            $this->result(Str::startsWith($appUrl, 'https://'), 'HTTPS', 'APP_URL debe utilizar https://.'),
            $this->result(in_array($databaseDriver, ['mysql', 'mariadb'], true), 'Base de datos de producción', 'Driver actual: '.$databaseDriver),
            $this->result(config('queue.default') !== 'sync', 'Cola asíncrona', 'QUEUE_CONNECTION no debe ser sync para reportes y correos.'),
            $this->result(in_array($workerMode, ['cron', 'supervisor', 'external'], true), 'Ejecución de la cola', 'Modo configurado: '.$workerMode),
            $this->result(config('cache.default') !== 'array', 'Cache persistente', 'CACHE_STORE no debe ser array.'),
            $this->result(config('mail.default') !== 'log', 'Correo saliente', 'Configura un proveedor SMTP real.', self::WARNING),
            $this->result(filled(config('backup.remote_disk')), 'Backup externo', 'Configura DB_BACKUP_REMOTE_DISK para conservar una copia fuera del hosting.', self::WARNING),
            $this->result(! (bool) config('demo.enabled'), 'Modo demo', 'APP_DEMO_MODE debe ser false.', self::WARNING),
        ];
    }

    /** @return array{status: string, check: string, detail: string} */
    private function result(bool $passes, string $check, string $detail, string $failureStatus = self::ERROR): array
    {
        return [
            'status' => $passes ? self::OK : $failureStatus,
            'check' => $check,
            'detail' => $detail,
        ];
    }
}
