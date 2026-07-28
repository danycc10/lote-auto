<?php

namespace App\Services\Financiamiento;

use App\Enums\CuotaEstatus;
use App\Enums\FormulaFinanciamiento;
use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GeneradorCuotasFinanciamientoService
{
    public function __construct(
        private CalculadoraFinanciamientoService $calculadora,
    ) {}

    public function regenerar(ContratoFinanciamiento $contrato): void
    {
        DB::transaction(function () use ($contrato) {
            $contrato->cuotas()->delete();

            $fechaBase = $contrato->fecha_primer_pago
                ? Carbon::parse($contrato->fecha_primer_pago)
                : Carbon::parse($contrato->fecha_contrato);

            $plazo = (int) $contrato->plazo;
            if ($plazo <= 0) {
                return;
            }

            $formula = FormulaFinanciamiento::tryFrom((string) $contrato->formula_calculo)
                ?? FormulaFinanciamiento::PlanaV1;
            $calculo = $this->calculadora->calcular(
                montoFinanciado: (float) $contrato->monto_financiado,
                tasaAnual: (float) $contrato->tasa_interes,
                plazo: $plazo,
                frecuencia: $contrato->frecuencia,
                formula: $formula,
            );

            foreach ($calculo['cuotas'] as $cuotaCalculada) {
                $numero = $cuotaCalculada['numero'];
                $fechaVencimiento = $this->calcularFecha($fechaBase->copy(), $contrato->frecuencia, $numero, $numero === 1);

                CuotaFinanciamiento::create([
                    'contrato_financiamiento_id' => $contrato->id,
                    'numero' => $numero,
                    'fecha_vencimiento' => $fechaVencimiento->toDateString(),
                    'monto_capital' => $cuotaCalculada['capital'],
                    'monto_interes' => $cuotaCalculada['interes'],
                    'monto_extra' => 0,
                    'monto' => $cuotaCalculada['monto'],
                    'monto_pagado' => 0,
                    'recargo_aplicado' => 0,
                    'saldo' => $cuotaCalculada['monto'],
                    'estatus' => CuotaEstatus::Pendiente->value,
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
