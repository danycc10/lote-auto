<?php

namespace App\Enums;

enum ReciboEstatus: string
{
    case Vigente = 'vigente';
    case Cancelado = 'cancelado';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(fn (self $caso) => $caso->value, self::cases());
    }
}
