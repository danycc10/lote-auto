<?php

namespace App\Livewire\Admin\CobranzaAutos;

use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use App\Models\PagoFinanciamiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public string $q = '';
    public string $estatus = 'activos'; // activos | atrasados | liquidados | todos
    public ?string $fechaDesde = null;
    public ?string $fechaHasta = null;

    public int $perPage = 10;

    protected $queryString = [
        'q' => ['except' => ''],
        'estatus' => ['except' => 'activos'],
        'fechaDesde' => ['except' => null],
        'fechaHasta' => ['except' => null],
    ];

    public function mount(): void
    {
        $this->fechaDesde = now()->startOfMonth()->format('Y-m-d');
        $this->fechaHasta = now()->endOfMonth()->format('Y-m-d');
    }

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

    protected function baseContratosQuery()
    {
        return ContratoFinanciamiento::query()
            ->with([
                'cliente',
                'auto.marca',
                'auto.modelo',
            ])
            ->where('estatus', '!=', 'cancelado');
    }

    protected function contratosQuery()
    {
        $query = $this->baseContratosQuery();

        $query->when($this->q, function ($q) {
            $term = '%' . trim($this->q) . '%';

            $q->where(function ($sub) use ($term) {
                $sub->where('folio', 'like', $term)
                    ->orWhereHas('cliente', function ($c) use ($term) {
                        $c->where('nombre', 'like', $term)
                            ->orWhere('apellido_paterno', 'like', $term)
                            ->orWhere('apellido_materno', 'like', $term)
                            ->orWhere('telefono', 'like', $term);
                    })
                    ->orWhereHas('auto', function ($a) use ($term) {
                        $a->where('vin', 'like', $term)
                            ->orWhere('placas', 'like', $term);
                    });
            });
        });

        $query->when($this->estatus === 'activos', function ($q) {
            $q->where('estatus', 'activo');
        });

        $query->when($this->estatus === 'liquidados', function ($q) {
            $q->where('estatus', 'liquidado');
        });

        $query->when($this->estatus === 'atrasados', function ($q) {
            $q->where('estatus', 'activo')
                ->whereHas('cuotas', function ($cuota) {
                    $cuota->whereIn('estatus', ['pendiente', 'parcial', 'vencida'])
                        ->where('estatus', '!=', 'cancelada')
                        ->whereDate('fecha_vencimiento', '<', today());
                });
        });

        return $query->latest('id');
    }

    protected function cuotasBase()
    {
        return CuotaFinanciamiento::query()
            ->where('estatus', '!=', 'cancelada')
            ->whereHas('contrato', function ($q) {
                $q->where('estatus', 'activo');
            });
    }

