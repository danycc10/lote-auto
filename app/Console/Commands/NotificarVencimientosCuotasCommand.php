<?php

namespace App\Console\Commands;

use App\Enums\CuotaEstatus;
use App\Jobs\EnviarNotificacionCuotaJob;
use App\Models\CuotaFinanciamiento;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotificarVencimientosCuotasCommand extends Command
{
    protected $signature = 'cuotas:notificar-vencimientos';

    protected $description = 'Envía correos de recordatorio 3 días antes del vencimiento y el día que vence la cuota.';

    public function handle(): int
    {
        $hoy = Carbon::today();
        $en3dias = $hoy->copy()->addDays(3);

        $recordatorios = $this->enviarRecordatorios($en3dias);
        $hoyVencen = $this->enviarVencimientosHoy($hoy);

        $this->info("Recordatorios 3 días : {$recordatorios}");
        $this->info("Vencen hoy           : {$hoyVencen}");

        return self::SUCCESS;
    }

    // Cuotas que vencen en exactamente 3 días y aún no fueron notificadas
    private function enviarRecordatorios(Carbon $fecha): int
    {
        $programados = 0;

        CuotaFinanciamiento::query()
            ->with(['contrato.cliente', 'contrato.auto.marca', 'contrato.auto.modelo'])
            ->whereIn('estatus', CuotaEstatus::pendientesDePago())
            ->whereDate('fecha_vencimiento', $fecha)
            ->whereNull('notificado_correo_at')
            ->whereHas('contrato', fn ($q) => $q->whereIn('estatus', ['activo', 'atrasado'])
            )
            ->chunkById(200, function ($cuotas) use (&$programados, $fecha): void {
                foreach ($cuotas as $cuota) {
                    if (! $cuota->contrato?->cliente?->correo) {
                        continue;
                    }

                    EnviarNotificacionCuotaJob::dispatch(
                        cuotaId: $cuota->id,
                        tipo: 'recordatorio',
                        fechaOperacion: $fecha->toDateString(),
                    );
                    $programados++;
                }
            });

        return $programados;
    }

    // Cuotas que vencen hoy y no han recibido notificación de vencimiento hoy
    private function enviarVencimientosHoy(Carbon $hoy): int
    {
        $programados = 0;

        CuotaFinanciamiento::query()
            ->with(['contrato.cliente', 'contrato.auto.marca', 'contrato.auto.modelo'])
            ->whereIn('estatus', CuotaEstatus::pendientesDePago())
            ->whereDate('fecha_vencimiento', $hoy)
            ->where(function ($q) use ($hoy) {
                // No notificada hoy (null o notificada antes de hoy)
                $q->whereNull('notificado_correo_at')
                    ->orWhereDate('notificado_correo_at', '<', $hoy);
            })
            ->whereHas('contrato', fn ($q) => $q->whereIn('estatus', ['activo', 'atrasado'])
            )
            ->chunkById(200, function ($cuotas) use (&$programados, $hoy): void {
                foreach ($cuotas as $cuota) {
                    if (! $cuota->contrato?->cliente?->correo) {
                        continue;
                    }

                    EnviarNotificacionCuotaJob::dispatch(
                        cuotaId: $cuota->id,
                        tipo: 'vencimiento_hoy',
                        fechaOperacion: $hoy->toDateString(),
                    );
                    $programados++;
                }
            });

        return $programados;
    }
}
