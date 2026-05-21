<?php

namespace App\Livewire\Admin\Administracion;

use App\Models\TarjetaCobro;
use Livewire\Component;

class TarjetasCobroIndex extends Component
{
    public string $modo = '';
    public ?int $tarjetaId = null;

    public string $nombre = '';
    public string $banco = '';
    public string $tipo = 'transferencia';
    public string $numero = '';
    public string $titular = '';
    public bool $activa = true;

    protected function rules(): array
    {
        return [
            'nombre'   => ['required', 'string', 'max:150'],
            'banco'    => ['nullable', 'string', 'max:100'],
            'tipo'     => ['required', 'in:tarjeta,transferencia,deposito'],
            'numero'   => ['nullable', 'string', 'max:100'],
            'titular'  => ['nullable', 'string', 'max:150'],
            'activa'   => ['boolean'],
        ];
    }

    protected array $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'tipo.required'   => 'El tipo es obligatorio.',
    ];

    public function getTarjetasProperty()
    {
        return TarjetaCobro::query()
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get()
            ->groupBy('tipo');
    }

    public function iniciarCrear(): void
    {
        $this->resetCampos();
        $this->modo = 'crear';
    }

    public function iniciarEditar(int $id): void
    {
        $tarjeta = TarjetaCobro::findOrFail($id);

        $this->tarjetaId = $id;
        $this->nombre   = $tarjeta->nombre;
        $this->banco    = $tarjeta->banco ?? '';
        $this->tipo     = $tarjeta->tipo;
        $this->numero   = $tarjeta->numero ?? '';
        $this->titular  = $tarjeta->titular ?? '';
        $this->activa   = $tarjeta->activa;
        $this->modo     = 'editar';
    }

    public function guardar(): void
    {
        $this->validate();

        $datos = [
            'nombre'  => $this->nombre,
            'banco'   => $this->banco ?: null,
            'tipo'    => $this->tipo,
            'numero'  => $this->numero ?: null,
            'titular' => $this->titular ?: null,
            'activa'  => $this->activa,
        ];

        if ($this->modo === 'crear') {
            TarjetaCobro::create($datos);
            session()->flash('success', 'Cuenta / terminal registrada correctamente.');
        } else {
            TarjetaCobro::findOrFail($this->tarjetaId)->update($datos);
            session()->flash('success', 'Cuenta / terminal actualizada correctamente.');
        }

        $this->cancelar();
    }

    public function toggleActiva(int $id): void
    {
        $tarjeta = TarjetaCobro::findOrFail($id);
        $tarjeta->activa = ! $tarjeta->activa;
        $tarjeta->save();
    }

    public function eliminar(int $id): void
    {
        TarjetaCobro::findOrFail($id)->delete();
        session()->flash('success', 'Cuenta eliminada.');
        if ($this->tarjetaId === $id) {
            $this->cancelar();
        }
    }

    public function cancelar(): void
    {
        $this->modo      = '';
        $this->tarjetaId = null;
        $this->resetCampos();
    }

    private function resetCampos(): void
    {
        $this->nombre  = '';
        $this->banco   = '';
        $this->tipo    = 'transferencia';
        $this->numero  = '';
        $this->titular = '';
        $this->activa  = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.administracion.tarjetas-cobro-index', [
            'tarjetas' => $this->tarjetas,
        ])
            ->layout('layouts.app')
            ->title('Tarjetas y cuentas de cobro');
    }
}
