<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            [
                'clave'       => 'contact.instagram',
                'valor'       => '',
                'descripcion' => 'URL del perfil de Instagram (ej: https://instagram.com/tunegocio)',
            ],
            [
                'clave'       => 'contact.facebook',
                'valor'       => '',
                'descripcion' => 'URL de la página de Facebook (ej: https://facebook.com/tunegocio)',
            ],
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
            'contact.instagram',
            'contact.facebook',
        ])->delete();
    }
};
