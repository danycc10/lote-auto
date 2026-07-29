<?php

namespace App\Http\Middleware;

use App\Support\DemoMode;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventChangesInDemoMode
{
    public function __construct(
        private DemoMode $demoMode,
    ) {}

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->demoMode->blocksRequest($request)) {
            $this->demoMode->ensureChangesAreAllowed($request);
        }

        return $next($request);
    }
}
