<?php

namespace App\Livewire\Admin\ContratosFinanciamiento;

use App\Enums\AutoEstatus;
use App\Models\ApartadoAuto;
use App\Models\Auto;
use App\Models\Cliente;
use App\Services\Apartados\ConvertirApartadoEnContratoService;
use App\Services\Financiamiento\CalculadoraFinanciamientoService;
use App\Services\Financiamiento\CrearContratoFinanciamientoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    private CalculadoraFinanciamientoService $calculadora;

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

    public ?int $apartado_auto_id = null;

    public bool $bloquear_auto_cliente = false;

    public float $anticipo_apartado = 0;

    public ?ApartadoAuto $apartadoActual = null;

    public function boot(CalculadoraFinanciamientoService $calculadora): void
    {
        $this->calculadora = $calculadora;
    }

    public function mount(): void
    {
        $this->fecha_contrato = now()->toDateString();
        $this->fecha_primer_pago = now()->addWeek()->toDateString();
        $this->folio = 'Automático al guardar';

        $this->apartado_auto_id = request()->integer('apartado_auto_id') ?: null;

        if ($this->apartado_auto_id) {
            $apartado = ApartadoAuto::with([
                'auto.marca',
                'auto.modelo',
                'cliente',
            ])->findOrFail($this->apartado_auto_id);

            app(ConvertirApartadoEnContratoService::class)->validarParaConvertir($apartado);

            $this->apartadoActual = $apartado;
            $this->bloquear_auto_cliente = true;

            $this->auto_id = $apartado->auto_id;
            $this->cliente_id = $apartado->cliente_id;

            $this->anticipo_apartado = (float) $apartado->monto_anticipo;
            $this->enganche = (float) $apartado->monto_anticipo;

            if ($apartado->auto) {
                $this->precio_contado = (float) $apartado->auto->precio_contado;
                $this->precio_venta = (float) (
                    $apartado->auto->precio_financiado > 0
                        ? $apartado->auto->precio_financiado
                        : $apartado->auto->precio_contado
                );
            }

            $this->recalcularTotales();
        }
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

            'estatus' => 'required|in:borrador,activo',
            'observaciones' => 'nullable|string',

            'contrato_firmado' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
        ];
    }

    protected $messages = [
        'auto_id.required' => 'Debes seleccionar un auto.',
        'cliente_id.required' => 'Debes seleccionar un cliente.',
        'fecha_contrato.required' => 'La fecha del contrato es obligatoria.',
        'precio_venta.required' => 'El precio de venta es obligatorio.',
        'plazo.required' => 'El plazo es obligatorio.',
        'monto_cuota.required' => 'El monto de la cuota es obligatorio.',
        'contrato_firmado.mimes' => 'El contrato firmado debe ser PDF, JPG, JPEG, PNG o WEBP.',
        'contrato_firmado.max' => 'El contrato firmado no debe exceder 10 MB.',
    ];

    public function getAutosProperty()
    {
        return Auto::query()
            ->with(['marca', 'modelo'])
            ->where('activo', true)
            ->when($this->apartado_auto_id, function ($query) {
                $query->where('id', $this->auto_id);
            }, function ($query) {
                $query->whereIn('estatus', [AutoEstatus::Disponible->value, AutoEstatus::Recuperado->value]);
            })
            ->orderByDesc('id')
            ->get()
            ->map(function ($auto) {
                $auto->label = trim(
                    ($auto->marca->nombre ?? '').' '.
                    ($auto->modelo->nombre ?? '').' '.
                    ($auto->anio ?? '')
                );

                if (! empty($auto->codigo_inventario)) {
                    $auto->label .= ' | Código: '.$auto->codigo_inventario;
                }

                if (! empty($auto->placa)) {
                    $auto->label .= ' | Placa: '.$auto->placa;
                }

                if (! empty($auto->vin)) {
                    $auto->label .= ' | VIN: '.$auto->vin;
                }

                if (! empty($auto->estatus)) {
                    $auto->label .= ' | '.strtoupper($auto->estatus);
                }

                return $auto;
            });
    }

    public function getClientesProperty()
    {
        return Cliente::query()
            ->where('activo', true)
            ->when($this->apartado_auto_id, function ($query) {
                $query->where('id', $this->cliente_id);
            })
            ->orderBy('nombre')
            ->orderBy('apellido_paterno')
            ->get();
    }

    public function updatedAutoId($value): void
    {
        if ($this->bloquear_auto_cliente) {
            return;
        }

        if (! $value) {
            return;
        }

        $auto = Auto::find($value);

        if (! $auto) {
            return;
        }

        $this->precio_contado = (float) $auto->precio_contado;
        $this->precio_venta = (float) ($auto->precio_financiado > 0 ? $auto->precio_financiado : $auto->precio_contado);

        $this->recalcularTotales();
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
        $calculo = $this->calculadora->calcular(
            montoFinanciado: $montoFinanciado,
            tasaAnual: min(max((float) $this->tasa_interes, 0), 100),
            plazo: min($plazo, 120),
            frecuencia: in_array($this->frecuencia, ['semanal', 'quincenal', 'mensual'], true)
                ? $this->frecuencia
                : 'semanal',
        );

        $this->monto_financiado = $calculo['monto_financiado'];
        $this->total_pagar = $calculo['total_pagar'];
        $this->monto_cuota = $calculo['monto_cuota'];
        $this->saldo_actual = $calculo['total_pagar'];
        $this->total_pagado = 0;
    }

    public function guardar(CrearContratoFinanciamientoService $creador)
    {
        // Los importes derivados nunca se confían al estado enviado por el navegador.
        $this->recalcularTotales();
        $data = $this->validate();
        $rutaContrato = $this->contrato_firmado
            ? $this->contrato_firmado->store(
                'contratos-financiamiento/pendientes/'.Str::uuid(),
                'private',
            )
            : null;

        try {
            $contratoCreado = $creador->crear(
                $data,
                $this->apartado_auto_id,
                (int) Auth::id(),
                $rutaContrato,
            );
        } catch (\Throwable $exception) {
            if ($rutaContrato) {
                Storage::disk('private')->delete($rutaContrato);
            }

            throw $exception;
        }

        $this->folio = $contratoCreado->folio;

        session()->flash('success', 'Contrato creado correctamente y cuotas generadas.');

        return redirect()->route('admin.contratos-financiamiento.show', $contratoCreado);
    }

    public function render()
    {
        return view('livewire.admin.contratos-financiamiento.create', [
            'autos' => $this->autos,
            'clientes' => $this->clientes,
        ])->layout('layouts.app');
    }
}
