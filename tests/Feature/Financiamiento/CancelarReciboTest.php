<?php

namespace Tests\Feature\Financiamiento;

use App\Models\ReciboFinanciamiento;
use App\Services\Financiamiento\CancelarReciboFinanciamientoService;
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
}
