<?php

namespace App\Enums;

enum ContratoEstatus: string
{
    case Borrador = 'borrador';
    case Activo = 'activo';
    case Atrasado = 'atrasado';
    case Liquidado = 'liquidado';
    case Cancelado = 'cancelado';
    case Reestructurado = 'reestructurado';
    case Recuperado = 'recuperado';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(fn (self $caso) => $caso->value, self::cases());
    }
}
