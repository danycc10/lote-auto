<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PagoFinanciamiento extends Model
{
    protected $table = 'pagos_financiamiento';

    protected $fillable = [
        'contrato_financiamiento_id',
        'cuota_id',
        'cliente_id',
        'capturado_por',
        'fecha_pago',
        'monto',
        'monto_aplicado',
        'monto_restante',
        'forma_pago',
        'referencia',
        'observaciones',
        'evidencia_path',
        'firma_path',
        'estatus',
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto' => 'decimal:2',
        'monto_aplicado' => 'decimal:2',
        'monto_restante' => 'decimal:2',
    ];

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(ContratoFinanciamiento::class, 'contrato_financiamiento_id');
    }

    public function cuota(): BelongsTo
    {
        return $this->belongsTo(CuotaFinanciamiento::class, 'cuota_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function capturadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'capturado_por');
    }

    public function aplicaciones(): HasMany
    {
        return $this->hasMany(AplicacionPagoFinanciamiento::class, 'pago_financiamiento_id');
    }

    public function recibos(): HasMany
    {
        return $this->hasMany(ReciboFinanciamiento::class, 'pago_financiamiento_id');
    }
}
