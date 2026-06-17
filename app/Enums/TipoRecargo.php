<?php

namespace App\Enums;

enum TipoRecargo: string
{
    case Fijo = 'fijo';
    case Porcentaje = 'porcentaje';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(fn (self $caso) => $caso->value, self::cases());
    }
}
