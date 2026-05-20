<?php

namespace App\Services\Financiamiento;

use App\Models\AuditoriaFinanciera;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditoriaFinancieraService
{
    public function registrar(
        string $accion,
        ?Model $modelo = null,
        ?array $antes = null,
        ?array $despues = null,
        ?string $observaciones = null
    ): AuditoriaFinanciera {
        return AuditoriaFinanciera::create([
            'user_id' => Auth::id(),
            'accion' => $accion,
            'modelo' => $modelo ? get_class($modelo) : null,
            'modelo_id' => $modelo?->getKey(),
            'antes' => $antes,
            'despues' => $despues,
            'observaciones' => $observaciones,
            'ip' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}