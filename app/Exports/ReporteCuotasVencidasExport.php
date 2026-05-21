<?php

namespace App\Exports;

use App\Models\CuotaFinanciamiento;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteCuotasVencidasExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function __construct(
        private readonly ?string $desde,
        private readonly ?string $hasta,
    ) {}

    public function query()
    {
        return CuotaFinanciamiento::query()
            ->with(['contrato.cliente', 'contrato.auto'])
            ->where('estatus', 'vencida')
            ->when($this->desde, fn ($q) => $q->whereDate('fecha_vencimiento', '>=', $this->desde))
            ->when($this->hasta, fn ($q) => $q->whereDate('fecha_vencimiento', '<=', $this->hasta))
            ->orderBy('fecha_vencimiento');
    }

    public function headings(): array
    {
        return [
            'Folio Contrato',
            'Cliente',
            'Auto',
            'Cuota #',
            'Fecha Vencimiento',
            'Días de Atraso',
            'Monto',
            'Pagado',
            'Saldo',
        ];
    }

    public function map($cuota): array
    {
        $diasAtraso = $cuota->fecha_vencimiento
            ? (int) now()->diffInDays($cuota->fecha_vencimiento, false) * -1
            : 0;

        return [
            $cuota->contrato?->folio ?? '',
            $cuota->contrato?->cliente?->nombre_completo ?? '',
            $cuota->contrato?->auto?->nombre_completo ?? '',
            $cuota->numero,
            $cuota->fecha_vencimiento?->format('d/m/Y') ?? '',
            max(0, $diasAtraso),
            $cuota->monto,
            $cuota->monto_pagado,
            $cuota->saldo,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
