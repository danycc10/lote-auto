<?php

namespace App\Services\Apartados;

use App\Models\ApartadoAuto;
use App\Models\Auto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CrearApartadoAutoService
{
    public function ejecutar(array $data): ApartadoAuto
    {
        return DB::transaction(function () use ($data) {
            /** @var Auto $auto */
            $auto = Auto::lockForUpdate()->findOrFail($data['auto_id']);

            if (!in_array($auto->estatus, ['disponible', 'recuperado'])) {
                throw ValidationException::withMessages([
                    'auto_id' => 'El auto seleccionado no está disponible para apartado.',
                ]);
            }

            $yaTieneApartadoActivo = $auto->apartados()
                ->where('estatus', 'activo')
                ->exists();

            if ($yaTieneApartadoActivo) {
                throw ValidationException::withMessages([
                    'auto_id' => 'El auto ya cuenta con un apartado activo.',
                ]);
            }

            $montoAnticipo = (float) ($data['monto_anticipo'] ?? 0);
            $saldoPendiente = max(0, $montoAnticipo);

            $apartado = ApartadoAuto::create([
                'folio' => $this->generarFolio(),
                'auto_id' => $auto->id,
                'cliente_id' => $data['cliente_id'] ?? null,
                'usuario_id' => $data['usuario_id'] ?? Auth::id(),
                'fecha_apartado' => $data['fecha_apartado'],
                'fecha_vencimiento' => $data['fecha_vencimiento'],
                'monto_anticipo' => $montoAnticipo,
                'saldo_pendiente' => $saldoPendiente,
                'forma_pago' => $data['forma_pago'] ?? null,
                'referencia' => $data['referencia'] ?? null,
                'nombre_cliente_temporal' => $data['nombre_cliente_temporal'] ?? null,
                'telefono_cliente_temporal' => $data['telefono_cliente_temporal'] ?? null,
                'correo_cliente_temporal' => $data['correo_cliente_temporal'] ?? null,
                'observaciones' => $data['observaciones'] ?? null,
                'estatus' => 'activo',
            ]);

            $auto->update([
                'estatus' => 'apartado',
            ]);

            return $apartado->fresh(['auto', 'cliente', 'usuario']);
        });
    }

    protected function generarFolio(): string
    {
        $prefix = 'APA-' . now()->format('Ymd') . '-';

        $ultimo = ApartadoAuto::query()
            ->where('folio', 'like', $prefix . '%')
            ->latest('id')
            ->value('folio');

        $consecutivo = 1;

        if ($ultimo && preg_match('/(\d+)$/', $ultimo, $m)) {
            $consecutivo = ((int) $m[1]) + 1;
        }

        return $prefix . str_pad((string) $consecutivo, 4, '0', STR_PAD_LEFT);
    }
}