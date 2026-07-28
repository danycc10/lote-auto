<?php

namespace App\Livewire\Admin\ContratosFinanciamiento;

use App\Enums\PagoEstatus;
use App\Enums\ReciboEstatus;
use App\Models\ContratoFinanciamiento;
use Livewire\Component;

class Show extends Component
{
    public ContratoFinanciamiento $contrato;

    public function mount(ContratoFinanciamiento $contrato): void
    {
        $this->contrato = $contrato->load([
            'cliente',
            'vendedor',
            'auto.marca',
            'auto.modelo',

            'cuotas' => fn ($q) => $q->orderBy('numero'),

            'pagos' => fn ($q) => $q->where('estatus', PagoEstatus::Aplicado->value)
                ->latest('fecha_pago'),

            'pagos.cuota',

            'recibos' => fn ($q) => $q->where('estatus', ReciboEstatus::Vigente->value)
                ->latest('fecha_recibo'),

            'recibos.pago',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.contratos-financiamiento.show')
            ->layout('layouts.app')
            ->title('Detalle contrato');
    }
}
