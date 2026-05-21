<?php

namespace App\Livewire\Admin\ContratosFinanciamiento;

use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use App\Models\TarjetaCobro;
use App\Services\Financiamiento\EstadoCuentaFinanciamientoService;
use App\Services\Financiamiento\RegistrarPagoFinanciamientoService;
use Livewire\Component;

class RegistrarPago extends Component
{
    public ContratoFinanciamiento $contrato;

    public $cuota_id = null;
    public $fecha_pago;
    public $monto;
    public bool $incluirRecargo = false;
    public $forma_pago = 'efectivo';
    public $tarjeta_cobro_id = null;
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

    public function getTarjetasDisponiblesProperty()
    {
        $formasConTarjeta = ['tarjeta', 'transferencia', 'deposito'];

        if (! in_array($this->forma_pago, $formasConTarjeta, true)) {
            return collect();
        }

        return TarjetaCobro::query()
            ->where('tipo', $this->forma_pago)
            ->where('activa', true)
            ->orderBy('nombre')
            ->get();
    }

    public function getCuotasDisponiblesProperty()
    {
        return CuotaFinanciamiento::query()
            ->where('contrato_financiamiento_id', $this->contrato->id)
            ->whereIn('estatus', ['pendiente', 'parcial', 'vencida'])
            ->orderBy('numero')
            ->get();
    }

    public function getCuotaSeleccionadaProperty(): ?CuotaFinanciamiento
    {
        if (!$this->cuota_id) {
            return null;
        }
        return $this->cuotasDisponibles->firstWhere('id', $this->cuota_id);
    }

    public function seleccionarCuota(int $id): void
    {
        $cuota = $this->cuotasDisponibles->firstWhere('id', $id);
        if (!$cuota) {
            return;
        }

        $this->cuota_id = $id;
        $this->incluirRecargo = false;
        $this->monto = number_format((float) $cuota->saldo, 2, '.', '');
        $this->concepto = 'Pago de cuota #' . $cuota->numero;
    }

    public function updatedIncluirRecargo($value): void
    {
        $cuota = $this->cuotaSeleccionada;
        if (!$cuota) {
            return;
        }

        $saldo = (float) $cuota->saldo;
        $recargo = $this->recargoSugerido;

        $this->monto = number_format(
            $value ? $saldo + $recargo : $saldo,
            2, '.', ''
        );
    }

    public function updatedFormaPago(): void
    {
        $this->tarjeta_cobro_id = null;
    }

    public function updatedCuotaId($value): void
    {
        $this->incluirRecargo = false;

        if (!$value) {
            $this->monto = null;
            return;
        }

        $cuota = CuotaFinanciamiento::query()
            ->where('contrato_financiamiento_id', $this->contrato->id)
            ->find($value);

        if ($cuota) {
            $this->monto = number_format((float) $cuota->saldo, 2, '.', '');
            $this->concepto = 'Pago de cuota #' . $cuota->numero;
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
                $this->tarjeta_cobro_id ? (int) $this->tarjeta_cobro_id : null,
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
            'cuotasDisponibles'   => $this->cuotasDisponibles,
            'recargoSugerido'     => $this->recargoSugerido,
            'cuotaSeleccionada'   => $this->cuotaSeleccionada,
            'tarjetasDisponibles' => $this->tarjetasDisponibles,
        ])
            ->layout('layouts.app')
            ->title('Registrar pago');
    }
}