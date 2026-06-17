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

    /**
     * Cuotas con saldo por cobrar (pendiente, parcial o vencida).
     *
     * @return list<string>
     */
    public static function conSaldo(): array
    {
        return [self::Pendiente->value, self::Parcial->value, self::Vencida->value];
    }

    /**
     * Cuotas aún no vencidas con saldo (pendiente o parcial).
     *
     * @return list<string>
     */
    public static function pendientesDePago(): array
    {
        return [self::Pendiente->value, self::Parcial->value];
    }
}
