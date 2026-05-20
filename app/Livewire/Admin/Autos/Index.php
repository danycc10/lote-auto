<?php

namespace App\Livewire\Admin\Autos;

use App\Models\Auto;
use App\Models\MarcaAuto;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $busqueda = '';
    public ?int $marcaId = null;
    public ?string $estatus = null;
    public ?string $destacado = null;
    public ?string $activo = null;
    public string $orden = 'recientes';

    protected $queryString = [
        'busqueda' => ['except' => ''],
        'marcaId' => ['except' => null],
        'estatus' => ['except' => null],
        'destacado' => ['except' => null],
        'activo' => ['except' => null],
        'orden' => ['except' => 'recientes'],
        'page' => ['except' => 1],
    ];

    public function updatingBusqueda(): void
    {
        $this->resetPage();
    }

    public function updatingMarcaId(): void
    {
        $this->resetPage();
    }

    public function updatingEstatus(): void
    {
        $this->resetPage();
    }

    public function updatingDestacado(): void
    {
        $this->resetPage();
    }

    public function updatingActivo(): void
    {
        $this->resetPage();
    }

    public function updatingOrden(): void
    {
        $this->resetPage();
    }

    public function limpiarFiltros(): void
    {
        $this->reset([
            'busqueda',
            'marcaId',
            'estatus',
            'destacado',
            'activo',
            'orden',
        ]);

        $this->orden = 'recientes';
        $this->resetPage();
    }

    public function getMarcasProperty()
    {
        return MarcaAuto::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();
    }

    protected function queryAutos()
    {
        return Auto::query()
            ->with([
                'marca',
                'modelo',
                'imagenes' => fn($q) => $q->orderByDesc('es_portada')->orderBy('orden'),
            ])
            ->when($this->busqueda !== '', function ($query) {
                $texto = trim($this->busqueda);

                $query->where(function ($q) use ($texto) {
                    $q->where('codigo_inventario', 'like', "%{$texto}%")
                        ->orWhere('vin', 'like', "%{$texto}%")
                        ->orWhere('placa', 'like', "%{$texto}%")
                        ->orWhere('version', 'like', "%{$texto}%")
                        ->orWhere('color', 'like', "%{$texto}%")
                        ->orWhere('anio', 'like', "%{$texto}%")
                        ->orWhere('transmision', 'like', "%{$texto}%")
                        ->orWhere('tipo_combustible', 'like', "%{$texto}%")
                        ->orWhereHas('marca', function ($sub) use ($texto) {
                            $sub->where('nombre', 'like', "%{$texto}%");
                        })
                        ->orWhereHas('modelo', function ($sub) use ($texto) {
                            $sub->where('nombre', 'like', "%{$texto}%");
                        });
                });
            })
            ->when($this->marcaId, fn($query) => $query->where('marca_auto_id', $this->marcaId))
            ->when($this->estatus, fn($query) => $query->where('estatus', $this->estatus))
            ->when($this->destacado !== null && $this->destacado !== '', function ($query) {
                $query->where('destacado', $this->destacado === '1');
            })
            ->when($this->activo !== null && $this->activo !== '', function ($query) {
                $query->where('activo', $this->activo === '1');
            })
            ->when($this->orden === 'recientes', fn($q) => $q->orderByDesc('id'))
            ->when($this->orden === 'antiguos', fn($q) => $q->orderBy('id'))
            ->when($this->orden === 'precio_menor', fn($q) => $q->orderBy('precio_contado'))
            ->when($this->orden === 'precio_mayor', fn($q) => $q->orderByDesc('precio_contado'))
            ->when($this->orden === 'anio_nuevo', fn($q) => $q->orderByDesc('anio'))
            ->when($this->orden === 'anio_viejo', fn($q) => $q->orderBy('anio'));
    }

    public function getAutosProperty()
    {
        return $this->queryAutos()->paginate(12);
    }

    public function getTotalAutosProperty(): int
    {
        return Auto::count();
    }

    public function getTotalActivosProperty(): int
    {
        return Auto::where('activo', true)->count();
    }

    public function getTotalDisponiblesProperty(): int
    {
        return Auto::where('estatus', 'disponible')->count();
    }

    public function getTotalDestacadosProperty(): int
    {
        return Auto::where('destacado', true)->count();
    }

    public function toggleActivo(int $autoId): void
    {
        $auto = Auto::findOrFail($autoId);

        $auto->update([
            'activo' => !$auto->activo,
        ]);

        session()->flash('success', 'Estatus de activo actualizado correctamente.');
    }

    public function toggleDestacado(int $autoId): void
    {
        $auto = Auto::findOrFail($autoId);

        $auto->update([
            'destacado' => !$auto->destacado,
        ]);

        session()->flash('success', 'Estado de destacado actualizado correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.autos.index', [
            'autos' => $this->autos,
            'marcas' => $this->marcas,
        ])->layout('layouts.app');
    }
}