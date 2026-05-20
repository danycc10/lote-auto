<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Apartados\VencerApartadosAutoService;

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