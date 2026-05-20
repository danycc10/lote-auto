<?php

namespace App\Livewire\Public;

use App\Models\Auto;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class LandingAutos extends Component
{
    public function render()
    {
        $autoModel = new Auto();

        $with = [];

        foreach (['marca', 'modelo', 'version', 'portada', 'imagenes'] as $relation) {
            if (method_exists($autoModel, $relation)) {
                $with[] = $relation;
            }
        }

        $baseQuery = Auto::query();

        if (!empty($with)) {
            $baseQuery->with($with);
        }

        if (Schema::hasColumn('autos', 'estatus')) {
            $baseQuery->where('estatus', 'disponible');
        }

        if (Schema::hasColumn('autos', 'activo')) {
            $baseQuery->where('activo', true);
        }

        if (Schema::hasColumn('autos', 'created_at')) {
            $baseQuery->latest();
        } else {
            $baseQuery->orderByDesc('id');
        }

        $autosDestacados = (clone $baseQuery)
            ->limit(6)
            ->get();

        /*
         * HERO:
         * Solo autos que tengan foto.
         * Primero intenta portada, si no, cualquier imagen.
         */
        $heroAutos = (clone $baseQuery)
            ->whereHas('imagenes')
            ->limit(8)
            ->get()
            ->filter(function ($auto) {
                return optional($auto->portada)->ruta || optional($auto->imagenes->first())->ruta;
            })
            ->take(5)
            ->values();

        return view('livewire.public.landing-autos', [
            'autosDestacados' => $autosDestacados,
            'heroAutos' => $heroAutos,
        ])->layout('layouts.guest');
    }
}