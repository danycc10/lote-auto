<?php

namespace App\Livewire\Admin\ContratosFinanciamiento;

use App\Enums\AutoEstatus;
use App\Enums\FormulaFinanciamiento;
use App\Models\Auto;
use App\Models\Cliente;
use App\Models\ContratoFinanciamiento;
use App\Services\Financiamiento\ActualizarContratoFinanciamientoService;
use App\Services\Financiamiento\CalculadoraFinanciamientoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    private CalculadoraFinanciamientoService $calculadora;

    public ContratoFinanciamiento $contrato;

    public $folio;

    public $auto_id;

    public $cliente_id;

    public $fecha_contrato;

    public $fecha_primer_pago;

    public $precio_contado = 0;

    public $precio_venta = 0;

    public $enganche = 0;

    public $comision_apertura = 0;

    public $monto_seguro = 0;

    public $monto_gps = 0;

    public $monto_financiado = 0;

    public $tasa_interes = 0;

    public $plazo = 12;

    public $frecuencia = 'semanal';

    public $monto_cuota = 0;

    public $total_pagar = 0;

    public $total_pagado = 0;

    public $saldo_actual = 0;

    public $dias_gracia = 0;

    public $tipo_recargo;

    public $valor_recargo = 0;

    public $estatus = 'activo';

    public $observaciones;

    public $contrato_firmado;

    public function boot(CalculadoraFinanciamientoService $calculadora): void
    {
        $this->calculadora = $calculadora;
    }

    public function mount(ContratoFinanciamiento $contrato): void
    {
        $this->contrato = $contrato->load(['auto', 'cliente', 'cuotas']);

        $this->folio = $contrato->folio;
        $this->auto_id = $contrato->auto_id;
        $this->cliente_id = $contrato->cliente_id;
        $this->fecha_contrato = optional($contrato->fecha_contrato)->format('Y-m-d');
        $this->fecha_primer_pago = optional($contrato->fecha_primer_pago)->format('Y-m-d');

        $this->precio_contado = (float) $contrato->precio_contado;
        $this->precio_venta = (float) $contrato->precio_venta;
        $this->enganche = (float) $contrato->enganche;
        $this->comision_apertura = (float) $contrato->comision_apertura;
        $this->monto_seguro = (float) $contrato->monto_seguro;
        $this->monto_gps = (float) $contrato->monto_gps;

        $this->monto_financiado = (float) $contrato->monto_financiado;
        $this->tasa_interes = (float) $contrato->tasa_interes;
        $this->plazo = (int) $contrato->plazo;
        $this->frecuencia = $contrato->frecuencia;
        $this->monto_cuota = (float) $contrato->monto_cuota;

        $this->total_pagar = (float) $contrato->total_pagar;
        $this->total_pagado = (float) $contrato->total_pagado;
        $this->saldo_actual = (float) $contrato->saldo_actual;

        $this->dias_gracia = (int) $contrato->dias_gracia;
        $this->tipo_recargo = $contrato->tipo_recargo;
        $this->valor_recargo = (float) $contrato->valor_recargo;

        $this->estatus = $contrato->estatus;
        $this->observaciones = $contrato->observaciones;
    }

    protected function rules(): array
    {
        return [
            'auto_id' => 'required|exists:autos,id',
            'cliente_id' => 'required|exists:clientes,id',

            'fecha_contrato' => 'required|date',
            'fecha_primer_pago' => 'nullable|date|after_or_equal:fecha_contrato',

            'precio_contado' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'enganche' => 'nullable|numeric|min:0|lte:precio_venta',
            'comision_apertura' => 'nullable|numeric|min:0',
            'monto_seguro' => 'nullable|numeric|min:0',
            'monto_gps' => 'nullable|numeric|min:0',

            'monto_financiado' => 'required|numeric|min:0',
            'tasa_interes' => 'nullable|numeric|min:0|max:100',
            'plazo' => 'required|integer|min:1|max:120',
            'frecuencia' => 'required|in:semanal,quincenal,mensual',
            'monto_cuota' => 'required|numeric|min:0',

            'total_pagar' => 'required|numeric|min:0',
            'total_pagado' => 'nullable|numeric|min:0',
            'saldo_actual' => 'required|numeric|min:0',

            'dias_gracia' => 'nullable|integer|min:0|max:60',
            'tipo_recargo' => 'nullable|in:fijo,porcentaje',
            'valor_recargo' => 'nullable|numeric|min:0',

            'estatus' => 'required|in:borrador,activo,atrasado,liquidado,cancelado,reestructurado,recuperado',
            'observaciones' => 'nullable|string',

            'contrato_firmado' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
        ];
    }

    public function getAutosProperty()
    {
        return Auto::query()
            ->with(['marca', 'modelo'])
            ->where('activo', true)
            ->where(function ($query) {
                $query
                    ->whereKey($this->contrato->auto_id)
                    ->orWhereIn('estatus', [
                        AutoEstatus::Disponible->value,
                        AutoEstatus::Recuperado->value,
                    ]);
            })
            ->orderByDesc('id')
            ->get();
    }

    public function getClientesProperty()
    {
        return Cliente::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->orderBy('apellido_paterno')
            ->get();
    }

    public function updatedPrecioVenta(): void
    {
        $this->recalcularTotales();
    }

    public function updatedEnganche(): void
    {
        $this->recalcularTotales();
    }

    public function updatedComisionApertura(): void
    {
        $this->recalcularTotales();
    }

    public function updatedMontoSeguro(): void
    {
        $this->recalcularTotales();
    }

    public function updatedMontoGps(): void
    {
        $this->recalcularTotales();
    }

    public function updatedTasaInteres(): void
    {
        $this->recalcularTotales();
    }

    public function updatedPlazo(): void
    {
        $this->recalcularTotales();
    }

    public function updatedFrecuencia(): void
    {
        $this->recalcularTotales();
    }

    public function recalcularTotales(): void
    {
        $precioVenta = (float) $this->precio_venta;
        $enganche = (float) $this->enganche;
        $comision = (float) $this->comision_apertura;
        $seguro = (float) $this->monto_seguro;
        $gps = (float) $this->monto_gps;
        $plazo = max((int) $this->plazo, 1);

        $montoBase = max($precioVenta - $enganche, 0);
        $montoFinanciado = $montoBase + $comision + $seguro + $gps;
        $formula = FormulaFinanciamiento::tryFrom((string) $this->contrato->formula_calculo)
            ?? FormulaFinanciamiento::PlanaV1;
        $calculo = $this->calculadora->calcular(
            montoFinanciado: $montoFinanciado,
            tasaAnual: min(max((float) $this->tasa_interes, 0), 100),
            plazo: min($plazo, 120),
            frecuencia: in_array($this->frecuencia, ['semanal', 'quincenal', 'mensual'], true)
                ? $this->frecuencia
                : 'semanal',
            formula: $formula,
        );

        $this->monto_financiado = $calculo['monto_financiado'];
        $this->total_pagar = $calculo['total_pagar'];
        $this->monto_cuota = $calculo['monto_cuota'];

        $pagado = (float) $this->total_pagado;
        $saldo = max($this->total_pagar - $pagado, 0);

        $this->saldo_actual = round($saldo, 2);
    }

    public function guardar(ActualizarContratoFinanciamientoService $actualizador)
    {
        // Conserva lo efectivamente cobrado y recalcula el resto en servidor.
        $this->total_pagado = (float) $this->contrato->fresh()->total_pagado;
        $this->recalcularTotales();
        $data = $this->validate();
        $rutaAnterior = $this->contrato->ruta_contrato_firmado;
        $rutaNueva = $this->contrato_firmado
            ? $this->contrato_firmado->store(
                'contratos-financiamiento/pendientes/'.Str::uuid(),
                'private',
            )
            : null;

        try {
            $this->contrato = $actualizador->actualizar(
                $this->contrato,
                $data,
                (int) Auth::id(),
                $rutaNueva,
            );
        } catch (\Throwable $exception) {
            if ($rutaNueva) {
                Storage::disk('private')->delete($rutaNueva);
            }

            throw $exception;
        }

        if ($rutaNueva && $rutaAnterior) {
            Storage::disk('private')->delete($rutaAnterior);
        }

        session()->flash('success', 'Contrato actualizado correctamente. Las cuotas fueron regeneradas.');

        return redirect()->route('admin.contratos-financiamiento.edit', $this->contrato);
    }

    public function eliminarArchivoContrato(): void
    {
        $ruta = $this->contrato->ruta_contrato_firmado;

        $this->contrato->update([
            'ruta_contrato_firmado' => null,
        ]);

        if ($ruta) {
            Storage::disk('private')->delete($ruta);
        }

        session()->flash('success', 'Archivo del contrato eliminado correctamente.');
    }

    public function render()
    {
        $contrato = $this->contrato->fresh(['auto.marca', 'auto.modelo', 'cliente', 'cuotas']);

        return view('livewire.admin.contratos-financiamiento.edit', [
            'autos' => $this->autos,
            'clientes' => $this->clientes,
            'contratoActual' => $contrato,
        ])->layout('layouts.app');
    }
}
