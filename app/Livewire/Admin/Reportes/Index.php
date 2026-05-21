<?php

namespace App\Livewire\Admin\Reportes;

use Livewire\Component;

class Index extends Component
{
    public string $tipo  = 'pagos';
    public string $desde = '';
    public string $hasta = '';

    public function mount(): void
    {
        $this->desde = now()->startOfMonth()->format('Y-m-d');
        $this->hasta = now()->format('Y-m-d');
    }

    public function reportes(): array
    {
        return [
            'pagos' => [
                'titulo'      => 'Pagos del período',
                'descripcion' => 'Todos los pagos aplicados con forma de pago, referencia, cliente y contrato.',
                'columnas'    => 'Fecha, Contrato, Cliente, Auto, Monto, Forma de pago, Referencia, Estatus, Cuota #',
                'usa_fechas'  => true,
                'icono'       => 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z',
            ],
            'contratos' => [
                'titulo'      => 'Contratos',
                'descripcion' => 'Cartera completa de contratos con saldos, tasas, plazos y estatus.',
                'columnas'    => 'Folio, Fecha, Cliente, Auto, Precio, Enganche, Financiado, Tasa, Plazo, Cuota, Total, Pagado, Saldo, Estatus',
                'usa_fechas'  => true,
                'icono'       => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
            ],
            'cuotas' => [
                'titulo'      => 'Cuotas vencidas',
                'descripcion' => 'Cuotas sin pagar con días de atraso acumulados por contrato y cliente.',
                'columnas'    => 'Contrato, Cliente, Auto, Cuota #, Vencimiento, Días atraso, Monto, Pagado, Saldo',
                'usa_fechas'  => true,
                'icono'       => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
            'inventario' => [
                'titulo'      => 'Inventario de autos',
                'descripcion' => 'Catálogo completo de vehículos activos con precios y características.',
                'columnas'    => 'Código, VIN, Placa, Marca, Modelo, Año, Color, Km, Transmisión, Combustible, Precio, Estatus',
                'usa_fechas'  => false,
                'icono'       => 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12',
            ],
            'apartados' => [
                'titulo'      => 'Apartados',
                'descripcion' => 'Reservas de autos con montos de anticipo, vencimientos y estatus.',
                'columnas'    => 'Folio, Fecha apartado, Vencimiento, Cliente, Teléfono, Auto, Anticipo, Saldo, Estatus',
                'usa_fechas'  => true,
                'icono'       => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
        ];
    }

    public function descargar(): void
    {
        $url = route('admin.reportes.export', [
            'tipo'  => $this->tipo,
            'desde' => $this->desde,
            'hasta' => $this->hasta,
        ]);

        $this->js("window.open('{$url}', '_blank')");
    }

    public function render()
    {
        return view('livewire.admin.reportes.index', [
            'reportes' => $this->reportes(),
        ])->layout('layouts.app');
    }
}
