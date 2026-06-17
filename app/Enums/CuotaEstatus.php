<?php

namespace App\Enums;

enum CuotaEstatus: string
{
    case Pendiente = 'pendiente';
    case Parcial = 'parcial';
    case Pagada = 'pagada';
    case Vencida = 'vencida';
    case Cancelada = 'cancelada';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(fn (self $caso) => $caso->value, self::cases());
    }
}
