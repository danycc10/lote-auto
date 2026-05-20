<?php

namespace App\Livewire\Admin\ContratosFinanciamiento;

use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use App\Services\Financiamiento\EstadoCuentaFinanciamientoService;
use App\Services\Financiamiento\RegistrarPagoFinanciamientoService;
use Livewire\Component;

class RegistrarPago extends Component
{
    public ContratoFinanciamiento $contrato;

    public $cuota_id = null;
    public $fecha_pago;
    public $monto;
    public $forma_pago = 'efectivo';
    public $referencia = null;
    public $concepto = null;
    public $observaciones = null;

    public function mount(ContratoFinanciamiento $contrato): void
    {
        $this->contrato = $contrato->load([
            'cliente',
            'auto.marca',
            'auto.modelo',
        ]);

        $this->fecha_pago = now()->toDateString();
    }

    protected function rules(): array
    {
        return [
            'cuota_id' => ['required', 'exists:cuotas_financiamiento,id'],
            'fecha_pago' => ['required', 'date'],
            'monto' => ['required', 'numeric', 'min:0.01'],
            'forma_pago' => ['required', 'in:efectivo,transferencia,tarjeta,deposito,otro'],
            'referencia' => ['nullable', 'string', 'max:255'],
            'concepto' => ['nullable', 'string', 'max:255'],
            'observaciones' => ['nullable', 'string'],
        ];
    }

    public function getRecargoSugeridoProperty(): float
    {
        if (!$this->cuota_id) {
            return 0.0;
        }

        $cuota = CuotaFinanciamiento::with('contrato')
            ->where('contrato_financiamiento_id', $this->contrato->id)
            ->find($this->cuota_id);

        if (!$cuota) {
            return 0.0;
        }

        return app(EstadoCuentaFinanciamientoService::class)->recargoSugerido($cuota);
    }

    public function getCuotasDisponiblesProperty()
    {
        return CuotaFinanciamiento::query()
            ->where('contrato_financiamiento_id', $this->contrato->id)
            ->whereIn('estatus', ['pendiente', 'parcial', 'vencida'])
            ->orderBy('numero')
            ->get();
    }

    public function updatedCuotaId($value): void
    {
        if (!$value) {
            $this->monto = null;
            return;
        }

        $cuota = CuotaFinanciamiento::query()
            ->where('contrato_financiamiento_id', $this->contrato->id)
            ->find($value);

        if ($cuota) {
            $this->monto = number_format((float) $cuota->saldo, 2, '.', '');

            if (blank($this->concepto)) {
                $this->concepto = 'Pago de cuota #' . $cuota->numero;
            }
        }
    }

    public function guardar(RegistrarPagoFinanciamientoService $service): void
    {
        $this->validate();

        $cuota = CuotaFinanciamiento::query()
            ->where('contrato_financiamiento_id', $this->contrato->id)
            ->findOrFail($this->cuota_id);

        try {
            $resultado = $service->ejecutar(
                $this->contrato,
                (float) str_replace(',', '', (string) $this->monto),
                $cuota,
                $this->fecha_pago,
                $this->concepto,
                $this->observaciones,
                $this->forma_pago,
                $this->referencia,
            );
        } catch (\RuntimeException $e) {
            $this->addError('monto', $e->getMessage());
            return;
        }

        session()->flash('ok', 'Pago registrado correctamente.');

        $this->redirectRoute('admin.recibos.show', $resultado['recibo']->id);
    }

    public function render()
    {
        return view('livewire.admin.contratos-financiamiento.registrar-pago', [
            'cuotasDisponibles' => $this->cuotasDisponibles,
            'recargoSugerido' => $this->recargoSugerido,
        ])
            ->layout('layouts.app')
            ->title('Registrar pago');
    }
}