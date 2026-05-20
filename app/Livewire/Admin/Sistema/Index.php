<?php

namespace App\Livewire\Admin\Sistema;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.sistema.index')
            ->layout('layouts.app');
    }
}