<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            // ── Announcement bar ────────────────────────────────────────────────────
            ['clave' => 'branding.anuncio_activo', 'valor' => '0',  'descripcion' => 'Mostrar barra de anuncio en la parte superior (1 = sí)'],
            ['clave' => 'branding.anuncio_texto',  'valor' => '',   'descripcion' => 'Texto del banner de anuncio superior'],

            // ── Sección Beneficios (id="financiamiento") ────────────────────────────
            ['clave' => 'branding.beneficios_eyebrow',   'valor' => 'Por qué elegirnos',                                  'descripcion' => 'Eyebrow de la sección de beneficios'],
            ['clave' => 'branding.beneficios_titulo',    'valor' => 'La forma más sencilla de tener tu auto',             'descripcion' => 'Título de la sección de beneficios'],
            ['clave' => 'branding.beneficios_subtitulo', 'valor' => 'Sin banco, sin burocracia. Financiamiento directo con nosotros.', 'descripcion' => 'Subtítulo de la sección de beneficios'],
            ['clave' => 'branding.beneficio_1_titulo',   'valor' => 'Inventario verificado',                              'descripcion' => 'Título del beneficio 1'],
            ['clave' => 'branding.beneficio_1_desc',     'valor' => 'Unidades en buen estado, con historial revisado. Lo que ves es lo que obtienes.', 'descripcion' => 'Descripción del beneficio 1'],
            ['clave' => 'branding.beneficio_2_titulo',   'valor' => 'Planes flexibles',                                   'descripcion' => 'Título del beneficio 2'],
            ['clave' => 'branding.beneficio_2_desc',     'valor' => 'Enganche y mensualidades adaptadas a tu presupuesto. Cotiza sin compromiso.', 'descripcion' => 'Descripción del beneficio 2'],
            ['clave' => 'branding.beneficio_3_titulo',   'valor' => 'Atención directa',                                   'descripcion' => 'Título del beneficio 3'],
            ['clave' => 'branding.beneficio_3_desc',     'valor' => 'Sin intermediarios. Hablas directo con nosotros por WhatsApp para resolver cualquier duda.', 'descripcion' => 'Descripción del beneficio 3'],

            // ── Sección Proceso (id="proceso") ─────────────────────────────────────
            ['clave' => 'branding.proceso_eyebrow',   'valor' => 'Proceso',                                                              'descripcion' => 'Eyebrow de la sección de proceso'],
            ['clave' => 'branding.proceso_titulo',    'valor' => 'Tu auto en 4 pasos',                                                   'descripcion' => 'Título de la sección de proceso'],
            ['clave' => 'branding.proceso_subtitulo', 'valor' => 'Sin papeleo complicado. Sin esperas largas. Con acompañamiento en cada etapa.', 'descripcion' => 'Subtítulo de la sección de proceso'],
            ['clave' => 'branding.paso_1_titulo',     'valor' => 'Elige tu auto',                                                        'descripcion' => 'Título del paso 1'],
            ['clave' => 'branding.paso_1_desc',       'valor' => 'Explora el catálogo y encuentra el auto que se adapta a ti.',           'descripcion' => 'Descripción del paso 1'],
            ['clave' => 'branding.paso_2_titulo',     'valor' => 'Cotiza en WhatsApp',                                                   'descripcion' => 'Título del paso 2'],
            ['clave' => 'branding.paso_2_desc',       'valor' => 'Escríbenos y recibe tu plan de pagos en minutos.',                      'descripcion' => 'Descripción del paso 2'],
            ['clave' => 'branding.paso_3_titulo',     'valor' => 'Presenta documentos',                                                  'descripcion' => 'Título del paso 3'],
            ['clave' => 'branding.paso_3_desc',       'valor' => 'Solo los básicos. Te guiamos en cada paso del trámite.',               'descripcion' => 'Descripción del paso 3'],
            ['clave' => 'branding.paso_4_titulo',     'valor' => 'Estrena tu auto',                                                      'descripcion' => 'Título del paso 4'],
            ['clave' => 'branding.paso_4_desc',       'valor' => 'Entrega rápida. Tu auto listo en días, no en meses.',                  'descripcion' => 'Descripción del paso 4'],

            // ── Sección Catálogo / Autos (id="autos") ──────────────────────────────
            ['clave' => 'branding.autos_eyebrow',    'valor' => 'Inventario',                                                            'descripcion' => 'Eyebrow de la sección de catálogo'],
            ['clave' => 'branding.autos_titulo',     'valor' => 'Autos disponibles',                                                     'descripcion' => 'Título de la sección de catálogo'],
            ['clave' => 'branding.autos_descripcion','valor' => 'Unidades listas para cotizar. Escríbenos para conocer el plan que más te conviene.', 'descripcion' => 'Descripción de la sección de catálogo'],

            // ── Sección Contacto (id="contacto") ───────────────────────────────────
            ['clave' => 'branding.contacto_titulo',      'valor' => '¿Tienes dudas?',                                                    'descripcion' => 'Título de la sección de contacto'],
            ['clave' => 'branding.contacto_subtitulo',   'valor' => 'Te ayudamos.',                                                      'descripcion' => 'Subtítulo de la sección de contacto'],
            ['clave' => 'branding.contacto_descripcion', 'valor' => 'Escríbenos y con gusto te asesoramos sobre disponibilidad, planes de pago y más.', 'descripcion' => 'Descripción de la sección de contacto'],

            // ── Analytics ───────────────────────────────────────────────────────────
            ['clave' => 'branding.ga_id',    'valor' => '', 'descripcion' => 'Google Analytics Measurement ID (ej: G-XXXXXXXXXX)'],
            ['clave' => 'branding.gtm_id',   'valor' => '', 'descripcion' => 'Google Tag Manager ID (ej: GTM-XXXXXXX)'],
            ['clave' => 'branding.pixel_id', 'valor' => '', 'descripcion' => 'Facebook / Meta Pixel ID (ej: 123456789)'],
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
            'branding.anuncio_activo', 'branding.anuncio_texto',
            'branding.beneficios_eyebrow', 'branding.beneficios_titulo', 'branding.beneficios_subtitulo',
            'branding.beneficio_1_titulo', 'branding.beneficio_1_desc',
            'branding.beneficio_2_titulo', 'branding.beneficio_2_desc',
            'branding.beneficio_3_titulo', 'branding.beneficio_3_desc',
            'branding.proceso_eyebrow', 'branding.proceso_titulo', 'branding.proceso_subtitulo',
            'branding.paso_1_titulo', 'branding.paso_1_desc',
            'branding.paso_2_titulo', 'branding.paso_2_desc',
            'branding.paso_3_titulo', 'branding.paso_3_desc',
            'branding.paso_4_titulo', 'branding.paso_4_desc',
            'branding.autos_eyebrow', 'branding.autos_titulo', 'branding.autos_descripcion',
            'branding.contacto_titulo', 'branding.contacto_subtitulo', 'branding.contacto_descripcion',
            'branding.ga_id', 'branding.gtm_id', 'branding.pixel_id',
        ])->delete();
    }
};
