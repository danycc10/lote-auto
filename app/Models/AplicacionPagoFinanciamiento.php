<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AplicacionPagoFinanciamiento extends Model
{
    protected $table = 'aplicaciones_pagos_financiamiento';

    protected $fillable = [
        'pago_financiamiento_id',
        'cuota_financiamiento_id',
        'monto',
        'monto_recargo',
        'monto_capital',
        'monto_interes',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'monto_recargo' => 'decimal:2',
        'monto_capital' => 'decimal:2',
        'monto_interes' => 'decimal:2',
    ];

    public function pago(): BelongsTo
    {
        return $this->belongsTo(PagoFinanciamiento::class, 'pago_financiamiento_id');
    }

    public function cuota(): BelongsTo
    {
        return $this->belongsTo(CuotaFinanciamiento::class, 'cuota_financiamiento_id');
    }
}