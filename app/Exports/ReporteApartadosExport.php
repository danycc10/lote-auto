<?php

namespace App\Exports;

use App\Models\ApartadoAuto;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteApartadosExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function __construct(
        private readonly ?string $desde,
        private readonly ?string $hasta,
    ) {}

    public function query()
    {
        return ApartadoAuto::query()
            ->with(['cliente', 'auto'])
            ->when($this->desde, fn ($q) => $q->whereDate('fecha_apartado', '>=', $this->desde))
            ->when($this->hasta, fn ($q) => $q->whereDate('fecha_apartado', '<=', $this->hasta))
            ->orderBy('fecha_apartado', 'desc');
    }

    public function headings(): array
    {
        return [
            'Folio',
            'Fecha Apartado',
            'Fecha Vencimiento',
            'Cliente',
            'Teléfono',
            'Auto',
            'Monto Anticipo',
            'Saldo Pendiente',
            'Estatus',
        ];
    }

    public function map($apartado): array
    {
        $nombreCliente = $apartado->cliente?->nombre_completo
            ?? trim((string) ($apartado->nombre_cliente_temporal ?? 'Sin cliente'));

        $telefono = $apartado->cliente?->telefono
            ?? '';

        return [
            $apartado->folio ?? '',
            $apartado->fecha_apartado?->format('d/m/Y') ?? '',
            $apartado->fecha_vencimiento?->format('d/m/Y') ?? '',
            $nombreCliente,
            $telefono,
            $apartado->auto?->nombre_completo ?? '',
            $apartado->monto_anticipo,
            $apartado->saldo_pendiente,
            ucfirst($apartado->estatus ?? ''),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
