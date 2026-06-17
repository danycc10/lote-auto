<?php

namespace App\Services\Apartados;

use App\Enums\ApartadoEstatus;
use App\Enums\AutoEstatus;
use App\Models\ApartadoAuto;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CancelarApartadoAutoService
{
    public function ejecutar(ApartadoAuto $apartado, ?string $motivoCancelacion = null): ApartadoAuto
    {
        return DB::transaction(function () use ($apartado, $motivoCancelacion) {
            $apartado->loadMissing('auto', 'contratoFinanciamiento');

            if ($apartado->estatus !== ApartadoEstatus::Activo->value) {
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
                'estatus' => ApartadoEstatus::Cancelado->value,
                'cancelado_at' => now(),
                'motivo_cancelacion' => $motivoCancelacion,
            ]);

            if ($apartado->auto && $apartado->auto->estatus === AutoEstatus::Apartado->value) {
                $apartado->auto->update([
                    'estatus' => AutoEstatus::Disponible->value,
                ]);
            }

            return $apartado->fresh(['auto', 'cliente']);
        });
    }
}