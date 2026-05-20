<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditoriaFinanciera extends Model
{
    protected $table = 'auditoria_financieras';

    protected $fillable = [
        'user_id',
        'accion',
        'modelo',
        'modelo_id',
        'antes',
        'despues',
        'observaciones',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'antes' => 'array',
        'despues' => 'array',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}