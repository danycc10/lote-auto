<?php

namespace App\Console\Commands;

use App\Enums\ContratoEstatus;
use App\Enums\CuotaEstatus;
use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use App\Support\DemoMode;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarcarCuotasVencidasCommand extends Command
{
    protected $signature = 'cuotas:marcar-vencidas';

    protected $description = 'Marca cuotas pendientes/parciales como vencidas (respeta días de gracia) y actualiza estatus del contrato.';

    public function handle(DemoMode $demoMode): int
    {
        if ($demoMode->enabled()) {
            $this->warn('Modo demo activo: no se actualizaron cuotas ni contratos.');

            return self::SUCCESS;
        }

        $hoy = Carbon::today();

        $marcadas = 0;
        $contratosActualizados = 0;
        $estatusFinales = [
            ContratoEstatus::Liquidado->value,
            ContratoEstatus::Cancelado->value,
            ContratoEstatus::Recuperado->value,
        ];

        CuotaFinanciamiento::query()
            ->with(['contrato:id,dias_gracia,estatus'])
            ->whereIn('estatus', [CuotaEstatus::Pendiente->value, CuotaEstatus::Parcial->value])
            ->whereDate('fecha_vencimiento', '<', $hoy)
            ->chunkById(500, function ($cuotas) use (
                $hoy,
                $estatusFinales,
                &$marcadas,
                &$contratosActualizados,
            ): void {
                $contratosAfectados = [];

                foreach ($cuotas as $cuota) {
                    $diasGracia = (int) ($cuota->contrato?->dias_gracia ?? 0);
                    $fechaLimite = Carbon::parse($cuota->fecha_vencimiento)->addDays($diasGracia);

                    if ($hoy->lessThanOrEqualTo($fechaLimite)) {
                        continue;
                    }

                    $cuota->estatus = CuotaEstatus::Vencida->value;
                    $cuota->saveQuietly();
                    $marcadas++;
                    $contratosAfectados[$cuota->contrato_financiamiento_id] = true;
                }

                if ($contratosAfectados === []) {
                    return;
                }

                $contratosActualizados += ContratoFinanciamiento::query()
                    ->whereKey(array_keys($contratosAfectados))
                    ->whereNotIn('estatus', $estatusFinales)
                    ->where('estatus', '!=', ContratoEstatus::Atrasado->value)
                    ->update([
                        'estatus' => ContratoEstatus::Atrasado->value,
                        'updated_at' => now(),
                    ]);
            });

        $this->info("Cuotas marcadas vencidas : {$marcadas}");
        $this->info("Contratos actualizados   : {$contratosActualizados}");

        return self::SUCCESS;
    }
}
