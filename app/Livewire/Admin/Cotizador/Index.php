<?php

namespace App\Livewire\Admin\Cotizador;

use App\Enums\AutoEstatus;
use App\Mail\CotizacionMail;
use App\Models\Auto;
use App\Models\Configuracion;
use App\Services\Financiamiento\CalculadoraFinanciamientoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    private CalculadoraFinanciamientoService $calculadora;

    public string $busqueda = '';

    public ?int $autoId = null;

    public float $enganche = 0;

    public int $plazo = 12;

    public float $tasaAnual = 0;

    public string $nombreCliente = '';

    public string $telefonoCliente = '';

    public string $correoCliente = '';

    public bool $mostrarModalCorreo = false;

    public string $correoEnvio = '';

    public function boot(CalculadoraFinanciamientoService $calculadora): void
    {
        $this->calculadora = $calculadora;
    }

    #[Computed]
    public function resultadosBusqueda(): array
    {
        if (strlen(trim($this->busqueda)) < 2) {
            return [];
        }

        $term = '%'.trim($this->busqueda).'%';

        return Auto::query()
            ->with(['marca', 'modelo', 'imagenPortada'])
            ->where('activo', true)
            ->where('estatus', AutoEstatus::Disponible->value)
            ->where(function ($q) use ($term) {
                $q->whereHas('marca', fn ($m) => $m->where('nombre', 'like', $term))
                    ->orWhereHas('modelo', fn ($m) => $m->where('nombre', 'like', $term))
                    ->orWhere('placa', 'like', $term)
                    ->orWhere('vin', 'like', $term)
                    ->orWhere('anio', 'like', $term);
            })
            ->limit(8)
            ->get()
            ->map(fn ($a) => [
                'id' => $a->id,
                'label' => trim(($a->marca?->nombre ?? '').' '.($a->modelo?->nombre ?? '').' '.$a->anio),
                'precio' => (float) $a->precio_financiado,
                'placas' => $a->placa ?? $a->vin ?? '—',
                'estatus' => $a->estatus,
                'imagen' => $a->imagenPortada?->url,
            ])
            ->toArray();
    }

    #[Computed]
    public function auto(): ?Auto
    {
        if (! $this->autoId) {
            return null;
        }

        return Auto::with(['marca', 'modelo', 'imagenPortada'])->find($this->autoId);
    }

    #[Computed]
    public function precioVenta(): float
    {
        return (float) ($this->auto()?->precio_financiado ?? 0);
    }

    #[Computed]
    public function montoFinanciado(): float
    {
        return max(0, $this->precioVenta() - $this->enganche);
    }

    #[Computed]
    public function cuotaMensual(): float
    {
        return $this->calculoFinanciero()['monto_cuota'];
    }

    #[Computed]
    public function totalPagar(): float
    {
        return $this->calculoFinanciero()['total_pagar'];
    }

    #[Computed]
    public function totalIntereses(): float
    {
        return $this->calculoFinanciero()['total_intereses'];
    }

    /**
     * @return array{
     *     monto_financiado: float,
     *     monto_cuota: float,
     *     total_pagar: float,
     *     total_intereses: float,
     *     cuotas: list<array{numero: int, capital: float, interes: float, monto: float, saldo: float}>
     * }
     */
    #[Computed]
    public function calculoFinanciero(): array
    {
        $montoFinanciado = $this->montoFinanciado();

        if (
            $montoFinanciado <= 0
            || $this->plazo < 1
            || $this->plazo > 120
            || $this->tasaAnual < 0
            || $this->tasaAnual > 100
        ) {
            return [
                'monto_financiado' => 0.0,
                'monto_cuota' => 0.0,
                'total_pagar' => 0.0,
                'total_intereses' => 0.0,
                'cuotas' => [],
            ];
        }

        return $this->calculadora->calcular(
            montoFinanciado: $montoFinanciado,
            tasaAnual: $this->tasaAnual,
            plazo: $this->plazo,
        );
    }

    #[Computed]
    public function tablaAmortizacion(): array
    {
        $calculo = $this->calculoFinanciero();

        if ($calculo['cuotas'] === []) {
            return [];
        }

        $fecha = now()->addMonth()->startOfMonth();

        return array_map(function (array $cuota) use ($fecha): array {
            return [
                'numero' => $cuota['numero'],
                'fecha' => $fecha->copy()->addMonthsNoOverflow($cuota['numero'] - 1)->format('d/m/Y'),
                'capital' => $cuota['capital'],
                'interes' => $cuota['interes'],
                'cuota' => $cuota['monto'],
                'saldo' => $cuota['saldo'],
            ];
        }, $calculo['cuotas']);
    }

    public function seleccionarAuto(int $id): void
    {
        $auto = Auto::find($id);
        if (! $auto) {
            return;
        }

        $this->autoId = $id;
        $this->enganche = round((float) $auto->precio_financiado * 0.20, 2);
        $this->busqueda = '';
        unset($this->resultadosBusqueda);
    }

    public function limpiar(): void
    {
        $this->autoId = null;
        $this->busqueda = '';
        $this->enganche = 0;
        $this->plazo = 12;
        $this->tasaAnual = 0;
        $this->nombreCliente = '';
        $this->telefonoCliente = '';
        $this->correoCliente = '';
    }

    public function abrirModalCorreo(): void
    {
        $this->correoEnvio = $this->correoCliente;
        $this->mostrarModalCorreo = true;
    }

    public function enviarPorCorreo(): void
    {
        $this->validate(['correoEnvio' => 'required|email']);

        if (! $this->autoId) {
            return;
        }

        $datos = $this->datosCotizacion();

        $pdf = Pdf::loadView('pdf.cotizador', $datos)->setPaper('letter', 'portrait');
        $pdfContent = $pdf->output();

        Mail::to($this->correoEnvio)->send(new CotizacionMail($datos, $pdfContent));

        $this->mostrarModalCorreo = false;
        $this->correoEnvio = '';

        $this->dispatch('toast', type: 'success', message: 'Cotización enviada al correo.');
    }

    public function datosCotizacion(): array
    {
        return [
            'auto' => $this->auto(),
            'nombreCliente' => $this->nombreCliente,
            'telefonoCliente' => $this->telefonoCliente,
            'enganche' => $this->enganche,
            'plazo' => $this->plazo,
            'tasaAnual' => $this->tasaAnual,
            'montoFinanciado' => $this->montoFinanciado(),
            'cuotaMensual' => $this->cuotaMensual(),
            'totalPagar' => $this->totalPagar(),
            'totalIntereses' => $this->totalIntereses(),
            'tablaAmortizacion' => $this->tablaAmortizacion(),
            'empresa' => $this->datosEmpresa(),
            'fechaGeneracion' => now()->format('d/m/Y'),
            'validezDias' => 7,
        ];
    }

    private function datosEmpresa(): array
    {
        return [
            'nombre' => Configuracion::obtener('branding.seo_titulo', config('app.name')),
            'logo' => Configuracion::obtener('branding.logo_url'),
            'telefono' => Configuracion::obtener('contact.whatsapp'),
            'direccion' => Configuracion::obtener('branding.direccion'),
            'horario' => Configuracion::obtener('branding.horario'),
        ];
    }

    public function urlPdf(): string
    {
        return route('admin.cotizador.pdf', array_filter([
            'auto_id' => $this->autoId,
            'enganche' => $this->enganche,
            'plazo' => $this->plazo,
            'tasa' => $this->tasaAnual ?: null,
            'cliente' => $this->nombreCliente ?: null,
        ]));
    }

    public function urlWhatsapp(): ?string
    {
        if (! $this->telefonoCliente || ! $this->autoId) {
            return null;
        }

        $tel = preg_replace('/[^0-9]/', '', $this->telefonoCliente);
        if (strlen($tel) === 10) {
            $tel = '52'.$tel;
        }

        $auto = $this->auto();
        $nombre = $auto?->marca?->nombre.' '.$auto?->modelo?->nombre.' '.$auto?->anio;

        $msg = ($this->nombreCliente ? "Hola {$this->nombreCliente}, " : 'Hola, ')
             ."te comparto la cotización del {$nombre}.\n\n"
             .'• Precio: $'.number_format($this->precioVenta(), 2)."\n"
             .'• Enganche: $'.number_format($this->enganche, 2)."\n"
             ."• Plazo: {$this->plazo} meses\n"
             .'• Mensualidad: $'.number_format($this->cuotaMensual(), 2)."\n"
             .'• Total a pagar: $'.number_format($this->totalPagar(), 2);

        return 'https://wa.me/'.$tel.'?text='.urlencode($msg);
    }

    public function render()
    {
        return view('livewire.admin.cotizador.index')->layout('layouts.app');
    }
}
