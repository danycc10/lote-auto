<?php

namespace App\Console\Commands;

use App\Enums\ReporteGeneradoEstatus;
use App\Models\ReporteGenerado;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

#[Signature('reportes:limpiar-expirados')]
#[Description('Elimina archivos temporales expirados y reportes fallidos que superaron su retención.')]
class LimpiarReportesExpiradosCommand extends Command
{
    public function handle(): int
    {
        $disk = Storage::disk((string) config('reportes.disk', 'local'));
        $limiteFallidos = now()->subDays(max(1, (int) config('reportes.failed_retention_days', 7)));
        $eliminados = 0;
        $errores = 0;

        ReporteGenerado::query()
            ->where(function (Builder $query) use ($limiteFallidos): void {
                $query->where('expires_at', '<=', now())
                    ->orWhere(function (Builder $query) use ($limiteFallidos): void {
                        $query->where('estatus', ReporteGeneradoEstatus::Fallido->value)
                            ->where('updated_at', '<=', $limiteFallidos);
                    });
            })
            ->chunkById(200, function ($reportes) use ($disk, &$eliminados, &$errores): void {
                foreach ($reportes as $reporte) {
                    if ($reporte->archivo && $disk->exists($reporte->archivo) && ! $disk->delete($reporte->archivo)) {
                        $errores++;

                        continue;
                    }

                    $reporte->delete();
                    $eliminados++;
                }
            });

        $this->info("Reportes eliminados: {$eliminados}");

        if ($errores > 0) {
            $this->error("Archivos que no pudieron eliminarse: {$errores}");

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
