<?php

namespace Tests\Feature\Financiamiento;

use App\Services\Financiamiento\EstadoCuentaFinanciamientoService;
use App\Services\Financiamiento\RegistrarPagoFinanciamientoService;

class EstadoCuentaFinanciamientoTest extends FinanciamientoTestCase
{
    public function test_usa_el_desglose_real_para_capital_e_interes_pendientes(): void
    {
        $user = $this->usuarioConPermiso('pagos.registrar');
        $this->actingAs($user);
        $contrato = $this->crearContrato();
        $cuota = $this->crearCuota($contrato, 1, [
            'monto_capital' => 20000,
            'monto_interes' => 5000,
            'monto' => 25000,
            'saldo' => 25000,
        ]);
        app(RegistrarPagoFinanciamientoService::class)->ejecutar(
            contrato: $contrato,
            monto: 10000,
            cuota: $cuota,
        );

        $estado = app(EstadoCuentaFinanciamientoService::class)->build($contrato->fresh());

        $this->assertSame(15000.0, $estado['resumen']['capital_pendiente']);
        $this->assertSame(0.0, $estado['resumen']['interes_pendiente']);
    }

    public function test_conserva_estimacion_para_un_pago_historico_sin_desglose(): void
    {
        $contrato = $this->crearContrato([
            'total_pagado' => 10000,
            'saldo_actual' => 90000,
        ]);
        $this->crearCuota($contrato, 1, [
            'monto_capital' => 20000,
            'monto_interes' => 5000,
            'monto' => 25000,
            'monto_pagado' => 10000,
            'saldo' => 15000,
            'estatus' => 'parcial',
        ]);

        $estado = app(EstadoCuentaFinanciamientoService::class)->build($contrato);

        $this->assertSame(12000.0, $estado['resumen']['capital_pendiente']);
        $this->assertSame(3000.0, $estado['resumen']['interes_pendiente']);
    }
}
