<?php

namespace Tests\Feature\Operations;

use App\Enums\ReporteGeneradoEstatus;
use App\Enums\TipoReporte;
use App\Models\ReporteGenerado;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LimpiarReportesExpiradosCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_elimina_reportes_expirados_y_fallidos_antiguos_sin_tocar_los_vigentes(): void
    {
        Storage::fake('local');
        config([
            'reportes.disk' => 'local',
            'reportes.failed_retention_days' => 7,
        ]);

        $usuario = User::factory()->create();
        $expirado = $this->crearReporte($usuario, ReporteGeneradoEstatus::Listo, now()->subMinute(), 'reportes/expirado.xlsx');
        $vigente = $this->crearReporte($usuario, ReporteGeneradoEstatus::Listo, now()->addHour(), 'reportes/vigente.xlsx');
        $fallido = $this->crearReporte($usuario, ReporteGeneradoEstatus::Fallido, null, 'reportes/fallido.xlsx');
        $fallido->forceFill(['updated_at' => now()->subDays(8)])->saveQuietly();

        Storage::disk('local')->put($expirado->archivo, 'expirado');
        Storage::disk('local')->put($vigente->archivo, 'vigente');
        Storage::disk('local')->put($fallido->archivo, 'fallido');

        $this->artisan('reportes:limpiar-expirados')
            ->expectsOutput('Reportes eliminados: 2')
            ->assertSuccessful();

        $this->assertModelMissing($expirado);
        $this->assertModelMissing($fallido);
        $this->assertModelExists($vigente);
        Storage::disk('local')->assertMissing('reportes/expirado.xlsx');
        Storage::disk('local')->assertMissing('reportes/fallido.xlsx');
        Storage::disk('local')->assertExists('reportes/vigente.xlsx');
    }

    private function crearReporte(
        User $usuario,
        ReporteGeneradoEstatus $estatus,
        mixed $expira,
        string $archivo,
    ): ReporteGenerado {
        return ReporteGenerado::query()->create([
            'user_id' => $usuario->id,
            'tipo' => TipoReporte::Inventario,
            'estatus' => $estatus,
            'archivo' => $archivo,
            'expires_at' => $expira,
        ]);
    }
}
