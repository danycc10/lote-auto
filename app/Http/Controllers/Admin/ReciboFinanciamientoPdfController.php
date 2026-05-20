<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReciboFinanciamiento;
use Barryvdh\DomPDF\Facade\Pdf;

class ReciboFinanciamientoPdfController extends Controller
{
    public function show(ReciboFinanciamiento $recibo)
    {
        $recibo->load(['contrato.auto.marca', 'contrato.auto.modelo', 'cliente', 'cuota', 'pago.capturadoPor']);

        $pdf = Pdf::loadView('pdf.contratos-financiamiento.recibo', [
            'recibo' => $recibo,
        ])->setPaper([0, 0, 226.77, 600]);

        return $pdf->stream('recibo-' . $recibo->folio . '.pdf');
    }
}
