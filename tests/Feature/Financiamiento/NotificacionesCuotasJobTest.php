<?php

namespace Tests\Feature\Financiamiento;

use App\Jobs\EnviarNotificacionCuotaJob;
use App\Mail\NotificacionVencimientoCuotaMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

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
}
