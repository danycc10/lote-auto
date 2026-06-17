<?php

namespace App\Enums;

enum AutoEstatus: string
{
    case Disponible = 'disponible';
    case Apartado = 'apartado';
    case VendidoContado = 'vendido_contado';
    case Financiado = 'financiado';
    case Liquidado = 'liquidado';
    case Recuperado = 'recuperado';
    case Inactivo = 'inactivo';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(fn (self $caso) => $caso->value, self::cases());
    }
}
