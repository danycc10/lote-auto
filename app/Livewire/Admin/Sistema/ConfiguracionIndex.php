<?php

namespace App\Livewire\Admin\Sistema;

use App\Models\Configuracion;
use Livewire\Component;

class ConfiguracionIndex extends Component
{
    public bool $financiamientoActivo = true;

    public function mount(): void
    {
        $this->financiamientoActivo = Configuracion::esActivo('modulo.financiamiento');
    }

    public function toggleFinanciamiento(): void
    {
        $this->financiamientoActivo = ! $this->financiamientoActivo;

        Configuracion::establecer('modulo.financiamiento', $this->financiamientoActivo ? '1' : '0');

        $this->dispatch('toast',
            type: 'success',
            message: $this->financiamientoActivo
                ? 'Módulo de financiamiento activado.'
                : 'Módulo de financiamiento desactivado.'
        );
    }

    public function render()
    {
        return view('livewire.admin.sistema.configuracion-index')
            ->layout('layouts.app')
            ->title('Configuración del sistema');
    }
}
