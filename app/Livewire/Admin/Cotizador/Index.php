<?php

namespace App\Livewire\Admin\Cotizador;

use App\Mail\CotizacionMail;
use App\Models\Auto;
use App\Models\Configuracion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public string $busqueda  = '';
    public ?int   $autoId    = null;
    public float  $enganche  = 0;
    public int    $plazo     = 12;
    public float  $tasaAnual = 0;

    public string $nombreCliente   = '';
    public string $telefonoCliente = '';
    public string $correoCliente   = '';

    public bool   $mostrarModalCorreo = false;
    public string $correoEnvio        = '';

    #[Computed]
    public function resultadosBusqueda(): array
    {
        if (strlen(trim($this->busqueda)) < 2) {
            return [];
        }

        $term = '%' . trim($this->busqueda) . '%';

        return Auto::query()
            ->with(['marca', 'modelo'])
            ->where('activo', true)
            ->where(function ($q) use ($term) {
                $q->whereHas('marca', fn ($m) => $m->where('nombre', 'like', $term))
                  ->orWhereHas('modelo', fn ($m) => $m->where('nombre', 'like', $term))
                  ->orWhere('placas', 'like', $term)
                  ->orWhere('vin', 'like', $term)
                  ->orWhere('anio', 'like', $term);
            })
            ->limit(8)
            ->get()
            ->map(fn ($a) => [
                'id'      => $a->id,
                'label'   => trim(($a->marca?->nombre ?? '') . ' ' . ($a->modelo?->nombre ?? '') . ' ' . $a->anio),
                'precio'  => (float) $a->precio_financiado,
                'placas'  => $a->placas ?? $a->vin ?? '—',
                'estatus' => $a->estatus,
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
        return (float) ($this->auto?->precio_financiado ?? 0);
    }

    #[Computed]
    public function montoFinanciado(): float
    {
        return max(0, $this->precioVenta - $this->enganche);
    }

    #[Computed]
    public function cuotaMensual(): float
    {
        if ($this->montoFinanciado <= 0 || $this->plazo <= 0) {
            return 0;
        }

        if ($this->tasaAnual <= 0) {
            return round($this->montoFinanciado / $this->plazo, 2);
        }

        $r = $this->tasaAnual / 100 / 12;

        return round($this->montoFinanciado * $r / (1 - pow(1 + $r, -$this->plazo)), 2);
    }

    #[Computed]
    public function totalPagar(): float
    {
        return round($this->cuotaMensual * $this->plazo, 2);
    }

    #[Computed]
    public function totalIntereses(): float
    {
        return max(0, round($this->totalPagar - $this->montoFinanciado, 2));
    }

    #[Computed]
    public function tablaAmortizacion(): array
    {
        if ($this->montoFinanciado <= 0 || $this->plazo <= 0) {
            return [];
        }

        $tabla  = [];
        $saldo  = $this->montoFinanciado;
        $cuota  = $this->cuotaMensual;
        $r      = $this->tasaAnual > 0 ? ($this->tasaAnual / 100 / 12) : 0;
        $fecha  = now()->addMonth()->startOfMonth();

        for ($i = 1; $i <= $this->plazo; $i++) {
            $interes = round($saldo * $r, 2);
            $capital = round($cuota - $interes, 2);
            $saldo   = round(max(0, $saldo - $capital), 2);

            if ($i === $this->plazo && $saldo > 0) {
                $capital += $saldo;
                $saldo    = 0;
            }

            $tabla[] = [
                'numero'  => $i,
                'fecha'   => $fecha->copy()->format('d/m/Y'),
                'capital' => $capital,
                'interes' => $interes,
                'cuota'   => round($capital + $interes, 2),
                'saldo'   => $saldo,
            ];

            $fecha->addMonth();
        }

        return $tabla;
    }

    public function seleccionarAuto(int $id): void
    {
        $auto = Auto::find($id);
        if (! $auto) {
            return;
        }

        $this->autoId   = $id;
        $this->enganche = round((float) $auto->precio_financiado * 0.20, 2);
        $this->busqueda = '';
        $this->unsetComputedProperties();
    }

    public function limpiar(): void
    {
        $this->autoId          = null;
        $this->busqueda        = '';
        $this->enganche        = 0;
        $this->plazo           = 12;
        $this->tasaAnual       = 0;
        $this->nombreCliente   = '';
        $this->telefonoCliente = '';
        $this->correoCliente   = '';
        $this->unsetComputedProperties();
    }

    public function abrirModalCorreo(): void
    {
        $this->correoEnvio        = $this->correoCliente;
        $this->mostrarModalCorreo = true;
    }

    public function enviarPorCorreo(): void
    {
        $this->validate(['correoEnvio' => 'required|email']);

        if (! $this->autoId) {
            return;
        }

        $datos = $this->datosCotizacion();

        $pdf        = Pdf::loadView('pdf.cotizador', $datos)->setPaper('letter', 'portrait');
        $pdfContent = $pdf->output();

        Mail::to($this->correoEnvio)->send(new CotizacionMail($datos, $pdfContent));

        $this->mostrarModalCorreo = false;
        $this->correoEnvio        = '';

        $this->dispatch('toast', type: 'success', message: 'Cotización enviada al correo.');
    }

    public function datosCotizacion(): array
    {
        return [
            'auto'             => $this->auto,
            'nombreCliente'    => $this->nombreCliente,
            'telefonoCliente'  => $this->telefonoCliente,
            'enganche'         => $this->enganche,
            'plazo'            => $this->plazo,
            'tasaAnual'        => $this->tasaAnual,
            'montoFinanciado'  => $this->montoFinanciado,
            'cuotaMensual'     => $this->cuotaMensual,
            'totalPagar'       => $this->totalPagar,
            'totalIntereses'   => $this->totalIntereses,
            'tablaAmortizacion'=> $this->tablaAmortizacion,
            'empresa'          => $this->datosEmpresa(),
            'fechaGeneracion'  => now()->format('d/m/Y'),
            'validezDias'      => 7,
        ];
    }

    private function datosEmpresa(): array
    {
        return [
            'nombre'    => Configuracion::obtener('branding.seo_titulo', config('app.name')),
            'logo'      => Configuracion::obtener('branding.logo_url'),
            'telefono'  => Configuracion::obtener('contact.whatsapp'),
            'direccion' => Configuracion::obtener('branding.direccion'),
            'horario'   => Configuracion::obtener('branding.horario'),
        ];
    }

    public function urlPdf(): string
    {
        return route('admin.cotizador.pdf', array_filter([
            'auto_id'  => $this->autoId,
            'enganche' => $this->enganche,
            'plazo'    => $this->plazo,
            'tasa'     => $this->tasaAnual ?: null,
            'cliente'  => $this->nombreCliente ?: null,
        ]));
    }

    public function urlWhatsapp(): ?string
    {
        if (! $this->telefonoCliente || ! $this->autoId) {
            return null;
        }

        $tel = preg_replace('/[^0-9]/', '', $this->telefonoCliente);
        if (strlen($tel) === 10) {
            $tel = '52' . $tel;
        }

        $auto   = $this->auto;
        $nombre = $auto?->marca?->nombre . ' ' . $auto?->modelo?->nombre . ' ' . $auto?->anio;

        $msg = ($this->nombreCliente ? "Hola {$this->nombreCliente}, " : "Hola, ")
             . "te comparto la cotización del {$nombre}.\n\n"
             . "• Precio: $" . number_format($this->precioVenta, 2) . "\n"
             . "• Enganche: $" . number_format($this->enganche, 2) . "\n"
             . "• Plazo: {$this->plazo} meses\n"
             . "• Mensualidad: $" . number_format($this->cuotaMensual, 2) . "\n"
             . "• Total a pagar: $" . number_format($this->totalPagar, 2);

        return 'https://wa.me/' . $tel . '?text=' . urlencode($msg);
    }

    public function render()
    {
        return view('livewire.admin.cotizador.index')->layout('layouts.app');
    }
}
