<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClienteArchivoController extends Controller
{
    public function show(Cliente $cliente, string $tipo): StreamedResponse
    {
        abort_unless(auth()->check(), 403);

        $ruta = match ($tipo) {
            'ine' => $cliente->ruta_ine,
            'comprobante' => $cliente->ruta_comprobante_domicilio,
            default => null,
        };

        abort_if(blank($ruta), 404);

        $disk = Storage::disk('private');

        abort_unless($disk->exists($ruta), 404);

        $mime = $disk->mimeType($ruta) ?: 'application/octet-stream';

        return $disk->response(
            $ruta,
            basename($ruta),
            [
                'Content-Type' => $mime,
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
            ]
        );
    }
}