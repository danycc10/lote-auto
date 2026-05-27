<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuotaFinanciamiento extends Model
{
    protected $table = 'cuotas_financiamiento';

    protected $fillable = [
        'contrato_financiamiento_id',
        'numero',
        'fecha_vencimiento',
        'monto_capital',
        'monto_interes',
        'monto_extra',
        'monto',
        'monto_pagado',
        'recargo_aplicado',
        'saldo',
        'estatus',
        'fecha_pago',
        'observaciones',
        'notificado_correo_at',
    ];

    protected $casts = [
        'numero' => 'integer',
        'fecha_vencimiento' => 'date',
        'monto_capital' => 'decimal:2',
        'monto_interes' => 'decimal:2',
        'monto_extra' => 'decimal:2',
        'monto' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
        'recargo_aplicado' => 'decimal:2',
        'saldo' => 'decimal:2',
        'fecha_pago' => 'datetime',
        'notificado_correo_at' => 'datetime',
    ];

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(ContratoFinanciamiento::class, 'contrato_financiamiento_id');
    }

    public function aplicacionesPago(): HasMany
    {
        return $this->hasMany(AplicacionPagoFinanciamiento::class, 'cuota_financiamiento_id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(PagoFinanciamiento::class, 'cuota_id');
    }

    public function recibos(): HasMany
    {
        return $this->hasMany(ReciboFinanciamiento::class, 'cuota_id');
    }
}
