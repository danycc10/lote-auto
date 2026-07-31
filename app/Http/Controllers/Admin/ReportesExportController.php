<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TipoReporte;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExportarReporteRequest;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportesExportController extends Controller
{
    public function export(ExportarReporteRequest $request): BinaryFileResponse
    {
        Gate::authorize('reportes.ver');

        $validated = $request->validated();
        $tipo = TipoReporte::from($validated['tipo']);
        $exportClass = $tipo->exportClass();
        $fecha = now()->format('Ymd');

        return Excel::download(
            new $exportClass($validated['desde'] ?? null, $validated['hasta'] ?? null),
            "{$tipo->filePrefix()}-{$fecha}.xlsx",
        );
    }
}
