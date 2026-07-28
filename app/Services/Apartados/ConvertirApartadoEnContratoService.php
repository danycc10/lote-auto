<?php

namespace App\Services\Apartados;

use App\Enums\ApartadoEstatus;
use App\Enums\AutoEstatus;
use App\Models\ApartadoAuto;
use App\Models\Auto;
use App\Models\ContratoFinanciamiento;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ConvertirApartadoEnContratoService
{
    public function validarParaConvertir(ApartadoAuto $apartado): ApartadoAuto
    {
        $apartado->loadMissing(['auto', 'cliente', 'contratoFinanciamiento']);

        if ($apartado->estatus !== ApartadoEstatus::Activo->value) {
            throw ValidationException::withMessages([
                'apartado' => 'Solo se pueden convertir apartados activos.',
            ]);
        }

        if (! $apartado->auto) {
            throw ValidationException::withMessages([
                'apartado' => 'El apartado no tiene auto relacionado.',
            ]);
        }

        if ($apartado->auto->estatus !== AutoEstatus::Apartado->value) {
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
            $contrato->refresh();

            // Bloquear el apartado dentro de la transacción para evitar doble conversión (TOCTOU).
            $apartado = ApartadoAuto::query()
                ->whereKey($apartado->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($apartado->estatus !== ApartadoEstatus::Activo->value) {
                throw ValidationException::withMessages([
                    'apartado' => 'El apartado ya no está activo.',
                ]);
            }

            if ($apartado->contratoFinanciamiento()->exists()) {
                throw ValidationException::withMessages([
                    'apartado' => 'Este apartado ya fue convertido a contrato.',
                ]);
            }

            $auto = null;

            if ($apartado->auto_id) {
                $auto = Auto::query()
                    ->whereKey($apartado->auto_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($auto->estatus !== AutoEstatus::Apartado->value) {
                    throw ValidationException::withMessages([
                        'apartado' => 'El auto ya no se encuentra en estatus apartado.',
                    ]);
                }
            }

            $contrato->update([
                'apartado_auto_id' => $apartado->id,
            ]);

            $apartado->update([
                'estatus' => ApartadoEstatus::Convertido->value,
                'saldo_pendiente' => 0,
            ]);

            if ($auto) {
                $auto->update([
                    'estatus' => AutoEstatus::Financiado->value,
                ]);
            }

            return $apartado->fresh(['auto', 'cliente', 'contratoFinanciamiento']);
        }, 3);
    }
}
