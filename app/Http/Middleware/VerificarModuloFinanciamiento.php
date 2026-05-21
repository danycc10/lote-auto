<?php

namespace App\Http\Middleware;

use App\Models\Configuracion;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarModuloFinanciamiento
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Configuracion::esActivo('modulo.financiamiento')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Módulo no disponible.'], 403);
            }

            return redirect()->route('admin.administracion.index')
                ->with('warning', 'El módulo de financiamiento no está habilitado en este sistema.');
        }

        return $next($request);
    }
}
