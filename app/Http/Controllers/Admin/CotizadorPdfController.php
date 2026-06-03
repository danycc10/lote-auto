<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auto;
use App\Models\Configuracion;
use Barryvdh\DomPDF\Facade\Pdf;

class CotizadorPdfController extends Controller
{
    public function show()
    {
        abort_unless(auth()->user()?->can('contratos.ver'), 403);

        $autoId    = (int) request('auto_id');
        $enganche  = (float) request('enganche', 0);
        $plazo     = (int) request('plazo', 12);
        $tasaAnual = (float) request('tasa', 0);
        $cliente   = (string) request('cliente', '');

        $auto = Auto::with(['marca', 'modelo'])->findOrFail($autoId);

        $precio          = (float) $auto->precio_financiado;
        $montoFinanciado = max(0, $precio - $enganche);

        if ($tasaAnual > 0 && $plazo > 0) {
            $r            = $tasaAnual / 100 / 12;
            $cuotaMensual = round($montoFinanciado * $r / (1 - pow(1 + $r, -$plazo)), 2);
        } else {
            $cuotaMensual = $plazo > 0 ? round($montoFinanciado / $plazo, 2) : 0;
        }

        $totalPagar    = round($cuotaMensual * $plazo, 2);
        $totalIntereses = max(0, round($totalPagar - $montoFinanciado, 2));

        $tabla  = [];
        $saldo  = $montoFinanciado;
        $r      = $tasaAnual > 0 ? ($tasaAnual / 100 / 12) : 0;
        $fecha  = now()->addMonth()->startOfMonth();

        for ($i = 1; $i <= $plazo; $i++) {
            $interes = round($saldo * $r, 2);
            $capital = round($cuotaMensual - $interes, 2);
            $saldo   = round(max(0, $saldo - $capital), 2);

            if ($i === $plazo && $saldo > 0) {
                $capital += $saldo;
                $saldo    = 0;
            }

            $tabla[] = [
                'numero'  => $i,
                'fecha'   => $fecha->copy()->format('d/m/Y'),
                'capital' => $capital,
                'interes' => $interes,
                'cuota'   => round($capital + $interes, 2),
                'saldo'   => $saldo,
            ];

            $fecha->addMonth();
        }

        $datos = [
            'auto'              => $auto,
            'nombreCliente'     => $cliente,
            'enganche'          => $enganche,
            'plazo'             => $plazo,
            'tasaAnual'         => $tasaAnual,
            'montoFinanciado'   => $montoFinanciado,
            'cuotaMensual'      => $cuotaMensual,
            'totalPagar'        => $totalPagar,
            'totalIntereses'    => $totalIntereses,
            'tablaAmortizacion' => $tabla,
            'empresa'           => [
                'nombre'    => Configuracion::obtener('branding.seo_titulo', config('app.name')),
                'logo'      => Configuracion::obtener('branding.logo_url'),
                'telefono'  => Configuracion::obtener('contact.whatsapp'),
                'direccion' => Configuracion::obtener('branding.direccion'),
                'horario'   => Configuracion::obtener('branding.horario'),
            ],
            'fechaGeneracion' => now()->format('d/m/Y'),
            'validezDias'     => 7,
        ];

        $nombre = ($auto->marca?->nombre ?? '') . '-' . ($auto->modelo?->nombre ?? '') . '-' . $auto->anio;
        $pdf    = Pdf::loadView('pdf.cotizador', $datos)->setPaper('letter', 'portrait');

        return $pdf->stream('cotizacion-' . \Str::slug($nombre) . '.pdf');
    }
}
