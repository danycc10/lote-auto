<?php

namespace App\Enums;

use App\Exports\ReporteApartadosExport;
use App\Exports\ReporteContratosExport;
use App\Exports\ReporteCuotasVencidasExport;
use App\Exports\ReporteInventarioExport;
use App\Exports\ReportePagosExport;

enum TipoReporte: string
{
    case Pagos = 'pagos';
    case Contratos = 'contratos';
    case Cuotas = 'cuotas';
    case Inventario = 'inventario';
    case Apartados = 'apartados';

    /**
     * @return class-string
     */
    public function exportClass(): string
    {
        return match ($this) {
            self::Pagos => ReportePagosExport::class,
            self::Contratos => ReporteContratosExport::class,
            self::Cuotas => ReporteCuotasVencidasExport::class,
            self::Inventario => ReporteInventarioExport::class,
            self::Apartados => ReporteApartadosExport::class,
        };
    }

    public function filePrefix(): string
    {
        return match ($this) {
            self::Pagos => 'reporte-pagos',
            self::Contratos => 'reporte-contratos',
            self::Cuotas => 'reporte-cuotas-vencidas',
            self::Inventario => 'reporte-inventario',
            self::Apartados => 'reporte-apartados',
        };
    }

    public function usesDates(): bool
    {
        return $this !== self::Inventario;
    }
}
