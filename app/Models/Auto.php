<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Auto extends Model
{
    protected $table = 'autos';

    protected $fillable = [
        'marca_auto_id',
        'modelo_auto_id',
        'codigo_inventario',
        'vin',
        'placa',
        'anio',
        'version',
        'color',
        'kilometraje',
        'transmision',
        'tipo_combustible',
        'precio_contado',
        'precio_financiado',
        'estatus',
        'descripcion',
        'destacado',
        'activo',
    ];

    protected $casts = [
        'anio' => 'integer',
        'kilometraje' => 'integer',
        'precio_contado' => 'decimal:2',
        'precio_financiado' => 'decimal:2',
        'destacado' => 'boolean',
        'activo' => 'boolean',
    ];

    public function marca(): BelongsTo
    {
        return $this->belongsTo(MarcaAuto::class, 'marca_auto_id');
    }

    public function modelo(): BelongsTo
    {
        return $this->belongsTo(ModeloAuto::class, 'modelo_auto_id');
    }

    public function imagenes(): HasMany
    {
        return $this->hasMany(ImagenAuto::class, 'auto_id');
    }

    public function imagenPortada()
    {
        return $this->hasOne(ImagenAuto::class, 'auto_id')->where('es_portada', true);
    }

    public function apartados(): HasMany
    {
        return $this->hasMany(ApartadoAuto::class, 'auto_id');
    }

    public function apartadoActivo(): HasOne
    {
        return $this->hasOne(ApartadoAuto::class, 'auto_id')
            ->where('estatus', 'activo')
            ->latestOfMany();
    }

    public function contratosFinanciamiento(): HasMany
    {
        return $this->hasMany(ContratoFinanciamiento::class, 'auto_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim(
            ($this->marca?->nombre ?? '') . ' ' .
                ($this->modelo?->nombre ?? '') . ' ' .
                $this->anio
        );
    }
}
