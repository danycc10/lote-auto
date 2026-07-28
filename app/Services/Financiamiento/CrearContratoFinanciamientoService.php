<?php

namespace App\Services\Financiamiento;

use App\Enums\ApartadoEstatus;
use App\Enums\AutoEstatus;
use App\Enums\ContratoEstatus;
use App\Enums\FormulaFinanciamiento;
use App\Models\ApartadoAuto;
use App\Models\Auto;
use App\Models\ContratoFinanciamiento;
use App\Services\Apartados\ConvertirApartadoEnContratoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CrearContratoFinanciamientoService
{
    public function __construct(
        private CalculadoraFinanciamientoService $calculadora,
        private GeneradorCuotasFinanciamientoService $generador,
        private HistorialFinanciamientoService $historial,
        private ConvertirApartadoEnContratoService $convertirApartado,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function crear(
        array $data,
        ?int $apartadoId,
        int $actorId,
        ?string $rutaContratoFirmado = null,
    ): ContratoFinanciamiento {
        return DB::transaction(function () use ($data, $apartadoId, $actorId, $rutaContratoFirmado) {
            $this->validarDatosFinancieros($data);

            $auto = Auto::query()
                ->whereKey((int) $data['auto_id'])
                ->lockForUpdate()
                ->firstOrFail();
            $apartado = $apartadoId
                ? ApartadoAuto::query()->whereKey($apartadoId)->lockForUpdate()->firstOrFail()
                : null;

            $this->validarDisponibilidad($auto, $apartado, $data);

            $montoFinanciado = max(
                (float) $data['precio_venta'] - (float) ($data['enganche'] ?? 0),
                0,
            )
                + (float) ($data['comision_apertura'] ?? 0)
                + (float) ($data['monto_seguro'] ?? 0)
                + (float) ($data['monto_gps'] ?? 0);
            $calculo = $this->calculadora->calcular(
                montoFinanciado: $montoFinanciado,
                tasaAnual: (float) ($data['tasa_interes'] ?? 0),
                plazo: (int) $data['plazo'],
                frecuencia: (string) $data['frecuencia'],
                formula: FormulaFinanciamiento::AnualidadV1,
            );

            $contrato = ContratoFinanciamiento::create([
                'folio' => $this->generarFolio(),
                'auto_id' => $auto->id,
                'cliente_id' => (int) $data['cliente_id'],
                'apartado_auto_id' => null,
                'vendedor_id' => $actorId,
                'fecha_contrato' => $data['fecha_contrato'],
                'fecha_primer_pago' => $data['fecha_primer_pago'] ?? null,
                'precio_contado' => $auto->precio_contado,
                'precio_venta' => $data['precio_venta'],
                'enganche' => $data['enganche'] ?? 0,
                'comision_apertura' => $data['comision_apertura'] ?? 0,
                'monto_seguro' => $data['monto_seguro'] ?? 0,
                'monto_gps' => $data['monto_gps'] ?? 0,
                'monto_financiado' => $calculo['monto_financiado'],
                'tasa_interes' => $data['tasa_interes'] ?? 0,
                'formula_calculo' => FormulaFinanciamiento::AnualidadV1->value,
                'plazo' => $data['plazo'],
                'frecuencia' => $data['frecuencia'],
                'monto_cuota' => $calculo['monto_cuota'],
                'total_pagar' => $calculo['total_pagar'],
                'total_pagado' => 0,
                'saldo_actual' => $calculo['total_pagar'],
                'dias_gracia' => $data['dias_gracia'] ?? 0,
                'tipo_recargo' => $data['tipo_recargo'] ?? null,
                'valor_recargo' => $data['valor_recargo'] ?? 0,
                'estatus' => $data['estatus'],
                'observaciones' => $data['observaciones'] ?? null,
                'ruta_contrato_firmado' => $rutaContratoFirmado,
            ]);

            $this->generador->regenerar($contrato);

            if ($apartado) {
                $this->convertirApartado->finalizarConversion($apartado, $contrato);
            } else {
                $auto->update([
                    'estatus' => AutoEstatus::Financiado->value,
                    'activo' => true,
                ]);
            }

            $this->historial->registrar(
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
                'Contrato creado y plan de financiamiento generado.',
                $actorId,
            );

            return $contrato->fresh();
        }, 3);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function validarDatosFinancieros(array $data): void
    {
        $precioVenta = (float) $data['precio_venta'];
        $enganche = (float) ($data['enganche'] ?? 0);

        if ($precioVenta < 0 || $enganche < 0 || $enganche > $precioVenta) {
            throw ValidationException::withMessages([
                'enganche' => 'El precio y el enganche no forman una operación válida.',
            ]);
        }

        foreach (['comision_apertura', 'monto_seguro', 'monto_gps'] as $campo) {
            if ((float) ($data[$campo] ?? 0) < 0) {
                throw ValidationException::withMessages([
                    $campo => 'Este importe no puede ser negativo.',
                ]);
            }
        }

        if (! in_array($data['estatus'], [
            ContratoEstatus::Borrador->value,
            ContratoEstatus::Activo->value,
        ], true)) {
            throw ValidationException::withMessages([
                'estatus' => 'Un contrato nuevo sólo puede iniciar como borrador o activo.',
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function validarDisponibilidad(Auto $auto, ?ApartadoAuto $apartado, array $data): void
    {
        if (! $auto->activo) {
            throw ValidationException::withMessages([
                'auto_id' => 'El auto seleccionado está inactivo.',
            ]);
        }

        $tieneContratoVigente = ContratoFinanciamiento::query()
            ->where('auto_id', $auto->id)
            ->whereNotIn('estatus', [
                ContratoEstatus::Cancelado->value,
                ContratoEstatus::Recuperado->value,
            ])
            ->exists();

        if ($tieneContratoVigente) {
            throw ValidationException::withMessages([
                'auto_id' => 'El auto ya está asociado a otro contrato vigente.',
            ]);
        }

        if (! $apartado) {
            if (! in_array($auto->estatus, [
                AutoEstatus::Disponible->value,
                AutoEstatus::Recuperado->value,
            ], true)) {
                throw ValidationException::withMessages([
                    'auto_id' => 'El auto no está disponible para generar un contrato.',
                ]);
            }

            return;
        }

        if ($apartado->estatus !== ApartadoEstatus::Activo->value) {
            throw ValidationException::withMessages([
                'apartado_auto_id' => 'El apartado ya no está activo.',
            ]);
        }

        if (
            (int) $apartado->auto_id !== $auto->id
            || (int) $apartado->cliente_id !== (int) $data['cliente_id']
        ) {
            throw ValidationException::withMessages([
                'apartado_auto_id' => 'El apartado no corresponde al auto y cliente seleccionados.',
            ]);
        }

        if ($auto->estatus !== AutoEstatus::Apartado->value) {
            throw ValidationException::withMessages([
                'auto_id' => 'El auto del apartado ya no está reservado.',
            ]);
        }

        if ((float) ($data['enganche'] ?? 0) < (float) $apartado->monto_anticipo) {
            throw ValidationException::withMessages([
                'enganche' => 'El enganche no puede ser menor al anticipo del apartado.',
            ]);
        }
    }

    private function generarFolio(): string
    {
        do {
            $folio = 'CF-'.now()->format('Ymd').'-'.Str::upper(Str::random(8));
        } while (ContratoFinanciamiento::query()->where('folio', $folio)->exists());

        return $folio;
    }
}
