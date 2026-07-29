<?php

namespace App\Models\Concerns;

use App\Support\DemoMode;

trait PreventsChangesInDemoMode
{
    public static function bootPreventsChangesInDemoMode(): void
    {
        $ensureChangesAreAllowed = static function (): void {
            if (app()->runningInConsole() && config('demo.allow_console_writes')) {
                return;
            }

            app(DemoMode::class)->ensureChangesAreAllowed();
        };

        static::saving($ensureChangesAreAllowed);
        static::deleting($ensureChangesAreAllowed);
    }
}
