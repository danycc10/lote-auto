<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            // Hero
            ['clave' => 'branding.badge_hero',        'valor' => 'Autos disponibles hoy',          'descripcion' => 'Texto del badge/eyebrow animado en el hero'],
            ['clave' => 'branding.cta_hero_primario',  'valor' => 'Ver autos disponibles',          'descripcion' => 'Texto del botón CTA principal del hero'],
            ['clave' => 'branding.cta_hero_secundario','valor' => 'Cotizar por WhatsApp',           'descripcion' => 'Texto del botón CTA secundario del hero'],
            // Estadísticas — labels
            ['clave' => 'branding.stat_1_label', 'valor' => 'Clientes atendidos',    'descripcion' => 'Etiqueta de la primera estadística'],
            ['clave' => 'branding.stat_2_label', 'valor' => 'Respuesta garantizada', 'descripcion' => 'Etiqueta de la segunda estadística'],
            ['clave' => 'branding.stat_3_label', 'valor' => 'Proceso transparente',  'descripcion' => 'Etiqueta de la tercera estadística'],
            // WhatsApp messages
            ['clave' => 'branding.wa_mensaje_general', 'valor' => 'Hola, quiero información sobre los autos disponibles', 'descripcion' => 'Mensaje pre-escrito para el botón de WhatsApp general'],
            ['clave' => 'branding.wa_mensaje_cotizar',  'valor' => 'Hola, quiero cotizar un auto',                        'descripcion' => 'Mensaje pre-escrito para el botón de cotización'],
            // Banner CTA
            ['clave' => 'branding.cta_eyebrow',     'valor' => '¿Listo para empezar?',                                                                 'descripcion' => 'Texto eyebrow del banner CTA'],
            ['clave' => 'branding.cta_titulo',       'valor' => 'Empieza hoy.',                                                                         'descripcion' => 'Línea 1 del título del banner CTA'],
            ['clave' => 'branding.cta_subtitulo',    'valor' => 'Sin compromiso.',                                                                       'descripcion' => 'Línea 2 (slate) del título del banner CTA'],
            ['clave' => 'branding.cta_descripcion',  'valor' => 'Más de 200 familias ya eligieron su auto con nosotros. Cotiza en minutos, estrena pronto.', 'descripcion' => 'Descripción del banner CTA'],
            // Beneficios / Trust badges
            ['clave' => 'branding.trust_1', 'valor' => 'Sin buró',              'descripcion' => 'Primer beneficio en el banner CTA'],
            ['clave' => 'branding.trust_2', 'valor' => 'Enganche desde 10%',    'descripcion' => 'Segundo beneficio en el banner CTA'],
            ['clave' => 'branding.trust_3', 'valor' => 'Plazos hasta 36 meses', 'descripcion' => 'Tercer beneficio en el banner CTA'],
            ['clave' => 'branding.trust_4', 'valor' => 'Proceso en días',       'descripcion' => 'Cuarto beneficio en el banner CTA'],
            // Footer
            ['clave' => 'branding.descripcion_footer', 'valor' => 'Financiamiento directo, sin banco ni burocracia. Tu próximo auto más cerca de lo que crees.', 'descripcion' => 'Párrafo bajo el logo en el footer de la landing'],
            // SEO
            ['clave' => 'branding.seo_titulo',      'valor' => '',                                                                                              'descripcion' => 'Meta title personalizado (vacío = usa el nombre de la app)'],
            ['clave' => 'branding.seo_descripcion',  'valor' => 'Encuentra tu auto ideal con planes de financiamiento accesibles. Sin complicaciones, respuesta en 24 horas.', 'descripcion' => 'Meta description del sitio público'],
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
            'branding.badge_hero', 'branding.cta_hero_primario', 'branding.cta_hero_secundario',
            'branding.stat_1_label', 'branding.stat_2_label', 'branding.stat_3_label',
            'branding.wa_mensaje_general', 'branding.wa_mensaje_cotizar',
            'branding.cta_eyebrow', 'branding.cta_titulo', 'branding.cta_subtitulo', 'branding.cta_descripcion',
            'branding.trust_1', 'branding.trust_2', 'branding.trust_3', 'branding.trust_4',
            'branding.descripcion_footer', 'branding.seo_titulo', 'branding.seo_descripcion',
        ])->delete();
    }
};
