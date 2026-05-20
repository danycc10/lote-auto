<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarcaAuto extends Model
{
    protected $table = 'marcas_autos';

    protected $fillable = [
        'nombre',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function modelos(): HasMany
    {
        return $this->hasMany(ModeloAuto::class, 'marca_auto_id');
    }

    public function autos(): HasMany
    {
        return $this->hasMany(Auto::class, 'marca_auto_id');
    }
}