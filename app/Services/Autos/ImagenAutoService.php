<?php

namespace App\Services\Autos;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImagenAutoService
{
    public function guardar(UploadedFile $archivo, int $autoId): array
    {
        $img = Image::read($archivo->getRealPath())
            ->scaleDown(1600, 1600);

        $nombre = uniqid() . '.webp';
        $ruta = "autos/{$autoId}/{$nombre}";

        Storage::disk('public')->put($ruta, (string) $img->toWebp(75));

        return [
            'ruta' => $ruta,
            'disco' => 'public',
            'mime_type' => 'image/webp',
            'tamano' => Storage::disk('public')->size($ruta),
        ];
    }

    public function eliminar(string $ruta, string $disco = 'public'): void
    {
        if ($ruta && Storage::disk($disco)->exists($ruta)) {
            Storage::disk($disco)->delete($ruta);
        }
    }
}