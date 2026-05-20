<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ImagenAuto extends Model
{
    protected $table = 'imagenes_autos';

    protected $fillable = [
        'auto_id',
        'ruta',
        'disco',
        'mime_type',
        'tamano',
        'es_portada',
        'orden',
    ];

    protected $casts = [
        'tamano' => 'integer',
        'es_portada' => 'boolean',
        'orden' => 'integer',
    ];

    protected $appends = [
        'url',
    ];

    public function auto(): BelongsTo
    {
        return $this->belongsTo(Auto::class, 'auto_id');
    }

    public function getUrlAttribute(): ?string
    {
        if (!$this->ruta) {
            return null;
        }

        return Storage::disk($this->disco)->url($this->ruta);
    }
}