<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            [
                'clave'       => 'contact.whatsapp',
                'valor'       => '',
                'descripcion' => 'Número de WhatsApp del negocio (solo dígitos, con código de país, ej: 5218001234567)',
            ],
            [
                'clave'       => 'contact.maps_embed',
                'valor'       => '',
                'descripcion' => 'URL del iframe de Google Maps (Compartir → Insertar mapa → copiar solo el src)',
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
            'contact.whatsapp',
            'contact.maps_embed',
        ])->delete();
    }
};
