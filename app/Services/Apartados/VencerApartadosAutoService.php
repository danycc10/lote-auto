<?php

namespace App\Services\Apartados;

use App\Enums\ApartadoEstatus;
use App\Enums\AutoEstatus;
use App\Models\ApartadoAuto;
use App\Models\Auto;
use Illuminate\Support\Facades\DB;

class VencerApartadosAutoService
{
    public function ejecutar(): int
    {
        $total = 0;

        ApartadoAuto::query()
            ->where('estatus', ApartadoEstatus::Activo->value)
            ->whereDate('fecha_vencimiento', '<', now()->toDateString())
            ->chunkById(100, function ($apartados) use (&$total) {
                foreach ($apartados as $apartado) {
                    DB::transaction(function () use ($apartado, &$total) {
                        $apartadoBloqueado = ApartadoAuto::query()
                            ->whereKey($apartado->id)
                            ->lockForUpdate()
                            ->firstOrFail();

                        if ($apartadoBloqueado->estatus !== ApartadoEstatus::Activo->value) {
                            return;
                        }

                        $auto = Auto::query()
                            ->whereKey($apartadoBloqueado->auto_id)
                            ->lockForUpdate()
                            ->first();

                        $apartadoBloqueado->update([
                            'estatus' => ApartadoEstatus::Vencido->value,
                        ]);

                        if ($auto?->estatus === AutoEstatus::Apartado->value) {
                            $auto->update([
                                'estatus' => AutoEstatus::Disponible->value,
                            ]);
                        }

                        $total++;
                    });
                }
            });

        return $total;
    }
}
