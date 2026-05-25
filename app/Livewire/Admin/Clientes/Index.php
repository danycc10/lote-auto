<?php

namespace App\Livewire\Admin\Clientes;

use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $busqueda = '';
    public ?string $activo = null;
    public string $orden = 'recientes';

    protected $queryString = [
        'busqueda' => ['except' => ''],
        'activo' => ['except' => null],
        'orden' => ['except' => 'recientes'],
        'page' => ['except' => 1],
    ];

    public function updatingBusqueda(): void
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
            'activo',
            'orden',
        ]);

        $this->orden = 'recientes';
        $this->resetPage();
    }

    protected function queryClientes()
    {
        return Cliente::query()
            ->when($this->busqueda !== '', function ($query) {
                $texto = trim($this->busqueda);

                $query->where(function ($q) use ($texto) {
                    $q->where('nombre', 'like', "%{$texto}%")
                        ->orWhere('apellido_paterno', 'like', "%{$texto}%")
                        ->orWhere('apellido_materno', 'like', "%{$texto}%")
                        ->orWhere('telefono', 'like', "%{$texto}%")
                        ->orWhere('correo', 'like', "%{$texto}%")
                        ->orWhere('curp', 'like', "%{$texto}%")
                        ->orWhere('rfc', 'like', "%{$texto}%")
                        ->orWhere('ciudad', 'like', "%{$texto}%")
                        ->orWhere('estado', 'like', "%{$texto}%");
                });
            })
            ->when($this->activo !== null && $this->activo !== '', function ($query) {
                $query->where('activo', $this->activo === '1');
            })
            ->when($this->orden === 'recientes', fn ($q) => $q->orderByDesc('id'))
            ->when($this->orden === 'antiguos', fn ($q) => $q->orderBy('id'))
            ->when($this->orden === 'nombre_asc', fn ($q) => $q->orderBy('nombre')->orderBy('apellido_paterno')->orderBy('apellido_materno'))
            ->when($this->orden === 'nombre_desc', fn ($q) => $q->orderByDesc('nombre')->orderByDesc('apellido_paterno')->orderByDesc('apellido_materno'));
    }

    public function getClientesProperty()
    {
        return $this->queryClientes()->paginate(12);
    }

    public function getTotalClientesProperty(): int
    {
        return Cliente::count();
    }

    public function getTotalActivosProperty(): int
    {
        return Cliente::where('activo', true)->count();
    }

    public function getTotalInactivosProperty(): int
    {
        return Cliente::where('activo', false)->count();
    }

    public function toggleActivo(int $clienteId): void
    {
        abort_unless(auth()->user()?->can('clientes.editar'), 403);

        $cliente = Cliente::findOrFail($clienteId);

        $cliente->update([
            'activo' => ! $cliente->activo,
        ]);

        session()->flash('success', 'Estatus actualizado correctamente.');
    }

    public function eliminar(int $clienteId): void
    {
        abort_unless(auth()->user()?->can('clientes.eliminar'), 403);

        $cliente = Cliente::findOrFail($clienteId);

        $cliente->delete();

        session()->flash('success', 'Cliente eliminado correctamente.');
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.admin.clientes.index', [
            'clientes' => $this->clientes,
        ])->layout('layouts.app');
    }
}