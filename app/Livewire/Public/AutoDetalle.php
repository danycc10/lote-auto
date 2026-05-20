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
        return view('livewire.public.auto-detalle')
            ->layout('layouts.guest');
    }
}