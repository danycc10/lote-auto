<?php

namespace App\Livewire\Public;

use App\Models\Auto;
use Livewire\Component;

class AutoDetalle extends Component
{
    public Auto $auto;

    public function mount(Auto $auto): void
    {
        abort_unless($auto->estatus === 'disponible' && $auto->activo, 404);

        $this->auto = $auto->load([
            'marca',
            'modelo',
            'imagenPortada',
            'imagenes',
        ]);
    }

    public function render()
    {
        // Autos de la misma marca primero, luego rellena con otros
        $relacionados = Auto::query()
            ->with(['marca', 'modelo', 'imagenPortada'])
            ->where('estatus', 'disponible')
            ->where('activo', true)
            ->where('id', '!=', $this->auto->id)
            ->where('marca_auto_id', $this->auto->marca_auto_id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        if ($relacionados->count() < 4) {
            $excluir = $relacionados->pluck('id')->push($this->auto->id);
            $relleno = Auto::query()
                ->with(['marca', 'modelo', 'imagenPortada'])
                ->where('estatus', 'disponible')
                ->where('activo', true)
                ->whereNotIn('id', $excluir)
                ->inRandomOrder()
                ->limit(4 - $relacionados->count())
                ->get();
            $relacionados = $relacionados->merge($relleno);
        }

        return view('livewire.public.auto-detalle', [
            'relacionados' => $relacionados,
        ])->layout('layouts.guest');
    }
}