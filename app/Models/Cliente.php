<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'telefono',
        'correo',
        'curp',
        'rfc',
        'direccion',
        'ciudad',
        'estado',
        'codigo_postal',
        'ocupacion',
        'ingreso_mensual',
        'ruta_ine',
        'ruta_comprobante_domicilio',
        'activo',
    ];

    protected $casts = [
        'ingreso_mensual' => 'decimal:2',
        'activo' => 'boolean',
    ];

    protected $appends = [
        'nombre_completo',
    ];

    public function contratosFinanciamiento(): HasMany
    {
        return $this->hasMany(ContratoFinanciamiento::class, 'cliente_id');
    }

    public function apartadosAutos(): HasMany
    {
        return $this->hasMany(ApartadoAuto::class, 'cliente_id');
    }

    public function pagosFinanciamiento(): HasMany
    {
        return $this->hasMany(PagoFinanciamiento::class, 'cliente_id');
    }

    public function recibosFinanciamiento(): HasMany
    {
        return $this->hasMany(ReciboFinanciamiento::class, 'cliente_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim(
            $this->nombre . ' ' .
                ($this->apellido_paterno ?? '') . ' ' .
                ($this->apellido_materno ?? '')
        );
    }
}
