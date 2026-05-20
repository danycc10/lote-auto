<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModeloAuto extends Model
{
    protected $table = 'modelos_autos';

    protected $fillable = [
        'marca_auto_id',
        'nombre',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function marca(): BelongsTo
    {
        return $this->belongsTo(MarcaAuto::class, 'marca_auto_id');
    }

    public function autos(): HasMany
    {
        return $this->hasMany(Auto::class, 'modelo_auto_id');
    }
}