<?php

namespace App\Support;

use App\Exceptions\DemoModeException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DemoMode
{
    public function enabled(): bool
    {
        return (bool) config('demo.enabled', false);
    }

    public function allowsRequest(Request $request): bool
    {
        $routeName = $request->route()?->getName();

        return $routeName !== null
            && in_array($routeName, config('demo.allowed_write_routes', []), true);
    }

    public function blocksRequest(Request $request): bool
    {
        if (! $this->enabled()) {
            return false;
        }

        $routeName = $request->route()?->getName();

        if (
            $routeName !== null
            && in_array($routeName, config('demo.blocked_routes', []), true)
        ) {
            return true;
        }

        return ! $request->isMethodSafe() && ! $this->allowsRequest($request);
    }

    public function blocksLivewireMethod(string $method): bool
    {
        return $this->enabled()
            && Str::startsWith($method, config('demo.blocked_livewire_method_prefixes', []));
    }

    public function ensureChangesAreAllowed(?Request $request = null): void
    {
        if (! $this->enabled()) {
            return;
        }

        if ($request && ! $this->blocksRequest($request)) {
            return;
        }

        throw new DemoModeException;
    }
}
