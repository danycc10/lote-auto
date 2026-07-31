<?php

namespace Tests\Feature\Reportes;

use App\Enums\ReporteGeneradoEstatus;
use App\Enums\TipoReporte;
use App\Livewire\Admin\Reportes\Index;
use App\Models\ReporteGenerado;
use App\Models\User;
use Database\Seeders\RolesPermisosSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ReportesDownloadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesPermisosSeeder::class);
        Storage::fake('local');
    }

    public function test_propietario_descarga_un_reporte_listo_desde_el_disco_privado(): void
    {
        $usuario = $this->usuarioReportero();
        $reporte = $this->reporteListo($usuario);
        Storage::disk('local')->put($reporte->archivo, 'xlsx');

        $this->actingAs($usuario)
            ->get(route('admin.reportes.download', $reporte))
            ->assertOk()
            ->assertDownload();
    }

    public function test_otro_reportero_no_puede_descargar_archivos_ajenos(): void
    {
        $reporte = $this->reporteListo($this->usuarioReportero());
        Storage::disk('local')->put($reporte->archivo, 'xlsx');

        $this->actingAs($this->usuarioReportero())
            ->get(route('admin.reportes.download', $reporte))
            ->assertForbidden();
    }

    public function test_rechaza_reportes_pendientes_o_expirados(): void
    {
        $usuario = $this->usuarioReportero();
        $pendiente = $this->reporteListo($usuario, [
            'archivo' => null,
            'estatus' => ReporteGeneradoEstatus::Pendiente,
            'expires_at' => null,
        ]);

        $this->actingAs($usuario)
            ->get(route('admin.reportes.download', $pendiente))
            ->assertStatus(409);

        $expirado = $this->reporteListo($usuario, ['expires_at' => now()->subMinute()]);
        $this->actingAs($usuario)
            ->get(route('admin.reportes.download', $expirado))
            ->assertGone();
    }

    public function test_pantalla_muestra_el_estado_y_la_descarga_de_reportes_propios(): void
    {
        $usuario = $this->usuarioReportero();
        $this->reporteListo($usuario);

        Livewire::actingAs($usuario)
            ->test(Index::class)
            ->assertSee('Reportes recientes')
            ->assertSee('Inventario de autos')
            ->assertSee('Listo')
            ->assertSee('Descargar');
    }

    private function usuarioReportero(): User
    {
        $usuario = User::factory()->create();
        $usuario->givePermissionTo('reportes.ver');

        return $usuario;
    }

    private function reporteListo(User $usuario, array $atributos = []): ReporteGenerado
    {
        return ReporteGenerado::create(array_merge([
            'user_id' => $usuario->id,
            'tipo' => TipoReporte::Inventario,
            'archivo' => 'reportes/'.$usuario->id.'/archivo.xlsx',
            'estatus' => ReporteGeneradoEstatus::Listo,
            'expires_at' => now()->addHour(),
        ], $atributos));
    }
}
