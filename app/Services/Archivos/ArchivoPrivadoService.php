<?php

namespace App\Services\Archivos;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ArchivoPrivadoService
{
    public function guardar(UploadedFile $archivo, string $directorio): string
    {
        $ruta = $archivo->store($directorio, 'private');

        if (! is_string($ruta) || $ruta === '') {
            throw new RuntimeException('No fue posible almacenar el documento privado.');
        }

        return $ruta;
    }

    public function eliminar(?string $ruta): void
    {
        if ($ruta === null || $ruta === '') {
            return;
        }

        $disk = Storage::disk('private');

        if ($disk->exists($ruta) && ! $disk->delete($ruta)) {
            Log::warning('No fue posible eliminar un documento privado sin referencia.', [
                'ruta' => $ruta,
            ]);
        }
    }
}
