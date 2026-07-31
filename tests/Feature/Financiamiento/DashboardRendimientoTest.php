<?php

namespace Tests\Feature\Financiamiento;

use App\Livewire\Admin\CobranzaAutos\Dashboard;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

class DashboardRendimientoTest extends FinanciamientoTestCase
{
    public function test_calcula_el_promedio_de_atraso_en_la_base_de_datos(): void
    {
        $this->travelTo(Carbon::parse('2026-07-31 12:00:00'));
        $user = $this->usuarioConPermiso('dashboard.ver');
        $contrato = $this->crearContrato();

        $this->crearCuota($contrato, 1, ['fecha_vencimiento' => today()->subDays(10)]);
        $this->crearCuota($contrato, 2, ['fecha_vencimiento' => today()->subDays(20)]);

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertViewHas('kpis', fn (array $kpis): bool => $kpis['dias_promedio_atraso'] === 15);
    }

    public function test_acota_la_lista_y_la_seleccion_masiva_de_cuotas_vencidas(): void
    {
        $user = $this->usuarioConPermiso('dashboard.ver', 'notificaciones.enviar');
        $contrato = $this->crearContrato();

        foreach (range(1, 55) as $numero) {
            $this->crearCuota($contrato, $numero, [
                'fecha_vencimiento' => today()->subDays(56 - $numero),
            ]);
        }

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertViewHas('cuotasVencidas', fn ($cuotas): bool => $cuotas->count() === 50)
            ->call('seleccionarAtrasadas')
            ->assertCount('seleccionados', 50);
    }

    public function test_el_render_no_hace_consultas_por_cada_contrato(): void
    {
        $user = $this->usuarioConPermiso('dashboard.ver');

        foreach (range(1, 10) as $numero) {
            $contrato = $this->crearContrato();
            $this->crearCuota($contrato, $numero);
        }

        DB::flushQueryLog();
        DB::enableQueryLog();

        Livewire::actingAs($user)->test(Dashboard::class)->assertOk();

        $queries = collect(DB::getQueryLog());

        $this->assertLessThanOrEqual(
            30,
            $queries->count(),
            'El dashboard volvió a ejecutar consultas que crecen con cada contrato.'
        );

        $blade = file_get_contents(resource_path('views/livewire/admin/cobranza-autos/dashboard.blade.php'));
        $this->assertIsString($blade);
        $this->assertStringNotContainsString('->cuotas()', $blade);
    }
}
