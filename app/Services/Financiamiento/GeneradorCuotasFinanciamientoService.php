<?php

namespace App\Services\Financiamiento;

use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GeneradorCuotasFinanciamientoService
{
    public function regenerar(ContratoFinanciamiento $contrato): void
    {
        DB::transaction(function () use ($contrato) {
            $contrato->cuotas()->delete();

            $fechaBase = $contrato->fecha_primer_pago
                ? Carbon::parse($contrato->fecha_primer_pago)
                : Carbon::parse($contrato->fecha_contrato);

            $plazo = (int) $contrato->plazo;
            $montoCuota = round((float) $contrato->monto_cuota, 2);
            $tasaInteres = (float) $contrato->tasa_interes;
            $montoFinanciado = (float) $contrato->monto_financiado;

            if ($plazo <= 0) {
                return;
            }

            $capitalBase = round($montoFinanciado / $plazo, 2);
            $acumulado = 0;

            for ($i = 1; $i <= $plazo; $i++) {
                $fechaVencimiento = $this->calcularFecha($fechaBase->copy(), $contrato->frecuencia, $i, $i === 1);

                $capital = $i === $plazo
                    ? round($montoFinanciado - $acumulado, 2)
                    : $capitalBase;

                $acumulado += $capital;

                $interes = round(($capital * $tasaInteres) / 100, 2);
                $montoExtra = round($montoCuota - ($capital + $interes), 2);

                if ($montoExtra < 0) {
                    $montoExtra = 0;
                }

                CuotaFinanciamiento::create([
                    'contrato_financiamiento_id' => $contrato->id,
                    'numero' => $i,
                    'fecha_vencimiento' => $fechaVencimiento->toDateString(),
                    'monto_capital' => $capital,
                    'monto_interes' => $interes,
                    'monto_extra' => $montoExtra,
                    'monto' => round($capital + $interes + $montoExtra, 2),
                    'monto_pagado' => 0,
                    'recargo_aplicado' => 0,
                    'saldo' => round($capital + $interes + $montoExtra, 2),
                    'estatus' => 'pendiente',
                    'fecha_pago' => null,
                    'observaciones' => null,
                ]);
            }
        });
    }

    protected function calcularFecha(Carbon $fechaBase, string $frecuencia, int $iteracion, bool $esPrimera): Carbon
    {
        if ($esPrimera) {
            return $fechaBase;
        }

        return match ($frecuencia) {
            'semanal' => $fechaBase->addWeeks($iteracion - 1),
            'quincenal' => $fechaBase->addDays(($iteracion - 1) * 15),
            'mensual' => $fechaBase->addMonthsNoOverflow($iteracion - 1),
            default => $fechaBase->addWeeks($iteracion - 1),
        };
    }
}