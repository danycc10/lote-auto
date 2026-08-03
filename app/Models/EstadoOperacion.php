<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoOperacion extends Model
{
    protected $table = 'estado_operaciones';

    protected $fillable = [
        'clave',
        'estado',
        'mensaje',
        'ejecutado_at',
    ];

    protected function casts(): array
    {
        return [
            'ejecutado_at' => 'datetime',
        ];
    }
}
