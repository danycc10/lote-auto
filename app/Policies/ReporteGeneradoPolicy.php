<?php

namespace App\Policies;

use App\Models\ReporteGenerado;
use App\Models\User;

class ReporteGeneradoPolicy
{
    public function download(User $user, ReporteGenerado $reporte): bool
    {
        return $user->can('reportes.ver')
            && ($reporte->user_id === $user->id || $user->hasRole('administrador'));
    }
}
