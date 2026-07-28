<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CotizacionPdfRequest;
use App\Models\Auto;
use App\Models\Configuracion;
use App\Services\Financiamiento\CalculadoraFinanciamientoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class CotizadorPdfController extends Controller
{
    public function show(
        CotizacionPdfRequest $request,
        CalculadoraFinanciamientoService $calculadora,
    ) {
        $validated = $request->validated();
        $auto = Auto::with(['marca', 'modelo'])->findOrFail($validated['auto_id']);
        $enganche = (float) ($validated['enganche'] ?? 0);
        $plazo = (int) $validated['plazo'];
        $tasaAnual = (float) ($validated['tasa'] ?? 0);
        $montoFinanciado = max(0, (float) $auto->precio_financiado - $enganche);
        $calculo = $calculadora->calcular($montoFinanciado, $tasaAnual, $plazo);
        $fecha = now()->addMonth()->startOfMonth();

        $tabla = array_map(
            fn (array $cuota): array => [
                'numero' => $cuota['numero'],
                'fecha' => $fecha->copy()->addMonthsNoOverflow($cuota['numero'] - 1)->format('d/m/Y'),
                'capital' => $cuota['capital'],
                'interes' => $cuota['interes'],
                'cuota' => $cuota['monto'],
                'saldo' => $cuota['saldo'],
            ],
            $calculo['cuotas'],
        );

        $datos = [
            'auto' => $auto,
            'nombreCliente' => (string) ($validated['cliente'] ?? ''),
            'enganche' => $enganche,
            'plazo' => $plazo,
            'tasaAnual' => $tasaAnual,
            'montoFinanciado' => $montoFinanciado,
            'cuotaMensual' => $calculo['monto_cuota'],
            'totalPagar' => $calculo['total_pagar'],
            'totalIntereses' => $calculo['total_intereses'],
            'tablaAmortizacion' => $tabla,
            'empresa' => [
                'nombre' => Configuracion::obtener('branding.seo_titulo', config('app.name')),
                'logo' => Configuracion::obtener('branding.logo_url'),
                'telefono' => Configuracion::obtener('contact.whatsapp'),
                'direccion' => Configuracion::obtener('branding.direccion'),
                'horario' => Configuracion::obtener('branding.horario'),
            ],
            'fechaGeneracion' => now()->format('d/m/Y'),
            'validezDias' => 7,
        ];

        $nombre = ($auto->marca?->nombre ?? '').'-'.($auto->modelo?->nombre ?? '').'-'.$auto->anio;
        $pdf = Pdf::loadView('pdf.cotizador', $datos)->setPaper('letter', 'portrait');

        return $pdf->stream('cotizacion-'.Str::slug($nombre).'.pdf');
    }
}
