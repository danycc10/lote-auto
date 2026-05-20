<?php

namespace App\Services\Financiamiento;

use App\Models\CuotaFinanciamiento;
use App\Models\HistorialFinanciamiento;
use App\Models\ReciboFinanciamiento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CancelarReciboFinanciamientoService
{
    public function __construct(
        protected AuditoriaFinancieraService $auditoriaService,
    ) {}

    public function execute(ReciboFinanciamiento $recibo, ?string $observaciones = null): ReciboFinanciamiento
    {
        return DB::transaction(function () use ($recibo, $observaciones) {
            if (! Auth::user()?->can('recibos.cancelar')) {
                throw new RuntimeException('No tienes permiso para cancelar recibos.');
            }

            $recibo = ReciboFinanciamiento::query()
                ->whereKey($recibo->id)
                ->lockForUpdate()
                ->firstOrFail();

            $recibo->loadMissing([
                'contrato',
                'cuota',
                'pago',
            ]);

            if ($recibo->estatus === 'cancelado') {
                throw new RuntimeException('El recibo ya está cancelado.');
            }

            $contrato = $recibo->contrato;

            if (! $contrato) {
                throw new RuntimeException('El recibo no tiene contrato relacionado.');
            }

            $contrato = $contrato->newQuery()
                ->whereKey($contrato->id)
                ->lockForUpdate()
                ->firstOrFail();

            $cuota = null;

            if ($recibo->cuota_id) {
                $cuota = CuotaFinanciamiento::query()
                    ->whereKey($recibo->cuota_id)
                    ->lockForUpdate()
                    ->firstOrFail();
            }

            $pago = $recibo->pago;

            if ($pago) {
                $pago = $pago->newQuery()
                    ->whereKey($pago->id)
                    ->lockForUpdate()
                    ->firstOrFail();
            }

            if ($cuota) {
                $existeCuotaPosteriorPagada = CuotaFinanciamiento::query()
                    ->where('contrato_financiamiento_id', $contrato->id)
                    ->where('numero', '>', $cuota->numero)
                    ->whereIn('estatus', ['pagada', 'parcial'])
                    ->exists();

                if ($existeCuotaPosteriorPagada) {
                    throw new RuntimeException(
                        'No se puede cancelar este recibo porque la cuota #' . $cuota->numero .
                        ' no es la última cuota pagada del contrato.'
                    );
                }
            }

            $antesContrato = $contrato->toArray();
            $antesCuota = $cuota?->toArray();
            $antesPago = $pago?->toArray();
            $antesRecibo = $recibo->toArray();

            $montoRecibo = (float) $recibo->monto;

            if ($pago && ($pago->estatus ?? null) !== 'cancelado') {
                $pago->estatus = 'cancelado';

                if (array_key_exists('cancelado_at', $pago->getAttributes())) {
                    $pago->cancelado_at = now();
                }

                if (array_key_exists('observaciones', $pago->getAttributes())) {
                    $pago->observaciones = trim(
                        ($pago->observaciones ?? '') . "\n" .
                        'Pago cancelado por cancelación de recibo ' . $recibo->folio
                    );
                }

                $pago->save();
            }

            if ($cuota) {
                $nuevoMontoPagado = max(0, (float) $cuota->monto_pagado - $montoRecibo);

                $cuota->monto_pagado = $nuevoMontoPagado;
                $cuota->saldo = max(0, (float) $cuota->monto - $nuevoMontoPagado);

                if ($nuevoMontoPagado <= 0) {
                    $cuota->estatus = 'pendiente';
                    $cuota->fecha_pago = null;
                } elseif ($nuevoMontoPagado < (float) $cuota->monto) {
                    $cuota->estatus = 'parcial';
                    $cuota->fecha_pago = null;
                } else {
                    $cuota->estatus = 'pagada';
                }

                if (array_key_exists('observaciones', $cuota->getAttributes())) {
                    $cuota->observaciones = trim(
                        ($cuota->observaciones ?? '') . "\n" .
                        'Se revirtió pago por cancelación de recibo ' . $recibo->folio
                    );
                }

                $cuota->save();
            }

            $totalPagado = DB::table('pagos_financiamiento')
                ->where('contrato_financiamiento_id', $contrato->id)
                ->where(function ($q) {
                    $q->whereNull('estatus')
                        ->orWhere('estatus', '!=', 'cancelado');
                })
                ->sum('monto');

            $contrato->total_pagado = $totalPagado;
            $contrato->saldo_actual = max(0, (float) $contrato->total_pagar - (float) $totalPagado);

            if (array_key_exists('estatus', $contrato->getAttributes())) {
                if ((float) $contrato->saldo_actual <= 0) {
                    $contrato->estatus = 'liquidado';
                } else {
                    $tieneVencidas = DB::table('cuotas_financiamiento')
                        ->where('contrato_financiamiento_id', $contrato->id)
                        ->where('estatus', 'vencida')
                        ->exists();

                    $contrato->estatus = $tieneVencidas ? 'atrasado' : 'activo';
                }
            }

            $contrato->save();

            $recibo->estatus = 'cancelado';
            $recibo->cancelado_at = now();

            $notaRecibo = trim(($recibo->observaciones ?? '') . "\n" . 'Cancelado manualmente.');

            if ($observaciones) {
                $notaRecibo .= "\n" . $observaciones;
            }

            $recibo->observaciones = trim($notaRecibo);
            $recibo->save();

            HistorialFinanciamiento::create([
                'contrato_financiamiento_id' => $contrato->id,
                'user_id' => Auth::id(),
                'evento' => 'cancelacion_recibo',
                'antes' => [
                    'contrato' => $antesContrato,
                    'cuota' => $antesCuota,
                    'pago' => $antesPago,
                    'recibo' => $antesRecibo,
                ],
                'despues' => [
                    'contrato' => $contrato->fresh()?->toArray(),
                    'cuota' => $cuota?->fresh()?->toArray(),
                    'pago' => $pago?->fresh()?->toArray(),
                    'recibo' => $recibo->fresh()?->toArray(),
                ],
                'observaciones' => $observaciones ?: 'Cancelación de recibo ' . $recibo->folio,
            ]);

            $this->auditoriaService->registrar(
                accion: 'recibo_cancelado',
                modelo: $recibo,
                antes: [
                    'contrato' => $antesContrato,
                    'cuota' => $antesCuota,
                    'pago' => $antesPago,
                    'recibo' => $antesRecibo,
                ],
                despues: [
                    'contrato' => $contrato->fresh()?->toArray(),
                    'cuota' => $cuota?->fresh()?->toArray(),
                    'pago' => $pago?->fresh()?->toArray(),
                    'recibo' => $recibo->fresh()?->toArray(),
                ],
                observaciones: $observaciones ?: 'Cancelación de recibo ' . $recibo->folio
            );

            return $recibo->fresh(['contrato', 'cuota', 'pago']);
        });
    }
}