<?php

namespace Tests\Feature\Reportes;

use App\Enums\TipoReporte;
use App\Livewire\Admin\Reportes\Index;
use App\Models\User;
use Database\Seeders\RolesPermisosSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReportesExportValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $reportero;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesPermisosSeeder::class);

        $rol = Role::findOrCreate('reportero-validacion', 'web');
        $rol->syncPermissions(['reportes.ver']);

        $this->reportero = User::factory()->create();
        $this->reportero->assignRole($rol);

        Excel::fake();
    }

    public function test_cada_tipo_valido_selecciona_su_exportador(): void
    {
        $fecha = now()->format('Ymd');

        foreach (TipoReporte::cases() as $tipo) {
            $parameters = ['tipo' => $tipo->value];

            if ($tipo->usesDates()) {
                $parameters += ['desde' => '2026-01-01', 'hasta' => '2026-01-31'];
            }

            $this->actingAs($this->reportero)
                ->get(route('admin.reportes.export', $parameters))
                ->assertOk();

            $exportClass = $tipo->exportClass();

            Excel::assertDownloaded(
                "{$tipo->filePrefix()}-{$fecha}.xlsx",
                fn (object $export): bool => $export instanceof $exportClass,
            );
        }
    }

    public function test_rechaza_tipo_de_reporte_desconocido(): void
    {
        $this->actingAs($this->reportero)
            ->getJson(route('admin.reportes.export', [
                'tipo' => 'desconocido',
                'desde' => '2026-01-01',
                'hasta' => '2026-01-31',
            ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('tipo');
    }

    public function test_reportes_temporales_requieren_ambas_fechas(): void
    {
        $this->actingAs($this->reportero)
            ->getJson(route('admin.reportes.export', ['tipo' => TipoReporte::Pagos->value]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['desde', 'hasta']);
    }

    public function test_inventario_no_requiere_fechas(): void
    {
        $this->actingAs($this->reportero)
            ->get(route('admin.reportes.export', ['tipo' => TipoReporte::Inventario->value]))
            ->assertOk();
    }

    public function test_rechaza_formato_y_orden_de_fechas_invalidos(): void
    {
        $this->actingAs($this->reportero)
            ->getJson(route('admin.reportes.export', [
                'tipo' => TipoReporte::Contratos->value,
                'desde' => '31/01/2026',
                'hasta' => '2026-01-01',
            ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('desde');

        $this->actingAs($this->reportero)
            ->getJson(route('admin.reportes.export', [
                'tipo' => TipoReporte::Contratos->value,
                'desde' => '2026-02-01',
                'hasta' => '2026-01-01',
            ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['desde', 'hasta']);
    }

    public function test_rechaza_rangos_mayores_a_un_ano(): void
    {
        $this->actingAs($this->reportero)
            ->getJson(route('admin.reportes.export', [
                'tipo' => TipoReporte::Apartados->value,
                'desde' => '2025-01-01',
                'hasta' => '2026-01-03',
            ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('hasta');
    }

    public function test_catalogo_visible_y_tipos_aceptados_permanecen_sincronizados(): void
    {
        $tiposVisibles = array_keys(app(Index::class)->reportes());
        $tiposAceptados = array_map(fn (TipoReporte $tipo): string => $tipo->value, TipoReporte::cases());

        $this->assertSame($tiposAceptados, $tiposVisibles);
    }
}
