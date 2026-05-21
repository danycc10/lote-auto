<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Configuracion extends Model
{
    protected $table = 'configuraciones';

    protected $fillable = ['clave', 'valor', 'descripcion'];

    public static function obtener(string $clave, mixed $default = null): mixed
    {
        return Cache::remember("sys_cfg_{$clave}", 300, fn () =>
            static::where('clave', $clave)->value('valor') ?? $default
        );
    }

    public static function establecer(string $clave, mixed $valor): void
    {
        static::updateOrCreate(['clave' => $clave], ['valor' => (string) $valor]);
        Cache::forget("sys_cfg_{$clave}");
    }

    public static function esActivo(string $clave): bool
    {
        // Default '1' para que instalaciones sin la tabla activen todo por defecto
        return filter_var(static::obtener($clave, '1'), FILTER_VALIDATE_BOOLEAN);
    }
}
