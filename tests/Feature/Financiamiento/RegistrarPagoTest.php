<?php

namespace Tests\Feature\Financiamiento;

use App\Models\PagoFinanciamiento;
use App\Models\ReciboFinanciamiento;
use App\Services\Financiamiento\RegistrarPagoFinanciamientoService;
use RuntimeException;

class RegistrarPagoTest extends FinanciamientoTestCase
{
    private function service(): RegistrarPagoFinanciamientoService
    {
        return app(RegistrarPagoFinanciamientoService::class);
    }

    public function test_pago_normal_registra_correctamente(): void
    {
        $user = $this->usuarioConPermiso('pagos.registrar');
        $this->actingAs($user);

        $contrato = $this->crearContrato();
        $cuota = $this->crearCuota($contrato, 1);

        $resultado = $this->service()->ejecutar(
            contrato: $contrato,
            monto: 25000,
            cuota: $cuota,
        );

        $this->assertInstanceOf(PagoFinanciamiento::class, $resultado['pago']);
        $this->assertInstanceOf(ReciboFinanciamiento::class, $resultado['recibo']);

        $this->assertDatabaseHas('pagos_financiamiento', [
            'contrato_financiamiento_id' => $contrato->id,
            'monto' => 25000,
            'estatus' => 'aplicado',
        ]);

        $this->assertDatabaseHas('recibos_financiamiento', [
            'contrato_financiamiento_id' => $contrato->id,
            'monto' => 25000,
            'estatus' => 'vigente',
        ]);

        $this->assertDatabaseHas('cuotas_financiamiento', [
            'id' => $cuota->id,
            'estatus' => 'pagada',
            'saldo' => 0,
        ]);

        $contrato->refresh();
        $this->assertEquals(75000.0, (float) $contrato->saldo_actual);
        $this->assertEquals(25000.0, (float) $contrato->total_pagado);
    }

    public function test_forma_pago_y_referencia_se_persisten(): void
    {
        $user = $this->usuarioConPermiso('pagos.registrar');
        $this->actingAs($user);

        $contrato = $this->crearContrato();
        $cuota = $this->crearCuota($contrato, 1);

        $this->service()->ejecutar(
            contrato: $contrato,
            monto: 25000,
            cuota: $cuota,
            formaPago: 'transferencia',
            referencia: 'REF-TEST-001',
        );

        $this->assertDatabaseHas('pagos_financiamiento', [
            'contrato_financiamiento_id' => $contrato->id,
            'forma_pago' => 'transferencia',
            'referencia' => 'REF-TEST-001',
        ]);
    }

    public function test_contrato_queda_atrasado_cuando_hay_cuotas_vencidas(): void
    {
        $user = $this->usuarioConPermiso('pagos.registrar');
        $this->actingAs($user);

        $contrato = $this->crearContrato(['estatus' => 'atrasado']);
        $this->crearCuota($contrato, 1, ['estatus' => 'vencida']);
        $cuota2 = $this->crearCuota($contrato, 2);

        $this->service()->ejecutar(
            contrato: $contrato,
            monto: 25000,
            cuota: $cuota2,
        );

        $contrato->refresh();
        $this->assertEquals('atrasado', $contrato->estatus);
    }

    public function test_monto_mayor_al_saldo_de_cuota_lanza_excepcion(): void
    {
        $user = $this->usuarioConPermiso('pagos.registrar');
        $this->actingAs($user);

        $contrato = $this->crearContrato();
        $cuota = $this->crearCuota($contrato, 1);

        $this->expectException(RuntimeException::class);

        $this->service()->ejecutar(
            contrato: $contrato,
            monto: 30000,
            cuota: $cuota,
        );
    }

    public function test_contrato_cancelado_lanza_excepcion(): void
    {
        $user = $this->usuarioConPermiso('pagos.registrar');
        $this->actingAs($user);

        $contrato = $this->crearContrato(['estatus' => 'cancelado', 'saldo_actual' => 100000]);
        $cuota = $this->crearCuota($contrato, 1);

        $this->expectException(RuntimeException::class);

        $this->service()->ejecutar(
            contrato: $contrato,
            monto: 25000,
            cuota: $cuota,
        );
    }
}
