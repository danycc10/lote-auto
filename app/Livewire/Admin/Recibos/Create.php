<?php

namespace App\Livewire\Admin\Recibos;

use Livewire\Component;

class Create extends Component
{
    public function mount(): void
    {
        session()->flash('info', 'Los recibos se generan automáticamente al registrar un pago en un contrato de financiamiento.');
        $this->redirectRoute('admin.recibos.index');
    }

    public function render()
    {
        return view('livewire.admin.recibos.create');
    }
}
