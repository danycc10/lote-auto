<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('configuraciones')->insertOrIgnore([
            'clave'       => 'branding.logo_url',
            'valor'       => '',
            'descripcion' => 'Ruta de la imagen del logo (storage). Vacío = usa el ícono SVG por defecto.',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        DB::table('configuraciones')->insertOrIgnore([
            'clave'       => 'branding.logo_ticket_url',
            'valor'       => '',
            'descripcion' => 'Ruta del logo para tickets/PDF térmico (versión negra/oscura). Vacío = solo texto.',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('configuraciones')->whereIn('clave', [
            'branding.logo_url',
            'branding.logo_ticket_url',
        ])->delete();
    }
};
