<?php

namespace App\Livewire\Admin\Sistema;

use App\Services\Operations\OperationalHealthService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SaludIndex extends Component
{
    /** @var array<string, mixed> */
    public array $health = [];

    public function mount(OperationalHealthService $healthService): void
    {
        $this->authorizeAccess();
        $this->health = $healthService->snapshot();
    }

    public function refreshHealth(OperationalHealthService $healthService): void
    {
        $this->authorizeAccess();
        $this->health = $healthService->snapshot();
    }

    public function render(): View
    {
        return view('livewire.admin.sistema.salud-index')
            ->layout('layouts.app');
    }

    private function authorizeAccess(): void
    {
        abort_unless(auth()->user()?->can('sistema.salud.ver'), 403);
    }
}
