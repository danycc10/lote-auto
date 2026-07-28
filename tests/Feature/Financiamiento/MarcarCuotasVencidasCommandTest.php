<?php

namespace Tests\Feature\Financiamiento;

class MarcarCuotasVencidasCommandTest extends FinanciamientoTestCase
{
    public function test_respeta_el_limite_inclusivo_de_dias_de_gracia(): void
    {
        $contrato = $this->crearContrato([
            'dias_gracia' => 3,
        ]);
        $cuota = $this->crearCuota($contrato, 1, [
            'fecha_vencimiento' => today()->subDays(3)->toDateString(),
        ]);

        $this->artisan('cuotas:marcar-vencidas')
            ->assertSuccessful();

        $this->assertSame('pendiente', $cuota->fresh()->estatus);
        $this->assertSame('activo', $contrato->fresh()->estatus);
    }

    public function test_marca_vencida_despues_de_la_gracia_y_actualiza_el_contrato(): void
    {
        $contrato = $this->crearContrato([
            'dias_gracia' => 3,
        ]);
        $cuota = $this->crearCuota($contrato, 1, [
            'fecha_vencimiento' => today()->subDays(4)->toDateString(),
        ]);

        $this->artisan('cuotas:marcar-vencidas')
            ->assertSuccessful();

        $this->assertSame('vencida', $cuota->fresh()->estatus);
        $this->assertSame('atrasado', $contrato->fresh()->estatus);
    }

    public function test_no_reabre_un_contrato_con_estatus_final(): void
    {
        $contrato = $this->crearContrato([
            'estatus' => 'cancelado',
            'dias_gracia' => 0,
        ]);
        $cuota = $this->crearCuota($contrato, 1, [
            'fecha_vencimiento' => today()->subDay()->toDateString(),
        ]);

        $this->artisan('cuotas:marcar-vencidas')
            ->assertSuccessful();

        $this->assertSame('vencida', $cuota->fresh()->estatus);
        $this->assertSame('cancelado', $contrato->fresh()->estatus);
    }
}
