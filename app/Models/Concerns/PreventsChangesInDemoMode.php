<?php

namespace App\Models\Concerns;

use App\Support\DemoMode;
use Illuminate\Database\Eloquent\Model;

trait PreventsChangesInDemoMode
{
    public static function bootPreventsChangesInDemoMode(): void
    {
        static::saving(function (Model $model): void {
            $model->ensureDemoModeAllowsChanges();
        });

        static::deleting(function (Model $model): void {
            $model->ensureDemoModeAllowsChanges();
        });
    }

    private function ensureDemoModeAllowsChanges(): void
    {
        if (app()->runningInConsole() && config('demo.allow_console_writes')) {
            return;
        }

        app(DemoMode::class)->ensureChangesAreAllowed();
    }
}
