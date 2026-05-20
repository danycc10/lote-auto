<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LogFinanciero extends Model
{
    protected $table = 'logs_financieros';

    protected $fillable = [
        'user_id',
        'tipo',
        'modulo',
        'referencia',
        'loggable_type',
        'loggable_id',
        'monto',
        'saldo_anterior',
        'saldo_nuevo',
        'moneda',
        'titulo',
        'descripcion',
        'metadata',
        'nivel',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'saldo_anterior' => 'decimal:2',
        'saldo_nuevo' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }
}