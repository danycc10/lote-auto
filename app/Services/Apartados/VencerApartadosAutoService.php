<?php

namespace App\Services\Apartados;

use App\Enums\ApartadoEstatus;
use App\Models\ApartadoAuto;
use Illuminate\Support\Facades\DB;

class VencerApartadosAutoService
{
    public function ejecutar(): int
    {
        $total = 0;

        ApartadoAuto::query()
            ->with('auto')
            ->where('estatus', ApartadoEstatus::Activo->value)
            ->whereDate('fecha_vencimiento', '<', now()->toDateString())
            ->chunkById(100, function ($apartados) use (&$total) {
                foreach ($apartados as $apartado) {
                    DB::transaction(function () use ($apartado, &$total) {
                        $apartado->refresh();

                        if ($apartado->estatus !== ApartadoEstatus::Activo->value) {
                            return;
                        }

                        $apartado->update([
                            'estatus' => ApartadoEstatus::Vencido->value,
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