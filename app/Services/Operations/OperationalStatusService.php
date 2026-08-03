<?php

namespace App\Services\Operations;

use App\Models\EstadoOperacion;

class OperationalStatusService
{
    public function success(string $key, ?string $message = null): void
    {
        $this->record($key, 'ok', $message);
    }

    public function failure(string $key, string $message): void
    {
        $this->record($key, 'error', $message);
    }

    private function record(string $key, string $status, ?string $message): void
    {
        EstadoOperacion::query()->updateOrCreate(
            ['clave' => $key],
            [
                'estado' => $status,
                'mensaje' => $message,
                'ejecutado_at' => now(),
            ],
        );
    }
}
