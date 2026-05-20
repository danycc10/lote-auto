<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReciboFinanciamiento extends Model
{
    protected $table = 'recibos_financiamiento';

    protected $fillable = [
        'folio',
        'contrato_financiamiento_id',
        'cuota_id',
        'pago_financiamiento_id',
        'cliente_id',
        'fecha_recibo',
        'monto',
        'saldo_anterior',
        'saldo_posterior',
        'concepto',
        'observaciones',
        'estatus',
        'cancelado_at',
    ];

    protected $casts = [
        'fecha_recibo' => 'date',
        'monto' => 'decimal:2',
        'saldo_anterior' => 'decimal:2',
        'saldo_posterior' => 'decimal:2',
        'cancelado_at' => 'datetime',
    ];

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(ContratoFinanciamiento::class, 'contrato_financiamiento_id');
    }

    public function cuota(): BelongsTo
    {
        return $this->belongsTo(CuotaFinanciamiento::class, 'cuota_id');
    }

    public function pago(): BelongsTo
    {
        return $this->belongsTo(PagoFinanciamiento::class, 'pago_financiamiento_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
