<?php

namespace App\Livewire\Admin\ContratosFinanciamiento;

use App\Models\ContratoFinanciamiento;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $busqueda = '';
    public ?string $estatus = null;
    public ?string $frecuencia = null;
    public string $orden = 'recientes';

    protected $queryString = [
        'busqueda' => ['except' => ''],
        'estatus' => ['except' => null],
        'frecuencia' => ['except' => null],
        'orden' => ['except' => 'recientes'],
        'page' => ['except' => 1],
    ];

    public function updatingBusqueda(): void
    {
        $this->resetPage();
    }

    public function updatingEstatus(): void
    {
        $this->resetPage();
    }

    public function updatingFrecuencia(): void
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
            'estatus',
            'frecuencia',
            'orden',
        ]);

        $this->orden = 'recientes';
        $this->resetPage();
    }

    protected function queryContratos()
    {
        return ContratoFinanciamiento::query()
            ->with(['auto.marca', 'auto.modelo', 'cliente'])
            ->when($this->busqueda !== '', function ($query) {
                $texto = trim($this->busqueda);

                $query->where(function ($q) use ($texto) {
                    $q->where('folio', 'like', "%{$texto}%")
                        ->orWhere('observaciones', 'like', "%{$texto}%")
                        ->orWhereHas('cliente', function ($sub) use ($texto) {
                            $sub->where('nombre', 'like', "%{$texto}%")
                                ->orWhere('apellido_paterno', 'like', "%{$texto}%")
                                ->orWhere('apellido_materno', 'like', "%{$texto}%")
                                ->orWhere('telefono', 'like', "%{$texto}%");
                        })
                        ->orWhereHas('auto', function ($sub) use ($texto) {
                            $sub->where('codigo_inventario', 'like', "%{$texto}%")
                                ->orWhere('vin', 'like', "%{$texto}%")
                                ->orWhere('placa', 'like', "%{$texto}%")
                                ->orWhere('anio', 'like', "%{$texto}%")
                                ->orWhereHas('marca', fn($marca) => $marca->where('nombre', 'like', "%{$texto}%"))
                                ->orWhereHas('modelo', fn($modelo) => $modelo->where('nombre', 'like', "%{$texto}%"));
                        });
                });
            })
            ->when($this->estatus, fn($q) => $q->where('estatus', $this->estatus))
            ->when($this->frecuencia, fn($q) => $q->where('frecuencia', $this->frecuencia))
            ->when($this->orden === 'recientes', fn($q) => $q->orderByDesc('id'))
            ->when($this->orden === 'antiguos', fn($q) => $q->orderBy('id'))
            ->when($this->orden === 'saldo_mayor', fn($q) => $q->orderByDesc('saldo_actual'))
            ->when($this->orden === 'saldo_menor', fn($q) => $q->orderBy('saldo_actual'))
            ->when($this->orden === 'fecha_contrato', fn($q) => $q->orderByDesc('fecha_contrato'));
    }

    public function getContratosProperty()
    {
        return $this->queryContratos()->paginate(12);
    }

    public function getTotalContratosProperty(): int
    {
        return ContratoFinanciamiento::count();
    }

    public function getTotalActivosProperty(): int
    {
        return ContratoFinanciamiento::where('estatus', 'activo')->count();
    }

    public function getTotalAtrasadosProperty(): int
    {
        return ContratoFinanciamiento::where('estatus', 'atrasado')->count();
    }

    public function getTotalLiquidadoProperty(): int
    {
        return ContratoFinanciamiento::where('estatus', 'liquidado')->count();
    }

    public function render()
    {
        return view('livewire.admin.contratos-financiamiento.index', [
            'contratos' => $this->contratos,
        ])->layout('layouts.app');
    }
}