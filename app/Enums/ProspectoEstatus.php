<?php

namespace App\Enums;

enum ProspectoEstatus: string
{
    case Nuevo = 'nuevo';
    case Contactado = 'contactado';
    case Interesado = 'interesado';
    case Negociacion = 'negociacion';
    case Ganado = 'ganado';
    case Perdido = 'perdido';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(fn (self $caso) => $caso->value, self::cases());
    }
}
