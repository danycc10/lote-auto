<?php

namespace App\Services\Apartados;

use App\Models\ApartadoAuto;
use Illuminate\Support\Facades\DB;

class VencerApartadosAutoService
{
    public function ejecutar(): int
    {
        $total = 0;

        ApartadoAuto::query()
            ->with('auto')
            ->where('estatus', 'activo')
            ->whereDate('fecha_vencimiento', '<', now()->toDateString())
            ->chunkById(100, function ($apartados) use (&$total) {
                foreach ($apartados as $apartado) {
                    DB::transaction(function () use ($apartado, &$total) {
                        $apartado->refresh();

                        if ($apartado->estatus !== 'activo') {
                            return;
                        }

                        $apartado->update([
                            'estatus' => 'vencido',
                        ]);

                        if ($apartado->auto && $apartado->auto->estatus === 'apartado') {
                            $apartado->auto->update([
                                'estatus' => 'disponible',
                            ]);
                        }

                        $total++;
                    });
                }
            });

        return $total;
    }
}