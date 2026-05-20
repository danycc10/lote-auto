<?php

namespace App\Livewire\Admin\ApartadosAutos;

use App\Models\Auto;
use App\Models\Cliente;
use Livewire\Component;
use App\Services\Apartados\CrearApartadoAutoService;

class Create extends Component
{
    public ?int $auto_id = null;
    public ?int $cliente_id = null;

    public string $fecha_apartado = '';
    public string $fecha_vencimiento = '';
    public string $monto_anticipo = '0';

    public ?string $forma_pago = null;
    public ?string $referencia = null;

    public ?string $nombre_cliente_temporal = null;
    public ?string $telefono_cliente_temporal = null;
    public ?string $correo_cliente_temporal = null;

    public ?string $observaciones = null;

    public string $buscarAuto = '';
    public string $buscarCliente = '';

    public function mount(): void
    {
        $this->fecha_apartado = now()->format('Y-m-d');
        $this->fecha_vencimiento = now()->addDays(3)->format('Y-m-d');
    }

    protected function rules(): array
    {
        return [
            'auto_id' => ['required', 'exists:autos,id'],
            'cliente_id' => ['nullable', 'exists:clientes,id'],
            'fecha_apartado' => ['required', 'date'],
            'fecha_vencimiento' => ['required', 'date', 'after_or_equal:fecha_apartado'],
            'monto_anticipo' => ['required', 'numeric', 'min:0'],
            'forma_pago' => ['nullable', 'string', 'max:50'],
            'referencia' => ['nullable', 'string', 'max:255'],
            'nombre_cliente_temporal' => ['nullable', 'string', 'max:255'],
            'telefono_cliente_temporal' => ['nullable', 'string', 'max:30'],
            'correo_cliente_temporal' => ['nullable', 'email', 'max:255'],
            'observaciones' => ['nullable', 'string'],
        ];
    }

    public function save(CrearApartadoAutoService $service)
    {
        $this->validate();

        if (!$this->cliente_id && blank($this->nombre_cliente_temporal)) {
            $this->addError('nombre_cliente_temporal', 'Debes seleccionar un cliente o capturar el nombre temporal.');
            return null;
        }

        $apartado = $service->ejecutar([
            'auto_id' => $this->auto_id,
            'cliente_id' => $this->cliente_id,
            'fecha_apartado' => $this->fecha_apartado,
            'fecha_vencimiento' => $this->fecha_vencimiento,
            'monto_anticipo' => $this->monto_anticipo,
            'forma_pago' => $this->forma_pago,
            'referencia' => $this->referencia,
            'nombre_cliente_temporal' => $this->nombre_cliente_temporal,
            'telefono_cliente_temporal' => $this->telefono_cliente_temporal,
            'correo_cliente_temporal' => $this->correo_cliente_temporal,
            'observaciones' => $this->observaciones,
        ]);

        session()->flash('ok', 'Apartado creado correctamente con folio ' . $apartado->folio . '.');

        return redirect()->route('admin.apartados-autos.index');
    }

    public function getAutosDisponiblesProperty()
    {
        return Auto::query()
            ->with(['marca', 'modelo'])
            ->whereIn('estatus', ['disponible', 'recuperado'])
            ->when($this->buscarAuto !== '', function ($q) {
                $search = trim($this->buscarAuto);

                $q->where(function ($sub) use ($search) {
                    $sub->where('vin', 'like', "%{$search}%")
                        ->orWhere('placa', 'like', "%{$search}%")
                        ->orWhere('anio', 'like', "%{$search}%")
                        ->orWhereHas('marca', fn($m) => $m->where('nombre', 'like', "%{$search}%"))
                        ->orWhereHas('modelo', fn($m) => $m->where('nombre', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('id')
            ->limit(25)
            ->get();
    }

    public function getClientesDisponiblesProperty()
    {
        return Cliente::query()
            ->when($this->buscarCliente !== '', function ($q) {
                $search = trim($this->buscarCliente);

                $q->where(function ($sub) use ($search) {
                    $sub->where('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido_paterno', 'like', "%{$search}%")
                        ->orWhere('apellido_materno', 'like', "%{$search}%")
                        ->orWhere('telefono', 'like', "%{$search}%")
                        ->orWhere('correo', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->limit(25)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.apartados-autos.create', [
            'autosDisponibles' => $this->autosDisponibles,
            'clientesDisponibles' => $this->clientesDisponibles,
        ])->layout('layouts.app');
    }
}
