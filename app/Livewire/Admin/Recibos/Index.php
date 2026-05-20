<?php

namespace App\Livewire\Admin\Recibos;

use App\Models\ReciboFinanciamiento;
use App\Services\Financiamiento\CancelarReciboFinanciamientoService;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class Index extends Component
{
    use WithPagination;

    public string $q = '';
    public string $sortBy = 'fecha_recibo';
    public string $sortDir = 'desc';
    public string $estatus = 'todos';

    public ?string $fechaDesde = null;
    public ?string $fechaHasta = null;
    public ?string $montoMin = null;
    public ?string $montoMax = null;
    public string $tipoRelacion = 'todos';

    public bool $modalCancelar = false;
    public ?int $reciboCancelarId = null;
    public ?string $reciboCancelarFolio = null;
    public string $motivoCancelacion = '';

    protected $queryString = [
        'q' => ['except' => ''],
        'sortBy' => ['except' => 'fecha_recibo'],
        'sortDir' => ['except' => 'desc'],
        'estatus' => ['except' => 'todos'],
        'fechaDesde' => ['except' => null],
        'fechaHasta' => ['except' => null],
        'montoMin' => ['except' => null],
        'montoMax' => ['except' => null],
        'tipoRelacion' => ['except' => 'todos'],
    ];

    public function updatingQ(): void
    {
        $this->resetPage();
    }
    public function updatingEstatus(): void
    {
        $this->resetPage();
    }
    public function updatingFechaDesde(): void
    {
        $this->resetPage();
    }
    public function updatingFechaHasta(): void
    {
        $this->resetPage();
    }
    public function updatingMontoMin(): void
    {
        $this->resetPage();
    }
    public function updatingMontoMax(): void
    {
        $this->resetPage();
    }
    public function updatingTipoRelacion(): void
    {
        $this->resetPage();
    }
    public function updatingSortBy(): void
    {
        $this->resetPage();
    }
    public function updatingSortDir(): void
    {
        $this->resetPage();
    }

    public function sort(string $field): void
    {
        $allowed = ['folio', 'fecha_recibo', 'monto', 'estatus', 'created_at'];

        if (!in_array($field, $allowed, true)) {
            return;
        }

        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
            return;
        }

        $this->sortBy = $field;
        $this->sortDir = 'asc';
    }

    public function limpiarFiltros(): void
    {
        $this->reset([
            'q',
            'estatus',
            'fechaDesde',
            'fechaHasta',
            'montoMin',
            'montoMax',
            'tipoRelacion',
        ]);

        $this->estatus = 'todos';
        $this->tipoRelacion = 'todos';
        $this->sortBy = 'fecha_recibo';
        $this->sortDir = 'desc';

        $this->resetPage();
    }

    public function abrirModalCancelar(int $id): void
    {
        $recibo = ReciboFinanciamiento::findOrFail($id);

        $this->reciboCancelarId = $recibo->id;
        $this->reciboCancelarFolio = $recibo->folio;
        $this->motivoCancelacion = '';
        $this->modalCancelar = true;
    }

    public function cerrarModalCancelar(): void
    {
        $this->reset([
            'modalCancelar',
            'reciboCancelarId',
            'reciboCancelarFolio',
            'motivoCancelacion',
        ]);

        $this->modalCancelar = false;
    }

    public function confirmarCancelacion(
        \App\Services\Financiamiento\CancelarReciboFinanciamientoService $service
    ): void {
        $this->validate([
            'motivoCancelacion' => ['nullable', 'string', 'max:1000'],
        ]);

        if (!$this->reciboCancelarId) {
            return;
        }

        try {
            $recibo = \App\Models\ReciboFinanciamiento::findOrFail($this->reciboCancelarId);

            $service->execute(
                $recibo,
                $this->motivoCancelacion ?: null
            );

            $this->cerrarModalCancelar();

            $this->dispatch(
                'toast',
                type: 'success',
                message: 'Recibo cancelado correctamente.'
            );

            $this->resetPage();
        } catch (\RuntimeException $e) {
            // error de negocio, por ejemplo no es la última cuota
            $this->cerrarModalCancelar();

            $this->dispatch(
                'toast',
                type: 'warning',
                message: $e->getMessage()
            );
        } catch (\Throwable $e) {
            $this->cerrarModalCancelar();

            $this->dispatch(
                'toast',
                type: 'error',
                message: 'Ocurrió un error inesperado al cancelar el recibo.'
            );
        }
    }

    public function getRecibosProperty()
    {
        return ReciboFinanciamiento::query()
            ->with([
                'cliente:id,nombre,apellido_paterno,apellido_materno',
                'contrato:id,folio',
                'cuota:id,numero,fecha_vencimiento,estatus,saldo',
                'pago:id,monto,estatus',
            ])
            ->when($this->q !== '', function (Builder $query) {
                $search = trim($this->q);

                $query->where(function (Builder $q) use ($search) {
                    $q->where('folio', 'like', "%{$search}%")
                        ->orWhere('concepto', 'like', "%{$search}%")
                        ->orWhere('observaciones', 'like', "%{$search}%")
                        ->orWhereHas('cliente', function (Builder $cliente) use ($search) {
                            $cliente->whereRaw(
                                "CONCAT_WS(' ', nombre, apellido_paterno, apellido_materno) LIKE ?",
                                ["%{$search}%"]
                            );
                        })
                        ->orWhereHas('contrato', function (Builder $contrato) use ($search) {
                            $contrato->where('folio', 'like', "%{$search}%");
                        });
                });
            })
            ->when($this->estatus !== 'todos', fn(Builder $q) => $q->where('estatus', $this->estatus))
            ->when($this->fechaDesde, fn(Builder $q) => $q->whereDate('fecha_recibo', '>=', $this->fechaDesde))
            ->when($this->fechaHasta, fn(Builder $q) => $q->whereDate('fecha_recibo', '<=', $this->fechaHasta))
            ->when($this->montoMin !== null && $this->montoMin !== '', fn(Builder $q) => $q->where('monto', '>=', (float) $this->montoMin))
            ->when($this->montoMax !== null && $this->montoMax !== '', fn(Builder $q) => $q->where('monto', '<=', (float) $this->montoMax))
            ->when($this->tipoRelacion === 'con_cuota', fn(Builder $q) => $q->whereNotNull('cuota_id'))
            ->when($this->tipoRelacion === 'pago_general', fn(Builder $q) => $q->whereNull('cuota_id'))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.admin.recibos.index', [
            'recibos' => $this->recibos,
        ])->layout('layouts.app')->title('Recibos');
    }
}
