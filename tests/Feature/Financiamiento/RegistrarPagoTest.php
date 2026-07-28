<?php

namespace Tests\Feature\Financiamiento;

use App\Livewire\Admin\ContratosFinanciamiento\RegistrarPago;
use App\Models\PagoFinanciamiento;
use App\Models\ReciboFinanciamiento;
use App\Services\Financiamiento\RegistrarPagoFinanciamientoService;
use Illuminate\Support\Str;
use Livewire\Livewire;
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
        $this->assertDatabaseHas('aplicaciones_pagos_financiamiento', [
            'pago_financiamiento_id' => $resultado['pago']->id,
            'cuota_financiamiento_id' => $cuota->id,
            'monto' => 25000,
            'monto_capital' => 25000,
            'monto_interes' => 0,
            'monto_recargo' => 0,
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

    public function test_pago_desde_livewire_aplica_y_registra_el_recargo_cobrado(): void
    {
        $user = $this->usuarioConPermiso('pagos.registrar');
        $this->actingAs($user);

        $contrato = $this->crearContrato([
            'tipo_recargo' => 'fijo',
            'valor_recargo' => 10,
        ]);
        $cuota = $this->crearCuota($contrato, 1, [
            'fecha_vencimiento' => now()->subDay()->toDateString(),
            'estatus' => 'vencida',
        ]);

        Livewire::test(RegistrarPago::class, ['contrato' => $contrato])
            ->call('seleccionarCuota', $cuota->id)
            ->set('incluirRecargo', true)
            ->assertSet('monto', '25010.00')
            ->call('guardar')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('pagos_financiamiento', [
            'cuota_id' => $cuota->id,
            'monto' => 25010,
            'monto_aplicado' => 25010,
        ]);
        $this->assertDatabaseHas('recibos_financiamiento', [
            'cuota_id' => $cuota->id,
            'monto' => 25010,
        ]);
        $this->assertDatabaseHas('cuotas_financiamiento', [
            'id' => $cuota->id,
            'recargo_aplicado' => 10,
            'monto_pagado' => 25010,
            'saldo' => 0,
            'estatus' => 'pagada',
        ]);
        $this->assertDatabaseHas('contratos_financiamiento', [
            'id' => $contrato->id,
            'total_pagar' => 100010,
            'total_pagado' => 25010,
            'saldo_actual' => 75000,
        ]);
        $this->assertDatabaseHas('aplicaciones_pagos_financiamiento', [
            'cuota_financiamiento_id' => $cuota->id,
            'monto' => 25010,
            'monto_recargo' => 10,
            'monto_capital' => 25000,
            'recargo_generado' => 10,
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

    public function test_reintentar_la_misma_clave_no_duplica_el_pago(): void
    {
        $user = $this->usuarioConPermiso('pagos.registrar');
        $this->actingAs($user);
        $contrato = $this->crearContrato();
        $cuota = $this->crearCuota($contrato);
        $clave = (string) Str::uuid();

        $primero = $this->service()->ejecutar(
            contrato: $contrato,
            monto: 10000,
            cuota: $cuota,
            idempotencyKey: $clave,
        );
        $segundo = $this->service()->ejecutar(
            contrato: $contrato->fresh(),
            monto: 10000,
            cuota: $cuota->fresh(),
            idempotencyKey: $clave,
        );

        $this->assertSame($primero['pago']->id, $segundo['pago']->id);
        $this->assertSame($primero['recibo']->id, $segundo['recibo']->id);
        $this->assertTrue($segundo['reutilizado']);
        $this->assertDatabaseCount('pagos_financiamiento', 1);
        $this->assertDatabaseCount('recibos_financiamiento', 1);
        $this->assertSame('10000.00', $contrato->fresh()->total_pagado);
        $this->assertSame('10000.00', $cuota->fresh()->monto_pagado);
    }
}
