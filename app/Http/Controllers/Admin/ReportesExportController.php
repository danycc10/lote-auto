<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReporteGeneradoEstatus;
use App\Enums\TipoReporte;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExportarReporteRequest;
use App\Jobs\GenerarReporteJob;
use App\Models\ReporteGenerado;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportesExportController extends Controller
{
    public function export(ExportarReporteRequest $request): RedirectResponse
    {
        Gate::authorize('reportes.ver');

        $validated = $request->validated();
        $tipo = TipoReporte::from($validated['tipo']);
        $reporte = ReporteGenerado::create([
            'user_id' => $request->user()->id,
            'tipo' => $tipo,
            'desde' => $validated['desde'] ?? null,
            'hasta' => $validated['hasta'] ?? null,
            'estatus' => 'pendiente',
        ]);

        GenerarReporteJob::dispatch($reporte->id);

        return to_route('admin.reportes.index')
            ->with('status', 'El reporte quedó en cola. Podrás descargarlo aquí cuando esté listo.');
    }

    public function download(ReporteGenerado $reporte): StreamedResponse
    {
        Gate::authorize('download', $reporte);

        abort_if($reporte->expires_at?->isPast(), 410, 'El archivo ya expiró. Genera un reporte nuevo.');
        $archivo = $reporte->archivo;
        abort_unless(
            $reporte->estatus === ReporteGeneradoEstatus::Listo && $archivo !== null && $archivo !== '',
            409,
            'El reporte todavía no está disponible.',
        );

        $disk = (string) config('reportes.disk', 'local');
        abort_unless(Storage::disk($disk)->exists($archivo), 404);

        return Storage::disk($disk)->download(
            $archivo,
            $reporte->tipo->filePrefix().'-'.$reporte->created_at->format('Ymd-His').'.xlsx',
        );
    }
}
