<?php

namespace App\Livewire\Admin\Sistema;

use App\Models\AuditoriaFinanciera;
use Livewire\Component;
use Livewire\WithPagination;

class AuditoriaIndex extends Component
{
    use WithPagination;

    public string $buscar = '';
    public string $accion = '';
    public string $modelo = '';
    public ?string $fechaInicio = null;
    public ?string $fechaFin = null;

    public ?int $auditoriaSeleccionadaId = null;

    protected $queryString = [
        'buscar' => ['except' => ''],
        'accion' => ['except' => ''],
        'modelo' => ['except' => ''],
        'fechaInicio' => ['except' => null],
        'fechaFin' => ['except' => null],
    ];

    public function updatingBuscar(): void
    {
        $this->resetPage();
    }

    public function updatingAccion(): void
    {
        $this->resetPage();
    }

    public function updatingModelo(): void
    {
        $this->resetPage();
    }

    public function limpiarFiltros(): void
    {
        $this->buscar = '';
        $this->accion = '';
        $this->modelo = '';
        $this->fechaInicio = null;
        $this->fechaFin = null;
        $this->auditoriaSeleccionadaId = null;

        $this->resetPage();
    }

    public function verDetalle(int $id): void
    {
        $this->auditoriaSeleccionadaId = $id;
    }

    public function cerrarDetalle(): void
    {
        $this->auditoriaSeleccionadaId = null;
    }

    public function render()
    {
        $auditorias = AuditoriaFinanciera::query()
            ->with('usuario')
            ->when($this->buscar, function ($query) {
                $query->where(function ($q) {
                    $q->where('accion', 'like', '%' . $this->buscar . '%')
                        ->orWhere('modelo', 'like', '%' . $this->buscar . '%')
                        ->orWhere('observaciones', 'like', '%' . $this->buscar . '%')
                        ->orWhere('ip', 'like', '%' . $this->buscar . '%');
                });
            })
            ->when($this->accion, fn ($q) => $q->where('accion', $this->accion))
            ->when($this->modelo, fn ($q) => $q->where('modelo', $this->modelo))
            ->when($this->fechaInicio, fn ($q) => $q->whereDate('created_at', '>=', $this->fechaInicio))
            ->when($this->fechaFin, fn ($q) => $q->whereDate('created_at', '<=', $this->fechaFin))
            ->latest()
            ->paginate(15);

        $acciones = AuditoriaFinanciera::query()
            ->select('accion')
            ->whereNotNull('accion')
            ->distinct()
            ->orderBy('accion')
            ->pluck('accion');

        $modelos = AuditoriaFinanciera::query()
            ->select('modelo')
            ->whereNotNull('modelo')
            ->distinct()
            ->orderBy('modelo')
            ->pluck('modelo');

        $auditoriaSeleccionada = $this->auditoriaSeleccionadaId
            ? AuditoriaFinanciera::with('usuario')->find($this->auditoriaSeleccionadaId)
            : null;

        return view('livewire.admin.sistema.auditoria-index', [
            'auditorias' => $auditorias,
            'acciones' => $acciones,
            'modelos' => $modelos,
            'auditoriaSeleccionada' => $auditoriaSeleccionada,
        ])->layout('layouts.app');
    }
}