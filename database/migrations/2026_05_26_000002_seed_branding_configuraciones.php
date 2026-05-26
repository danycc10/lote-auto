<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            ['clave' => 'branding.color_primario',   'valor' => '#3b82f6', 'descripcion' => 'Color de acento primario (azul) en hex'],
            ['clave' => 'branding.color_secundario',  'valor' => '#10b981', 'descripcion' => 'Color de acento secundario (verde) en hex'],
            ['clave' => 'branding.tagline',            'valor' => 'Autos financiados',        'descripcion' => 'Subtítulo bajo el logo en navbar y footers'],
            ['clave' => 'branding.hero_titulo',        'valor' => 'Tu próximo auto.',          'descripcion' => 'Primera línea del título principal del hero'],
            ['clave' => 'branding.hero_acento',        'valor' => 'Financiado.',               'descripcion' => 'Segunda línea del héroe (resaltada con gradiente)'],
            ['clave' => 'branding.hero_descripcion',   'valor' => 'Explora nuestro inventario, conoce los planes de pago y cotiza en minutos. Sin letra chica, sin trámites complicados.', 'descripcion' => 'Párrafo descriptivo bajo el título del hero'],
            ['clave' => 'branding.stat_1_valor',       'valor' => '200+',  'descripcion' => 'Valor de la primera estadística (hero)'],
            ['clave' => 'branding.stat_2_valor',       'valor' => '24h',   'descripcion' => 'Valor de la segunda estadística (hero)'],
            ['clave' => 'branding.stat_3_valor',       'valor' => '100%',  'descripcion' => 'Valor de la tercera estadística (hero)'],
            ['clave' => 'branding.horario',            'valor' => 'Lun–Sáb · 9:00 AM – 7:00 PM', 'descripcion' => 'Horario de atención mostrado en la landing'],
            ['clave' => 'branding.direccion',          'valor' => 'Tu Ciudad, Estado, México',     'descripcion' => 'Dirección física mostrada en la landing'],
        ];

        foreach ($defaults as $row) {
            DB::table('configuraciones')->insertOrIgnore(array_merge($row, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        DB::table('configuraciones')->whereIn('clave', [
            'branding.color_primario', 'branding.color_secundario',
            'branding.tagline', 'branding.hero_titulo', 'branding.hero_acento',
            'branding.hero_descripcion', 'branding.stat_1_valor',
            'branding.stat_2_valor', 'branding.stat_3_valor',
            'branding.horario', 'branding.direccion',
        ])->delete();
    }
};
