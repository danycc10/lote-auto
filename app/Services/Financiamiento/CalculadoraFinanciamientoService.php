<?php

namespace App\Services\Financiamiento;

use App\Enums\FormulaFinanciamiento;
use InvalidArgumentException;

class CalculadoraFinanciamientoService
{
    /**
     * @return array{
     *     monto_financiado: float,
     *     monto_cuota: float,
     *     total_pagar: float,
     *     total_intereses: float,
     *     cuotas: list<array{numero: int, capital: float, interes: float, monto: float, saldo: float}>
     * }
     */
    public function calcular(
        float $montoFinanciado,
        float $tasaAnual,
        int $plazo,
        string $frecuencia = 'mensual',
        FormulaFinanciamiento $formula = FormulaFinanciamiento::AnualidadV1,
    ): array {
        $this->validateInputs($montoFinanciado, $tasaAnual, $plazo, $frecuencia);

        $montoFinanciado = round($montoFinanciado, 2);
        $tasaAnual = round($tasaAnual, 4);

        return match ($formula) {
            FormulaFinanciamiento::PlanaV1 => $this->calcularPlana($montoFinanciado, $tasaAnual, $plazo),
            FormulaFinanciamiento::AnualidadV1 => $this->calcularAnualidad(
                $montoFinanciado,
                $tasaAnual,
                $plazo,
                $this->periodosPorAnio($frecuencia),
            ),
        };
    }

    /**
     * @return array{
     *     monto_financiado: float,
     *     monto_cuota: float,
     *     total_pagar: float,
     *     total_intereses: float,
     *     cuotas: list<array{numero: int, capital: float, interes: float, monto: float, saldo: float}>
     * }
     */
    private function calcularPlana(float $principal, float $tasa, int $plazo): array
    {
        $totalIntereses = round($principal * ($tasa / 100), 2);
        $totalPagar = round($principal + $totalIntereses, 2);
        $capitalBase = round($principal / $plazo, 2);
        $cuotas = [];
        $capitalAcumulado = 0.0;
        $montoAcumulado = 0.0;
        $saldo = $principal;

        for ($numero = 1; $numero <= $plazo; $numero++) {
            $capital = $numero === $plazo
                ? round($principal - $capitalAcumulado, 2)
                : $capitalBase;
            $interes = $numero === $plazo
                ? round($totalPagar - $montoAcumulado - $capital, 2)
                : round($capital * ($tasa / 100), 2);
            $monto = round($capital + $interes, 2);
            $saldo = round(max(0, $saldo - $capital), 2);

            $cuotas[] = compact('numero', 'capital', 'interes', 'monto', 'saldo');
            $capitalAcumulado = round($capitalAcumulado + $capital, 2);
            $montoAcumulado = round($montoAcumulado + $monto, 2);
        }

        return [
            'monto_financiado' => $principal,
            'monto_cuota' => $cuotas[0]['monto'],
            'total_pagar' => $totalPagar,
            'total_intereses' => $totalIntereses,
            'cuotas' => $cuotas,
        ];
    }

    /**
     * @return array{
     *     monto_financiado: float,
     *     monto_cuota: float,
     *     total_pagar: float,
     *     total_intereses: float,
     *     cuotas: list<array{numero: int, capital: float, interes: float, monto: float, saldo: float}>
     * }
     */
    private function calcularAnualidad(float $principal, float $tasaAnual, int $plazo, int $periodosPorAnio): array
    {
        $tasaPeriodo = $tasaAnual > 0 ? ($tasaAnual / 100) / $periodosPorAnio : 0.0;
        $cuotaRegular = $tasaPeriodo > 0
            ? round($principal * $tasaPeriodo / (1 - (1 + $tasaPeriodo) ** (-$plazo)), 2)
            : round($principal / $plazo, 2);

        $cuotas = [];
        $saldo = $principal;
        $totalIntereses = 0.0;
        $totalPagar = 0.0;

        for ($numero = 1; $numero <= $plazo; $numero++) {
            $interes = round($saldo * $tasaPeriodo, 2);
            $capital = $numero === $plazo
                ? $saldo
                : min(round($cuotaRegular - $interes, 2), $saldo);
            $monto = round($capital + $interes, 2);
            $saldo = round(max(0, $saldo - $capital), 2);

            $cuotas[] = compact('numero', 'capital', 'interes', 'monto', 'saldo');
            $totalIntereses = round($totalIntereses + $interes, 2);
            $totalPagar = round($totalPagar + $monto, 2);
        }

        return [
            'monto_financiado' => $principal,
            'monto_cuota' => $cuotas[0]['monto'],
            'total_pagar' => $totalPagar,
            'total_intereses' => $totalIntereses,
            'cuotas' => $cuotas,
        ];
    }

    private function validateInputs(float $principal, float $tasa, int $plazo, string $frecuencia): void
    {
        if ($principal < 0) {
            throw new InvalidArgumentException('El monto financiado no puede ser negativo.');
        }

        if ($tasa < 0 || $tasa > 100) {
            throw new InvalidArgumentException('La tasa anual debe estar entre 0 y 100.');
        }

        if ($plazo < 1 || $plazo > 120) {
            throw new InvalidArgumentException('El plazo debe estar entre 1 y 120 periodos.');
        }

        if (! in_array($frecuencia, ['semanal', 'quincenal', 'mensual'], true)) {
            throw new InvalidArgumentException('La frecuencia de pago no es válida.');
        }
    }

    private function periodosPorAnio(string $frecuencia): int
    {
        return match ($frecuencia) {
            'semanal' => 52,
            'quincenal' => 24,
            'mensual' => 12,
            default => throw new InvalidArgumentException('La frecuencia de pago no es válida.'),
        };
    }
}
