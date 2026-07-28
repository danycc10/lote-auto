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

    public function etiqueta(): string
    {
        return match ($this) {
            self::Borrador => 'Borrador',
            self::Activo => 'Activo',
            self::Atrasado => 'Atrasado',
            self::Liquidado => 'Liquidado',
            self::Cancelado => 'Cancelado',
            self::Reestructurado => 'Reestructurado',
            self::Recuperado => 'Recuperado',
        };
    }

    /**
     * @return list<self>
     */
    public function transicionesPermitidas(): array
    {
        return match ($this) {
            self::Borrador => [self::Borrador, self::Activo, self::Cancelado],
            self::Activo => [
                self::Activo,
                self::Cancelado,
                self::Reestructurado,
                self::Recuperado,
            ],
            self::Atrasado => [
                self::Atrasado,
                self::Activo,
                self::Cancelado,
                self::Reestructurado,
                self::Recuperado,
            ],
            self::Liquidado => [self::Liquidado],
            self::Cancelado => [self::Cancelado],
            self::Reestructurado => [self::Reestructurado],
            self::Recuperado => [self::Recuperado],
        };
    }

    public function puedeTransicionarA(self $destino): bool
    {
        return in_array($destino, $this->transicionesPermitidas(), true);
    }

    public function cancelaPlanDePagos(): bool
    {
        return in_array($this, [
            self::Cancelado,
            self::Reestructurado,
            self::Recuperado,
        ], true);
    }
}
