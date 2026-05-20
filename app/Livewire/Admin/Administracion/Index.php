<?php

namespace App\Livewire\Admin\Administracion;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.administracion.index')
            ->layout('layouts.app');
    }
}