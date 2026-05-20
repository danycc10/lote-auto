<?php

namespace App\Livewire\Admin\ContratosFinanciamiento;

use App\Models\Auto;
use App\Models\Cliente;
use App\Models\ContratoFinanciamiento;
use App\Models\ApartadoAuto;
use App\Services\Apartados\ConvertirApartadoEnContratoService;
use App\Services\Financiamiento\GeneradorCuotasFinanciamientoService;
use App\Services\Financiamiento\HistorialFinanciamientoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

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

    public ?int $apartado_auto_id = null;

    public bool $bloquear_auto_cliente = false;
    public float $anticipo_apartado = 0;
    public ?ApartadoAuto $apartadoActual = null;

    public function mount(): void
    {
        $this->fecha_contrato = now()->toDateString();
        $this->fecha_primer_pago = now()->addWeek()->toDateString();
        $this->vendedor_id = auth()->id();
        $this->folio = $this->generarFolio();

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
                $query->whereIn('estatus', ['disponible', 'apartado', 'recuperado']);
            })
            ->orderByDesc('id')
            ->get()
            ->map(function ($auto) {
                $auto->label = trim(
                    ($auto->marca->nombre ?? '') . ' ' .
                    ($auto->modelo->nombre ?? '') . ' ' .
                    ($auto->anio ?? '')
                );

                if (!empty($auto->codigo_inventario)) {
                    $auto->label .= ' | Código: ' . $auto->codigo_inventario;
                }

                if (!empty($auto->placa)) {
                    $auto->label .= ' | Placa: ' . $auto->placa;
                }

                if (!empty($auto->vin)) {
                    $auto->label .= ' | VIN: ' . $auto->vin;
                }

                if (!empty($auto->estatus)) {
                    $auto->label .= ' | ' . strtoupper($auto->estatus);
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

        if (!$value) {
            return;
        }

        $auto = Auto::find($value);

        if (!$auto) {
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
        $this->saldo_actual = round($totalPagar, 2);
        $this->total_pagado = 0;
    }

    protected function generarFolio(): string
    {
        $prefijo = 'CF-' . now()->format('Ymd');

        $ultimo = ContratoFinanciamiento::query()
            ->where('folio', 'like', $prefijo . '-%')
            ->latest('id')
            ->value('folio');

        if (!$ultimo) {
            return $prefijo . '-001';
        }

        $partes = explode('-', $ultimo);
        $numero = (int) end($partes);
        $siguiente = str_pad((string) ($numero + 1), 3, '0', STR_PAD_LEFT);

        return $prefijo . '-' . $siguiente;
    }

    public function guardar(
        GeneradorCuotasFinanciamientoService $generador,
        HistorialFinanciamientoService $historial,
        ConvertirApartadoEnContratoService $convertirService
    ) {
        $data = $this->validate();

        $data['folio'] = $this->generarFolio();
        $this->folio = $data['folio'];

        $auto = Auto::findOrFail($data['auto_id']);

        if (!in_array($auto->estatus, ['disponible', 'apartado', 'recuperado'], true)) {
            $this->addError('auto_id', 'Ese auto no está disponible para generar un contrato.');
            return;
        }

        if ($this->apartado_auto_id) {
            $apartado = ApartadoAuto::with(['auto', 'cliente'])->findOrFail($this->apartado_auto_id);

            $convertirService->validarParaConvertir($apartado);

            if ((int) $apartado->auto_id !== (int) $data['auto_id']) {
                $this->addError('auto_id', 'El auto no coincide con el apartado seleccionado.');
                return;
            }

            if ((int) $apartado->cliente_id !== (int) $data['cliente_id']) {
                $this->addError('cliente_id', 'El cliente no coincide con el apartado seleccionado.');
                return;
            }

            if ((float) $data['enganche'] < (float) $apartado->monto_anticipo) {
                $this->addError('enganche', 'El enganche no puede ser menor al anticipo del apartado.');
                return;
            }
        }

        $contratoCreado = DB::transaction(function () use ($data, $auto, $generador, $historial, $convertirService) {
            $contrato = ContratoFinanciamiento::create([
                'folio' => $data['folio'],
                'auto_id' => $data['auto_id'],
                'cliente_id' => $data['cliente_id'],
                'apartado_auto_id' => $this->apartado_auto_id,
                'vendedor_id' => $data['vendedor_id'] ?? Auth::id(),
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
                'ruta_contrato_firmado' => null,
            ]);

            if ($this->contrato_firmado) {
                $slug = Str::slug($contrato->folio);
                $ruta = $this->contrato_firmado->store("contratos-financiamiento/{$contrato->id}-{$slug}", 'private');

                $contrato->update([
                    'ruta_contrato_firmado' => $ruta,
                ]);
            }

            $generador->regenerar($contrato);

            if ($this->apartado_auto_id) {
                $apartado = ApartadoAuto::findOrFail($this->apartado_auto_id);
                $convertirService->finalizarConversion($apartado, $contrato);
            }

            $historial->registrar(
                $contrato,
                'contrato_creado',
                null,
                $contrato->fresh()->only([
                    'folio',
                    'cliente_id',
                    'auto_id',
                    'fecha_contrato',
                    'monto_financiado',
                    'total_pagar',
                    'saldo_actual',
                    'estatus',
                ]),
                'Contrato creado y plan de financiamiento generado.'
            );

            $auto->update([
                'estatus' => 'financiado',
                'activo' => true,
            ]);

            return $contrato;
        });

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