<?php

namespace App\Services\Autos;

use GdImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class ImagenAutoService
{
    private const MAX_DIMENSION = 1600;

    private const WEBP_QUALITY = 78;

    /**
     * @return array{ruta: string, disco: string, mime_type: string, tamano: int}
     */
    public function guardar(UploadedFile $archivo, int $autoId): array
    {
        if (! function_exists('imagewebp')) {
            throw new RuntimeException('La extensión GD con soporte WebP es obligatoria para procesar imágenes.');
        }

        $origen = @imagecreatefromstring($archivo->getContent());

        if (! $origen instanceof GdImage) {
            throw new RuntimeException('No fue posible decodificar la imagen cargada.');
        }

        try {
            $contenido = $this->convertirAWebp($origen);
        } finally {
            imagedestroy($origen);
        }

        $ruta = "autos/{$autoId}/".Str::uuid().'.webp';
        $disk = Storage::disk('public');

        try {
            if (! $disk->put($ruta, $contenido, 'public')) {
                throw new RuntimeException('No fue posible almacenar la imagen procesada.');
            }

            $tamano = $disk->size($ruta);
        } catch (Throwable $exception) {
            $disk->delete($ruta);

            throw $exception;
        }

        return [
            'ruta' => $ruta,
            'disco' => 'public',
            'mime_type' => 'image/webp',
            'tamano' => $tamano,
        ];
    }

    public function eliminar(string $ruta, string $disco = 'public'): void
    {
        if ($ruta && Storage::disk($disco)->exists($ruta)) {
            Storage::disk($disco)->delete($ruta);
        }
    }

    private function convertirAWebp(GdImage $origen): string
    {
        $anchoOriginal = imagesx($origen);
        $altoOriginal = imagesy($origen);
        $escala = min(1, self::MAX_DIMENSION / max($anchoOriginal, $altoOriginal));
        $ancho = max(1, (int) round($anchoOriginal * $escala));
        $alto = max(1, (int) round($altoOriginal * $escala));
        $destino = imagecreatetruecolor($ancho, $alto);

        if (! $destino instanceof GdImage) {
            throw new RuntimeException('No fue posible reservar memoria para procesar la imagen.');
        }

        imagealphablending($destino, false);
        imagesavealpha($destino, true);
        $transparente = imagecolorallocatealpha($destino, 0, 0, 0, 127);
        imagefill($destino, 0, 0, $transparente);

        $nivelBuffer = ob_get_level();

        try {
            if (! imagecopyresampled(
                $destino,
                $origen,
                0,
                0,
                0,
                0,
                $ancho,
                $alto,
                $anchoOriginal,
                $altoOriginal,
            )) {
                throw new RuntimeException('No fue posible redimensionar la imagen.');
            }

            ob_start();
            $convertida = imagewebp($destino, null, self::WEBP_QUALITY);
            $contenido = ob_get_clean();

            if (! $convertida || ! is_string($contenido) || $contenido === '') {
                throw new RuntimeException('No fue posible convertir la imagen a WebP.');
            }

            return $contenido;
        } finally {
            while (ob_get_level() > $nivelBuffer) {
                ob_end_clean();
            }

            imagedestroy($destino);
        }
    }
}
