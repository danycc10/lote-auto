<?php

namespace App\Livewire\Admin\Recibos;

use App\Models\ReciboFinanciamiento;
use Livewire\Component;

class Edit extends Component
{
    public ReciboFinanciamiento $recibo;

    public string $concepto = '';
    public string $observaciones = '';

    public function mount(ReciboFinanciamiento $recibo): void
    {
        $this->recibo = $recibo;

        $this->concepto = $recibo->concepto ?? '';
        $this->observaciones = $recibo->observaciones ?? '';
    }

    public function guardar(): void
    {
        $this->validate([
            'concepto' => ['nullable', 'string', 'max:1000'],
            'observaciones' => ['nullable', 'string', 'max:3000'],
        ]);

        $this->recibo->update([
            'concepto' => $this->concepto,
            'observaciones' => $this->observaciones,
        ]);

        session()->flash('ok', 'Recibo actualizado correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.recibos.edit')
            ->layout('layouts.app')
            ->title('Editar Recibo');
    }
}