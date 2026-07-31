<?php

namespace App\Livewire\Admin\CobranzaAutos;

use App\Enums\CuotaEstatus;
use App\Jobs\EnviarNotificacionCuotaJob;
use App\Models\Configuracion;
use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use App\Models\PagoFinanciamiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    private const CUOTAS_VENCIDAS_LIMIT = 50;

    public string $q = '';

    public string $estatus = 'activos'; // activos | atrasados | liquidados | todos

    public ?string $fechaDesde = null;

    public ?string $fechaHasta = null;

    public int $perPage = 10;

    public array $seleccionados = [];

    public array $cuotasParaEnviar = [];

    public bool $mostrarModal = false;

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
        $query = $this->baseContratosQuery()
            ->withSum([
                'cuotas as saldo_pendiente' => fn ($cuota) => $cuota
                    ->whereIn('estatus', CuotaEstatus::conSaldo()),
            ], DB::raw('COALESCE(saldo, monto)'))
            ->addSelect([
                'proxima_cuota_monto' => CuotaFinanciamiento::query()
                    ->select('monto')
                    ->whereColumn('contrato_financiamiento_id', 'contratos_financiamiento.id')
                    ->whereIn('estatus', CuotaEstatus::conSaldo())
                    ->orderBy('fecha_vencimiento')
                    ->limit(1),
            ]);

        $query->when($this->q, function ($q) {
            $term = '%'.trim($this->q).'%';

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
                            ->orWhere('placa', 'like', $term);
                    });
            });
        });

        $query->when($this->estatus === 'activos', function ($q) {
            $q->whereIn('estatus', ['activo', 'atrasado']);
        });

        $query->when($this->estatus === 'liquidados', function ($q) {
            $q->where('estatus', 'liquidado');
        });

        $query->when($this->estatus === 'atrasados', function ($q) {
            $q->whereIn('estatus', ['activo', 'atrasado'])
                ->whereHas('cuotas', function ($cuota) {
                    $cuota->whereIn('estatus', CuotaEstatus::conSaldo())
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
                $q->whereIn('estatus', ['activo', 'atrasado']);
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
            ->whereIn('estatus', CuotaEstatus::conSaldo())
            ->whereDate('fecha_vencimiento', '<', $today)
            ->sum(DB::raw('COALESCE(saldo, monto)'));

        $totalPorVencer = (clone $this->cuotasBase())
            ->whereIn('estatus', CuotaEstatus::pendientesDePago())
            ->whereBetween('fecha_vencimiento', [$today, $today->copy()->addDays(7)])
            ->sum(DB::raw('COALESCE(saldo, monto)'));

        $cobradoMes = (clone $this->pagosBase())
            ->whereBetween('fecha_pago', [$startMonth, $endMonth])
            ->sum('monto');

        $contratosActivos = ContratoFinanciamiento::query()
            ->whereIn('estatus', ['activo', 'atrasado'])
            ->count();

        $contratosConAtraso = ContratoFinanciamiento::query()
            ->whereIn('estatus', ['activo', 'atrasado'])
            ->whereHas('cuotas', function ($q) use ($today) {
                $q->whereIn('estatus', CuotaEstatus::conSaldo())
                    ->where('estatus', '!=', 'cancelada')
                    ->whereDate('fecha_vencimiento', '<', $today);
            })
            ->count();

        $cuotasVencidas = (clone $this->cuotasBase())
            ->whereIn('estatus', CuotaEstatus::conSaldo())
            ->whereDate('fecha_vencimiento', '<', $today)
            ->count();

        $pctMorosidad = $contratosActivos > 0
            ? round($contratosConAtraso / $contratosActivos * 100, 1)
            : 0;

        $diasPromedioAtraso = $this->diasPromedioAtraso($today);

        $cuotasCriticasCount = (clone $this->cuotasBase())
            ->whereIn('estatus', CuotaEstatus::conSaldo())
            ->whereDate('fecha_vencimiento', '<', $today->copy()->subDays(30))
            ->count();

        $montoCritico = (clone $this->cuotasBase())
            ->whereIn('estatus', CuotaEstatus::conSaldo())
            ->whereDate('fecha_vencimiento', '<', $today->copy()->subDays(30))
            ->sum(DB::raw('COALESCE(saldo, monto)'));

        return [
            'total_vencido' => $totalVencido,
            'total_por_vencer' => $totalPorVencer,
            'cobrado_mes' => $cobradoMes,
            'contratos_activos' => $contratosActivos,
            'contratos_con_atraso' => $contratosConAtraso,
            'cuotas_vencidas' => $cuotasVencidas,
            'pct_morosidad' => $pctMorosidad,
            'dias_promedio_atraso' => $diasPromedioAtraso,
            'cuotas_criticas_count' => $cuotasCriticasCount,
            'monto_critico' => $montoCritico,
        ];
    }

    public function getProximosVencimientosProperty()
    {
        return (clone $this->cuotasBase())
            ->with(['contrato.cliente', 'contrato.auto.marca', 'contrato.auto.modelo'])
            ->whereIn('estatus', CuotaEstatus::pendientesDePago())
            ->whereBetween('fecha_vencimiento', [today(), today()->copy()->addDays(7)])
            ->orderBy('fecha_vencimiento')
            ->limit(8)
            ->get();
    }

    public function getCuotasVencidasProperty()
    {
        return (clone $this->cuotasBase())
            ->with(['contrato.cliente', 'contrato.auto.marca', 'contrato.auto.modelo'])
            ->whereIn('estatus', CuotaEstatus::conSaldo())
            ->whereDate('fecha_vencimiento', '<', today())
            ->orderBy('fecha_vencimiento')
            ->limit(self::CUOTAS_VENCIDAS_LIMIT)
            ->get();
    }

    public function getAlertasCriticasProperty()
    {
        return ContratoFinanciamiento::query()
            ->with(['cliente', 'auto.marca', 'auto.modelo'])
            ->whereIn('estatus', ['activo', 'atrasado'])
            ->whereHas('cuotas', function ($q) {
                $q->whereIn('estatus', CuotaEstatus::conSaldo())
                    ->where('estatus', '!=', 'cancelada')
                    ->whereDate('fecha_vencimiento', '<', today()->subDays(30));
            })
            ->withSum([
                'cuotas as monto_critico' => function ($q) {
                    $q->whereIn('estatus', CuotaEstatus::conSaldo())
                        ->where('estatus', '!=', 'cancelada')
                        ->whereDate('fecha_vencimiento', '<', today()->subDays(30));
                },
            ], DB::raw('COALESCE(saldo, monto)'))
            ->withMin([
                'cuotas as fecha_mas_antigua' => function ($q) {
                    $q->whereIn('estatus', CuotaEstatus::conSaldo())
                        ->where('estatus', '!=', 'cancelada')
                        ->whereDate('fecha_vencimiento', '<', today()->subDays(30));
                },
            ], 'fecha_vencimiento')
            ->orderByDesc('monto_critico')
            ->limit(5)
            ->get();
    }

    public function getContratosTopAtrasoProperty()
    {
        return ContratoFinanciamiento::query()
            ->with(['cliente', 'auto.marca', 'auto.modelo'])
            ->whereIn('estatus', ['activo', 'atrasado'])
            ->whereHas('cuotas', function ($q) {
                $q->whereIn('estatus', CuotaEstatus::conSaldo())
                    ->where('estatus', '!=', 'cancelada')
                    ->whereDate('fecha_vencimiento', '<', today());
            })
            ->withCount([
                'cuotas as cuotas_atrasadas_count' => function ($q) {
                    $q->whereIn('estatus', CuotaEstatus::conSaldo())
                        ->where('estatus', '!=', 'cancelada')
                        ->whereDate('fecha_vencimiento', '<', today());
                },
            ])
            ->withSum([
                'cuotas as total_atrasado' => function ($q) {
                    $q->whereIn('estatus', CuotaEstatus::conSaldo())
                        ->where('estatus', '!=', 'cancelada')
                        ->whereDate('fecha_vencimiento', '<', today());
                },
            ], DB::raw('COALESCE(saldo, monto)'))
            ->orderByDesc('total_atrasado')
            ->limit(8)
            ->get();
    }

    public function getCobranzaPorDiaProperty(): array
    {
        [$desde, $hasta] = $this->validatedDateRange();

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

    public function abrirModalIndividual(int $cuotaId): void
    {
        abort_unless(auth()->user()?->can('notificaciones.enviar'), 403);

        $this->cuotasParaEnviar = [(string) $cuotaId];
        $this->mostrarModal = true;
    }

    public function abrirModalLote(): void
    {
        abort_unless(auth()->user()?->can('notificaciones.enviar'), 403);

        if (empty($this->seleccionados)) {
            $this->dispatch('toast', type: 'error', message: 'Selecciona al menos una cuota.');

            return;
        }

        $this->cuotasParaEnviar = $this->seleccionados;
        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
        $this->cuotasParaEnviar = [];
    }

    public function seleccionarAtrasadas(): void
    {
        abort_unless(auth()->user()?->can('notificaciones.enviar'), 403);

        $this->seleccionados = (clone $this->cuotasBase())
            ->whereIn('estatus', CuotaEstatus::conSaldo())
            ->whereDate('fecha_vencimiento', '<', today())
            ->where(function ($q) {
                $q->whereNull('notificado_correo_at')
                    ->orWhereDate('notificado_correo_at', '<', today());
            })
            ->orderBy('fecha_vencimiento')
            ->limit(self::CUOTAS_VENCIDAS_LIMIT)
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->toArray();
    }

    public function limpiarSeleccion(): void
    {
        $this->seleccionados = [];
    }

    public function confirmarEnvio(): void
    {
        abort_unless(auth()->user()?->can('notificaciones.enviar'), 403);

        if (empty($this->cuotasParaEnviar)) {
            $this->cerrarModal();

            return;
        }

        $asunto = Configuracion::obtener('notif.correo_asunto', 'Recordatorio de pago — Cuota #{numero_cuota} / Contrato {folio}');
        $cuerpo = Configuracion::obtener('notif.correo_cuerpo', "Estimado/a {nombre},\n\nLa cuota #{numero_cuota} de su contrato {folio} venció el {fecha_vencimiento} ({dias_atraso} días de atraso). Monto pendiente: \${monto_pendiente}.\n\nPor favor comuníquese con nosotros.");

        $cuotas = CuotaFinanciamiento::with(['contrato.cliente'])
            ->whereIn('id', $this->cuotasParaEnviar)
            ->get();

        $enviados = 0;
        $sinCorreo = 0;
        $yaNotificadosHoy = 0;

        foreach ($cuotas as $cuota) {
            $cliente = $cuota->contrato?->cliente;

            if (! $cliente?->correo) {
                $sinCorreo++;

                continue;
            }

            if ($cuota->notificado_correo_at?->isToday()) {
                $yaNotificadosHoy++;

                continue;
            }

            $diasAtraso = (int) now()->diffInDays(Carbon::parse($cuota->fecha_vencimiento));
            $montoPendiente = (float) ($cuota->saldo ?: $cuota->monto);

            $vars = [
                '{nombre}' => $cliente->nombre_completo,
                '{folio}' => $cuota->contrato->folio,
                '{numero_cuota}' => $cuota->numero,
                '{fecha_vencimiento}' => Carbon::parse($cuota->fecha_vencimiento)->format('d/m/Y'),
                '{dias_atraso}' => $diasAtraso,
                '{monto_pendiente}' => number_format($montoPendiente, 2),
                '{monto_cuota}' => number_format((float) $cuota->monto, 2),
            ];

            EnviarNotificacionCuotaJob::dispatch(
                cuotaId: $cuota->id,
                tipo: 'manual',
                asunto: str_replace(array_keys($vars), array_values($vars), $asunto),
                cuerpo: str_replace(array_keys($vars), array_values($vars), $cuerpo),
                fechaOperacion: now()->toDateString(),
            );

            $enviados++;
        }

        // Limpiar selección solo si fue envío masivo
        if (count($this->cuotasParaEnviar) > 1 || count($this->seleccionados) > 0) {
            $this->seleccionados = [];
        }

        $this->cerrarModal();

        $parts = [];
        if ($enviados > 0) {
            $parts[] = "En cola: {$enviados}";
        }
        if ($yaNotificadosHoy > 0) {
            $parts[] = "Ya notificados hoy: {$yaNotificadosHoy}";
        }
        if ($sinCorreo > 0) {
            $parts[] = "Sin correo: {$sinCorreo}";
        }

        $type = $enviados > 0 ? 'success' : ($yaNotificadosHoy > 0 ? 'warning' : 'error');
        $this->dispatch('toast', type: $type, message: implode(' · ', $parts));
    }

    protected function modalDestinatarios(): array
    {
        if (empty($this->cuotasParaEnviar)) {
            return [];
        }

        return CuotaFinanciamiento::with(['contrato.cliente'])
            ->whereIn('id', $this->cuotasParaEnviar)
            ->orderBy('fecha_vencimiento')
            ->get()
            ->map(fn ($c) => [
                'nombre' => $c->contrato?->cliente?->nombre_completo ?? '—',
                'correo' => $c->contrato?->cliente?->correo ?: null,
                'cuota' => $c->numero,
                'folio' => $c->contrato?->folio ?? '—',
                'monto' => number_format((float) ($c->saldo ?: $c->monto), 2),
                'dias' => (int) now()->diffInDays(Carbon::parse($c->fecha_vencimiento)),
                'notificado_hoy' => $c->notificado_correo_at?->isToday() ?? false,
            ])
            ->toArray();
    }

    public function render()
    {
        $perPage = in_array($this->perPage, [10, 25, 50], true) ? $this->perPage : 10;
        $contratos = $this->contratosQuery()->paginate($perPage);

        return view('livewire.admin.cobranza-autos.dashboard', [
            'contratos' => $contratos,
            'kpis' => $this->kpis,
            'proximosVencimientos' => $this->proximosVencimientos,
            'cuotasVencidas' => $this->cuotasVencidas,
            'contratosTopAtraso' => $this->contratosTopAtraso,
            'alertasCriticas' => $this->alertasCriticas,
            'cobranzaPorDia' => $this->cobranzaPorDia,
            'waMensajePlantilla' => Configuracion::obtener('notif.wa_mensaje', 'Hola {nombre}, tiene pagos vencidos por ${monto_atrasado} en su contrato {folio}. Por favor comuníquese con nosotros.'),
            'modalDestinatarios' => $this->mostrarModal ? $this->modalDestinatarios() : [],
        ])->layout('layouts.app');
    }

    private function diasPromedioAtraso(Carbon $today): int
    {
        $driver = DB::connection()->getDriverName();
        $expression = match ($driver) {
            'sqlite' => 'AVG(julianday(?) - julianday(fecha_vencimiento))',
            'pgsql' => 'AVG((?::date - fecha_vencimiento::date))',
            'sqlsrv' => 'AVG(CAST(DATEDIFF(day, fecha_vencimiento, ?) AS FLOAT))',
            default => 'AVG(DATEDIFF(?, fecha_vencimiento))',
        };

        $average = (clone $this->cuotasBase())
            ->whereIn('estatus', CuotaEstatus::conSaldo())
            ->whereDate('fecha_vencimiento', '<', $today)
            ->selectRaw("{$expression} as dias_promedio", [$today->toDateString()])
            ->value('dias_promedio');

        return $average !== null ? (int) round((float) $average) : 0;
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function validatedDateRange(): array
    {
        $dates = Validator::validate(
            [
                'fecha_desde' => $this->fechaDesde,
                'fecha_hasta' => $this->fechaHasta,
            ],
            [
                'fecha_desde' => ['required', 'date_format:Y-m-d', 'before_or_equal:fecha_hasta'],
                'fecha_hasta' => ['required', 'date_format:Y-m-d', 'after_or_equal:fecha_desde'],
            ],
        );

        return [
            Carbon::createFromFormat('Y-m-d', $dates['fecha_desde'])->startOfDay(),
            Carbon::createFromFormat('Y-m-d', $dates['fecha_hasta'])->endOfDay(),
        ];
    }
}
