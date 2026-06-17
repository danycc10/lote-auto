<?php

namespace App\Livewire\Admin\Clientes;

use App\Livewire\Concerns\ClienteFormRules;
use App\Models\Cliente;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use ClienteFormRules;
    use WithFileUploads;

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

    protected function rules(): array
    {
        return $this->reglasCliente();
    }

    protected function messages(): array
    {
        return $this->mensajesCliente();
    }

    public function guardar()
    {
        $data = $this->validate();

        $cliente = Cliente::create([
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

        $basePath = 'clientes/' . $cliente->id . '-' . Str::slug($cliente->nombre_completo ?: $cliente->nombre);

        if ($this->ine) {
            $cliente->ruta_ine = $this->ine->store($basePath . '/documentos', 'private');
        }

        if ($this->comprobante_domicilio) {
            $cliente->ruta_comprobante_domicilio = $this->comprobante_domicilio->store($basePath . '/documentos', 'private');
        }

        $cliente->save();

        session()->flash('success', 'Cliente creado correctamente.');

        return redirect()->route('admin.clientes.index');
    }

    public function render()
    {
        return view('livewire.admin.clientes.create')
            ->layout('layouts.app');
    }
}