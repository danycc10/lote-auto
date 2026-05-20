<?php

namespace Tests\Feature\Financiamiento;

use App\Models\CuotaFinanciamiento;
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
}
