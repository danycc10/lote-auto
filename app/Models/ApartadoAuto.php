<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ApartadoAuto extends Model
{
    protected $table = 'apartados_autos';

    protected $fillable = [
        'folio',
        'auto_id',
        'cliente_id',
        'usuario_id',
        'fecha_apartado',
        'fecha_vencimiento',
        'monto_anticipo',
        'saldo_pendiente',
        'forma_pago',
        'referencia',
        'nombre_cliente_temporal',
        'telefono_cliente_temporal',
        'correo_cliente_temporal',
        'observaciones',
        'estatus',
        'cancelado_at',
        'motivo_cancelacion',
    ];

    protected $casts = [
        'fecha_apartado' => 'date',
        'fecha_vencimiento' => 'date',
        'monto_anticipo' => 'decimal:2',
        'saldo_pendiente' => 'decimal:2',
        'cancelado_at' => 'datetime',
    ];

    protected $appends = [
        'cliente_mostrable',
    ];

    public function auto(): BelongsTo
    {
        return $this->belongsTo(Auto::class, 'auto_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

      public function contratoFinanciamiento(): HasOne
    {
        return $this->hasOne(ContratoFinanciamiento::class, 'apartado_auto_id');
    }

    public function getClienteMostrableAttribute(): string
    {
        if ($this->cliente) {
            return $this->cliente->nombre_completo;
        }

        return trim((string) ($this->nombre_cliente_temporal ?? 'Sin cliente'));
    }

    public function scopeActivos($query)
    {
        return $query->where('estatus', 'activo');
    }

    public function estaVigente(): bool
    {
        return $this->estatus === 'activo'
            && $this->fecha_vencimiento
            && $this->fecha_vencimiento->greaterThanOrEqualTo(now()->startOfDay());
    }
}