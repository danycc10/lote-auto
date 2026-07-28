<?php

namespace App\Models;

use App\Enums\ContratoEstatus;
use App\Enums\CuotaEstatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContratoFinanciamiento extends Model
{
    protected $table = 'contratos_financiamiento';

    protected $fillable = [
        'folio',
        'auto_id',
        'cliente_id',
        'apartado_auto_id',
        'vendedor_id',
        'fecha_contrato',
        'fecha_primer_pago',
        'precio_contado',
        'precio_venta',
        'enganche',
        'comision_apertura',
        'monto_seguro',
        'monto_gps',
        'monto_financiado',
        'tasa_interes',
        'formula_calculo',
        'plazo',
        'frecuencia',
        'monto_cuota',
        'total_pagar',
        'total_pagado',
        'saldo_actual',
        'dias_gracia',
        'tipo_recargo',
        'valor_recargo',
        'estatus',
        'observaciones',
        'ruta_contrato_firmado',
    ];

    protected $casts = [
        'fecha_contrato' => 'date',
        'fecha_primer_pago' => 'date',
        'precio_contado' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'enganche' => 'decimal:2',
        'comision_apertura' => 'decimal:2',
        'monto_seguro' => 'decimal:2',
        'monto_gps' => 'decimal:2',
        'monto_financiado' => 'decimal:2',
        'tasa_interes' => 'decimal:4',
        'monto_cuota' => 'decimal:2',
        'total_pagar' => 'decimal:2',
        'total_pagado' => 'decimal:2',
        'saldo_actual' => 'decimal:2',
        'dias_gracia' => 'integer',
        'valor_recargo' => 'decimal:2',
        'plazo' => 'integer',
    ];

    protected $attributes = [
        'formula_calculo' => 'plana_v1',
    ];

    protected $appends = ['nombre_resumen'];

    /** @return BelongsTo<Auto, $this> */
    public function auto(): BelongsTo
    {
        return $this->belongsTo(Auto::class, 'auto_id');
    }

    /** @return BelongsTo<Cliente, $this> */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /** @return BelongsTo<User, $this> */
    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    /** @return HasMany<CuotaFinanciamiento, $this> */
    public function cuotas(): HasMany
    {
        return $this->hasMany(CuotaFinanciamiento::class, 'contrato_financiamiento_id');
    }

    /** @return HasMany<PagoFinanciamiento, $this> */
    public function pagos(): HasMany
    {
        return $this->hasMany(PagoFinanciamiento::class, 'contrato_financiamiento_id');
    }

    /** @return HasMany<ReciboFinanciamiento, $this> */
    public function recibos(): HasMany
    {
        return $this->hasMany(ReciboFinanciamiento::class, 'contrato_financiamiento_id');
    }

    /** @return HasMany<HistorialFinanciamiento, $this> */
    public function historiales(): HasMany
    {
        return $this->hasMany(HistorialFinanciamiento::class, 'contrato_financiamiento_id');
    }

    /** @return BelongsTo<ApartadoAuto, $this> */
    public function apartadoAuto(): BelongsTo
    {
        return $this->belongsTo(ApartadoAuto::class, 'apartado_auto_id');
    }

    public function getNombreResumenAttribute(): string
    {
        return trim(($this->folio ?? '').' - '.($this->cliente?->nombre_completo ?? 'Sin cliente'));
    }

    public function recalcularEstatus(): void
    {
        if ((float) $this->saldo_actual <= 0) {
            $this->estatus = ContratoEstatus::Liquidado->value;

            return;
        }

        $tieneVencidas = $this->cuotas()->where('estatus', CuotaEstatus::Vencida->value)->exists();
        $this->estatus = $tieneVencidas ? ContratoEstatus::Atrasado->value : ContratoEstatus::Activo->value;
    }
}
