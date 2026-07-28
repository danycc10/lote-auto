<?php

namespace App\Providers;

use App\Http\Middleware\VerificarModuloFinanciamiento;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Spatie\Permission\Middleware\PermissionMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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
    }
}
