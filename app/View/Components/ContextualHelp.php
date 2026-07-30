<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class ContextualHelp extends Component
{
    /**
     * @var array{
     *     title: string,
     *     purpose: string,
     *     steps: list<string>,
     *     information_title: string,
     *     information: list<string>,
     *     tip?: string
     * }
     */
    public array $help;

    public function __construct()
    {
        $routeName = Route::currentRouteName();
        $screens = config('screen-help.screens', []);
        $default = config('screen-help.default', []);

        if (! is_array($screens)) {
            $screens = [];
        }

        $help = is_string($routeName) ? ($screens[$routeName] ?? $default) : $default;
        $this->help = is_array($help) ? $help : [];
    }

    public function render(): View
    {
        return view('components.contextual-help');
    }
}
