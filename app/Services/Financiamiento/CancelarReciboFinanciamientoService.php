<?php

namespace App\Services\Financiamiento;

use App\Enums\CuotaEstatus;
use App\Enums\PagoEstatus;
use App\Enums\ReciboEstatus;
use App\Models\AplicacionPagoFinanciamiento;
use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use App\Models\HistorialFinanciamiento;
use App\Models\PagoFinanciamiento;
use App\Models\ReciboFinanciamiento;
use App\Support\DemoMode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CancelarReciboFinanciamientoService
{
    public function __construct(
        protected AuditoriaFinancieraService $auditoriaService,
        protected DemoMode $demoMode,
    ) {}

    public function execute(ReciboFinanciamiento $recibo, ?string $observaciones = null): ReciboFinanciamiento
    {
        $this->demoMode->ensureChangesAreAllowed();

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

            if ($recibo->estatus === ReciboEstatus::Cancelado->value) {
                throw new RuntimeException('El recibo ya está cancelado.');
            }

            $tieneReciboPosterior = ReciboFinanciamiento::query()
                ->where('contrato_financiamiento_id', $recibo->contrato_financiamiento_id)
                ->where('id', '>', $recibo->id)
                ->where('estatus', ReciboEstatus::Vigente->value)
                ->exists();

            if ($tieneReciboPosterior) {
                throw new RuntimeException('Sólo se puede cancelar el último recibo vigente del contrato.');
            }

            $contrato = $recibo->contrato;

            if (! $contrato) {
                throw new RuntimeException('El recibo no tiene contrato relacionado.');
            }

            $contrato = ContratoFinanciamiento::query()
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
                $pago = PagoFinanciamiento::query()
                    ->whereKey($pago->id)
                    ->lockForUpdate()
                    ->firstOrFail();
            }

            $aplicacion = $pago
                ? AplicacionPagoFinanciamiento::query()
                    ->where('pago_financiamiento_id', $pago->id)
                    ->lockForUpdate()
                    ->first()
                : null;

            if ($cuota) {
                $existeCuotaPosteriorPagada = CuotaFinanciamiento::query()
                    ->where('contrato_financiamiento_id', $contrato->id)
                    ->where('numero', '>', $cuota->numero)
                    ->whereIn('estatus', [CuotaEstatus::Pagada->value, CuotaEstatus::Parcial->value])
                    ->exists();

                if ($existeCuotaPosteriorPagada) {
                    throw new RuntimeException(
                        'No se puede cancelar este recibo porque la cuota #'.$cuota->numero.
                        ' no es la última cuota pagada del contrato.'
                    );
                }
            }

            $antesContrato = $contrato->toArray();
            $antesCuota = $cuota?->toArray();
            $antesPago = $pago?->toArray();
            $antesRecibo = $recibo->toArray();

            $montoRecibo = (float) $recibo->monto;
            $recargoGenerado = 0.0;

            if ($aplicacion) {
                $montoRecibo = (float) $aplicacion->monto;
                $recargoGenerado = (float) $aplicacion->recargo_generado;
            }

            if ($pago && ($pago->estatus ?? null) !== PagoEstatus::Cancelado->value) {
                $pago->estatus = PagoEstatus::Cancelado->value;

                if (array_key_exists('cancelado_at', $pago->getAttributes())) {
                    $pago->cancelado_at = now();
                }

                if (array_key_exists('observaciones', $pago->getAttributes())) {
                    $pago->observaciones = trim(
                        ($pago->observaciones ?? '')."\n".
                        'Pago cancelado por cancelación de recibo '.$recibo->folio
                    );
                }

                $pago->save();
            }

            if ($cuota) {
                $nuevoMontoPagado = max(0, (float) $cuota->monto_pagado - $montoRecibo);

                if ($recargoGenerado > 0) {
                    $cuota->recargo_aplicado = max(
                        0,
                        (float) $cuota->recargo_aplicado - $recargoGenerado,
                    );
                    $cuota->monto = max(0, (float) $cuota->monto - $recargoGenerado);
                    $contrato->total_pagar = max(
                        0,
                        (float) $contrato->total_pagar - $recargoGenerado,
                    );
                }

                $cuota->monto_pagado = $nuevoMontoPagado;
                $cuota->saldo = max(0, (float) $cuota->monto - $nuevoMontoPagado);

                if ($nuevoMontoPagado <= 0) {
                    $cuota->estatus = CuotaEstatus::Pendiente->value;
                    $cuota->fecha_pago = null;
                } elseif ($nuevoMontoPagado < (float) $cuota->monto) {
                    $cuota->estatus = CuotaEstatus::Parcial->value;
                    $cuota->fecha_pago = null;
                } else {
                    $cuota->estatus = CuotaEstatus::Pagada->value;
                }

                if (array_key_exists('observaciones', $cuota->getAttributes())) {
                    $cuota->observaciones = trim(
                        ($cuota->observaciones ?? '')."\n".
                        'Se revirtió pago por cancelación de recibo '.$recibo->folio
                    );
                }

                $cuota->save();
            }

            $totalPagado = DB::table('pagos_financiamiento')
                ->where('contrato_financiamiento_id', $contrato->id)
                ->where(function ($q) {
                    $q->whereNull('estatus')
                        ->orWhere('estatus', '!=', PagoEstatus::Cancelado->value);
                })
                ->sum('monto_aplicado');

            $contrato->total_pagado = $totalPagado;
            $contrato->saldo_actual = max(0, (float) $contrato->total_pagar - (float) $totalPagado);

            $contrato->recalcularEstatus();
            $contrato->save();

            $recibo->estatus = ReciboEstatus::Cancelado->value;
            $recibo->cancelado_at = now();

            $notaRecibo = trim(($recibo->observaciones ?? '')."\n".'Cancelado manualmente.');

            if ($observaciones) {
                $notaRecibo .= "\n".$observaciones;
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
                'observaciones' => $observaciones ?: 'Cancelación de recibo '.$recibo->folio,
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
                observaciones: $observaciones ?: 'Cancelación de recibo '.$recibo->folio
            );

            return $recibo->fresh(['contrato', 'cuota', 'pago']);
        }, 3);
    }
}
