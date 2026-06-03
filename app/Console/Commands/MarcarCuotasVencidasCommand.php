<?php

namespace App\Console\Commands;

use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarcarCuotasVencidasCommand extends Command
{
    protected $signature   = 'cuotas:marcar-vencidas';
    protected $description = 'Marca cuotas pendientes/parciales como vencidas (respeta días de gracia) y actualiza estatus del contrato.';

    public function handle(): int
    {
        $hoy = Carbon::today();

        $cuotas = CuotaFinanciamiento::query()
            ->with(['contrato:id,dias_gracia,estatus'])
            ->whereIn('estatus', ['pendiente', 'parcial'])
            ->whereDate('fecha_vencimiento', '<', $hoy)
            ->get();

        $marcadas          = 0;
        $contratosAfectados = collect();

        foreach ($cuotas as $cuota) {
            $diasGracia  = (int) ($cuota->contrato?->dias_gracia ?? 0);
            $fechaLimite = Carbon::parse($cuota->fecha_vencimiento)->addDays($diasGracia);

            if ($hoy->gt($fechaLimite)) {
                $cuota->estatus = 'vencida';
                $cuota->saveQuietly();
                $marcadas++;
                $contratosAfectados->push($cuota->contrato_financiamiento_id);
            }
        }

        // Recalcular estatus de los contratos afectados
        $idsUnicos = $contratosAfectados->unique()->values();

        foreach ($idsUnicos as $id) {
            $contrato = ContratoFinanciamiento::find($id);

            if ($contrato && ! in_array($contrato->estatus, ['liquidado', 'cancelado', 'recuperado'], true)) {
                $contrato->recalcularEstatus();
                $contrato->saveQuietly();
            }
        }

        $this->info("Cuotas marcadas vencidas : {$marcadas}");
        $this->info("Contratos actualizados   : {$idsUnicos->count()}");

        return self::SUCCESS;
    }
}
