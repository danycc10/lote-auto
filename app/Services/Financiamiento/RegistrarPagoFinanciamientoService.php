<?php

namespace App\Services\Financiamiento;

use App\Enums\ContratoEstatus;
use App\Enums\CuotaEstatus;
use App\Enums\PagoEstatus;
use App\Enums\ReciboEstatus;
use App\Mail\PagoConfirmadoMail;
use App\Models\AplicacionPagoFinanciamiento;
use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use App\Models\PagoFinanciamiento;
use App\Models\ReciboFinanciamiento;
use App\Support\DemoMode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use RuntimeException;

class RegistrarPagoFinanciamientoService
{
    public function __construct(
        protected GenerarFolioReciboFinanciamientoService $folioService,
        protected AuditoriaFinancieraService $auditoriaService,
        protected LogFinancieroService $logFinancieroService,
        protected DemoMode $demoMode,
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
        float $recargo = 0,
        ?string $idempotencyKey = null,
    ): array {
        $this->demoMode->ensureChangesAreAllowed();

        $resultado = DB::transaction(function () use (
            $contrato,
            $monto,
            $cuota,
            $fechaPago,
            $concepto,
            $observaciones,
            $formaPago,
            $referencia,
            $tarjetaCobroId,
            $recargo,
            $idempotencyKey,
        ) {
            $fechaPago = $fechaPago ?: now()->toDateString();

            if (! Auth::user()?->can('pagos.registrar')) {
                throw new RuntimeException('No tienes permiso para registrar pagos.');
            }

            if ($monto <= 0) {
                throw new RuntimeException('El monto del pago debe ser mayor a 0.');
            }

            if ($idempotencyKey !== null && ! Str::isUuid($idempotencyKey)) {
                throw new RuntimeException('La clave de idempotencia del pago no es válida.');
            }

            $contratoBloqueado = ContratoFinanciamiento::query()
                ->whereKey($contrato->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($idempotencyKey !== null) {
                $pagoExistente = PagoFinanciamiento::query()
                    ->where('contrato_financiamiento_id', $contratoBloqueado->id)
                    ->where('idempotency_key', $idempotencyKey)
                    ->first();

                if ($pagoExistente) {
                    return [
                        'pago' => $pagoExistente,
                        'recibo' => ReciboFinanciamiento::query()
                            ->where('pago_financiamiento_id', $pagoExistente->id)
                            ->firstOrFail(),
                        'contrato' => $contratoBloqueado,
                        'cuota' => $pagoExistente->cuota,
                        'reutilizado' => true,
                    ];
                }
            }

            $estatusBloqueantes = [
                ContratoEstatus::Cancelado->value,
                ContratoEstatus::Reestructurado->value,
                ContratoEstatus::Recuperado->value,
            ];

            if (in_array($contratoBloqueado->estatus, $estatusBloqueantes, true)) {
                throw new RuntimeException('No se puede registrar pago en un contrato con estatus: '.$contratoBloqueado->estatus);
            }

            $antesContrato = $contratoBloqueado->toArray();
            $cuotaBloqueada = null;
            $antesCuota = null;

            if ($cuota) {
                $cuotaBloqueada = CuotaFinanciamiento::query()
                    ->whereKey($cuota->id)
                    ->where('contrato_financiamiento_id', $contratoBloqueado->id)
                    ->lockForUpdate()
                    ->firstOrFail();
                $antesCuota = $cuotaBloqueada->toArray();

                if ($cuotaBloqueada->estatus === CuotaEstatus::Cancelada->value) {
                    throw new RuntimeException('No se puede pagar una cuota cancelada.');
                }

                if ($cuotaBloqueada->estatus === CuotaEstatus::Pagada->value) {
                    throw new RuntimeException('Esta cuota ya está pagada.');
                }

                // Aplicar recargo a la cuota antes de validar montos
                $recargo = round(max(0, $recargo), 2);
                if ($recargo > 0) {
                    $cuotaBloqueada->recargo_aplicado = round((float) ($cuotaBloqueada->recargo_aplicado ?? 0) + $recargo, 2);
                    $cuotaBloqueada->monto = round((float) $cuotaBloqueada->monto + $recargo, 2);
                    $cuotaBloqueada->saldo = round((float) ($cuotaBloqueada->saldo ?? $cuotaBloqueada->monto) + $recargo, 2);
                    $cuotaBloqueada->save();

                    // Actualizar también los totales del contrato
                    $contratoBloqueado->total_pagar = round((float) $contratoBloqueado->total_pagar + $recargo, 2);
                    $contratoBloqueado->saldo_actual = round((float) $contratoBloqueado->saldo_actual + $recargo, 2);
                    $contratoBloqueado->save();
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

            $pago = PagoFinanciamiento::create([
                'idempotency_key' => $idempotencyKey,
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
                'estatus' => PagoEstatus::Aplicado->value,
                'observaciones' => $observaciones,
            ]);

            if ($cuotaBloqueada) {
                $montoCuota = (float) ($cuotaBloqueada->monto ?? 0);
                $montoPagadoAnterior = (float) ($cuotaBloqueada->monto_pagado ?? 0);
                $nuevoMontoPagado = $montoPagadoAnterior + $monto;
                $desglose = $this->desglosarAplicacion(
                    $cuotaBloqueada,
                    $montoPagadoAnterior,
                    $monto,
                    $recargo,
                );

                AplicacionPagoFinanciamiento::create([
                    'pago_financiamiento_id' => $pago->id,
                    'cuota_financiamiento_id' => $cuotaBloqueada->id,
                    ...$desglose,
                ]);

                $cuotaBloqueada->monto_pagado = $nuevoMontoPagado;
                $cuotaBloqueada->saldo = max(0, $montoCuota - $nuevoMontoPagado);

                if ($nuevoMontoPagado <= 0) {
                    $cuotaBloqueada->estatus = CuotaEstatus::Pendiente->value;
                    $cuotaBloqueada->fecha_pago = null;
                } elseif ($nuevoMontoPagado < $montoCuota) {
                    $cuotaBloqueada->estatus = CuotaEstatus::Parcial->value;
                    $cuotaBloqueada->fecha_pago = $fechaPago;
                } else {
                    $cuotaBloqueada->estatus = CuotaEstatus::Pagada->value;
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
                'estatus' => ReciboEstatus::Vigente->value,
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
                observaciones: 'Pago registrado con recibo '.$recibo->folio
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

            $resultado = [
                'pago' => $pago->fresh(),
                'recibo' => $recibo->fresh(),
                'contrato' => $contratoBloqueado->fresh(),
                'cuota' => $cuotaBloqueada?->fresh(),
                'reutilizado' => false,
            ];

            return $resultado;
        }, 3);

        // Enviar confirmación al cliente fuera de la transacción (no bloquea el commit)
        if (! $resultado['reutilizado']) {
            $this->enviarConfirmacionCliente($resultado['pago'], $resultado['recibo']);
        }

        return $resultado;
    }

    /**
     * @return array{
     *     monto: float,
     *     monto_recargo: float,
     *     monto_interes: float,
     *     monto_capital: float,
     *     monto_extra: float,
     *     recargo_generado: float
     * }
     */
    private function desglosarAplicacion(
        CuotaFinanciamiento $cuota,
        float $pagadoAnterior,
        float $monto,
        float $recargoGenerado,
    ): array {
        $componentes = [
            'monto_recargo' => (float) $cuota->recargo_aplicado,
            'monto_interes' => (float) $cuota->monto_interes,
            'monto_capital' => (float) $cuota->monto_capital,
            'monto_extra' => (float) $cuota->monto_extra,
        ];
        $restanteHistorico = round($pagadoAnterior, 2);
        $restantePago = round($monto, 2);
        $desglose = [];

        foreach ($componentes as $campo => $totalComponente) {
            $cubiertoAntes = min($totalComponente, $restanteHistorico);
            $restanteHistorico = round(max(0, $restanteHistorico - $cubiertoAntes), 2);
            $pendiente = round(max(0, $totalComponente - $cubiertoAntes), 2);
            $aplicado = min($pendiente, $restantePago);
            $desglose[$campo] = round($aplicado, 2);
            $restantePago = round(max(0, $restantePago - $aplicado), 2);
        }

        if ($restantePago > 0.009) {
            throw new RuntimeException('El pago no puede distribuirse completamente en la cuota.');
        }

        return [
            'monto' => round($monto, 2),
            'monto_recargo' => $desglose['monto_recargo'],
            'monto_interes' => $desglose['monto_interes'],
            'monto_capital' => $desglose['monto_capital'],
            'monto_extra' => $desglose['monto_extra'],
            'recargo_generado' => round($recargoGenerado, 2),
        ];
    }

    private function enviarConfirmacionCliente(PagoFinanciamiento $pago, ReciboFinanciamiento $recibo): void
    {
        try {
            $correo = $pago->contrato?->cliente?->correo;

            if ($correo) {
                Mail::to($correo)->queue(new PagoConfirmadoMail($pago, $recibo));
            }
        } catch (\Throwable $e) {
            // El correo no debe bloquear ni revertir el pago, pero sí queda registrado.
            report($e);
        }
    }
}