protected function pagosBase()
{
    return PagoFinanciamiento::query()
        ->where('estatus', '!=', 'cancelado')
        ->whereHas('contrato', function ($q) {
            $q->where('estatus', '!=', 'cancelado');
        });
}

    public function getKpisProperty(): array
    {
        $today = today();
        $startMonth = now()->startOfMonth()->toDateString();
        $endMonth = now()->endOfMonth()->toDateString();

        $totalVencido = (clone $this->cuotasBase())
            ->whereIn('estatus', ['pendiente', 'parcial', 'vencida'])
            ->whereDate('fecha_vencimiento', '<', $today)
            ->sum(DB::raw('COALESCE(saldo, monto)'));

        $totalPorVencer = (clone $this->cuotasBase())
            ->whereIn('estatus', ['pendiente', 'parcial'])
            ->whereBetween('fecha_vencimiento', [$today, $today->copy()->addDays(7)])
            ->sum(DB::raw('COALESCE(saldo, monto)'));

        $cobradoMes = (clone $this->pagosBase())
            ->whereBetween('fecha_pago', [$startMonth, $endMonth])
            ->sum('monto');

        $contratosActivos = ContratoFinanciamiento::query()
            ->where('estatus', 'activo')
            ->count();

        $contratosConAtraso = ContratoFinanciamiento::query()
            ->where('estatus', 'activo')
            ->whereHas('cuotas', function ($q) use ($today) {
                $q->whereIn('estatus', ['pendiente', 'parcial', 'vencida'])
                    ->where('estatus', '!=', 'cancelada')
                    ->whereDate('fecha_vencimiento', '<', $today);
            })
            ->count();

        $cuotasVencidas = (clone $this->cuotasBase())
            ->whereIn('estatus', ['pendiente', 'parcial', 'vencida'])
            ->whereDate('fecha_vencimiento', '<', $today)
            ->count();

        return [
            'total_vencido' => $totalVencido,
            'total_por_vencer' => $totalPorVencer,
            'cobrado_mes' => $cobradoMes,
            'contratos_activos' => $contratosActivos,
            'contratos_con_atraso' => $contratosConAtraso,
            'cuotas_vencidas' => $cuotasVencidas,
        ];
    }

    public function getProximosVencimientosProperty()
    {
        return (clone $this->cuotasBase())
            ->with(['contrato.cliente', 'contrato.auto.marca', 'contrato.auto.modelo'])
            ->whereIn('estatus', ['pendiente', 'parcial'])
            ->whereBetween('fecha_vencimiento', [today(), today()->copy()->addDays(7)])
            ->orderBy('fecha_vencimiento')
            ->limit(8)
            ->get();
    }

    public function getCuotasVencidasProperty()
    {
        return (clone $this->cuotasBase())
            ->with(['contrato.cliente', 'contrato.auto.marca', 'contrato.auto.modelo'])
            ->whereIn('estatus', ['pendiente', 'parcial', 'vencida'])
            ->whereDate('fecha_vencimiento', '<', today())
            ->orderBy('fecha_vencimiento')
            ->limit(10)
            ->get();
    }

    public function getContratosTopAtrasoProperty()
    {
        return ContratoFinanciamiento::query()
            ->with(['cliente', 'auto.marca', 'auto.modelo'])
            ->where('estatus', 'activo')
            ->whereHas('cuotas', function ($q) {
                $q->whereIn('estatus', ['pendiente', 'parcial', 'vencida'])
                    ->where('estatus', '!=', 'cancelada')
                    ->whereDate('fecha_vencimiento', '<', today());
            })
            ->withCount([
                'cuotas as cuotas_atrasadas_count' => function ($q) {
                    $q->whereIn('estatus', ['pendiente', 'parcial', 'vencida'])
                        ->where('estatus', '!=', 'cancelada')
                        ->whereDate('fecha_vencimiento', '<', today());
                }
            ])
            ->withSum([
                'cuotas as total_atrasado' => function ($q) {
                    $q->whereIn('estatus', ['pendiente', 'parcial', 'vencida'])
                        ->where('estatus', '!=', 'cancelada')
                        ->whereDate('fecha_vencimiento', '<', today());
                }
            ], DB::raw('COALESCE(saldo, monto)'))
            ->orderByDesc('total_atrasado')
            ->limit(8)
            ->get();
    }

    public function getCobranzaPorDiaProperty(): array
    {
        $desde = Carbon::parse($this->fechaDesde)->startOfDay();
        $hasta = Carbon::parse($this->fechaHasta)->endOfDay();

        $rows = (clone $this->pagosBase())
            ->selectRaw('DATE(fecha_pago) as fecha, SUM(monto) as total')
            ->whereBetween('fecha_pago', [$desde, $hasta])
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        return [
            'labels' => $rows->pluck('fecha')
                ->map(fn ($f) => Carbon::parse($f)->format('d/m'))
                ->values()
                ->all(),

            'data' => $rows->pluck('total')
                ->map(fn ($v) => round((float) $v, 2))
                ->values()
                ->all(),
        ];
    }

    public function render()
    {
        $contratos = $this->contratosQuery()->paginate($this->perPage);

        return view('livewire.admin.cobranza-autos.dashboard', [
            'contratos' => $contratos,
            'kpis' => $this->kpis,
            'proximosVencimientos' => $this->proximosVencimientos,
            'cuotasVencidas' => $this->cuotasVencidas,
            'contratosTopAtraso' => $this->contratosTopAtraso,
            'cobranzaPorDia' => $this->cobranzaPorDia,
        ])->layout('layouts.app');
    }
}