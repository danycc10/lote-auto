<?php

namespace App\Livewire\Admin\ApartadosAutos;

use App\Models\ApartadoAuto;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Apartados\CancelarApartadoAutoService;

class Index extends Component
{
    use WithPagination;

    public string $q = '';
    public string $estatus = 'todos';
    public string $sortBy = 'id';
    public string $sortDir = 'desc';

    public ?int $cancelId = null;
    public ?string $motivoCancelacion = null;

    protected $queryString = [
        'q' => ['except' => ''],
        'estatus' => ['except' => 'todos'],
        'sortBy' => ['except' => 'id'],
        'sortDir' => ['except' => 'desc'],
    ];

    public function updatingQ(): void
    {
        $this->resetPage();
    }

    public function updatingEstatus(): void
    {
        $this->resetPage();
    }

    public function sort(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
            return;
        }

        $this->sortBy = $field;
        $this->sortDir = 'asc';
    }

    public function confirmarCancelacion(int $id): void
    {
        $this->cancelId = $id;
        $this->motivoCancelacion = null;
    }

    public function cerrarModalCancelacion(): void
    {
        $this->cancelId = null;
        $this->motivoCancelacion = null;
        $this->resetErrorBag();
    }

    public function cancelar(CancelarApartadoAutoService $service): void
    {
        $this->validate([
            'cancelId' => ['required', 'integer', 'exists:apartados_autos,id'],
            'motivoCancelacion' => ['nullable', 'string'],
        ]);

        $apartado = ApartadoAuto::findOrFail($this->cancelId);

        $service->ejecutar($apartado, $this->motivoCancelacion);

        $this->cerrarModalCancelacion();

        session()->flash('ok', 'Apartado cancelado correctamente.');
    }

public function render()
{
    $apartados = ApartadoAuto::query()
        ->with([
            'auto.marca',
            'auto.modelo',
            'cliente',
            'usuario',
        ])
        ->when($this->q !== '', function (Builder $query) {
            $search = trim($this->q);

            $query->where(function (Builder $sub) use ($search) {
                $sub->where('folio', 'like', "%{$search}%")
                    ->orWhere('nombre_cliente_temporal', 'like', "%{$search}%")
                    ->orWhere('telefono_cliente_temporal', 'like', "%{$search}%")
                    ->orWhere('correo_cliente_temporal', 'like', "%{$search}%")
                    ->orWhereHas('cliente', function (Builder $cliente) use ($search) {
                        $cliente->where('nombre', 'like', "%{$search}%")
                            ->orWhere('apellido_paterno', 'like', "%{$search}%")
                            ->orWhere('apellido_materno', 'like', "%{$search}%")
                            ->orWhere('telefono', 'like', "%{$search}%")
                            ->orWhere('correo', 'like', "%{$search}%");
                    })
                    ->orWhereHas('auto', function (Builder $auto) use ($search) {
                        $auto->where('vin', 'like', "%{$search}%")
                            ->orWhere('placa', 'like', "%{$search}%")
                            ->orWhere('anio', 'like', "%{$search}%")
                            ->orWhereHas('marca', function (Builder $marca) use ($search) {
                                $marca->where('nombre', 'like', "%{$search}%");
                            })
                            ->orWhereHas('modelo', function (Builder $modelo) use ($search) {
                                $modelo->where('nombre', 'like', "%{$search}%");
                            });
                    });
            });
        })
        ->when($this->estatus !== 'todos', function (Builder $query) {
            $query->where('estatus', $this->estatus);
        })
        ->orderBy($this->sortBy, $this->sortDir)
        ->paginate(15);

    return view('livewire.admin.apartados-autos.index', [
        'apartados' => $apartados,
    ])->layout('layouts.app');
}
}