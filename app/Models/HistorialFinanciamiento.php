<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialFinanciamiento extends Model
{
    protected $table = 'historiales_financiamiento';

    protected $fillable = [
        'contrato_financiamiento_id',
        'user_id',
        'evento',
        'antes',
        'despues',
        'observaciones',
    ];

    protected $casts = [
        'antes' => 'array',
        'despues' => 'array',
    ];

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(ContratoFinanciamiento::class, 'contrato_financiamiento_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}