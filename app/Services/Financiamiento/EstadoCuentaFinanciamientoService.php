<?php

namespace App\Services\Financiamiento;

use App\Enums\CuotaEstatus;
use App\Enums\PagoEstatus;
use App\Enums\TipoRecargo;
use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use Carbon\Carbon;

class EstadoCuentaFinanciamientoService
{
    public function build(ContratoFinanciamiento $contrato): array
    {
        $contrato->loadMissing([
            'cliente',
            'auto.marca',
            'auto.modelo',
            'cuotas.aplicacionesPago.pago',
            'pagos.aplicaciones.cuota',
            'recibos',
            'historiales.usuario',
        ]);

        $cuotas = $contrato->cuotas->sortBy('numero')->values();
        $pagos = $contrato->pagos->sortByDesc('fecha_pago')->values();
        $recibos = $contrato->recibos->sortByDesc('fecha_recibo')->values();
        $historiales = $contrato->historiales->sortByDesc('created_at')->take(20)->values();

        $hoy = now()->startOfDay();
        $pagadas = $cuotas->where('estatus', CuotaEstatus::Pagada->value);
        $pendientes = $cuotas->whereIn('estatus', CuotaEstatus::conSaldo());
        $vencidas = $cuotas->filter(fn (CuotaFinanciamiento $cuota) => $this->isVencida($cuota, $hoy));
        $proximaCuota = $pendientes->sortBy('fecha_vencimiento')->first();

        $capitalPendiente = $pendientes->sum(function (CuotaFinanciamiento $cuota) {
            return max(
                (float) $cuota->monto_capital - $this->pagadoAComponente($cuota, 'monto_capital'),
                0,
            );
        });

        $interesPendiente = $pendientes->sum(function (CuotaFinanciamiento $cuota) {
            return max(
                (float) $cuota->monto_interes - $this->pagadoAComponente($cuota, 'monto_interes'),
                0,
            );
        });

        return [
            'resumen' => [
                'total_contrato' => (float) $contrato->total_pagar,
                'total_pagado' => (float) $contrato->total_pagado,
                'saldo_actual' => (float) $contrato->saldo_actual,
                'cuotas_total' => $cuotas->count(),
                'cuotas_pagadas' => $pagadas->count(),
                'cuotas_pendientes' => $pendientes->count(),
                'cuotas_vencidas' => $vencidas->count(),
                'monto_vencido' => round($vencidas->sum(fn (CuotaFinanciamiento $c) => max((float) $c->saldo, 0)), 2),
                'capital_pendiente' => round($capitalPendiente, 2),
                'interes_pendiente' => round($interesPendiente, 2),
                'proxima_cuota' => $proximaCuota,
                'dias_atraso_maximo' => (int) $vencidas->map(fn (CuotaFinanciamiento $c) => $this->diasAtraso($c, $hoy))->max(),
            ],
            'cuotas' => $cuotas,
            'pagos' => $pagos,
            'recibos' => $recibos,
            'historiales' => $historiales,
        ];
    }

    public function diasAtraso(CuotaFinanciamiento $cuota, ?Carbon $hoy = null): int
    {
        $hoy ??= now()->startOfDay();
        $vence = Carbon::parse($cuota->fecha_vencimiento)->startOfDay();
        $gracia = (int) ($cuota->contrato?->dias_gracia ?? 0);
        $fechaLimite = $vence->copy()->addDays($gracia);

        if ($cuota->estatus === CuotaEstatus::Pagada->value || $hoy->lessThanOrEqualTo($fechaLimite)) {
            return 0;
        }

        return $fechaLimite->diffInDays($hoy);
    }

    public function recargoSugerido(CuotaFinanciamiento $cuota, ?Carbon $hoy = null): float
    {
        $hoy ??= now()->startOfDay();
        $dias = $this->diasAtraso($cuota, $hoy);

        if ($dias <= 0) {
            return 0;
        }

        $contrato = $cuota->contrato;
        if (! $contrato || ! $contrato->tipo_recargo || (float) $contrato->valor_recargo <= 0) {
            return 0;
        }

        $base = (float) $cuota->saldo;

        if ($contrato->tipo_recargo === TipoRecargo::Fijo->value) {
            return round((float) $contrato->valor_recargo, 2);
        }

        return round($base * (((float) $contrato->valor_recargo) / 100), 2);
    }

    protected function isVencida(CuotaFinanciamiento $cuota, Carbon $hoy): bool
    {
        if ($cuota->estatus === CuotaEstatus::Pagada->value || $cuota->estatus === CuotaEstatus::Cancelada->value) {
            return false;
        }

        return $this->diasAtraso($cuota, $hoy) > 0;
    }

    protected function pagadoAComponente(CuotaFinanciamiento $cuota, string $campo): float
    {
        $montoComponente = (float) $cuota->{$campo};
        $aplicacionesVigentes = $cuota->aplicacionesPago
            ->filter(
                fn ($aplicacion) => $aplicacion->pago?->estatus === PagoEstatus::Aplicado->value,
            );
        $totalRastreado = (float) $aplicacionesVigentes->sum('monto');
        $componenteRastreado = (float) $aplicacionesVigentes->sum($campo);
        $pagoSinDesglose = max((float) $cuota->monto_pagado - $totalRastreado, 0);

        if ($pagoSinDesglose <= 0) {
            return min($componenteRastreado, $montoComponente);
        }

        $base = max((float) $cuota->monto, 0.01);
        $proporcion = $montoComponente / $base;
        $estimadoHistorico = round($pagoSinDesglose * $proporcion, 2);

        return min(round($componenteRastreado + $estimadoHistorico, 2), $montoComponente);
    }
}
