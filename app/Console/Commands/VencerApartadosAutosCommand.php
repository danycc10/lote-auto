<?php

namespace App\Console\Commands;

use App\Services\Apartados\VencerApartadosAutoService;
use App\Support\DemoMode;
use Illuminate\Console\Command;

class VencerApartadosAutosCommand extends Command
{
    protected $signature = 'apartados:vencer';

    protected $description = 'Vence apartados de autos expirados y libera las unidades';

    public function handle(VencerApartadosAutoService $service, DemoMode $demoMode): int
    {
        if ($demoMode->enabled()) {
            $this->warn('Modo demo activo: no se vencieron apartados.');

            return self::SUCCESS;
        }

        $total = $service->ejecutar();

        $this->info("Apartados vencidos procesados: {$total}");

        return self::SUCCESS;
    }
}
