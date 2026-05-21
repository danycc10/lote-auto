<?php

namespace App\Exports;

use App\Models\ContratoFinanciamiento;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteContratosExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function __construct(
        private readonly ?string $desde,
        private readonly ?string $hasta,
    ) {}

    public function query()
    {
        return ContratoFinanciamiento::query()
            ->with(['cliente', 'auto.marca', 'auto.modelo'])
            ->when($this->desde, fn ($q) => $q->whereDate('fecha_contrato', '>=', $this->desde))
            ->when($this->hasta, fn ($q) => $q->whereDate('fecha_contrato', '<=', $this->hasta))
            ->orderBy('fecha_contrato');
    }

    public function headings(): array
    {
        return [
            'Folio',
            'Fecha Contrato',
            'Cliente',
            'Auto',
            'Precio Venta',
            'Enganche',
            'Financiado',
            'Tasa %',
            'Plazo (meses)',
            'Cuota Mensual',
            'Total a Pagar',
            'Total Pagado',
            'Saldo Actual',
            'Estatus',
        ];
    }

    public function map($contrato): array
    {
        return [
            $contrato->folio,
            $contrato->fecha_contrato?->format('d/m/Y') ?? '',
            $contrato->cliente?->nombre_completo ?? '',
            $contrato->auto?->nombre_completo ?? '',
            $contrato->precio_venta,
            $contrato->enganche,
            $contrato->monto_financiado,
            $contrato->tasa_interes,
            $contrato->plazo,
            $contrato->monto_cuota,
            $contrato->total_pagar,
            $contrato->total_pagado,
            $contrato->saldo_actual,
            ucfirst($contrato->estatus),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
