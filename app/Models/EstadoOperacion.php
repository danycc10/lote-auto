<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $clave
 * @property string $estado
 * @property string|null $mensaje
 * @property Carbon $ejecutado_at
 */
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
