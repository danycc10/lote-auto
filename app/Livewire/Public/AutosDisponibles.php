<?php

namespace App\Livewire\Public;

use App\Models\Auto;
use App\Models\MarcaAuto;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class AutosDisponibles extends Component
{
    use WithPagination;

    public string $search = '';
    public string $marca = '';
    public string $transmision = '';
    public string $combustible = '';
    public string $precioMin = '';
    public string $precioMax = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'marca' => ['except' => ''],
        'transmision' => ['except' => ''],
        'combustible' => ['except' => ''],
        'precioMin' => ['except' => ''],
        'precioMax' => ['except' => ''],
    ];

    public function updating($field): void
    {
        $this->resetPage();
    }

    public function limpiarFiltros(): void
    {
        $this->reset([
            'search',
            'marca',
            'transmision',
            'combustible',
            'precioMin',
            'precioMax',
        ]);

        $this->resetPage();
    }

    public function render()
    {
        $autos = Auto::query()
            ->with(['marca', 'modelo', 'imagenPortada', 'imagenes'])
            ->where('estatus', 'disponible')
            ->where('activo', true)
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('codigo_inventario', 'like', "%{$this->search}%")
                        ->orWhere('vin', 'like', "%{$this->search}%")
                        ->orWhere('placa', 'like', "%{$this->search}%")
                        ->orWhere('version', 'like', "%{$this->search}%")
                        ->orWhere('anio', 'like', "%{$this->search}%")
                        ->orWhereHas('marca', function ($m) {
                            $m->where('nombre', 'like', "%{$this->search}%");
                        })
                        ->orWhereHas('modelo', function ($m) {
                            $m->where('nombre', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->marca, fn ($q) => $q->where('marca_auto_id', $this->marca))
            ->when($this->transmision, fn ($q) => $q->where('transmision', $this->transmision))
            ->when($this->combustible, fn ($q) => $q->where('tipo_combustible', $this->combustible))
            ->when($this->precioMin, fn ($q) => $q->where('precio_contado', '>=', $this->precioMin))
            ->when($this->precioMax, fn ($q) => $q->where('precio_contado', '<=', $this->precioMax))
            ->latest()
            ->paginate(12);

        $marcas = Cache::remember('marcas_activas_v1', now()->addHour(), fn () =>
            MarcaAuto::query()->where('activo', true)->orderBy('nombre')->get()
        );

        return view('livewire.public.autos-disponibles', [
            'autos' => $autos,
            'marcas' => $marcas,
        ])->layout('layouts.guest');
    }
}