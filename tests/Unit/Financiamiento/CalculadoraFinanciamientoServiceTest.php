<?php

namespace Tests\Unit\Financiamiento;

use App\Enums\FormulaFinanciamiento;
use App\Services\Financiamiento\CalculadoraFinanciamientoService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CalculadoraFinanciamientoServiceTest extends TestCase
{
    private CalculadoraFinanciamientoService $calculadora;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calculadora = new CalculadoraFinanciamientoService;
    }

    public function test_anualidad_conserva_capital_y_cierra_el_saldo(): void
    {
        $resultado = $this->calculadora->calcular(
            montoFinanciado: 100000,
            tasaAnual: 12,
            plazo: 12,
            frecuencia: 'mensual',
        );

        $this->assertCount(12, $resultado['cuotas']);
        $this->assertEqualsWithDelta(
            100000,
            array_sum(array_column($resultado['cuotas'], 'capital')),
            0.01,
        );
        $this->assertSame(0.0, $resultado['cuotas'][11]['saldo']);
        $this->assertEqualsWithDelta(
            $resultado['total_pagar'],
            array_sum(array_column($resultado['cuotas'], 'monto')),
            0.01,
        );
        $this->assertGreaterThan(0, $resultado['total_intereses']);
    }

    public function test_formula_plana_preserva_el_calculo_historico(): void
    {
        $resultado = $this->calculadora->calcular(
            montoFinanciado: 100000,
            tasaAnual: 12,
            plazo: 4,
            frecuencia: 'mensual',
            formula: FormulaFinanciamiento::PlanaV1,
        );

        $this->assertSame(112000.0, $resultado['total_pagar']);
        $this->assertSame(28000.0, $resultado['monto_cuota']);
        $this->assertSame(12000.0, $resultado['total_intereses']);
        $this->assertSame(0.0, $resultado['cuotas'][3]['saldo']);
    }

    public function test_rechaza_parametros_fuera_de_los_limites_del_dominio(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->calculadora->calcular(
            montoFinanciado: 100000,
            tasaAnual: 101,
            plazo: 12,
        );
    }
}
