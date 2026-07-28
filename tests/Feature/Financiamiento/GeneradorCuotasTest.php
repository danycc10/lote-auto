<?php

namespace Tests\Feature\Financiamiento;

use App\Enums\FormulaFinanciamiento;
use App\Models\CuotaFinanciamiento;
use App\Services\Financiamiento\CalculadoraFinanciamientoService;
use App\Services\Financiamiento\GeneradorCuotasFinanciamientoService;

class GeneradorCuotasTest extends FinanciamientoTestCase
{
    private function service(): GeneradorCuotasFinanciamientoService
    {
        return app(GeneradorCuotasFinanciamientoService::class);
    }

    public function test_suma_de_cuotas_igual_a_total_pagar(): void
    {
        $contrato = $this->crearContrato([
            'monto_financiado' => 60000,
            'tasa_interes' => 0,
            'plazo' => 3,
            'monto_cuota' => 20000,
            'total_pagar' => 60000,
        ]);

        $this->service()->regenerar($contrato);

        $sumaCuotas = CuotaFinanciamiento::where('contrato_financiamiento_id', $contrato->id)
            ->sum('monto');

        $this->assertEquals((float) $contrato->total_pagar, round((float) $sumaCuotas, 2));
    }

    public function test_cuotas_tienen_numeracion_consecutiva(): void
    {
        $plazo = 5;
        $contrato = $this->crearContrato([
            'monto_financiado' => 50000,
            'tasa_interes' => 0,
            'plazo' => $plazo,
            'monto_cuota' => 10000,
            'total_pagar' => 50000,
        ]);

        $this->service()->regenerar($contrato);

        $numeros = CuotaFinanciamiento::where('contrato_financiamiento_id', $contrato->id)
            ->orderBy('numero')
            ->pluck('numero')
            ->toArray();

        $this->assertCount($plazo, $numeros);
        $this->assertEquals(range(1, $plazo), $numeros);
    }

    public function test_genera_tabla_de_anualidad_con_interes_decreciente(): void
    {
        $calculo = app(CalculadoraFinanciamientoService::class)->calcular(
            montoFinanciado: 100000,
            tasaAnual: 12,
            plazo: 12,
            frecuencia: 'mensual',
        );
        $contrato = $this->crearContrato([
            'formula_calculo' => FormulaFinanciamiento::AnualidadV1->value,
            'monto_financiado' => $calculo['monto_financiado'],
            'tasa_interes' => 12,
            'plazo' => 12,
            'monto_cuota' => $calculo['monto_cuota'],
            'total_pagar' => $calculo['total_pagar'],
            'saldo_actual' => $calculo['total_pagar'],
        ]);

        $this->service()->regenerar($contrato);

        $cuotas = CuotaFinanciamiento::query()
            ->where('contrato_financiamiento_id', $contrato->id)
            ->orderBy('numero')
            ->get();

        $this->assertCount(12, $cuotas);
        $this->assertGreaterThan((float) $cuotas->last()->monto_interes, (float) $cuotas->first()->monto_interes);
        $this->assertEqualsWithDelta($calculo['total_pagar'], (float) $cuotas->sum('monto'), 0.01);
        $this->assertEqualsWithDelta(100000, (float) $cuotas->sum('monto_capital'), 0.01);
    }
}
