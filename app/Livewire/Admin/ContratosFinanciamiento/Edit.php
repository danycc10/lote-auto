<?php

namespace App\Livewire\Admin\ContratosFinanciamiento;

use App\Models\Auto;
use App\Models\Cliente;
use App\Models\ContratoFinanciamiento;
use App\Services\Financiamiento\GeneradorCuotasFinanciamientoService;
use App\Services\Financiamiento\HistorialFinanciamientoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public ContratoFinanciamiento $contrato;

    public $folio;
    public $auto_id;
    public $cliente_id;
    public $vendedor_id;

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

    public function mount(ContratoFinanciamiento $contrato): void
    {
        $this->contrato = $contrato->load(['auto', 'cliente', 'cuotas']);

        $this->folio = $contrato->folio;
        $this->auto_id = $contrato->auto_id;
        $this->cliente_id = $contrato->cliente_id;
        $this->vendedor_id = $contrato->vendedor_id;

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
            'folio' => 'required|string|max:255|unique:contratos_financiamiento,folio,' . $this->contrato->id,
            'auto_id' => 'required|exists:autos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'vendedor_id' => 'nullable|exists:users,id',

            'fecha_contrato' => 'required|date',
            'fecha_primer_pago' => 'nullable|date|after_or_equal:fecha_contrato',

            'precio_contado' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'enganche' => 'nullable|numeric|min:0',
            'comision_apertura' => 'nullable|numeric|min:0',
            'monto_seguro' => 'nullable|numeric|min:0',
            'monto_gps' => 'nullable|numeric|min:0',

            'monto_financiado' => 'required|numeric|min:0',
            'tasa_interes' => 'nullable|numeric|min:0',
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

    public function recalcularTotales(): void
    {
        $precioVenta = (float) $this->precio_venta;
        $enganche = (float) $this->enganche;
        $comision = (float) $this->comision_apertura;
        $seguro = (float) $this->monto_seguro;
        $gps = (float) $this->monto_gps;
        $tasa = (float) $this->tasa_interes;
        $plazo = max((int) $this->plazo, 1);

        $montoBase = max($precioVenta - $enganche, 0);
        $montoFinanciado = $montoBase + $comision + $seguro + $gps;
        $interesTotal = $montoFinanciado * ($tasa / 100);
        $totalPagar = $montoFinanciado + $interesTotal;
        $montoCuota = $plazo > 0 ? ($totalPagar / $plazo) : 0;

        $this->monto_financiado = round($montoFinanciado, 2);
        $this->total_pagar = round($totalPagar, 2);
        $this->monto_cuota = round($montoCuota, 2);

        $pagado = (float) $this->total_pagado;
        $saldo = max($this->total_pagar - $pagado, 0);

        $this->saldo_actual = round($saldo, 2);
    }

    public function guardar(GeneradorCuotasFinanciamientoService $generador, HistorialFinanciamientoService $historial)
    {
        $data = $this->validate();

        if ($this->contrato->pagos()->where('estatus', 'aplicado')->exists()) {
            $this->addError('folio', 'Este contrato ya tiene pagos aplicados. Para no romper la trazabilidad financiera, ya no se puede regenerar desde edición.');
            return;
        }

        $antes = $this->contrato->only([
            'folio','auto_id','cliente_id','fecha_contrato','fecha_primer_pago','precio_venta','enganche',
            'monto_financiado','tasa_interes','plazo','frecuencia','monto_cuota','total_pagar','saldo_actual','estatus'
        ]);

        DB::transaction(function () use ($data, $generador, $historial, $antes) {
            $autoAnteriorId = $this->contrato->auto_id;

            $this->contrato->update([
                'folio' => $data['folio'],
                'auto_id' => $data['auto_id'],
                'cliente_id' => $data['cliente_id'],
                'vendedor_id' => $data['vendedor_id'] ?? $this->contrato->vendedor_id,
                'fecha_contrato' => $data['fecha_contrato'],
                'fecha_primer_pago' => $data['fecha_primer_pago'] ?? null,
                'precio_contado' => $data['precio_contado'],
                'precio_venta' => $data['precio_venta'],
                'enganche' => $data['enganche'] ?? 0,
                'comision_apertura' => $data['comision_apertura'] ?? 0,
                'monto_seguro' => $data['monto_seguro'] ?? 0,
                'monto_gps' => $data['monto_gps'] ?? 0,
                'monto_financiado' => $data['monto_financiado'],
                'tasa_interes' => $data['tasa_interes'] ?? 0,
                'plazo' => $data['plazo'],
                'frecuencia' => $data['frecuencia'],
                'monto_cuota' => $data['monto_cuota'],
                'total_pagar' => $data['total_pagar'],
                'total_pagado' => $data['total_pagado'] ?? 0,
                'saldo_actual' => $data['saldo_actual'],
                'dias_gracia' => $data['dias_gracia'] ?? 0,
                'tipo_recargo' => $data['tipo_recargo'] ?? null,
                'valor_recargo' => $data['valor_recargo'] ?? 0,
                'estatus' => $data['estatus'],
                'observaciones' => $data['observaciones'] ?? null,
            ]);

            if ($this->contrato_firmado) {
                if ($this->contrato->ruta_contrato_firmado && Storage::disk('private')->exists($this->contrato->ruta_contrato_firmado)) {
                    Storage::disk('private')->delete($this->contrato->ruta_contrato_firmado);
                }

                $slug = Str::slug($this->contrato->folio);
                $ruta = $this->contrato_firmado->store("contratos-financiamiento/{$this->contrato->id}-{$slug}", 'private');

                $this->contrato->update([
                    'ruta_contrato_firmado' => $ruta,
                ]);
            }

            $generador->regenerar($this->contrato->fresh());

            if ($autoAnteriorId !== (int) $data['auto_id']) {
                $autoAnterior = Auto::find($autoAnteriorId);
                if ($autoAnterior) {
                    $autoAnterior->update([
                        'estatus' => 'disponible',
                    ]);
                }
            }

            $autoNuevo = Auto::find($data['auto_id']);
            if ($autoNuevo) {
                $autoNuevo->update([
                    'estatus' => in_array($data['estatus'], ['cancelado', 'recuperado'], true) ? 'recuperado' : 'financiado',
                ]);
            }

            $historial->registrar(
                $this->contrato->fresh(),
                'contrato_actualizado',
                $antes,
                $this->contrato->fresh()->only([
                    'folio','auto_id','cliente_id','fecha_contrato','fecha_primer_pago','precio_venta','enganche',
                    'monto_financiado','tasa_interes','plazo','frecuencia','monto_cuota','total_pagar','saldo_actual','estatus'
                ]),
                'Contrato actualizado y cuotas regeneradas.'
            );
        });

        session()->flash('success', 'Contrato actualizado correctamente. Las cuotas fueron regeneradas.');

        return redirect()->route('admin.contratos-financiamiento.edit', $this->contrato);
    }

    public function eliminarArchivoContrato(): void
    {
        if ($this->contrato->ruta_contrato_firmado && Storage::disk('private')->exists($this->contrato->ruta_contrato_firmado)) {
            Storage::disk('private')->delete($this->contrato->ruta_contrato_firmado);
        }

        $this->contrato->update([
            'ruta_contrato_firmado' => null,
        ]);

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