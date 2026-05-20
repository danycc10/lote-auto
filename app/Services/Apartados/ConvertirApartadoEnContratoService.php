<?php

namespace App\Services\Apartados;

use App\Models\ApartadoAuto;
use App\Models\ContratoFinanciamiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ConvertirApartadoEnContratoService
{
    public function validarParaConvertir(ApartadoAuto $apartado): ApartadoAuto
    {
        $apartado->loadMissing(['auto', 'cliente', 'contratoFinanciamiento']);

        if ($apartado->estatus !== 'activo') {
            throw ValidationException::withMessages([
                'apartado' => 'Solo se pueden convertir apartados activos.',
            ]);
        }

        if (!$apartado->auto) {
            throw ValidationException::withMessages([
                'apartado' => 'El apartado no tiene auto relacionado.',
            ]);
        }

        if ($apartado->auto->estatus !== 'apartado') {
            throw ValidationException::withMessages([
                'apartado' => 'El auto ya no se encuentra en estatus apartado.',
            ]);
        }

        if ($apartado->contratoFinanciamiento()->exists()) {
            throw ValidationException::withMessages([
                'apartado' => 'Este apartado ya fue convertido a contrato.',
            ]);
        }

        return $apartado;
    }

    public function finalizarConversion(ApartadoAuto $apartado, ContratoFinanciamiento $contrato): ApartadoAuto
    {
        return DB::transaction(function () use ($apartado, $contrato) {
            $apartado->refresh();
            $contrato->refresh();

            if ($apartado->estatus !== 'activo') {
                throw ValidationException::withMessages([
                    'apartado' => 'El apartado ya no está activo.',
                ]);
            }

            $contrato->update([
                'apartado_auto_id' => $apartado->id,
            ]);

            $apartado->update([
                'estatus' => 'convertido',
                'saldo_pendiente' => 0,
            ]);

            if ($apartado->auto) {
                $apartado->auto->update([
                    'estatus' => 'financiado',
                ]);
            }

            return $apartado->fresh(['auto', 'cliente', 'contratoFinanciamiento']);
        });
    }
}