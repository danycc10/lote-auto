<?php

namespace App\Livewire\Hooks;

use App\Exceptions\DemoModeException;
use App\Support\DemoMode;
use Closure;
use Livewire\ComponentHook;
use Throwable;

class DemoModeHook extends ComponentHook
{
    public function call(string $method, array $params, Closure $returnEarly): void
    {
        if (! (new DemoMode)->blocksLivewireMethod($method)) {
            return;
        }

        $this->notify();
        $returnEarly();
    }

    public function exception(Throwable $exception, Closure $stopPropagation): void
    {
        if (! $exception instanceof DemoModeException) {
            return;
        }

        $this->notify();
        $stopPropagation();
    }

    private function notify(): void
    {
        $this->component->dispatch(
            'toast',
            type: 'warning',
            title: 'Modo demo',
            message: 'Esta acción está deshabilitada en la demostración.',
        );
    }
}
