<?php

namespace App\Livewire\Admin\CobranzaAutos;

use App\Mail\RecordatorioPagoMail;
use App\Models\Configuracion;
use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use App\Models\PagoFinanciamiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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

    public array $seleccionados     = [];
    public array $cuotasParaEnviar = [];
    public bool  $mostrarModal     = false;

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
            ->limit(50)
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

    public function abrirModalIndividual(int $cuotaId): void
    {
        $this->cuotasParaEnviar = [(string) $cuotaId];
        $this->mostrarModal     = true;
    }

    public function abrirModalLote(): void
    {
        if (empty($this->seleccionados)) {
            $this->dispatch('toast', type: 'error', message: 'Selecciona al menos una cuota.');
            return;
        }

        $this->cuotasParaEnviar = $this->seleccionados;
        $this->mostrarModal     = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal     = false;
        $this->cuotasParaEnviar = [];
    }

    public function seleccionarAtrasadas(): void
    {
        $this->seleccionados = (clone $this->cuotasBase())
            ->whereIn('estatus', ['pendiente', 'parcial', 'vencida'])
            ->whereDate('fecha_vencimiento', '<', today())
            ->where(function ($q) {
                $q->whereNull('notificado_correo_at')
                  ->orWhereDate('notificado_correo_at', '<', today());
            })
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
        if (empty($this->cuotasParaEnviar)) {
            $this->cerrarModal();
            return;
        }

        $asunto = Configuracion::obtener('notif.correo_asunto', 'Recordatorio de pago — Cuota #{numero_cuota} / Contrato {folio}');
        $cuerpo = Configuracion::obtener('notif.correo_cuerpo', "Estimado/a {nombre},\n\nLa cuota #{numero_cuota} de su contrato {folio} venció el {fecha_vencimiento} ({dias_atraso} días de atraso). Monto pendiente: \${monto_pendiente}.\n\nPor favor comuníquese con nosotros.");

        $cuotas = CuotaFinanciamiento::with(['contrato.cliente'])
            ->whereIn('id', $this->cuotasParaEnviar)
            ->get();

        $enviados         = 0;
        $sinCorreo        = 0;
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

            $diasAtraso     = (int) now()->diffInDays(Carbon::parse($cuota->fecha_vencimiento));
            $montoPendiente = (float) ($cuota->saldo ?: $cuota->monto);

            $vars = [
                '{nombre}'            => $cliente->nombre_completo,
                '{folio}'             => $cuota->contrato->folio,
                '{numero_cuota}'      => $cuota->numero,
                '{fecha_vencimiento}' => Carbon::parse($cuota->fecha_vencimiento)->format('d/m/Y'),
                '{dias_atraso}'       => $diasAtraso,
                '{monto_pendiente}'   => number_format($montoPendiente, 2),
                '{monto_cuota}'       => number_format((float) $cuota->monto, 2),
            ];

            Mail::to($cliente->correo)->send(
                new RecordatorioPagoMail(
                    str_replace(array_keys($vars), array_values($vars), $asunto),
                    str_replace(array_keys($vars), array_values($vars), $cuerpo),
                )
            );

            $cuota->update(['notificado_correo_at' => now()]);

            $enviados++;
        }

        // Limpiar selección solo si fue envío masivo
        if (count($this->cuotasParaEnviar) > 1 || count($this->seleccionados) > 0) {
            $this->seleccionados = [];
        }

        $this->cerrarModal();

        $parts = [];
        if ($enviados > 0)         { $parts[] = "Enviados: {$enviados}"; }
        if ($yaNotificadosHoy > 0) { $parts[] = "Ya notificados hoy: {$yaNotificadosHoy}"; }
        if ($sinCorreo > 0)        { $parts[] = "Sin correo: {$sinCorreo}"; }

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
                'nombre'          => $c->contrato?->cliente?->nombre_completo ?? '—',
                'correo'          => $c->contrato?->cliente?->correo ?: null,
                'cuota'           => $c->numero,
                'folio'           => $c->contrato?->folio ?? '—',
                'monto'           => number_format((float) ($c->saldo ?: $c->monto), 2),
                'dias'            => (int) now()->diffInDays(Carbon::parse($c->fecha_vencimiento)),
                'notificado_hoy'  => $c->notificado_correo_at?->isToday() ?? false,
            ])
            ->toArray();
    }

    public function render()
    {
        $contratos = $this->contratosQuery()->paginate($this->perPage);

        return view('livewire.admin.cobranza-autos.dashboard', [
            'contratos'            => $contratos,
            'kpis'                 => $this->kpis,
            'proximosVencimientos' => $this->proximosVencimientos,
            'cuotasVencidas'       => $this->cuotasVencidas,
            'contratosTopAtraso'   => $this->contratosTopAtraso,
            'cobranzaPorDia'       => $this->cobranzaPorDia,
            'waMensajePlantilla'   => Configuracion::obtener('notif.wa_mensaje', 'Hola {nombre}, tiene pagos vencidos por ${monto_atrasado} en su contrato {folio}. Por favor comuníquese con nosotros.'),
            'modalDestinatarios'   => $this->mostrarModal ? $this->modalDestinatarios() : [],
        ])->layout('layouts.app');
    }
}