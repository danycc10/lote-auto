<?php

namespace App\Livewire\Admin\Sistema;

use App\Models\Configuracion;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class BrandingIndex extends Component
{
    use WithFileUploads;

    // ── Logo ─────────────────────────────────────────────────────
    public string $logoUrl       = '';
    public $logoTemp             = null;
    public string $logoTicketUrl = '';
    public $logoTicketTemp       = null;

    // ── Colores ──────────────────────────────────────────────────
    public string $colorPrimario   = '#3b82f6';
    public string $colorSecundario = '#10b981';

    // ── Identidad ─────────────────────────────────────────────────
    public string $tagline            = 'Autos financiados';
    public string $badgeHero          = 'Autos disponibles hoy';
    public string $descripcionFooter  = '';

    // ── Hero ──────────────────────────────────────────────────────
    public string $heroTitulo       = 'Tu próximo auto.';
    public string $heroAcento       = 'Financiado.';
    public string $heroDescripcion  = '';
    public string $ctaHeroPrimario  = 'Ver autos disponibles';
    public string $ctaHeroSecundario = 'Cotizar por WhatsApp';

    // ── Estadísticas ──────────────────────────────────────────────
    public string $stat1Valor = '200+';
    public string $stat1Label = 'Clientes atendidos';
    public string $stat2Valor = '24h';
    public string $stat2Label = 'Respuesta garantizada';
    public string $stat3Valor = '100%';
    public string $stat3Label = 'Proceso transparente';

    // ── Beneficios / Trust badges ─────────────────────────────────
    public string $trust1 = 'Sin buró';
    public string $trust2 = 'Enganche desde 10%';
    public string $trust3 = 'Plazos hasta 36 meses';
    public string $trust4 = 'Proceso en días';

    // ── Banner CTA ────────────────────────────────────────────────
    public string $ctaEyebrow    = '¿Listo para empezar?';
    public string $ctaTitulo     = 'Empieza hoy.';
    public string $ctaSubtitulo  = 'Sin compromiso.';
    public string $ctaDescripcion = '';

    // ── WhatsApp ──────────────────────────────────────────────────
    public string $waMensajeGeneral = 'Hola, quiero información sobre los autos disponibles';
    public string $waMensajeCotizar = 'Hola, quiero cotizar un auto';

    // ── Contacto ──────────────────────────────────────────────────
    public string $horario   = 'Lun–Sáb · 9:00 AM – 7:00 PM';
    public string $direccion = 'Tu Ciudad, Estado, México';

    // ── SEO ───────────────────────────────────────────────────────
    public string $seoTitulo      = '';
    public string $seoDescripcion = '';

    // ── Announcement bar ──────────────────────────────────────────
    public bool   $anuncioActivo = false;
    public string $anuncioTexto  = '';

    // ── Sección Beneficios ────────────────────────────────────────
    public string $beneficiosEyebrow   = '';
    public string $beneficiosTitulo    = '';
    public string $beneficiosSubtitulo = '';
    public string $beneficio1Titulo    = '';
    public string $beneficio1Desc      = '';
    public string $beneficio2Titulo    = '';
    public string $beneficio2Desc      = '';
    public string $beneficio3Titulo    = '';
    public string $beneficio3Desc      = '';

    // ── Sección Proceso ───────────────────────────────────────────
    public string $procesoEyebrow   = '';
    public string $procesoTitulo    = '';
    public string $procesoSubtitulo = '';
    public string $paso1Titulo      = '';
    public string $paso1Desc        = '';
    public string $paso2Titulo      = '';
    public string $paso2Desc        = '';
    public string $paso3Titulo      = '';
    public string $paso3Desc        = '';
    public string $paso4Titulo      = '';
    public string $paso4Desc        = '';

    // ── Sección Catálogo ──────────────────────────────────────────
    public string $autosEyebrow    = '';
    public string $autosTitulo     = '';
    public string $autosDescripcion= '';

    // ── Sección Contacto ──────────────────────────────────────────
    public string $contactoTitulo      = '';
    public string $contactoSubtitulo   = '';
    public string $contactoDescripcion = '';

    // ── Analytics ─────────────────────────────────────────────────
    public string $gaId    = '';
    public string $gtmId   = '';
    public string $pixelId = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasAnyRole(['administrador', 'gerente']), 403);

        $c = fn(string $key, string $default = '') => Configuracion::obtener($key, $default);

        $this->logoUrl       = $c('branding.logo_url',        '');
        $this->logoTicketUrl = $c('branding.logo_ticket_url', '');

        $this->colorPrimario    = $c('branding.color_primario',   '#3b82f6');
        $this->colorSecundario  = $c('branding.color_secundario', '#10b981');

        $this->tagline           = $c('branding.tagline',           'Autos financiados');
        $this->badgeHero         = $c('branding.badge_hero',        'Autos disponibles hoy');
        $this->descripcionFooter = $c('branding.descripcion_footer', 'Financiamiento directo, sin banco ni burocracia. Tu próximo auto más cerca de lo que crees.');

        $this->heroTitulo        = $c('branding.hero_titulo',       'Tu próximo auto.');
        $this->heroAcento        = $c('branding.hero_acento',       'Financiado.');
        $this->heroDescripcion   = $c('branding.hero_descripcion',  '');
        $this->ctaHeroPrimario   = $c('branding.cta_hero_primario', 'Ver autos disponibles');
        $this->ctaHeroSecundario = $c('branding.cta_hero_secundario', 'Cotizar por WhatsApp');

        $this->stat1Valor = $c('branding.stat_1_valor', '200+');
        $this->stat1Label = $c('branding.stat_1_label', 'Clientes atendidos');
        $this->stat2Valor = $c('branding.stat_2_valor', '24h');
        $this->stat2Label = $c('branding.stat_2_label', 'Respuesta garantizada');
        $this->stat3Valor = $c('branding.stat_3_valor', '100%');
        $this->stat3Label = $c('branding.stat_3_label', 'Proceso transparente');

        $this->trust1 = $c('branding.trust_1', 'Sin buró');
        $this->trust2 = $c('branding.trust_2', 'Enganche desde 10%');
        $this->trust3 = $c('branding.trust_3', 'Plazos hasta 36 meses');
        $this->trust4 = $c('branding.trust_4', 'Proceso en días');

        $this->ctaEyebrow    = $c('branding.cta_eyebrow',    '¿Listo para empezar?');
        $this->ctaTitulo     = $c('branding.cta_titulo',     'Empieza hoy.');
        $this->ctaSubtitulo  = $c('branding.cta_subtitulo',  'Sin compromiso.');
        $this->ctaDescripcion = $c('branding.cta_descripcion', 'Más de 200 familias ya eligieron su auto con nosotros. Cotiza en minutos, estrena pronto.');

        $this->waMensajeGeneral = $c('branding.wa_mensaje_general', 'Hola, quiero información sobre los autos disponibles');
        $this->waMensajeCotizar = $c('branding.wa_mensaje_cotizar',  'Hola, quiero cotizar un auto');

        $this->horario   = $c('branding.horario',   'Lun–Sáb · 9:00 AM – 7:00 PM');
        $this->direccion = $c('branding.direccion', 'Tu Ciudad, Estado, México');

        $this->seoTitulo      = $c('branding.seo_titulo',      '');
        $this->seoDescripcion = $c('branding.seo_descripcion', 'Encuentra tu auto ideal con planes de financiamiento accesibles. Sin complicaciones, respuesta en 24 horas.');

        $this->anuncioActivo = (bool) $c('branding.anuncio_activo', '0');
        $this->anuncioTexto  = $c('branding.anuncio_texto', '');

        $this->beneficiosEyebrow   = $c('branding.beneficios_eyebrow',   'Por qué elegirnos');
        $this->beneficiosTitulo    = $c('branding.beneficios_titulo',    'La forma más sencilla de tener tu auto');
        $this->beneficiosSubtitulo = $c('branding.beneficios_subtitulo', 'Sin banco, sin burocracia. Financiamiento directo con nosotros.');
        $this->beneficio1Titulo    = $c('branding.beneficio_1_titulo',   'Inventario verificado');
        $this->beneficio1Desc      = $c('branding.beneficio_1_desc',     'Unidades en buen estado, con historial revisado. Lo que ves es lo que obtienes.');
        $this->beneficio2Titulo    = $c('branding.beneficio_2_titulo',   'Planes flexibles');
        $this->beneficio2Desc      = $c('branding.beneficio_2_desc',     'Enganche y mensualidades adaptadas a tu presupuesto. Cotiza sin compromiso.');
        $this->beneficio3Titulo    = $c('branding.beneficio_3_titulo',   'Atención directa');
        $this->beneficio3Desc      = $c('branding.beneficio_3_desc',     'Sin intermediarios. Hablas directo con nosotros por WhatsApp para resolver cualquier duda.');

        $this->procesoEyebrow   = $c('branding.proceso_eyebrow',   'Proceso');
        $this->procesoTitulo    = $c('branding.proceso_titulo',    'Tu auto en 4 pasos');
        $this->procesoSubtitulo = $c('branding.proceso_subtitulo', 'Sin papeleo complicado. Sin esperas largas. Con acompañamiento en cada etapa.');
        $this->paso1Titulo      = $c('branding.paso_1_titulo',     'Elige tu auto');
        $this->paso1Desc        = $c('branding.paso_1_desc',       'Explora el catálogo y encuentra el auto que se adapta a ti.');
        $this->paso2Titulo      = $c('branding.paso_2_titulo',     'Cotiza en WhatsApp');
        $this->paso2Desc        = $c('branding.paso_2_desc',       'Escríbenos y recibe tu plan de pagos en minutos.');
        $this->paso3Titulo      = $c('branding.paso_3_titulo',     'Presenta documentos');
        $this->paso3Desc        = $c('branding.paso_3_desc',       'Solo los básicos. Te guiamos en cada paso del trámite.');
        $this->paso4Titulo      = $c('branding.paso_4_titulo',     'Estrena tu auto');
        $this->paso4Desc        = $c('branding.paso_4_desc',       'Entrega rápida. Tu auto listo en días, no en meses.');

        $this->autosEyebrow     = $c('branding.autos_eyebrow',     'Inventario');
        $this->autosTitulo      = $c('branding.autos_titulo',      'Autos disponibles');
        $this->autosDescripcion = $c('branding.autos_descripcion', 'Unidades listas para cotizar. Escríbenos para conocer el plan que más te conviene.');

        $this->contactoTitulo      = $c('branding.contacto_titulo',      '¿Tienes dudas?');
        $this->contactoSubtitulo   = $c('branding.contacto_subtitulo',   'Te ayudamos.');
        $this->contactoDescripcion = $c('branding.contacto_descripcion', 'Escríbenos y con gusto te asesoramos sobre disponibilidad, planes de pago y más.');

        $this->gaId    = $c('branding.ga_id',    '');
        $this->gtmId   = $c('branding.gtm_id',   '');
        $this->pixelId = $c('branding.pixel_id', '');
    }

    public function subirLogo(): void
    {
        abort_unless(auth()->user()?->hasAnyRole(['administrador', 'gerente']), 403);

        $this->validate(['logoTemp' => ['required', 'image', 'max:2048', 'mimes:png,jpg,jpeg,svg,webp']]);

        if ($this->logoUrl) {
            Storage::disk('public')->delete($this->logoUrl);
        }

        $path = $this->logoTemp->store('branding', 'public');
        Configuracion::establecer('branding.logo_url', $path);
        $this->logoUrl  = $path;
        $this->logoTemp = null;

        $this->dispatch('toast', type: 'success', message: 'Logo actualizado correctamente.');
    }

    public function eliminarLogo(): void
    {
        abort_unless(auth()->user()?->hasAnyRole(['administrador', 'gerente']), 403);

        if ($this->logoUrl) {
            Storage::disk('public')->delete($this->logoUrl);
        }

        Configuracion::establecer('branding.logo_url', '');
        $this->logoUrl = '';

        $this->dispatch('toast', type: 'success', message: 'Logo eliminado. Se usará el ícono por defecto.');
    }

    public function subirLogoTicket(): void
    {
        abort_unless(auth()->user()?->hasAnyRole(['administrador', 'gerente']), 403);

        $this->validate(['logoTicketTemp' => ['required', 'image', 'max:1024', 'mimes:png,jpg,jpeg']]);

        if ($this->logoTicketUrl) {
            Storage::disk('public')->delete($this->logoTicketUrl);
        }

        $path = $this->logoTicketTemp->store('branding', 'public');
        Configuracion::establecer('branding.logo_ticket_url', $path);
        $this->logoTicketUrl  = $path;
        $this->logoTicketTemp = null;

        $this->dispatch('toast', type: 'success', message: 'Logo de ticket actualizado.');
    }

    public function eliminarLogoTicket(): void
    {
        abort_unless(auth()->user()?->hasAnyRole(['administrador', 'gerente']), 403);

        if ($this->logoTicketUrl) {
            Storage::disk('public')->delete($this->logoTicketUrl);
        }

        Configuracion::establecer('branding.logo_ticket_url', '');
        $this->logoTicketUrl = '';

        $this->dispatch('toast', type: 'success', message: 'Logo de ticket eliminado.');
    }

    public function guardar(): void
    {
        abort_unless(auth()->user()?->hasAnyRole(['administrador', 'gerente']), 403);

        $this->validate([
            'colorPrimario'    => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'colorSecundario'  => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'tagline'          => ['required', 'string', 'max:80'],
            'badgeHero'        => ['nullable', 'string', 'max:60'],
            'descripcionFooter'=> ['nullable', 'string', 'max:200'],
            'heroTitulo'       => ['required', 'string', 'max:100'],
            'heroAcento'       => ['required', 'string', 'max:80'],
            'heroDescripcion'  => ['nullable', 'string', 'max:300'],
            'ctaHeroPrimario'  => ['nullable', 'string', 'max:50'],
            'ctaHeroSecundario'=> ['nullable', 'string', 'max:50'],
            'stat1Valor'       => ['nullable', 'string', 'max:20'],
            'stat1Label'       => ['nullable', 'string', 'max:40'],
            'stat2Valor'       => ['nullable', 'string', 'max:20'],
            'stat2Label'       => ['nullable', 'string', 'max:40'],
            'stat3Valor'       => ['nullable', 'string', 'max:20'],
            'stat3Label'       => ['nullable', 'string', 'max:40'],
            'trust1'           => ['nullable', 'string', 'max:50'],
            'trust2'           => ['nullable', 'string', 'max:50'],
            'trust3'           => ['nullable', 'string', 'max:50'],
            'trust4'           => ['nullable', 'string', 'max:50'],
            'ctaEyebrow'       => ['nullable', 'string', 'max:80'],
            'ctaTitulo'        => ['nullable', 'string', 'max:80'],
            'ctaSubtitulo'     => ['nullable', 'string', 'max:80'],
            'ctaDescripcion'   => ['nullable', 'string', 'max:300'],
            'waMensajeGeneral' => ['nullable', 'string', 'max:200'],
            'waMensajeCotizar' => ['nullable', 'string', 'max:200'],
            'horario'          => ['nullable', 'string', 'max:100'],
            'direccion'        => ['nullable', 'string', 'max:200'],
            'seoTitulo'        => ['nullable', 'string', 'max:120'],
            'seoDescripcion'   => ['nullable', 'string', 'max:300'],
            'anuncioTexto'     => ['nullable', 'string', 'max:200'],
            'beneficiosEyebrow'   => ['nullable', 'string', 'max:80'],
            'beneficiosTitulo'    => ['nullable', 'string', 'max:120'],
            'beneficiosSubtitulo' => ['nullable', 'string', 'max:300'],
            'beneficio1Titulo'    => ['nullable', 'string', 'max:80'],
            'beneficio1Desc'      => ['nullable', 'string', 'max:300'],
            'beneficio2Titulo'    => ['nullable', 'string', 'max:80'],
            'beneficio2Desc'      => ['nullable', 'string', 'max:300'],
            'beneficio3Titulo'    => ['nullable', 'string', 'max:80'],
            'beneficio3Desc'      => ['nullable', 'string', 'max:300'],
            'procesoEyebrow'      => ['nullable', 'string', 'max:80'],
            'procesoTitulo'       => ['nullable', 'string', 'max:120'],
            'procesoSubtitulo'    => ['nullable', 'string', 'max:300'],
            'paso1Titulo'         => ['nullable', 'string', 'max:80'],
            'paso1Desc'           => ['nullable', 'string', 'max:200'],
            'paso2Titulo'         => ['nullable', 'string', 'max:80'],
            'paso2Desc'           => ['nullable', 'string', 'max:200'],
            'paso3Titulo'         => ['nullable', 'string', 'max:80'],
            'paso3Desc'           => ['nullable', 'string', 'max:200'],
            'paso4Titulo'         => ['nullable', 'string', 'max:80'],
            'paso4Desc'           => ['nullable', 'string', 'max:200'],
            'autosEyebrow'        => ['nullable', 'string', 'max:80'],
            'autosTitulo'         => ['nullable', 'string', 'max:120'],
            'autosDescripcion'    => ['nullable', 'string', 'max:300'],
            'contactoTitulo'      => ['nullable', 'string', 'max:80'],
            'contactoSubtitulo'   => ['nullable', 'string', 'max:80'],
            'contactoDescripcion' => ['nullable', 'string', 'max:300'],
            'gaId'                => ['nullable', 'string', 'max:30', 'regex:/^(G-[A-Z0-9]+)?$/'],
            'gtmId'               => ['nullable', 'string', 'max:20', 'regex:/^(GTM-[A-Z0-9]+)?$/'],
            'pixelId'             => ['nullable', 'string', 'max:20', 'regex:/^[0-9]*$/'],
        ], [
            'colorPrimario.regex'  => 'Debe ser un color hexadecimal válido (#rrggbb).',
            'colorSecundario.regex'=> 'Debe ser un color hexadecimal válido (#rrggbb).',
            'tagline.required'     => 'El tagline es obligatorio.',
            'heroTitulo.required'  => 'El título del hero es obligatorio.',
            'heroAcento.required'  => 'El texto resaltado es obligatorio.',
        ]);

        $e = fn(string $key, string $val) => Configuracion::establecer($key, $val);

        $e('branding.color_primario',   $this->colorPrimario);
        $e('branding.color_secundario', $this->colorSecundario);

        $e('branding.tagline',            $this->tagline);
        $e('branding.badge_hero',         $this->badgeHero);
        $e('branding.descripcion_footer', $this->descripcionFooter);

        $e('branding.hero_titulo',        $this->heroTitulo);
        $e('branding.hero_acento',        $this->heroAcento);
        $e('branding.hero_descripcion',   $this->heroDescripcion);
        $e('branding.cta_hero_primario',  $this->ctaHeroPrimario);
        $e('branding.cta_hero_secundario',$this->ctaHeroSecundario);

        $e('branding.stat_1_valor', $this->stat1Valor);
        $e('branding.stat_1_label', $this->stat1Label);
        $e('branding.stat_2_valor', $this->stat2Valor);
        $e('branding.stat_2_label', $this->stat2Label);
        $e('branding.stat_3_valor', $this->stat3Valor);
        $e('branding.stat_3_label', $this->stat3Label);

        $e('branding.trust_1', $this->trust1);
        $e('branding.trust_2', $this->trust2);
        $e('branding.trust_3', $this->trust3);
        $e('branding.trust_4', $this->trust4);

        $e('branding.cta_eyebrow',    $this->ctaEyebrow);
        $e('branding.cta_titulo',     $this->ctaTitulo);
        $e('branding.cta_subtitulo',  $this->ctaSubtitulo);
        $e('branding.cta_descripcion',$this->ctaDescripcion);

        $e('branding.wa_mensaje_general', $this->waMensajeGeneral);
        $e('branding.wa_mensaje_cotizar',  $this->waMensajeCotizar);

        $e('branding.horario',   $this->horario);
        $e('branding.direccion', $this->direccion);

        $e('branding.seo_titulo',      $this->seoTitulo);
        $e('branding.seo_descripcion', $this->seoDescripcion);

        $e('branding.anuncio_activo', $this->anuncioActivo ? '1' : '0');
        $e('branding.anuncio_texto',  $this->anuncioTexto);

        $e('branding.beneficios_eyebrow',   $this->beneficiosEyebrow);
        $e('branding.beneficios_titulo',    $this->beneficiosTitulo);
        $e('branding.beneficios_subtitulo', $this->beneficiosSubtitulo);
        $e('branding.beneficio_1_titulo',   $this->beneficio1Titulo);
        $e('branding.beneficio_1_desc',     $this->beneficio1Desc);
        $e('branding.beneficio_2_titulo',   $this->beneficio2Titulo);
        $e('branding.beneficio_2_desc',     $this->beneficio2Desc);
        $e('branding.beneficio_3_titulo',   $this->beneficio3Titulo);
        $e('branding.beneficio_3_desc',     $this->beneficio3Desc);

        $e('branding.proceso_eyebrow',   $this->procesoEyebrow);
        $e('branding.proceso_titulo',    $this->procesoTitulo);
        $e('branding.proceso_subtitulo', $this->procesoSubtitulo);
        $e('branding.paso_1_titulo',     $this->paso1Titulo);
        $e('branding.paso_1_desc',       $this->paso1Desc);
        $e('branding.paso_2_titulo',     $this->paso2Titulo);
        $e('branding.paso_2_desc',       $this->paso2Desc);
        $e('branding.paso_3_titulo',     $this->paso3Titulo);
        $e('branding.paso_3_desc',       $this->paso3Desc);
        $e('branding.paso_4_titulo',     $this->paso4Titulo);
        $e('branding.paso_4_desc',       $this->paso4Desc);

        $e('branding.autos_eyebrow',     $this->autosEyebrow);
        $e('branding.autos_titulo',      $this->autosTitulo);
        $e('branding.autos_descripcion', $this->autosDescripcion);

        $e('branding.contacto_titulo',      $this->contactoTitulo);
        $e('branding.contacto_subtitulo',   $this->contactoSubtitulo);
        $e('branding.contacto_descripcion', $this->contactoDescripcion);

        $e('branding.ga_id',    $this->gaId);
        $e('branding.gtm_id',   $this->gtmId);
        $e('branding.pixel_id', $this->pixelId);

        $this->dispatch('toast', type: 'success', message: 'Contenido actualizado. Los cambios se reflejan en máximo 5 minutos.');
    }

    public function render()
    {
        return view('livewire.admin.sistema.branding-index')
            ->layout('layouts.app')
            ->title('Apariencia del sitio');
    }
}
