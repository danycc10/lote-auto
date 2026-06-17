<?php

namespace App\Enums;

enum ApartadoEstatus: string
{
    case Activo = 'activo';
    case Convertido = 'convertido';
    case Vencido = 'vencido';
    case Cancelado = 'cancelado';

    /**
     * Valores planos, útil para reglas de validación Rule::in().
     *
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(fn (self $caso) => $caso->value, self::cases());
    }
}
