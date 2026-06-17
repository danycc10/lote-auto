@php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Models\Configuracion;

$c = fn(string $key, string $default = '') => Configuracion::obtener($key, $default);

$whatsapp           = $c('contact.whatsapp',              '5210000000000');
$homeUrl            = Route::has('public.home')  ? route('public.home')  : url('/');
$catalogoUrl        = Route::has('public.autos') ? route('public.autos') : '#autos';

$tagline            = $c('branding.tagline',           'Autos financiados');
$descripcionFooter  = $c('branding.descripcion_footer','Financiamiento directo, sin banco ni burocracia.');

$badgeHero          = $c('branding.badge_hero',         'Autos disponibles hoy');
$heroTitulo         = $c('branding.hero_titulo',        'Tu próximo auto.');
$heroAcento         = $c('branding.hero_acento',        'Financiado.');
$heroDescripcion    = $c('branding.hero_descripcion',   'Explora nuestro inventario, conoce los planes de pago y cotiza en minutos. Sin letra chica, sin trámites complicados.');
$ctaHeroPrimario    = $c('branding.cta_hero_primario',  'Ver autos disponibles');
$ctaHeroSecundario  = $c('branding.cta_hero_secundario','Cotizar por WhatsApp');

$stat1Valor = $c('branding.stat_1_valor', '200+');
$stat1Label = $c('branding.stat_1_label', 'Clientes atendidos');
$stat2Valor = $c('branding.stat_2_valor', '24h');
$stat2Label = $c('branding.stat_2_label', 'Respuesta garantizada');
$stat3Valor = $c('branding.stat_3_valor', '100%');
$stat3Label = $c('branding.stat_3_label', 'Proceso transparente');

$ctaEyebrow    = $c('branding.cta_eyebrow',    '¿Listo para empezar?');
$ctaTitulo     = $c('branding.cta_titulo',     'Empieza hoy.');
$ctaSubtitulo  = $c('branding.cta_subtitulo',  'Sin compromiso.');
$ctaDescripcion = $c('branding.cta_descripcion','Más de 200 familias ya eligieron su auto con nosotros. Cotiza en minutos, estrena pronto.');

$trust1 = $c('branding.trust_1', 'Sin buró');
$trust2 = $c('branding.trust_2', 'Enganche desde 10%');
$trust3 = $c('branding.trust_3', 'Plazos hasta 36 meses');
$trust4 = $c('branding.trust_4', 'Proceso en días');

$horario   = $c('branding.horario',   'Lun–Sáb · 9:00 AM – 7:00 PM');
$direccion = $c('branding.direccion', 'Tu Ciudad, Estado, México');

$waMsgGeneral = $c('branding.wa_mensaje_general', 'Hola, quiero información sobre los autos disponibles');
$waMsgCotizar = $c('branding.wa_mensaje_cotizar',  'Hola, quiero cotizar un auto');
$waBase    = 'https://wa.me/' . $whatsapp . '?text=';
$waGeneral = $waBase . urlencode($waMsgGeneral);
$waCotizar = $waBase . urlencode($waMsgCotizar);

$anuncioActivo = (bool) $c('branding.anuncio_activo', '0');
$anuncioTexto  = $c('branding.anuncio_texto', '');

$beneficiosEyebrow   = $c('branding.beneficios_eyebrow',   'Por qué elegirnos');
$beneficiosTitulo    = $c('branding.beneficios_titulo',    'La forma más sencilla de tener tu auto');
$beneficiosSubtitulo = $c('branding.beneficios_subtitulo', 'Sin banco, sin burocracia. Financiamiento directo con nosotros.');
$beneficio1Titulo    = $c('branding.beneficio_1_titulo',   'Inventario verificado');
$beneficio1Desc      = $c('branding.beneficio_1_desc',     'Unidades en buen estado, con historial revisado. Lo que ves es lo que obtienes.');
$beneficio2Titulo    = $c('branding.beneficio_2_titulo',   'Planes flexibles');
$beneficio2Desc      = $c('branding.beneficio_2_desc',     'Enganche y mensualidades adaptadas a tu presupuesto. Cotiza sin compromiso.');
$beneficio3Titulo    = $c('branding.beneficio_3_titulo',   'Atención directa');
$beneficio3Desc      = $c('branding.beneficio_3_desc',     'Sin intermediarios. Hablas directo con nosotros por WhatsApp para resolver cualquier duda.');

$procesoEyebrow   = $c('branding.proceso_eyebrow',   'Proceso');
$procesoTitulo    = $c('branding.proceso_titulo',    'Tu auto en 4 pasos');
$procesoSubtitulo = $c('branding.proceso_subtitulo', 'Sin papeleo complicado. Sin esperas largas. Con acompañamiento en cada etapa.');
$paso1Titulo      = $c('branding.paso_1_titulo',     'Elige tu auto');
$paso1Desc        = $c('branding.paso_1_desc',       'Explora el catálogo y encuentra el auto que se adapta a ti.');
$paso2Titulo      = $c('branding.paso_2_titulo',     'Cotiza en WhatsApp');
$paso2Desc        = $c('branding.paso_2_desc',       'Escríbenos y recibe tu plan de pagos en minutos.');
$paso3Titulo      = $c('branding.paso_3_titulo',     'Presenta documentos');
$paso3Desc        = $c('branding.paso_3_desc',       'Solo los básicos. Te guiamos en cada paso del trámite.');
$paso4Titulo      = $c('branding.paso_4_titulo',     'Estrena tu auto');
$paso4Desc        = $c('branding.paso_4_desc',       'Entrega rápida. Tu auto listo en días, no en meses.');

$autosEyebrow     = $c('branding.autos_eyebrow',     'Inventario');
$autosTitulo      = $c('branding.autos_titulo',      'Autos disponibles');
$autosDescripcion = $c('branding.autos_descripcion', 'Unidades listas para cotizar. Escríbenos para conocer el plan que más te conviene.');

$logoUrl = $c('branding.logo_url', '');

$contactoTitulo      = $c('branding.contacto_titulo',      '¿Tienes dudas?');
$contactoSubtitulo   = $c('branding.contacto_subtitulo',   'Te ayudamos.');
$contactoDescripcion = $c('branding.contacto_descripcion', 'Escríbenos y con gusto te asesoramos sobre disponibilidad, planes de pago y más.');

$seoTitulo      = Configuracion::obtener('branding.seo_titulo',      '');
$seoDescripcion = Configuracion::obtener('branding.seo_descripcion', 'Encuentra tu auto ideal con planes de financiamiento accesibles.');

$pasos = [
    ['num' => '01', 'titulo' => $paso1Titulo, 'desc' => $paso1Desc, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>'],
    ['num' => '02', 'titulo' => $paso2Titulo, 'desc' => $paso2Desc, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>'],
    ['num' => '03', 'titulo' => $paso3Titulo, 'desc' => $paso3Desc, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>'],
    ['num' => '04', 'titulo' => $paso4Titulo, 'desc' => $paso4Desc, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>'],
];

$beneficios = [
    ['titulo' => $beneficio1Titulo, 'desc' => $beneficio1Desc, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>'],
    ['titulo' => $beneficio2Titulo, 'desc' => $beneficio2Desc, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>'],
    ['titulo' => $beneficio3Titulo, 'desc' => $beneficio3Desc, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/>'],
];

$waIconSvg = '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>';
@endphp
