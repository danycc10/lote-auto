<?php

namespace App\Services\Financiamiento;

use App\Models\ReciboFinanciamiento;
use Carbon\Carbon;

class GenerarFolioReciboFinanciamientoService
{
    public function execute($fechaRecibo = null): string
    {
        $fecha = $fechaRecibo
            ? Carbon::parse($fechaRecibo)
            : now();

        $fechaTexto = $fecha->format('Ymd');

        $ultimoFolio = ReciboFinanciamiento::query()
            ->whereDate('fecha_recibo', $fecha->toDateString())
            ->where('folio', 'like', 'RF-' . $fechaTexto . '-%')
            ->lockForUpdate()
            ->orderByDesc('id')
            ->value('folio');

        $consecutivo = 1;

        if (
            $ultimoFolio &&
            preg_match('/^RF-' . $fechaTexto . '-(\d+)$/', $ultimoFolio, $match)
        ) {
            $consecutivo = ((int) $match[1]) + 1;
        }

        return sprintf('RF-%s-%04d', $fechaTexto, $consecutivo);
    }
}