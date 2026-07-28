<?php

namespace App\Console\Commands;

use App\Services\Apartados\VencerApartadosAutoService;
use Illuminate\Console\Command;

class VencerApartadosAutosCommand extends Command
{
    protected $signature = 'apartados:vencer';

    protected $description = 'Vence apartados de autos expirados y libera las unidades';

    public function handle(VencerApartadosAutoService $service): int
    {
        $total = $service->ejecutar();

        $this->info("Apartados vencidos procesados: {$total}");

        return self::SUCCESS;
    }
}
