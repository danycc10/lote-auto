<?php

namespace App\Jobs;

use App\Enums\CuotaEstatus;
use App\Mail\NotificacionVencimientoCuotaMail;
use App\Mail\RecordatorioPagoMail;
use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use App\Support\DemoMode;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EnviarNotificacionCuotaJob implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $maxExceptions = 3;

    public int $timeout = 60;

    public bool $failOnTimeout = true;

    public int $uniqueFor = 3600;

    public function __construct(
        public readonly int $cuotaId,
        public readonly string $tipo,
        public readonly ?string $asunto = null,
        public readonly ?string $cuerpo = null,
        public readonly string $fechaOperacion = '',
    ) {}

    public function handle(): void
    {
        if (app(DemoMode::class)->enabled()) {
            return;
        }

        $cuota = CuotaFinanciamiento::query()
            ->find($this->cuotaId);

        if (
            ! $cuota
            || ! in_array($cuota->estatus, CuotaEstatus::pendientesDePago(), true)
            || $cuota->notificado_correo_at?->isToday()
        ) {
            return;
        }

        $contrato = ContratoFinanciamiento::query()
            ->with([
                'cliente',
                'auto.marca',
                'auto.modelo',
            ])
            ->find($cuota->contrato_financiamiento_id);
        $cuota->setRelation('contrato', $contrato);
        $correo = $contrato?->cliente?->correo;

        if (! $correo) {
            return;
        }

        $mailable = $this->asunto !== null && $this->cuerpo !== null
            ? new RecordatorioPagoMail($this->asunto, $this->cuerpo)
            : new NotificacionVencimientoCuotaMail($cuota, $this->tipo);

        Mail::to($correo)->send($mailable);

        $cuota->update([
            'notificado_correo_at' => now(),
        ]);
    }

    /**
     * @return list<int>
     */
    public function backoff(): array
    {
        return [60, 300, 900];
    }

    public function uniqueId(): string
    {
        $fecha = $this->fechaOperacion !== '' ? $this->fechaOperacion : now()->toDateString();

        return "{$this->cuotaId}:{$this->tipo}:{$fecha}";
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('No fue posible enviar la notificación de una cuota.', [
            'cuota_id' => $this->cuotaId,
            'tipo' => $this->tipo,
            'fecha_operacion' => $this->fechaOperacion,
            'error' => $exception?->getMessage(),
        ]);
    }
}
