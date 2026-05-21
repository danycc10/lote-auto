<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReporteApartadosExport;
use App\Exports\ReporteContratosExport;
use App\Exports\ReporteCuotasVencidasExport;
use App\Exports\ReporteInventarioExport;
use App\Exports\ReportePagosExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportesExportController extends Controller
{
    public function export(Request $request)
    {
        $tipo  = $request->input('tipo', 'pagos');
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        $exports = [
            'pagos'      => [ReportePagosExport::class,          'reporte-pagos'],
            'contratos'  => [ReporteContratosExport::class,      'reporte-contratos'],
            'cuotas'     => [ReporteCuotasVencidasExport::class, 'reporte-cuotas-vencidas'],
            'inventario' => [ReporteInventarioExport::class,     'reporte-inventario'],
            'apartados'  => [ReporteApartadosExport::class,      'reporte-apartados'],
        ];

        abort_unless(array_key_exists($tipo, $exports), 404);

        [$class, $nombre] = $exports[$tipo];
        $fecha = now()->format('Ymd');

        return Excel::download(new $class($desde, $hasta), "{$nombre}-{$fecha}.xlsx");
    }
}
