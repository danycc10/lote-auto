<?php

namespace App\Livewire\Admin\ContratosFinanciamiento;

use App\Models\ContratoFinanciamiento;
use App\Services\Financiamiento\EstadoCuentaFinanciamientoService;
use Livewire\Component;

class EstadoCuenta extends Component
{
    public ContratoFinanciamiento $contrato;

    public array $estado = [];

    public function mount(ContratoFinanciamiento $contrato, EstadoCuentaFinanciamientoService $service): void
    {
        $this->contrato = $contrato;
        $this->estado = $service->build($contrato);
    }

    public function render()
    {
        return view('livewire.admin.contratos-financiamiento.estado-cuenta', [
            'contrato' => $this->contrato->fresh(['cliente', 'auto.marca', 'auto.modelo']),
            'resumen' => $this->estado['resumen'] ?? [],
            'cuotas' => $this->estado['cuotas'] ?? collect(),
            'pagos' => $this->estado['pagos'] ?? collect(),
            'recibos' => $this->estado['recibos'] ?? collect(),
            'historiales' => $this->estado['historiales'] ?? collect(),
        ])->layout('layouts.app');
    }
}
