<?php

namespace Tests\Feature\Reportes;

use App\Enums\ReporteGeneradoEstatus;
use App\Enums\TipoReporte;
use App\Jobs\GenerarReporteJob;
use App\Models\ReporteGenerado;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use RuntimeException;
use Tests\TestCase;

class GenerarReporteJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_genera_el_archivo_privado_y_marca_el_reporte_como_listo(): void
    {
        Excel::fake();
        $reporte = $this->crearReporte();
        $job = new GenerarReporteJob($reporte->id);

        $job->handle();

        $reporte->refresh();
        $ruta = "reportes/{$reporte->user_id}/{$reporte->uuid}.xlsx";

        Excel::assertStored($ruta, 'local');
        $this->assertSame(ReporteGeneradoEstatus::Listo, $reporte->estatus);
        $this->assertSame($ruta, $reporte->archivo);
        $this->assertNotNull($reporte->expires_at);
    }

    public function test_job_es_idempotente_y_declara_limites_operativos(): void
    {
        Excel::shouldReceive('store')->never();
        $reporte = $this->crearReporte(['estatus' => ReporteGeneradoEstatus::Listo]);
        $job = new GenerarReporteJob($reporte->id);

        $job->handle();

        $this->assertSame(3, $job->tries);
        $this->assertSame(3, $job->maxExceptions);
        $this->assertSame(300, $job->timeout);
        $this->assertTrue($job->failOnTimeout);
        $this->assertSame([30, 120, 300], $job->backoff());
    }

    public function test_fallo_final_limpia_el_archivo_y_conserva_un_error_seguro(): void
    {
        Storage::fake('local');
        Log::shouldReceive('error')->once();
        $reporte = $this->crearReporte([
            'archivo' => 'reportes/temporal.xlsx',
            'estatus' => ReporteGeneradoEstatus::Procesando,
        ]);
        Storage::disk('local')->put($reporte->archivo, 'contenido parcial');

        (new GenerarReporteJob($reporte->id))->failed(new RuntimeException('ruta interna sensible'));

        $reporte->refresh();
        Storage::disk('local')->assertMissing('reportes/temporal.xlsx');
        $this->assertSame(ReporteGeneradoEstatus::Fallido, $reporte->estatus);
        $this->assertSame('No fue posible generar el archivo. Inténtalo nuevamente.', $reporte->error);
        $this->assertNull($reporte->archivo);
    }

    private function crearReporte(array $atributos = []): ReporteGenerado
    {
        return ReporteGenerado::create(array_merge([
            'user_id' => User::factory()->create()->id,
            'tipo' => TipoReporte::Pagos,
            'desde' => '2026-07-01',
            'hasta' => '2026-07-31',
            'estatus' => ReporteGeneradoEstatus::Pendiente,
        ], $atributos));
    }
}
