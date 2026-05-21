<?php

namespace App\Exports;

use App\Models\Auto;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteInventarioExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function __construct(
        private readonly ?string $desde,
        private readonly ?string $hasta,
    ) {}

    public function query()
    {
        return Auto::query()
            ->with(['marca', 'modelo'])
            ->where('activo', true)
            ->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Código Inventario',
            'VIN',
            'Placa',
            'Marca',
            'Modelo',
            'Año',
            'Color',
            'Km',
            'Transmisión',
            'Combustible',
            'Precio Contado',
            'Precio Financiado',
            'Estatus',
        ];
    }

    public function map($auto): array
    {
        return [
            $auto->codigo_inventario ?? '',
            $auto->vin ?? '',
            $auto->placa ?? '',
            $auto->marca?->nombre ?? '',
            $auto->modelo?->nombre ?? '',
            $auto->anio ?? '',
            $auto->color ?? '',
            $auto->kilometraje ?? 0,
            ucfirst($auto->transmision ?? ''),
            ucfirst($auto->tipo_combustible ?? ''),
            $auto->precio_contado,
            $auto->precio_financiado,
            ucfirst($auto->estatus ?? ''),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
