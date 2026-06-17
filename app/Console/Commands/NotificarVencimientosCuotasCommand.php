<?php

namespace App\Console\Commands;

use App\Enums\CuotaEstatus;
use App\Mail\NotificacionVencimientoCuotaMail;
use App\Models\CuotaFinanciamiento;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotificarVencimientosCuotasCommand extends Command
{
    protected $signature   = 'cuotas:notificar-vencimientos';
    protected $description = 'Envía correos de recordatorio 3 días antes del vencimiento y el día que vence la cuota.';

    public function handle(): int
    {
        $hoy     = Carbon::today();
        $en3dias = $hoy->copy()->addDays(3);

        $recordatorios = $this->enviarRecordatorios($en3dias);
        $hoyVencen     = $this->enviarVencimientosHoy($hoy);

        $this->info("Recordatorios 3 días : {$recordatorios}");
        $this->info("Vencen hoy           : {$hoyVencen}");

        return self::SUCCESS;
    }

    // Cuotas que vencen en exactamente 3 días y aún no fueron notificadas
    private function enviarRecordatorios(Carbon $fecha): int
    {
        $cuotas = CuotaFinanciamiento::query()
            ->with(['contrato.cliente', 'contrato.auto.marca', 'contrato.auto.modelo'])
            ->whereIn('estatus', CuotaEstatus::pendientesDePago())
            ->whereDate('fecha_vencimiento', $fecha)
            ->whereNull('notificado_correo_at')
            ->whereHas('contrato', fn ($q) =>
                $q->whereIn('estatus', ['activo', 'atrasado'])
            )
            ->get();

        $enviados = 0;

        foreach ($cuotas as $cuota) {
            $correo = $cuota->contrato?->cliente?->correo;

            if (! $correo) {
                continue;
            }

            try {
                Mail::to($correo)->queue(new NotificacionVencimientoCuotaMail($cuota, 'recordatorio'));
                $cuota->notificado_correo_at = now();
                $cuota->saveQuietly();
                $enviados++;
            } catch (\Throwable $e) {
                $this->warn("Error enviando recordatorio cuota #{$cuota->id}: {$e->getMessage()}");
                report($e);
            }
        }

        return $enviados;
    }

    // Cuotas que vencen hoy y no han recibido notificación de vencimiento hoy
    private function enviarVencimientosHoy(Carbon $hoy): int
    {
        $cuotas = CuotaFinanciamiento::query()
            ->with(['contrato.cliente', 'contrato.auto.marca', 'contrato.auto.modelo'])
            ->whereIn('estatus', CuotaEstatus::pendientesDePago())
            ->whereDate('fecha_vencimiento', $hoy)
            ->where(function ($q) use ($hoy) {
                // No notificada hoy (null o notificada antes de hoy)
                $q->whereNull('notificado_correo_at')
                  ->orWhereDate('notificado_correo_at', '<', $hoy);
            })
            ->whereHas('contrato', fn ($q) =>
                $q->whereIn('estatus', ['activo', 'atrasado'])
            )
            ->get();

        $enviados = 0;

        foreach ($cuotas as $cuota) {
            $correo = $cuota->contrato?->cliente?->correo;

            if (! $correo) {
                continue;
            }

            try {
                Mail::to($correo)->queue(new NotificacionVencimientoCuotaMail($cuota, 'vencimiento_hoy'));
                $cuota->notificado_correo_at = now();
                $cuota->saveQuietly();
                $enviados++;
            } catch (\Throwable $e) {
                $this->warn("Error enviando vencimiento cuota #{$cuota->id}: {$e->getMessage()}");
                report($e);
            }
        }

        return $enviados;
    }
}
