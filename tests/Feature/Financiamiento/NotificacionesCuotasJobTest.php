<?php

namespace Tests\Feature\Financiamiento;

use App\Jobs\EnviarNotificacionCuotaJob;
use App\Mail\NotificacionVencimientoCuotaMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use RuntimeException;

class NotificacionesCuotasJobTest extends FinanciamientoTestCase
{
    public function test_comando_programa_lotes_sin_marcar_como_enviado_antes_de_tiempo(): void
    {
        Queue::fake();
        $contrato = $this->crearContrato();
        $contrato->cliente->update(['correo' => 'cliente@example.com']);
        $cuota = $this->crearCuota($contrato, 1, [
            'fecha_vencimiento' => now()->addDays(3)->toDateString(),
        ]);

        $this->artisan('cuotas:notificar-vencimientos')
            ->assertSuccessful();

        Queue::assertPushed(
            EnviarNotificacionCuotaJob::class,
            fn (EnviarNotificacionCuotaJob $job): bool => $job->cuotaId === $cuota->id
                && $job->tipo === 'recordatorio',
        );
        $this->assertNull($cuota->fresh()->notificado_correo_at);
    }

    public function test_job_marca_la_cuota_solo_despues_del_envio_y_evita_duplicados(): void
    {
        Mail::fake();
        $contrato = $this->crearContrato();
        $contrato->cliente->update(['correo' => 'cliente@example.com']);
        $cuota = $this->crearCuota($contrato, 1);
        $job = new EnviarNotificacionCuotaJob(
            cuotaId: $cuota->id,
            tipo: 'recordatorio',
            fechaOperacion: now()->toDateString(),
        );

        $job->handle();
        $job->handle();

        Mail::assertSent(NotificacionVencimientoCuotaMail::class, 1);
        $this->assertNotNull($cuota->fresh()->notificado_correo_at);
    }

    public function test_job_declara_limites_y_registra_el_fallo_final(): void
    {
        Log::shouldReceive('error')
            ->once()
            ->withArgs(
                fn (string $mensaje, array $contexto): bool => $mensaje === 'No fue posible enviar la notificación de una cuota.'
                    && $contexto['cuota_id'] === 37
                    && $contexto['tipo'] === 'recordatorio'
                    && $contexto['error'] === 'Servidor SMTP no disponible',
            );
        $job = new EnviarNotificacionCuotaJob(37, 'recordatorio', fechaOperacion: '2026-07-31');

        $this->assertSame(3, $job->tries);
        $this->assertSame(3, $job->maxExceptions);
        $this->assertSame(60, $job->timeout);
        $this->assertTrue($job->failOnTimeout);
        $this->assertSame([60, 300, 900], $job->backoff());

        $job->failed(new RuntimeException('Servidor SMTP no disponible'));
    }
}
