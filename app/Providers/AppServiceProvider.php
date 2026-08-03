<?php

namespace App\Providers;

use App\Http\Middleware\PreventChangesInDemoMode;
use App\Http\Middleware\VerificarModuloFinanciamiento;
use App\Livewire\Hooks\DemoModeHook;
use App\Models\User;
use Illuminate\Database\Connection;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Events\DiagnosingHealth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use RuntimeException;
use Spatie\Permission\Middleware\PermissionMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $customPublicPath = config('hosting.public_path');

        if (is_string($customPublicPath) && filled($customPublicPath)) {
            $this->app->usePublicPath($customPublicPath);
        }

        $uploadMiddleware = config('livewire.temporary_file_upload.middleware')
            ?: ['throttle:60,1'];

        config()->set(
            'livewire.temporary_file_upload.middleware',
            array_values(array_unique([
                ...(array) $uploadMiddleware,
                PreventChangesInDemoMode::class,
            ])),
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user): ?bool {
            return $user->hasRole('administrador') ? true : null;
        });

        Livewire::addPersistentMiddleware([
            PermissionMiddleware::class,
            VerificarModuloFinanciamiento::class,
        ]);
        Livewire::componentHook(DemoModeHook::class);

        Event::listen(DiagnosingHealth::class, function (): void {
            DB::select('select 1');

            $clave = 'health:'.gethostname();
            Cache::put($clave, 'ok', 10);

            if (Cache::pull($clave) !== 'ok') {
                throw new RuntimeException('El cache no está disponible.');
            }
        });

        DB::whenQueryingForLongerThan(
            (int) config('database.slow_query_ms', 500),
            function (Connection $connection, QueryExecuted $event): void {
                Log::warning('Consulta lenta detectada.', [
                    'connection' => $connection->getName(),
                    'time_ms' => $event->time,
                    'sql' => $event->sql,
                ]);
            },
        );
    }
}
