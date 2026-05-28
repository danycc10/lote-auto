<?php

namespace App\Livewire\Admin\Catalogos;

use App\Models\MarcaAuto;
use App\Models\ModeloAuto;
use Livewire\Component;

class MarcasModelosIndex extends Component
{
    // ── Marca ──────────────────────────────────────────────
    public string $marcaNombre    = '';
    public ?int   $marcaEditandoId = null;
    public ?int   $marcaSeleccionada = null;
    public bool   $mostrarFormMarca = false;

    // ── Modelo ─────────────────────────────────────────────
    public string $modeloNombre    = '';
    public ?int   $modeloEditandoId = null;
    public int|string $modeloMarcaId = '';
    public bool   $mostrarFormModelo = false;

    protected function rulesMarca(): array
    {
        return [
            'marcaNombre' => ['required', 'string', 'max:100'],
        ];
    }

    protected function rulesModelo(): array
    {
        return [
            'modeloNombre'   => ['required', 'string', 'max:100'],
            'modeloMarcaId'  => ['required', 'exists:marcas_autos,id'],
        ];
    }

    // ── Marcas ─────────────────────────────────────────────

    public function nuevaMarca(): void
    {
        abort_unless(auth()->user()?->can('autos.editar'), 403);
        $this->resetMarcaForm();
        $this->mostrarFormMarca = true;
    }

    public function editarMarca(int $id): void
    {
        abort_unless(auth()->user()?->can('autos.editar'), 403);
        $marca = MarcaAuto::findOrFail($id);
        $this->marcaEditandoId  = $id;
        $this->marcaNombre      = $marca->nombre;
        $this->mostrarFormMarca = true;
    }

    public function guardarMarca(): void
    {
        abort_unless(auth()->user()?->can('autos.editar'), 403);
        $this->validate($this->rulesMarca());

        MarcaAuto::updateOrCreate(
            ['id' => $this->marcaEditandoId],
            ['nombre' => trim($this->marcaNombre), 'activo' => true]
        );

        $this->resetMarcaForm();
        $this->dispatch('toast', type: 'success', message: 'Marca guardada.');
    }

    public function cancelarMarca(): void
    {
        $this->resetMarcaForm();
    }

    public function toggleActivaMarca(int $id): void
    {
        abort_unless(auth()->user()?->can('autos.editar'), 403);
        $marca = MarcaAuto::findOrFail($id);
        $marca->update(['activo' => !$marca->activo]);
    }

    public function eliminarMarca(int $id): void
    {
        abort_unless(auth()->user()?->can('autos.eliminar'), 403);
        $marca = MarcaAuto::findOrFail($id);

        if ($marca->autos()->exists()) {
            $this->dispatch('toast', type: 'error', message: 'No se puede eliminar: tiene autos asociados.');
            return;
        }

        $marca->modelos()->delete();
        $marca->delete();

        if ($this->marcaSeleccionada === $id) {
            $this->marcaSeleccionada = null;
        }

        $this->dispatch('toast', type: 'success', message: 'Marca eliminada.');
    }

    public function seleccionarMarca(int $id): void
    {
        $this->marcaSeleccionada = $this->marcaSeleccionada === $id ? null : $id;
        $this->resetModeloForm();
        $this->modeloMarcaId = $this->marcaSeleccionada ?? '';
    }

    protected function resetMarcaForm(): void
    {
        $this->marcaNombre      = '';
        $this->marcaEditandoId  = null;
        $this->mostrarFormMarca = false;
    }

    // ── Modelos ────────────────────────────────────────────

    public function nuevoModelo(): void
    {
        abort_unless(auth()->user()?->can('autos.editar'), 403);
        $this->resetModeloForm();
        $this->mostrarFormModelo = true;
    }

    public function editarModelo(int $id): void
    {
        abort_unless(auth()->user()?->can('autos.editar'), 403);
        $modelo = ModeloAuto::findOrFail($id);
        $this->modeloEditandoId  = $id;
        $this->modeloNombre      = $modelo->nombre;
        $this->modeloMarcaId     = $modelo->marca_auto_id;
        $this->mostrarFormModelo = true;
    }

    public function guardarModelo(): void
    {
        abort_unless(auth()->user()?->can('autos.editar'), 403);
        $this->validate($this->rulesModelo());

        ModeloAuto::updateOrCreate(
            ['id' => $this->modeloEditandoId],
            [
                'nombre'       => trim($this->modeloNombre),
                'marca_auto_id'=> $this->modeloMarcaId,
                'activo'       => true,
            ]
        );

        $this->resetModeloForm();
        $this->dispatch('toast', type: 'success', message: 'Modelo guardado.');
    }

    public function cancelarModelo(): void
    {
        $this->resetModeloForm();
    }

    public function toggleActivoModelo(int $id): void
    {
        abort_unless(auth()->user()?->can('autos.editar'), 403);
        $modelo = ModeloAuto::findOrFail($id);
        $modelo->update(['activo' => !$modelo->activo]);
    }

    public function eliminarModelo(int $id): void
    {
        abort_unless(auth()->user()?->can('autos.eliminar'), 403);
        $modelo = ModeloAuto::findOrFail($id);

        if ($modelo->autos()->exists()) {
            $this->dispatch('toast', type: 'error', message: 'No se puede eliminar: tiene autos asociados.');
            return;
        }

        $modelo->delete();
        $this->dispatch('toast', type: 'success', message: 'Modelo eliminado.');
    }

    protected function resetModeloForm(): void
    {
        $this->modeloNombre      = '';
        $this->modeloEditandoId  = null;
        $this->modeloMarcaId     = $this->marcaSeleccionada ?? '';
        $this->mostrarFormModelo = false;
    }

    public function render()
    {
        $marcas = MarcaAuto::withCount(['modelos', 'autos'])
            ->orderBy('nombre')
            ->get();

        $modelos = $this->marcaSeleccionada
            ? ModeloAuto::where('marca_auto_id', $this->marcaSeleccionada)
                ->withCount('autos')
                ->orderBy('nombre')
                ->get()
            : collect();

        $marcaActual = $this->marcaSeleccionada
            ? MarcaAuto::find($this->marcaSeleccionada)
            : null;

        return view('livewire.admin.catalogos.marcas-modelos-index', [
            'marcas'      => $marcas,
            'modelos'     => $modelos,
            'marcaActual' => $marcaActual,
        ])->layout('layouts.app');
    }
}
