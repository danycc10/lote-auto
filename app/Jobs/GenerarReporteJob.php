<?php

namespace App\Jobs;

use App\Enums\ReporteGeneradoEstatus;
use App\Models\ReporteGenerado;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class GenerarReporteJob implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $maxExceptions = 3;

    public int $timeout = 300;

    public bool $failOnTimeout = true;

    public int $uniqueFor = 3600;

    public function __construct(public readonly int $reporteId) {}

    public function handle(): void
    {
        $reporte = ReporteGenerado::query()->find($this->reporteId);

        if (! $reporte || $reporte->estatus === ReporteGeneradoEstatus::Listo) {
            return;
        }

        $reporte->update([
            'estatus' => ReporteGeneradoEstatus::Procesando,
            'error' => null,
        ]);

        $tipo = $reporte->tipo;
        $exportClass = $tipo->exportClass();
        $disk = (string) config('reportes.disk', 'local');
        $ruta = "reportes/{$reporte->user_id}/{$reporte->uuid}.xlsx";

        Excel::store(
            new $exportClass($reporte->desde?->toDateString(), $reporte->hasta?->toDateString()),
            $ruta,
            $disk,
        );

        $reporte->update([
            'archivo' => $ruta,
            'estatus' => ReporteGeneradoEstatus::Listo,
            'expires_at' => now()->addHours(max(1, (int) config('reportes.expiration_hours', 24))),
        ]);
    }

    /** @return list<int> */
    public function backoff(): array
    {
        return [30, 120, 300];
    }

    public function uniqueId(): string
    {
        return (string) $this->reporteId;
    }

    public function failed(?Throwable $exception): void
    {
        $reporte = ReporteGenerado::query()->find($this->reporteId);

        if ($reporte) {
            if ($reporte->archivo) {
                Storage::disk((string) config('reportes.disk', 'local'))->delete($reporte->archivo);
            }

            $reporte->update([
                'archivo' => null,
                'estatus' => ReporteGeneradoEstatus::Fallido,
                'error' => 'No fue posible generar el archivo. Inténtalo nuevamente.',
                'expires_at' => null,
            ]);
        }

        Log::error('Falló la generación asíncrona de un reporte.', [
            'reporte_id' => $this->reporteId,
            'error' => $exception?->getMessage(),
        ]);
    }
}
