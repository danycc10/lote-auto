<?php

namespace App\Services\Financiamiento;

use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use App\Models\PagoFinanciamiento;
use App\Models\ReciboFinanciamiento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class RegistrarPagoFinanciamientoService
{
    public function __construct(
        protected GenerarFolioReciboFinanciamientoService $folioService,
        protected AuditoriaFinancieraService $auditoriaService,
        protected LogFinancieroService $logFinancieroService,
    ) {}

    public function ejecutar(
        ContratoFinanciamiento $contrato,
        float $monto,
        ?CuotaFinanciamiento $cuota = null,
        ?string $fechaPago = null,
        ?string $concepto = null,
        ?string $observaciones = null,
        ?string $formaPago = null,
        ?string $referencia = null,
        ?int $tarjetaCobroId = null,
    ): array {
        return DB::transaction(function () use (
            $contrato,
            $monto,
            $cuota,
            $fechaPago,
            $concepto,
            $observaciones,
            $formaPago,
            $referencia,
            $tarjetaCobroId,
        ) {
            $fechaPago = $fechaPago ?: now()->toDateString();

            if (! Auth::user()?->can('pagos.registrar')) {
                throw new RuntimeException('No tienes permiso para registrar pagos.');
            }

            if ($monto <= 0) {
                throw new RuntimeException('El monto del pago debe ser mayor a 0.');
            }

            $contratoBloqueado = ContratoFinanciamiento::query()
                ->whereKey($contrato->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (in_array($contratoBloqueado->estatus, ['cancelado', 'reestructurado', 'recuperado'], true)) {
                throw new RuntimeException('No se puede registrar pago en un contrato con estatus: ' . $contratoBloqueado->estatus);
            }

            $cuotaBloqueada = null;

            if ($cuota) {
                $cuotaBloqueada = CuotaFinanciamiento::query()
                    ->whereKey($cuota->id)
                    ->where('contrato_financiamiento_id', $contratoBloqueado->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($cuotaBloqueada->estatus === 'cancelada') {
                    throw new RuntimeException('No se puede pagar una cuota cancelada.');
                }

                if ($cuotaBloqueada->estatus === 'pagada') {
                    throw new RuntimeException('Esta cuota ya está pagada.');
                }

                $saldoCuota = (float) ($cuotaBloqueada->saldo ?? 0);

                if ($saldoCuota <= 0) {
                    throw new RuntimeException('La cuota no tiene saldo pendiente.');
                }

                if ($monto > $saldoCuota) {
                    throw new RuntimeException('El monto no puede ser mayor al saldo pendiente de la cuota.');
                }
            }

            $saldoAnteriorContrato = (float) ($contratoBloqueado->saldo_actual ?? 0);

            if ($saldoAnteriorContrato <= 0) {
                throw new RuntimeException('El contrato ya no tiene saldo pendiente.');
            }

            if ($monto > $saldoAnteriorContrato) {
                throw new RuntimeException('El monto no puede ser mayor al saldo actual del contrato.');
            }

            $antesContrato = $contratoBloqueado->toArray();
            $antesCuota = $cuotaBloqueada?->toArray();

            $pago = PagoFinanciamiento::create([
                'contrato_financiamiento_id' => $contratoBloqueado->id,
                'cliente_id' => $contratoBloqueado->cliente_id,
                'cuota_id' => $cuotaBloqueada?->id,
                'capturado_por' => Auth::id(),
                'fecha_pago' => $fechaPago,
                'monto' => $monto,
                'monto_aplicado' => $monto,
                'monto_restante' => 0,
                'forma_pago' => $formaPago ?? 'efectivo',
                'referencia' => $referencia,
                'tarjeta_cobro_id' => $tarjetaCobroId,
                'estatus' => 'aplicado',
                'observaciones' => $observaciones,
            ]);

            if ($cuotaBloqueada) {
                $montoCuota = (float) ($cuotaBloqueada->monto ?? 0);
                $nuevoMontoPagado = (float) ($cuotaBloqueada->monto_pagado ?? 0) + $monto;

                $cuotaBloqueada->monto_pagado = $nuevoMontoPagado;
                $cuotaBloqueada->saldo = max(0, $montoCuota - $nuevoMontoPagado);

                if ($nuevoMontoPagado <= 0) {
                    $cuotaBloqueada->estatus = 'pendiente';
                    $cuotaBloqueada->fecha_pago = null;
                } elseif ($nuevoMontoPagado < $montoCuota) {
                    $cuotaBloqueada->estatus = 'parcial';
                    $cuotaBloqueada->fecha_pago = $fechaPago;
                } else {
                    $cuotaBloqueada->estatus = 'pagada';
                    $cuotaBloqueada->fecha_pago = $fechaPago;
                }

                $cuotaBloqueada->save();
            }

            $totalPagar = (float) ($contratoBloqueado->total_pagar ?? 0);
            $nuevoTotalPagado = (float) ($contratoBloqueado->total_pagado ?? 0) + $monto;
            $nuevoSaldoActual = max(0, $totalPagar - $nuevoTotalPagado);

            $contratoBloqueado->total_pagado = $nuevoTotalPagado;
            $contratoBloqueado->saldo_actual = $nuevoSaldoActual;

            $contratoBloqueado->recalcularEstatus();
            $contratoBloqueado->save();

            $folio = $this->folioService->execute($fechaPago);

            $recibo = ReciboFinanciamiento::create([
                'folio' => $folio,
                'contrato_financiamiento_id' => $contratoBloqueado->id,
                'cuota_id' => $cuotaBloqueada?->id,
                'pago_financiamiento_id' => $pago->id,
                'cliente_id' => $contratoBloqueado->cliente_id,
                'fecha_recibo' => $fechaPago,
                'monto' => $monto,
                'saldo_anterior' => $saldoAnteriorContrato,
                'saldo_posterior' => $nuevoSaldoActual,
                'concepto' => $concepto ?: 'Pago de financiamiento',
                'observaciones' => $observaciones,
                'estatus' => 'vigente',
            ]);

            $this->auditoriaService->registrar(
                accion: 'pago_registrado',
                modelo: $pago,
                antes: [
                    'contrato' => $antesContrato,
                    'cuota' => $antesCuota,
                ],
                despues: [
                    'contrato' => $contratoBloqueado->fresh()?->toArray(),
                    'cuota' => $cuotaBloqueada?->fresh()?->toArray(),
                    'pago' => $pago->fresh()?->toArray(),
                    'recibo' => $recibo->fresh()?->toArray(),
                ],
                observaciones: 'Pago registrado con recibo ' . $recibo->folio
            );

            $this->logFinancieroService->pagoRegistrado(
                pago: $pago,
                folioRecibo: $recibo->folio,
                monto: $monto,
                saldoAnterior: $saldoAnteriorContrato,
                saldoNuevo: $nuevoSaldoActual,
                metadata: [
                    'contrato_id' => $contratoBloqueado->id,
                    'cliente_id' => $contratoBloqueado->cliente_id,
                    'cuota_id' => $cuotaBloqueada?->id,
                    'pago_id' => $pago->id,
                    'recibo_id' => $recibo->id,
                    'fecha_pago' => $fechaPago,
                    'concepto' => $concepto ?: 'Pago de financiamiento',
                ]
            );

            if ((float) $nuevoSaldoActual <= 0) {
                $this->logFinancieroService->contratoLiquidado(
                    contrato: $contratoBloqueado,
                    referencia: $recibo->folio,
                    metadata: [
                        'contrato_id' => $contratoBloqueado->id,
                        'cliente_id' => $contratoBloqueado->cliente_id,
                        'recibo_id' => $recibo->id,
                        'pago_id' => $pago->id,
                    ]
                );
            }

            return [
                'pago' => $pago->fresh(),
                'recibo' => $recibo->fresh(),
                'contrato' => $contratoBloqueado->fresh(),
                'cuota' => $cuotaBloqueada?->fresh(),
            ];
        });
    }
}