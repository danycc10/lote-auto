<?php

namespace App\Models;

use App\Enums\ReporteGeneradoEstatus;
use App\Enums\TipoReporte;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property TipoReporte $tipo
 * @property Carbon|null $desde
 * @property Carbon|null $hasta
 * @property string|null $archivo
 * @property ReporteGeneradoEstatus $estatus
 * @property string|null $error
 * @property Carbon|null $expires_at
 */
class ReporteGenerado extends Model
{
    protected $table = 'reportes_generados';

    protected $fillable = [
        'uuid',
        'user_id',
        'tipo',
        'desde',
        'hasta',
        'archivo',
        'estatus',
        'error',
        'expires_at',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $reporte): void {
            $reporte->uuid ??= (string) Str::uuid();
        });
    }

    protected function casts(): array
    {
        return [
            'tipo' => TipoReporte::class,
            'estatus' => ReporteGeneradoEstatus::class,
            'desde' => 'date',
            'hasta' => 'date',
            'expires_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
