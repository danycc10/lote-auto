<?php

namespace App\Jobs;

use App\Enums\CuotaEstatus;
use App\Mail\NotificacionVencimientoCuotaMail;
use App\Mail\RecordatorioPagoMail;
use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class EnviarNotificacionCuotaJob implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $tries = 3;

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
}
