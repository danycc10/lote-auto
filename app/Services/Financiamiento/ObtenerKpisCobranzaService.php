<?php

namespace App\Services\Financiamiento;

use App\Enums\CuotaEstatus;
use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use App\Models\PagoFinanciamiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ObtenerKpisCobranzaService
{
    /**
     * @return array{
     *     total_vencido: float,
     *     total_por_vencer: float,
     *     cobrado_mes: float,
     *     contratos_activos: int,
     *     contratos_con_atraso: int,
     *     cuotas_vencidas: int,
     *     pct_morosidad: float,
     *     dias_promedio_atraso: int,
     *     cuotas_criticas_count: int,
     *     monto_critico: float
     * }
     */
    public function ejecutar(?Carbon $fecha = null): array
    {
        $hoy = ($fecha ?? today())->copy()->startOfDay();
        $ttl = max(1, (int) config('cobranza.dashboard_kpis_ttl', 30));

        /** @var array{
         *     total_vencido: float,
         *     total_por_vencer: float,
         *     cobrado_mes: float,
         *     contratos_activos: int,
         *     contratos_con_atraso: int,
         *     cuotas_vencidas: int,
         *     pct_morosidad: float,
         *     dias_promedio_atraso: int,
         *     cuotas_criticas_count: int,
         *     monto_critico: float
         * } $kpis
         */
        $kpis = Cache::remember(
            'cobranza:dashboard:kpis:'.$hoy->toDateString(),
            now()->addSeconds($ttl),
            fn (): array => $this->calcular($hoy),
        );

        return $kpis;
    }

    /**
     * @return array{
     *     total_vencido: float,
     *     total_por_vencer: float,
     *     cobrado_mes: float,
     *     contratos_activos: int,
     *     contratos_con_atraso: int,
     *     cuotas_vencidas: int,
     *     pct_morosidad: float,
     *     dias_promedio_atraso: int,
     *     cuotas_criticas_count: int,
     *     monto_critico: float
     * }
     */
    private function calcular(Carbon $hoy): array
    {
        $fechaHoy = $hoy->toDateString();
        $fechaLimite = $hoy->copy()->addDays(7)->toDateString();
        $fechaCritica = $hoy->copy()->subDays(30)->toDateString();
        $conSaldo = CuotaEstatus::conSaldo();
        $pendientes = CuotaEstatus::pendientesDePago();
        $marcadoresConSaldo = implode(', ', array_fill(0, count($conSaldo), '?'));
        $marcadoresPendientes = implode(', ', array_fill(0, count($pendientes), '?'));
        $diferenciaDias = $this->expresionDiferenciaDias();

        $cuotas = CuotaFinanciamiento::query()
            ->where('estatus', '!=', CuotaEstatus::Cancelada->value)
            ->whereHas('contrato', fn ($query) => $query->whereIn('estatus', ['activo', 'atrasado']))
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN estatus IN ({$marcadoresConSaldo}) AND fecha_vencimiento < ? THEN COALESCE(saldo, monto) ELSE 0 END), 0) AS total_vencido",
                [...$conSaldo, $fechaHoy],
            )
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN estatus IN ({$marcadoresPendientes}) AND fecha_vencimiento BETWEEN ? AND ? THEN COALESCE(saldo, monto) ELSE 0 END), 0) AS total_por_vencer",
                [...$pendientes, $fechaHoy, $fechaLimite],
            )
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN estatus IN ({$marcadoresConSaldo}) AND fecha_vencimiento < ? THEN 1 ELSE 0 END), 0) AS cuotas_vencidas",
                [...$conSaldo, $fechaHoy],
            )
            ->selectRaw(
                "COALESCE(AVG(CASE WHEN estatus IN ({$marcadoresConSaldo}) AND fecha_vencimiento < ? THEN {$diferenciaDias} ELSE NULL END), 0) AS dias_promedio_atraso",
                [...$conSaldo, $fechaHoy, $fechaHoy],
            )
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN estatus IN ({$marcadoresConSaldo}) AND fecha_vencimiento < ? THEN 1 ELSE 0 END), 0) AS cuotas_criticas_count",
                [...$conSaldo, $fechaCritica],
            )
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN estatus IN ({$marcadoresConSaldo}) AND fecha_vencimiento < ? THEN COALESCE(saldo, monto) ELSE 0 END), 0) AS monto_critico",
                [...$conSaldo, $fechaCritica],
            )
            ->firstOrFail();

        $contratos = ContratoFinanciamiento::query()
            ->whereIn('estatus', ['activo', 'atrasado'])
            ->selectRaw('COUNT(*) AS contratos_activos')
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN EXISTS (
                    SELECT 1
                    FROM cuotas_financiamiento AS cuota_atrasada
                    WHERE cuota_atrasada.contrato_financiamiento_id = contratos_financiamiento.id
                      AND cuota_atrasada.estatus IN ({$marcadoresConSaldo})
                      AND cuota_atrasada.fecha_vencimiento < ?
                ) THEN 1 ELSE 0 END), 0) AS contratos_con_atraso",
                [...$conSaldo, $fechaHoy],
            )
            ->firstOrFail();

        $cobradoMes = PagoFinanciamiento::query()
            ->where('estatus', '!=', 'cancelado')
            ->whereHas('contrato', fn ($query) => $query->where('estatus', '!=', 'cancelado'))
            ->whereBetween('fecha_pago', [
                $hoy->copy()->startOfMonth()->toDateString(),
                $hoy->copy()->endOfMonth()->toDateString(),
            ])
            ->sum('monto');

        $contratosActivos = (int) $contratos->getAttribute('contratos_activos');
        $contratosConAtraso = (int) $contratos->getAttribute('contratos_con_atraso');

        return [
            'total_vencido' => (float) $cuotas->getAttribute('total_vencido'),
            'total_por_vencer' => (float) $cuotas->getAttribute('total_por_vencer'),
            'cobrado_mes' => (float) $cobradoMes,
            'contratos_activos' => $contratosActivos,
            'contratos_con_atraso' => $contratosConAtraso,
            'cuotas_vencidas' => (int) $cuotas->getAttribute('cuotas_vencidas'),
            'pct_morosidad' => $contratosActivos > 0
                ? round($contratosConAtraso / $contratosActivos * 100, 1)
                : 0.0,
            'dias_promedio_atraso' => (int) round((float) $cuotas->getAttribute('dias_promedio_atraso')),
            'cuotas_criticas_count' => (int) $cuotas->getAttribute('cuotas_criticas_count'),
            'monto_critico' => (float) $cuotas->getAttribute('monto_critico'),
        ];
    }

    private function expresionDiferenciaDias(): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlite' => 'julianday(?) - julianday(fecha_vencimiento)',
            'pgsql' => '(?::date - fecha_vencimiento::date)',
            'sqlsrv' => 'CAST(DATEDIFF(day, fecha_vencimiento, ?) AS FLOAT)',
            default => 'DATEDIFF(?, fecha_vencimiento)',
        };
    }
}
