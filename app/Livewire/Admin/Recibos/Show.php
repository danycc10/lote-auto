<?php

namespace App\Livewire\Admin\Recibos;

use App\Models\ReciboFinanciamiento;
use Livewire\Component;

class Show extends Component
{
    public ReciboFinanciamiento $recibo;

    public function mount(ReciboFinanciamiento $recibo): void
    {
        $this->recibo = $recibo->load([
            'cliente',
            'contrato.auto',
            'contrato.vendedor',
            'cuota',
            'pago',
        ]);
    }

    public function getBadgeColorProperty(): string
    {
        return match ($this->recibo->estatus) {
            'vigente'    => 'bg-green-100 text-green-700 border-green-200',
            'cancelado'  => 'bg-red-100 text-red-700 border-red-200',
            'pendiente'  => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            default      => 'bg-gray-100 text-gray-700 border-gray-200',
        };
    }

    public function render()
    {
        return view('livewire.admin.recibos.show')
            ->layout('layouts.app')
            ->title('Recibo ' . $this->recibo->folio);
    }
}