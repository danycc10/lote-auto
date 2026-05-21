<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TarjetaCobro extends Model
{
    protected $table = 'tarjetas_cobro';

    protected $fillable = [
        'nombre',
        'banco',
        'tipo',
        'numero',
        'titular',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function scopeActiva(Builder $query): Builder
    {
        return $query->where('activa', true);
    }

    public function scopeParaTipo(Builder $query, string $tipo): Builder
    {
        return $query->where('tipo', $tipo)->where('activa', true);
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(PagoFinanciamiento::class, 'tarjeta_cobro_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        $partes = [$this->nombre];
        if ($this->banco) {
            $partes[] = $this->banco;
        }
        if ($this->numero) {
            $partes[] = '****' . ltrim($this->numero, '*');
        }
        return implode(' · ', $partes);
    }
}
