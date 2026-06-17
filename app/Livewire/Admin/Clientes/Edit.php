<?php

namespace App\Livewire\Admin\Clientes;

use App\Livewire\Concerns\ClienteFormRules;
use App\Models\Cliente;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use ClienteFormRules;
    use WithFileUploads;

    public Cliente $cliente;

    public $nombre;
    public $apellido_paterno;
    public $apellido_materno;
    public $telefono;
    public $correo;
    public $curp;
    public $rfc;
    public $direccion;
    public $ciudad;
    public $estado;
    public $codigo_postal;
    public $ocupacion;
    public $ingreso_mensual;
    public $activo = true;

    public $ine;
    public $comprobante_domicilio;

    public function mount(Cliente $cliente): void
    {
        $this->cliente = $cliente;

        $this->nombre = $cliente->nombre;
        $this->apellido_paterno = $cliente->apellido_paterno;
        $this->apellido_materno = $cliente->apellido_materno;
        $this->telefono = $cliente->telefono;
        $this->correo = $cliente->correo;
        $this->curp = $cliente->curp;
        $this->rfc = $cliente->rfc;
        $this->direccion = $cliente->direccion;
        $this->ciudad = $cliente->ciudad;
        $this->estado = $cliente->estado;
        $this->codigo_postal = $cliente->codigo_postal;
        $this->ocupacion = $cliente->ocupacion;
        $this->ingreso_mensual = $cliente->ingreso_mensual;
        $this->activo = (bool) $cliente->activo;
    }

    protected function rules(): array
    {
        return $this->reglasCliente($this->cliente->id);
    }

    protected function messages(): array
    {
        return $this->mensajesCliente();
    }

    public function actualizar()
    {
        $data = $this->validate();

        $this->cliente->update([
            'nombre' => $data['nombre'],
            'apellido_paterno' => $data['apellido_paterno'] ?? null,
            'apellido_materno' => $data['apellido_materno'] ?? null,
            'telefono' => $data['telefono'] ?? null,
            'correo' => $data['correo'] ?? null,
            'curp' => $data['curp'] ?? null,
            'rfc' => $data['rfc'] ?? null,
            'direccion' => $data['direccion'] ?? null,
            'ciudad' => $data['ciudad'] ?? null,
            'estado' => $data['estado'] ?? null,
            'codigo_postal' => $data['codigo_postal'] ?? null,
            'ocupacion' => $data['ocupacion'] ?? null,
            'ingreso_mensual' => $data['ingreso_mensual'] ?? null,
            'activo' => (bool) ($data['activo'] ?? true),
        ]);

        $basePath = 'clientes/' . $this->cliente->id . '-' . Str::slug($this->cliente->nombre_completo ?: $this->cliente->nombre);

        if ($this->ine) {
            if ($this->cliente->ruta_ine && Storage::disk('private')->exists($this->cliente->ruta_ine)) {
                Storage::disk('private')->delete($this->cliente->ruta_ine);
            }

            $this->cliente->ruta_ine = $this->ine->store($basePath . '/documentos', 'private');
        }

        if ($this->comprobante_domicilio) {
            if ($this->cliente->ruta_comprobante_domicilio && Storage::disk('private')->exists($this->cliente->ruta_comprobante_domicilio)) {
                Storage::disk('private')->delete($this->cliente->ruta_comprobante_domicilio);
            }

            $this->cliente->ruta_comprobante_domicilio = $this->comprobante_domicilio->store($basePath . '/documentos', 'private');
        }

        $this->cliente->save();

        session()->flash('success', 'Cliente actualizado correctamente.');

        return redirect()->route('admin.clientes.edit', $this->cliente);
    }

    public function eliminarArchivo(string $tipo): void
    {
        if ($tipo === 'ine') {
            if ($this->cliente->ruta_ine && Storage::disk('private')->exists($this->cliente->ruta_ine)) {
                Storage::disk('private')->delete($this->cliente->ruta_ine);
            }

            $this->cliente->ruta_ine = null;
        }

        if ($tipo === 'comprobante') {
            if ($this->cliente->ruta_comprobante_domicilio && Storage::disk('private')->exists($this->cliente->ruta_comprobante_domicilio)) {
                Storage::disk('private')->delete($this->cliente->ruta_comprobante_domicilio);
            }

            $this->cliente->ruta_comprobante_domicilio = null;
        }

        $this->cliente->save();

        session()->flash('success', 'Archivo eliminado correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.clientes.edit')
            ->layout('layouts.app');
    }
}