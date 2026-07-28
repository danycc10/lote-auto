<?php

namespace App\Services\Financiamiento;

use App\Enums\AutoEstatus;
use App\Enums\ContratoEstatus;
use App\Enums\FormulaFinanciamiento;
use App\Enums\PagoEstatus;
use App\Models\Auto;
use App\Models\ContratoFinanciamiento;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ActualizarContratoFinanciamientoService
{
    public function __construct(
        private CalculadoraFinanciamientoService $calculadora,
        private GeneradorCuotasFinanciamientoService $generador,
        private HistorialFinanciamientoService $historial,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function actualizar(
        ContratoFinanciamiento $contrato,
        array $data,
        int $actorId,
        ?string $nuevaRutaContrato = null,
    ): ContratoFinanciamiento {
        return DB::transaction(function () use ($contrato, $data, $actorId, $nuevaRutaContrato) {
            $contrato = ContratoFinanciamiento::query()
                ->whereKey($contrato->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($contrato->pagos()->where('estatus', PagoEstatus::Aplicado->value)->exists()) {
                throw ValidationException::withMessages([
                    'folio' => 'El contrato ya tiene pagos aplicados y no puede regenerarse.',
                ]);
            }

            $autos = $this->bloquearAutos(
                (int) $contrato->auto_id,
                (int) $data['auto_id'],
            );
            $autoAnterior = $autos->get((int) $contrato->auto_id);
            $autoNuevo = $autos->get((int) $data['auto_id']);

            if (! $autoAnterior instanceof Auto || ! $autoNuevo instanceof Auto) {
                throw ValidationException::withMessages([
                    'auto_id' => 'No fue posible localizar el auto seleccionado.',
                ]);
            }

            $this->validarCambio($contrato, $autoNuevo, $data);

            $formula = FormulaFinanciamiento::tryFrom((string) $contrato->formula_calculo)
                ?? FormulaFinanciamiento::PlanaV1;
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
                formula: $formula,
            );
            $antes = $contrato->only([
                'folio',
                'auto_id',
                'cliente_id',
                'fecha_contrato',
                'fecha_primer_pago',
                'precio_venta',
                'enganche',
                'monto_financiado',
                'tasa_interes',
                'plazo',
                'frecuencia',
                'monto_cuota',
                'total_pagar',
                'saldo_actual',
                'estatus',
            ]);

            $contrato->update([
                'auto_id' => $autoNuevo->id,
                'cliente_id' => (int) $data['cliente_id'],
                'fecha_contrato' => $data['fecha_contrato'],
                'fecha_primer_pago' => $data['fecha_primer_pago'] ?? null,
                'precio_contado' => $autoNuevo->precio_contado,
                'precio_venta' => $data['precio_venta'],
                'enganche' => $data['enganche'] ?? 0,
                'comision_apertura' => $data['comision_apertura'] ?? 0,
                'monto_seguro' => $data['monto_seguro'] ?? 0,
                'monto_gps' => $data['monto_gps'] ?? 0,
                'monto_financiado' => $calculo['monto_financiado'],
                'tasa_interes' => $data['tasa_interes'] ?? 0,
                'plazo' => $data['plazo'],
                'frecuencia' => $data['frecuencia'],
                'monto_cuota' => $calculo['monto_cuota'],
                'total_pagar' => $calculo['total_pagar'],
                'saldo_actual' => $calculo['total_pagar'],
                'dias_gracia' => $data['dias_gracia'] ?? 0,
                'tipo_recargo' => $data['tipo_recargo'] ?? null,
                'valor_recargo' => $data['valor_recargo'] ?? 0,
                'estatus' => $data['estatus'],
                'observaciones' => $data['observaciones'] ?? null,
                'ruta_contrato_firmado' => $nuevaRutaContrato ?? $contrato->ruta_contrato_firmado,
            ]);

            $this->generador->regenerar($contrato);

            if ($autoAnterior->id !== $autoNuevo->id) {
                $autoAnterior->update([
                    'estatus' => AutoEstatus::Disponible->value,
                ]);
            }

            $autoNuevo->update([
                'estatus' => in_array($data['estatus'], [
                    ContratoEstatus::Cancelado->value,
                    ContratoEstatus::Recuperado->value,
                ], true)
                    ? AutoEstatus::Recuperado->value
                    : AutoEstatus::Financiado->value,
            ]);

            $this->historial->registrar(
                $contrato,
                'contrato_actualizado',
                $antes,
                $contrato->fresh()->only(array_keys($antes)),
                'Contrato actualizado y cuotas regeneradas.',
                $actorId,
            );

            return $contrato->fresh();
        }, 3);
    }

    /**
     * @return Collection<int, Auto>
     */
    private function bloquearAutos(int $autoAnteriorId, int $autoNuevoId): Collection
    {
        return Auto::query()
            ->whereKey([$autoAnteriorId, $autoNuevoId])
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function validarCambio(ContratoFinanciamiento $contrato, Auto $autoNuevo, array $data): void
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

        if (! in_array($data['estatus'], ContratoEstatus::values(), true)) {
            throw ValidationException::withMessages([
                'estatus' => 'El estatus del contrato no es válido.',
            ]);
        }

        $cambiaAuto = (int) $contrato->auto_id !== $autoNuevo->id;
        $cambiaCliente = (int) $contrato->cliente_id !== (int) $data['cliente_id'];

        if ($contrato->apartado_auto_id && ($cambiaAuto || $cambiaCliente)) {
            throw ValidationException::withMessages([
                'auto_id' => 'No se puede cambiar el auto o cliente de un contrato originado por apartado.',
            ]);
        }

        if (! $cambiaAuto) {
            return;
        }

        if (
            ! $autoNuevo->activo
            || ! in_array($autoNuevo->estatus, [
                AutoEstatus::Disponible->value,
                AutoEstatus::Recuperado->value,
            ], true)
        ) {
            throw ValidationException::withMessages([
                'auto_id' => 'El auto nuevo no está disponible para financiamiento.',
            ]);
        }

        $tieneContratoVigente = ContratoFinanciamiento::query()
            ->where('auto_id', $autoNuevo->id)
            ->whereKeyNot($contrato->id)
            ->whereNotIn('estatus', [
                ContratoEstatus::Cancelado->value,
                ContratoEstatus::Recuperado->value,
            ])
            ->exists();

        if ($tieneContratoVigente) {
            throw ValidationException::withMessages([
                'auto_id' => 'El auto nuevo ya está asociado a otro contrato vigente.',
            ]);
        }
    }
}
