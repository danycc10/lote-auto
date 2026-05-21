<?php

namespace App\Livewire\Public;

use App\Models\Auto;
use Livewire\Component;

class LandingAutos extends Component
{
    public function render()
    {
        $autosDestacados = Auto::query()
            ->with(['marca', 'modelo', 'imagenPortada'])
            ->where('estatus', 'disponible')
            ->where('activo', true)
            ->latest()
            ->limit(6)
            ->get();

        $heroAutos = Auto::query()
            ->with(['marca', 'modelo', 'imagenPortada', 'imagenes'])
            ->where('estatus', 'disponible')
            ->where('activo', true)
            ->whereHas('imagenes')
            ->latest()
            ->limit(8)
            ->get()
            ->filter(fn ($auto) =>
                optional($auto->imagenPortada)->ruta || optional($auto->imagenes->first())->ruta
            )
            ->take(5)
            ->values();

        return view('livewire.public.landing-autos', [
            'autosDestacados' => $autosDestacados,
            'heroAutos'       => $heroAutos,
        ])->layout('layouts.guest');
    }
}
