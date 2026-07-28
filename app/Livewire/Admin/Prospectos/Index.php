<?php

namespace App\Livewire\Admin\Prospectos;

use App\Enums\AutoEstatus;
use App\Enums\ProspectoEstatus;
use App\Models\Auto;
use App\Models\Prospecto;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $q = '';

    public string $estatus = '';

    public int $perPage = 15;

    // Modal editar / crear
    public bool $mostrarModal = false;

    public ?int $prospectoId = null;

    public string $nombre = '';

    public string $telefono = '';

    public string $correo = '';

    public string $origen = '';

    public string $observaciones = '';

    public ?int $autoId = null;

    public ?int $usuarioAsignadoId = null;

    public string $estatusForm = 'nuevo';

    // Búsqueda de auto en el modal
    public string $busquedaAuto = '';

    protected $queryString = [
        'q' => ['except' => ''],
        'estatus' => ['except' => ''],
    ];

    public function updatingQ(): void
    {
        $this->resetPage();
    }

    public function updatingEstatus(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function prospectos()
    {
        return Prospecto::query()
            ->with(['auto.marca', 'auto.modelo', 'usuarioAsignado'])
            ->when($this->q, function ($q) {
                $term = '%'.trim($this->q).'%';
                $q->where(function ($search) use ($term) {
                    $search->where('nombre', 'like', $term)
                        ->orWhere('telefono', 'like', $term)
                        ->orWhere('correo', 'like', $term);
                });
            })
            ->when(
                in_array($this->estatus, ProspectoEstatus::values(), true),
                fn ($q) => $q->where('estatus', $this->estatus),
            )
            ->latest()
            ->paginate(in_array($this->perPage, [15, 30, 50], true) ? $this->perPage : 15);
    }

    #[Computed]
    public function conteoEstatus(): array
    {
        return Prospecto::query()
            ->selectRaw('estatus, COUNT(*) as total')
            ->groupBy('estatus')
            ->pluck('total', 'estatus')
            ->toArray();
    }

    #[Computed]
    public function usuarios()
    {
        return User::orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function autosBusqueda(): array
    {
        if (strlen(trim($this->busquedaAuto)) < 2) {
            return [];
        }

        $term = '%'.trim($this->busquedaAuto).'%';

        return Auto::query()
            ->with(['marca', 'modelo'])
            ->where('activo', true)
            ->whereIn('estatus', [AutoEstatus::Disponible->value, AutoEstatus::Apartado->value])
            ->where(function ($q) use ($term) {
                $q->whereHas('marca', fn ($m) => $m->where('nombre', 'like', $term))
                    ->orWhereHas('modelo', fn ($m) => $m->where('nombre', 'like', $term))
                    ->orWhere('anio', 'like', $term)
                    ->orWhere('placa', 'like', $term);
            })
            ->limit(6)
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'label' => trim(($a->marca?->nombre ?? '').' '.($a->modelo?->nombre ?? '').' '.$a->anio),
            ])
            ->toArray();
    }

    public function abrirModalNuevo(): void
    {
        abort_unless(auth()->user()?->can('clientes.crear'), 403);

        $this->resetForm();
        $this->mostrarModal = true;
    }

    public function editar(int $id): void
    {
        abort_unless(auth()->user()?->can('clientes.editar'), 403);

        $p = Prospecto::findOrFail($id);

        $this->prospectoId = $p->id;
        $this->nombre = $p->nombre;
        $this->telefono = $p->telefono ?? '';
        $this->correo = $p->correo ?? '';
        $this->origen = $p->origen ?? '';
        $this->observaciones = $p->observaciones ?? '';
        $this->autoId = $p->auto_id;
        $this->usuarioAsignadoId = $p->usuario_asignado_id;
        $this->estatusForm = $p->estatus;
        $this->busquedaAuto = $p->auto
            ? trim(($p->auto->marca?->nombre ?? '').' '.($p->auto->modelo?->nombre ?? '').' '.$p->auto->anio)
            : '';

        $this->mostrarModal = true;
    }

    public function seleccionarAuto(int $id, string $label): void
    {
        $this->autoId = $id;
        $this->busquedaAuto = $label;
        $this->unsetComputedProperties();
    }

    public function guardar(): void
    {
        $permission = $this->prospectoId ? 'clientes.editar' : 'clientes.crear';
        abort_unless(auth()->user()?->can($permission), 403);

        $this->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:30',
            'correo' => 'nullable|email|max:255',
            'estatusForm' => 'required|in:nuevo,contactado,interesado,negociacion,ganado,perdido',
        ]);

        $datos = [
            'nombre' => trim($this->nombre),
            'telefono' => $this->telefono ?: null,
            'correo' => $this->correo ?: null,
            'origen' => $this->origen ?: null,
            'observaciones' => $this->observaciones ?: null,
            'auto_id' => $this->autoId,
            'usuario_asignado_id' => $this->usuarioAsignadoId,
            'estatus' => $this->estatusForm,
        ];

        if ($this->prospectoId) {
            Prospecto::findOrFail($this->prospectoId)->update($datos);
            $this->dispatch('toast', type: 'success', message: 'Prospecto actualizado.');
        } else {
            Prospecto::create($datos);
            $this->dispatch('toast', type: 'success', message: 'Prospecto registrado.');
        }

        $this->mostrarModal = false;
        $this->resetForm();
        $this->unsetComputedProperties();
    }

    public function cambiarEstatus(int $id, string $estatus): void
    {
        abort_unless(auth()->user()?->can('clientes.editar'), 403);

        $validated = validator(
            ['estatus' => $estatus],
            ['estatus' => ['required', Rule::enum(ProspectoEstatus::class)]],
        )->validate();

        Prospecto::findOrFail($id)->update([
            'estatus' => $validated['estatus'],
            'ultimo_contacto_at' => in_array($validated['estatus'], [ProspectoEstatus::Contactado->value, ProspectoEstatus::Interesado->value, ProspectoEstatus::Negociacion->value, ProspectoEstatus::Ganado->value], true)
                ? now()
                : null,
        ]);

        $this->unsetComputedProperties();
        $this->dispatch('toast', type: 'success', message: 'Estatus actualizado.');
    }

    public function marcarContactado(int $id): void
    {
        abort_unless(auth()->user()?->can('clientes.editar'), 403);

        Prospecto::findOrFail($id)->update(['ultimo_contacto_at' => now()]);
        $this->unsetComputedProperties();
        $this->dispatch('toast', type: 'success', message: 'Último contacto registrado.');
    }

    public function eliminar(int $id): void
    {
        abort_unless(auth()->user()?->can('clientes.eliminar'), 403);

        Prospecto::findOrFail($id)->delete();
        $this->unsetComputedProperties();
        $this->dispatch('toast', type: 'success', message: 'Prospecto eliminado.');
    }

    private function resetForm(): void
    {
        $this->prospectoId = null;
        $this->nombre = '';
        $this->telefono = '';
        $this->correo = '';
        $this->origen = '';
        $this->observaciones = '';
        $this->autoId = null;
        $this->usuarioAsignadoId = null;
        $this->estatusForm = ProspectoEstatus::Nuevo->value;
        $this->busquedaAuto = '';
    }

    public function render()
    {
        return view('livewire.admin.prospectos.index')
            ->layout('layouts.app')
            ->title('Prospectos');
    }
}
