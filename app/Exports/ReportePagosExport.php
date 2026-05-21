<?php

namespace App\Exports;

use App\Models\PagoFinanciamiento;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportePagosExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function __construct(
        private readonly ?string $desde,
        private readonly ?string $hasta,
    ) {}

    public function query()
    {
        return PagoFinanciamiento::query()
            ->with(['contrato.cliente', 'contrato.auto.marca', 'contrato.auto.modelo', 'cuota'])
            ->when($this->desde, fn ($q) => $q->whereDate('fecha_pago', '>=', $this->desde))
            ->when($this->hasta, fn ($q) => $q->whereDate('fecha_pago', '<=', $this->hasta))
            ->orderBy('fecha_pago');
    }

    public function headings(): array
    {
        return [
            'Fecha Pago',
            'Folio Contrato',
            'Cliente',
            'Auto',
            'Monto',
            'Forma de Pago',
            'Referencia',
            'Estatus',
            'Cuota #',
        ];
    }

    public function map($pago): array
    {
        return [
            $pago->fecha_pago?->format('d/m/Y') ?? '',
            $pago->contrato?->folio ?? '',
            $pago->contrato?->cliente?->nombre_completo ?? '',
            $pago->contrato?->auto?->nombre_completo ?? '',
            $pago->monto_aplicado,
            ucfirst($pago->forma_pago ?? ''),
            $pago->referencia ?? '',
            ucfirst($pago->estatus ?? ''),
            $pago->cuota?->numero ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
