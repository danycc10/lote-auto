<?php

namespace App\Exceptions;

use Illuminate\Contracts\Debug\ShouldntReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use RuntimeException;

class DemoModeException extends RuntimeException implements ShouldntReport
{
    public function __construct()
    {
        parent::__construct('Esta acción está deshabilitada en el modo demo.');
    }

    public function render(Request $request): JsonResponse|Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->getMessage(),
            ], 423);
        }

        return response()->view('errors.423', status: 423);
    }
}
