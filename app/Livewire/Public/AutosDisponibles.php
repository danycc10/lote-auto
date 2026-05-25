<?php

namespace App\Livewire\Public;

use App\Models\Auto;
use App\Models\MarcaAuto;
use Livewire\Component;
use Livewire\WithPagination;

class AutosDisponibles extends Component
{
    use WithPagination;

    public string $search      = '';
    public string $marca       = '';
    public string $transmision = '';
    public string $combustible = '';
    public string $precioMin   = '';
    public string $precioMax   = '';
    public string $kmMin       = '';
    public string $kmMax       = '';
    public string $anio        = '';
    public string $color       = '';

    protected $queryString = [
        'search'      => ['except' => ''],
        'marca'       => ['except' => ''],
        'transmision' => ['except' => ''],
        'combustible' => ['except' => ''],
        'precioMin'   => ['except' => ''],
        'precioMax'   => ['except' => ''],
        'kmMin'       => ['except' => ''],
        'kmMax'       => ['except' => ''],
        'anio'        => ['except' => ''],
        'color'       => ['except' => ''],
    ];

    public function updating(): void
    {
        $this->resetPage();
    }

    public function limpiarFiltros(): void
    {
        $this->reset([
            'search', 'marca', 'transmision', 'combustible',
            'precioMin', 'precioMax', 'kmMin', 'kmMax', 'anio', 'color',
        ]);

        $this->resetPage();
        $this->dispatch('reset-sliders');
    }

    public function render()
    {
        $base = Auto::query()
            ->where('estatus', 'disponible')
            ->where('activo', true);

        $autos = (clone $base)
            ->with(['marca', 'modelo', 'imagenPortada', 'imagenes'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('codigo_inventario', 'like', "%{$this->search}%")
                        ->orWhere('vin', 'like', "%{$this->search}%")
                        ->orWhere('placa', 'like', "%{$this->search}%")
                        ->orWhere('version', 'like', "%{$this->search}%")
                        ->orWhere('anio', 'like', "%{$this->search}%")
                        ->orWhereHas('marca', fn ($m) => $m->where('nombre', 'like', "%{$this->search}%"))
                        ->orWhereHas('modelo', fn ($m) => $m->where('nombre', 'like', "%{$this->search}%"));
                });
            })
            ->when($this->marca,       fn ($q) => $q->where('marca_auto_id', $this->marca))
            ->when($this->transmision, fn ($q) => $q->where('transmision', $this->transmision))
            ->when($this->combustible, fn ($q) => $q->where('tipo_combustible', $this->combustible))
            ->when($this->precioMin,   fn ($q) => $q->where('precio_contado', '>=', $this->precioMin))
            ->when($this->precioMax,   fn ($q) => $q->where('precio_contado', '<=', $this->precioMax))
            ->when($this->kmMin,       fn ($q) => $q->where('kilometraje', '>=', $this->kmMin))
            ->when($this->kmMax,       fn ($q) => $q->where('kilometraje', '<=', $this->kmMax))
            ->when($this->anio,        fn ($q) => $q->where('anio', $this->anio))
            ->when($this->color,       fn ($q) => $q->where('color', 'like', "%{$this->color}%"))
            ->latest()
            ->paginate(12);

        $marcas = MarcaAuto::orderBy('nombre')->where('activo', true)->pluck('nombre', 'id');

        $anios = (clone $base)->whereNotNull('anio')
            ->distinct()->orderByDesc('anio')->pluck('anio');

        $colores = (clone $base)->whereNotNull('color')
            ->distinct()->orderBy('color')->pluck('color');

        $maxPrecio = (clone $base)->max('precio_contado') ?: 2000000;
        $maxKm     = (clone $base)->max('kilometraje')    ?: 300000;

        return view('livewire.public.autos-disponibles', [
            'autos'     => $autos,
            'marcas'    => $marcas,
            'anios'     => $anios,
            'colores'   => $colores,
            'maxPrecio' => (int) ceil($maxPrecio / 10000) * 10000,
            'maxKm'     => (int) ceil($maxKm / 10000) * 10000,
        ])->layout('layouts.guest');
    }
}