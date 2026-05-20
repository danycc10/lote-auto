<?php

namespace App\Livewire\Admin\Finanzas;

use App\Models\LogFinanciero;
use Livewire\Component;
use Livewire\WithPagination;

class LogsFinancierosIndex extends Component
{
    use WithPagination;

    public string $buscar = '';
    public string $tipo = '';
    public string $nivel = '';
    public ?string $fechaInicio = null;
    public ?string $fechaFin = null;

    public ?int $logSeleccionadoId = null;

    protected $queryString = [
        'buscar' => ['except' => ''],
        'tipo' => ['except' => ''],
        'nivel' => ['except' => ''],
        'fechaInicio' => ['except' => null],
        'fechaFin' => ['except' => null],
    ];

    public function updatingBuscar(): void
    {
        $this->resetPage();
    }

    public function updatingTipo(): void
    {
        $this->resetPage();
    }

    public function updatingNivel(): void
    {
        $this->resetPage();
    }

    public function updatingFechaInicio(): void
    {
        $this->resetPage();
    }

    public function updatingFechaFin(): void
    {
        $this->resetPage();
    }

    public function limpiarFiltros(): void
    {
        $this->buscar = '';
        $this->tipo = '';
        $this->nivel = '';
        $this->fechaInicio = null;
        $this->fechaFin = null;
        $this->logSeleccionadoId = null;

        $this->resetPage();
    }

    public function verDetalle(int $logId): void
    {
        $this->logSeleccionadoId = $logId;
    }

    public function cerrarDetalle(): void
    {
        $this->logSeleccionadoId = null;
    }

    public function render()
    {
        $query = LogFinanciero::query()
            ->with('usuario')
            ->when($this->buscar !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('titulo', 'like', '%' . $this->buscar . '%')
                        ->orWhere('descripcion', 'like', '%' . $this->buscar . '%')
                        ->orWhere('referencia', 'like', '%' . $this->buscar . '%')
                        ->orWhere('tipo', 'like', '%' . $this->buscar . '%');
                });
            })
            ->when($this->tipo !== '', fn ($q) => $q->where('tipo', $this->tipo))
            ->when($this->nivel !== '', fn ($q) => $q->where('nivel', $this->nivel))
            ->when($this->fechaInicio, fn ($q) => $q->whereDate('created_at', '>=', $this->fechaInicio))
            ->when($this->fechaFin, fn ($q) => $q->whereDate('created_at', '<=', $this->fechaFin));

        $logs = $query
            ->latest()
            ->paginate(15);

        $tipos = LogFinanciero::query()
            ->whereNotNull('tipo')
            ->select('tipo')
            ->distinct()
            ->orderBy('tipo')
            ->pluck('tipo')
            ->values();

        $niveles = LogFinanciero::query()
            ->whereNotNull('nivel')
            ->select('nivel')
            ->distinct()
            ->orderBy('nivel')
            ->pluck('nivel')
            ->values();

        $logSeleccionado = $this->logSeleccionadoId
            ? LogFinanciero::query()
                ->with('usuario')
                ->find($this->logSeleccionadoId)
            : null;

        return view('livewire.admin.finanzas.logs-financieros-index', [
            'logs' => $logs,
            'tipos' => $tipos,
            'niveles' => $niveles,
            'logSeleccionado' => $logSeleccionado,
        ])->layout('layouts.app');
    }
}