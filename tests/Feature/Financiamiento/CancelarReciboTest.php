<?php

namespace Tests\Feature\Financiamiento;

use App\Services\Financiamiento\CancelarReciboFinanciamientoService;
use App\Services\Financiamiento\RegistrarPagoFinanciamientoService;
use RuntimeException;

class CancelarReciboTest extends FinanciamientoTestCase
{
    private function service(): CancelarReciboFinanciamientoService
    {
        return app(CancelarReciboFinanciamientoService::class);
    }

    public function test_cancela_ultimo_recibo_y_revierte_datos(): void
    {
        $user = $this->usuarioConPermiso('recibos.cancelar');
        $this->actingAs($user);

        $contrato = $this->crearContrato(['total_pagado' => 25000, 'saldo_actual' => 75000]);
        $cuota = $this->crearCuota($contrato, 1, [
            'estatus' => 'pagada',
            'monto_pagado' => 25000,
            'saldo' => 0,
        ]);

        ['pago' => $pago, 'recibo' => $recibo] = $this->crearPagoYRecibo($contrato, $cuota, $user);

        $this->service()->execute($recibo, 'Prueba de cancelación');

        $this->assertDatabaseHas('recibos_financiamiento', [
            'id' => $recibo->id,
            'estatus' => 'cancelado',
        ]);

        $this->assertDatabaseHas('pagos_financiamiento', [
            'id' => $pago->id,
            'estatus' => 'cancelado',
        ]);

        $this->assertDatabaseHas('cuotas_financiamiento', [
            'id' => $cuota->id,
            'estatus' => 'pendiente',
            'monto_pagado' => 0,
        ]);
    }

    public function test_no_puede_cancelar_si_hay_cuota_posterior_pagada(): void
    {
        $user = $this->usuarioConPermiso('recibos.cancelar');
        $this->actingAs($user);

        $contrato = $this->crearContrato(['total_pagado' => 50000, 'saldo_actual' => 50000]);

        $cuota1 = $this->crearCuota($contrato, 1, [
            'estatus' => 'pagada',
            'monto_pagado' => 25000,
            'saldo' => 0,
        ]);
        $this->crearCuota($contrato, 2, [
            'estatus' => 'pagada',
            'monto_pagado' => 25000,
            'saldo' => 0,
        ]);

        ['recibo' => $recibo] = $this->crearPagoYRecibo($contrato, $cuota1, $user);

        $this->expectException(RuntimeException::class);

        $this->service()->execute($recibo, 'Intento de cancelación inválido');
    }

    public function test_recibo_ya_cancelado_lanza_excepcion(): void
    {
        $user = $this->usuarioConPermiso('recibos.cancelar');
        $this->actingAs($user);

        $contrato = $this->crearContrato(['total_pagado' => 25000, 'saldo_actual' => 75000]);
        $cuota = $this->crearCuota($contrato, 1, [
            'estatus' => 'pagada',
            'monto_pagado' => 25000,
            'saldo' => 0,
        ]);

        ['recibo' => $recibo] = $this->crearPagoYRecibo($contrato, $cuota, $user);

        $recibo->estatus = 'cancelado';
        $recibo->cancelado_at = now();
        $recibo->save();

        $this->expectException(RuntimeException::class);

        $this->service()->execute($recibo, 'Segundo intento');
    }

    public function test_cancelar_pago_con_recargo_restaura_cuota_y_contrato(): void
    {
        $user = $this->usuarioConPermiso('pagos.registrar', 'recibos.cancelar');
        $this->actingAs($user);
        $contrato = $this->crearContrato();
        $cuota = $this->crearCuota($contrato);
        $resultado = app(RegistrarPagoFinanciamientoService::class)->ejecutar(
            contrato: $contrato,
            monto: 25010,
            cuota: $cuota,
            recargo: 10,
        );

        $this->service()->execute($resultado['recibo']);

        $this->assertDatabaseHas('cuotas_financiamiento', [
            'id' => $cuota->id,
            'monto' => 25000,
            'monto_pagado' => 0,
            'recargo_aplicado' => 0,
            'saldo' => 25000,
            'estatus' => 'pendiente',
        ]);
        $this->assertDatabaseHas('contratos_financiamiento', [
            'id' => $contrato->id,
            'total_pagar' => 100000,
            'total_pagado' => 0,
            'saldo_actual' => 100000,
        ]);
    }

    public function test_solo_permite_cancelar_el_ultimo_recibo_vigente(): void
    {
        $user = $this->usuarioConPermiso('pagos.registrar', 'recibos.cancelar');
        $this->actingAs($user);
        $contrato = $this->crearContrato();
        $cuota = $this->crearCuota($contrato);
        $registrar = app(RegistrarPagoFinanciamientoService::class);
        $primerPago = $registrar->ejecutar(
            contrato: $contrato,
            monto: 10000,
            cuota: $cuota,
        );
        $registrar->ejecutar(
            contrato: $contrato->fresh(),
            monto: 5000,
            cuota: $cuota->fresh(),
        );

        $this->expectException(RuntimeException::class);

        $this->service()->execute($primerPago['recibo']);
    }
}
