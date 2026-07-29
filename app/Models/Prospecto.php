<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prospecto extends Model
{
    use Concerns\PreventsChangesInDemoMode;
    use SoftDeletes;

    protected $table = 'prospectos';

    protected $fillable = [
        'auto_id',
        'usuario_asignado_id',
        'nombre',
        'telefono',
        'correo',
        'estatus',
        'origen',
        'observaciones',
        'ultimo_contacto_at',
    ];

    protected $casts = [
        'ultimo_contacto_at' => 'datetime',
    ];

    public function auto(): BelongsTo
    {
        return $this->belongsTo(Auto::class, 'auto_id');
    }

    public function usuarioAsignado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_asignado_id');
    }
}
