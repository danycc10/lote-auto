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

        $base = Auto::query()
            ->with(['marca', 'modelo', 'imagenPortada', 'imagenes'])
            ->where('estatus', 'disponible')
            ->where('activo', true)
            ->whereHas('imagenes');

        $destacados = (clone $base)
            ->where('destacado', true)
            ->latest()
            ->limit(5)
            ->get()
            ->filter(fn ($a) => optional($a->imagenPortada)->ruta || optional($a->imagenes->first())->ruta);

        $relleno = (clone $base)
            ->where('destacado', false)
            ->whereNotIn('id', $destacados->pluck('id'))
            ->latest()
            ->limit(5 - $destacados->count())
            ->get()
            ->filter(fn ($a) => optional($a->imagenPortada)->ruta || optional($a->imagenes->first())->ruta);

        $heroAutos = $destacados->concat($relleno)->take(5)->values();

        return view('livewire.public.landing-autos', [
            'autosDestacados' => $autosDestacados,
            'heroAutos'       => $heroAutos,
        ])->layout('layouts.guest');
    }
}
