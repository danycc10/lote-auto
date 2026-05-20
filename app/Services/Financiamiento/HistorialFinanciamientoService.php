<?php

namespace App\Services\Financiamiento;

use App\Models\ContratoFinanciamiento;
use App\Models\HistorialFinanciamiento;
use Illuminate\Support\Facades\Auth;

class HistorialFinanciamientoService
{
    public function registrar(
        ContratoFinanciamiento $contrato,
        string $evento,
        ?array $antes = null,
        ?array $despues = null,
        ?string $observaciones = null,
        ?int $userId = null,
    ): HistorialFinanciamiento {
        return HistorialFinanciamiento::create([
            'contrato_financiamiento_id' => $contrato->id,
            'user_id' => $userId ?? Auth::id(),
            'evento' => $evento,
            'antes' => $antes,
            'despues' => $despues,
            'observaciones' => $observaciones,
        ]);
    }
}
