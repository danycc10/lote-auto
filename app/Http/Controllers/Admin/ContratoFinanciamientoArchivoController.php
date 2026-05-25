<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContratoFinanciamiento;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContratoFinanciamientoArchivoController extends Controller
{
    public function show(ContratoFinanciamiento $contrato): StreamedResponse
    {
        abort_unless(auth()->user()?->can('contratos.ver'), 403);
        abort_if(blank($contrato->ruta_contrato_firmado), 404);

        $disk = Storage::disk('private');
        abort_unless($disk->exists($contrato->ruta_contrato_firmado), 404);

        return $disk->response(
            $contrato->ruta_contrato_firmado,
            basename($contrato->ruta_contrato_firmado),
            [
                'Content-Type' => $disk->mimeType($contrato->ruta_contrato_firmado) ?: 'application/octet-stream',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
            ]
        );
    }
}
