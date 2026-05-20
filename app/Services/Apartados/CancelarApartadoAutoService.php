<?php

namespace App\Services\Apartados;

use App\Models\ApartadoAuto;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CancelarApartadoAutoService
{
    public function ejecutar(ApartadoAuto $apartado, ?string $motivoCancelacion = null): ApartadoAuto
    {
        return DB::transaction(function () use ($apartado, $motivoCancelacion) {
            $apartado->loadMissing('auto', 'contratoFinanciamiento');

            if ($apartado->estatus !== 'activo') {
                throw ValidationException::withMessages([
                    'apartado' => 'Solo se pueden cancelar apartados activos.',
                ]);
            }

            if ($apartado->contratoFinanciamiento()->exists()) {
                throw ValidationException::withMessages([
                    'apartado' => 'No se puede cancelar porque el apartado ya fue convertido a contrato.',
                ]);
            }

            $apartado->update([
                'estatus' => 'cancelado',
                'cancelado_at' => now(),
                'motivo_cancelacion' => $motivoCancelacion,
            ]);

            if ($apartado->auto && $apartado->auto->estatus === 'apartado') {
                $apartado->auto->update([
                    'estatus' => 'disponible',
                ]);
            }

            return $apartado->fresh(['auto', 'cliente']);
        });
    }
}