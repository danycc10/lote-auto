@php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

$whatsapp = \App\Models\Configuracion::obtener('contact.whatsapp', '5210000000000');
$logoUrl  = \App\Models\Configuracion::obtener('branding.logo_url', '');
$homeUrl  = Route::has('public.home')  ? route('public.home')  : url('/');
$catUrl   = Route::has('public.autos') ? route('public.autos') : url('/autos');

$marca  = $auto->marca?->nombre  ?? 'Marca';
$modelo = $auto->modelo?->nombre ?? 'Modelo';
$titulo = trim($marca . ' ' . $modelo);

$imagenes = $auto->imagenes ?? collect();

// Construir lista de URLs para Alpine.js
$imagenesUrls = $imagenes->map(function ($img) {
    $ruta = $img->ruta ?? '';
    if (Str::startsWith($ruta, ['http://', 'https://'])) {
        return ['url' => $ruta];
    } elseif (Str::startsWith($ruta, ['/storage/'])) {
        return ['url' => asset(ltrim($ruta, '/'))];
    } elseif (Str::startsWith($ruta, ['storage/'])) {
        return ['url' => asset($ruta)];
    } elseif (Str::startsWith($ruta, ['public/'])) {
        return ['url' => asset('storage/' . Str::after($ruta, 'public/'))];
    }
    return ['url' => asset('storage/' . $ruta)];
})->values()->toArray();

// Imagen portada
$portadaRuta = $auto->imagenPortada?->ruta ?? $imagenes->first()?->ruta ?? null;
$portadaUrl  = null;
if ($portadaRuta) {
    if (Str::startsWith($portadaRuta, ['http://', 'https://'])) {
        $portadaUrl = $portadaRuta;
    } elseif (Str::startsWith($portadaRuta, ['/storage/'])) {
        $portadaUrl = asset(ltrim($portadaRuta, '/'));
    } elseif (Str::startsWith($portadaRuta, ['storage/'])) {
        $portadaUrl = asset($portadaRuta);
    } elseif (Str::startsWith($portadaRuta, ['public/'])) {
        $portadaUrl = asset('storage/' . Str::after($portadaRuta, 'public/'));
    } else {
        $portadaUrl = asset('storage/' . $portadaRuta);
    }
}

// Si la portada no está en $imagenesUrls, la anteponemos
if ($portadaUrl && !$imagenesUrls) {
    $imagenesUrls = [['url' => $portadaUrl]];
} elseif ($portadaUrl && !collect($imagenesUrls)->contains('url', $portadaUrl)) {
    array_unshift($imagenesUrls, ['url' => $portadaUrl]);
}

$mensajeWa = 'Hola, me interesa el ' . $titulo . ($auto->anio ? ' ' . $auto->anio : '') . '. ¿Puede darme más información?';

$specs = array_filter([
    ['label' => 'Año',          'value' => $auto->anio],
    ['label' => 'Versión',      'value' => $auto->version],
    ['label' => 'Color',        'value' => $auto->color ? ucfirst($auto->color) : null],
    ['label' => 'Kilometraje',  'value' => $auto->kilometraje !== null ? number_format((int)$auto->kilometraje) . ' km' : null],
    ['label' => 'Transmisión',  'value' => $auto->transmision ? ucfirst($auto->transmision) : null],
    ['label' => 'Combustible',  'value' => $auto->tipo_combustible ? ucfirst($auto->tipo_combustible) : null],
    ['label' => 'Inventario',   'value' => $auto->codigo_inventario],
    ['label' => 'VIN',          'value' => $auto->vin],
    ['label' => 'Placa',        'value' => $auto->placa],
], fn($s) => !empty($s['value']));
@endphp

@section('title', $titulo . ' ' . ($auto->anio ?? ''))
@section('meta_description', 'Auto ' . $titulo . ' ' . ($auto->anio ?? '') . ' disponible con financiamiento. ' . config('app.name'))
@section('og_title', $titulo . ' ' . ($auto->anio ?? '') . ' — ' . config('app.name'))
@section('og_image', $portadaUrl ?? asset('favicon.ico'))

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio',    'item' => $homeUrl],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Catálogo', 'item' => $catUrl],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $titulo . ' ' . ($auto->anio ?? ''), 'item' => url()->current()],
            ],
        ],
        array_filter([
            '@type'  => 'Product',
            'name'   => $titulo . ' ' . ($auto->anio ?? ''),
            'description' => $auto->descripcion ?? ('Auto ' . $titulo . ' disponible con financiamiento en ' . config('app.name')),
            'brand'  => ['@type' => 'Brand', 'name' => $marca],
            'image'  => $portadaUrl ?: null,
            'offers' => [
                '@type'         => 'Offer',
                'priceCurrency' => 'MXN',
                'price'         => (string) number_format((float) ($auto->precio_contado ?? 0), 2, '.', ''),
                'availability'  => 'https://schema.org/InStock',
                'url'           => url()->current(),
            ],
        ]),
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

<div class="bg-[#06091a] text-white overflow-x-hidden min-h-screen" style="background-color:#06091a">

    <x-public-navbar :whatsapp="$whatsapp" />

    {{-- ======================================================
         HERO HEADER
         ====================================================== --}}
    <section class="relative overflow-hidden pt-[68px]" aria-label="Encabezado del auto">
        <div class="absolute inset-0 bg-[#06091a] bg-dot-grid"></div>
        <div class="absolute -left-40 top-0 h-[400px] w-[600px] rounded-full bg-blue-600/15 blur-[120px] pointer-events-none" aria-hidden="true"></div>
        <div class="absolute right-0 bottom-0 h-[300px] w-[400px] rounded-full bg-emerald-600/10 blur-[100px] pointer-events-none" aria-hidden="true"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-slate-400 mb-5" aria-label="Migas de pan">
                <a href="{{ $homeUrl }}" class="hover:text-white transition">Inicio</a>
                <svg class="h-3.5 w-3.5 text-slate-600 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                <a href="{{ $catUrl }}" class="hover:text-white transition">Catálogo</a>
                <svg class="h-3.5 w-3.5 text-slate-600 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                <span class="text-white font-medium truncate max-w-[200px]">{{ $titulo }}</span>
            </nav>

            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    @if($auto->destacado)
                    <span class="inline-flex items-center gap-1.5 mb-3 rounded-full bg-amber-500/15 border border-amber-500/30 px-3 py-1 text-xs font-semibold text-amber-400">
                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z" clip-rule="evenodd"/></svg>
                        Auto destacado
                    </span>
                    @endif

                    <h1 class="text-3xl md:text-5xl font-black leading-tight">
                        {{ $titulo }}
                        <span class="text-slate-400 font-normal text-2xl md:text-3xl">{{ $auto->anio }}</span>
                    </h1>

                    @if(!empty($auto->version))
                    <p class="mt-2 text-slate-400 text-lg">{{ $auto->version }}</p>
                    @endif
                </div>

                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/15 border border-emerald-500/30 px-4 py-2 text-sm font-bold text-emerald-400">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse" aria-hidden="true"></span>
                    Disponible
                </span>
            </div>
        </div>
    </section>

    {{-- ======================================================
         MAIN CONTENT
         ====================================================== --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-10 items-start">

            {{-- LEFT: GALERÍA --}}
            <div class="lg:col-span-2 space-y-4"
                 x-data="{
                     active: 0,
                     images: {{ Js::from($imagenesUrls) }},
                     prev() { this.active = this.active > 0 ? this.active - 1 : this.images.length - 1 },
                     next() { this.active = this.active < this.images.length - 1 ? this.active + 1 : 0 }
                 }">

                {{-- Imagen principal --}}
                <div class="relative rounded-2xl overflow-hidden bg-slate-900 border border-white/[0.07] aspect-[16/10]">

                    @if($imagenesUrls)
                        <template x-for="(img, i) in images" :key="i">
                            <img :src="img.url"
                                 :alt="'{{ $titulo }} - imagen ' + (i+1)"
                                 width="1200" height="750"
                                 x-show="active === i"
                                 loading="eager"
                                 class="absolute inset-0 h-full w-full object-cover transition-opacity duration-300"
                                 :class="active === i ? 'opacity-100' : 'opacity-0'">
                        </template>

                        {{-- Controles --}}
                        @if(count($imagenesUrls) > 1)
                        <button @click="prev()"
                                type="button"
                                aria-label="Imagen anterior"
                                class="absolute left-3 top-1/2 -translate-y-1/2 h-10 w-10 flex items-center justify-center rounded-full bg-black/50 backdrop-blur border border-white/10 text-white transition hover:bg-black/70">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/></svg>
                        </button>
                        <button @click="next()"
                                type="button"
                                aria-label="Imagen siguiente"
                                class="absolute right-3 top-1/2 -translate-y-1/2 h-10 w-10 flex items-center justify-center rounded-full bg-black/50 backdrop-blur border border-white/10 text-white transition hover:bg-black/70">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                        </button>

                        {{-- Contador --}}
                        <div class="absolute bottom-3 right-3 rounded-full bg-black/60 backdrop-blur px-3 py-1 text-xs font-semibold text-white tabular-nums" aria-live="polite">
                            <span x-text="active + 1"></span>/<span x-text="images.length"></span>
                        </div>
                        @endif

                    @else
                        {{-- Sin imágenes --}}
                        <div class="h-full flex flex-col items-center justify-center text-slate-600" aria-label="Sin imágenes disponibles">
                            <svg class="h-20 w-20 mb-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/>
                                <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/>
                                <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                            </svg>
                            <p class="text-sm font-semibold">Sin imágenes disponibles</p>
                        </div>
                    @endif
                </div>

                {{-- Miniaturas --}}
                @if(count($imagenesUrls) > 1)
                <div class="flex gap-2.5 overflow-x-auto pb-1 scrollbar-hide" role="list" aria-label="Miniaturas de imágenes">
                    <template x-for="(img, i) in images" :key="'thumb-' + i">
                        <button @click="active = i"
                                type="button"
                                :aria-label="'Ver imagen ' + (i+1)"
                                :aria-pressed="active === i"
                                class="shrink-0 h-20 w-28 rounded-xl overflow-hidden border-2 transition"
                                :class="active === i ? 'border-blue-500 opacity-100' : 'border-white/[0.08] opacity-50 hover:opacity-75'">
                            <img :src="img.url" :alt="'Miniatura ' + (i+1)" width="112" height="80" class="h-full w-full object-cover">
                        </button>
                    </template>
                </div>
                @endif

                {{-- DESCRIPCIÓN --}}
                @if(!empty($auto->descripcion))
                <div class="rounded-2xl border border-white/[0.07] bg-slate-900/60 p-6">
                    <h2 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M2 3.75A.75.75 0 0 1 2.75 3h11.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 3.75Zm0 4.167a.75.75 0 0 1 .75-.75h11.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75Zm0 4.166a.75.75 0 0 1 .75-.75h5a.75.75 0 0 1 0 1.5h-5a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd"/></svg>
                        Descripción
                    </h2>
                    <p class="text-slate-300 leading-relaxed whitespace-pre-line text-sm">{{ $auto->descripcion }}</p>
                </div>
                @endif

                {{-- SPECS GRID --}}
                @if($specs)
                <div class="rounded-2xl border border-white/[0.07] bg-slate-900/60 p-6">
                    <h2 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <svg class="h-5 w-5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M2.5 3A1.5 1.5 0 0 0 1 4.5v4A1.5 1.5 0 0 0 2.5 10h6A1.5 1.5 0 0 0 10 8.5v-4A1.5 1.5 0 0 0 8.5 3h-6Zm11 2A1.5 1.5 0 0 0 12 6.5v7a1.5 1.5 0 0 0 1.5 1.5h4a1.5 1.5 0 0 0 1.5-1.5v-7A1.5 1.5 0 0 0 17.5 5h-4Zm-10 7A1.5 1.5 0 0 0 2 13.5v2A1.5 1.5 0 0 0 3.5 17h6a1.5 1.5 0 0 0 1.5-1.5v-2A1.5 1.5 0 0 0 9.5 12h-6Z" clip-rule="evenodd"/></svg>
                        Características
                    </h2>
                    <dl class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($specs as $spec)
                        <div class="rounded-xl bg-slate-800/60 border border-white/[0.05] px-4 py-3">
                            <dt class="text-xs text-slate-500 mb-0.5">{{ $spec['label'] }}</dt>
                            <dd class="text-sm font-bold text-white">{{ $spec['value'] }}</dd>
                        </div>
                        @endforeach
                    </dl>
                </div>
                @endif

            </div>{{-- /LEFT --}}

            {{-- RIGHT: SIDEBAR STICKY --}}
            <aside class="lg:sticky lg:top-24 space-y-4">

                {{-- PRECIO CARD --}}
                <div class="rounded-2xl border border-white/[0.08] bg-slate-900/80 backdrop-blur p-6 shadow-2xl shadow-black/40">

                    {{-- Precio contado --}}
                    @if($auto->precio_contado > 0)
                    <div class="mb-1">
                        <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Precio contado</p>
                        <p class="text-4xl font-black text-white tabular-nums mt-1">
                            ${{ number_format((float)$auto->precio_contado, 0) }}
                        </p>
                    </div>
                    @endif

                    {{-- Precio financiado --}}
                    @if($auto->precio_financiado > 0)
                    <div class="mt-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 px-4 py-3">
                        <p class="text-xs text-emerald-400 font-semibold uppercase tracking-wide">Con financiamiento</p>
                        <p class="text-2xl font-black text-emerald-300 tabular-nums mt-0.5">
                            ${{ number_format((float)$auto->precio_financiado, 0) }}
                        </p>
                        <p class="text-xs text-emerald-500/70 mt-1">Sujeto a aprobación crediticia</p>
                    </div>
                    @endif

                    {{-- Mini specs --}}
                    <div class="mt-5 grid grid-cols-2 gap-2">
                        @if($auto->anio)
                        <div class="rounded-xl bg-slate-800/60 px-3 py-2.5 text-center">
                            <p class="text-[10px] text-slate-500 uppercase tracking-wide">Año</p>
                            <p class="text-sm font-black text-white mt-0.5">{{ $auto->anio }}</p>
                        </div>
                        @endif
                        @if($auto->kilometraje !== null)
                        <div class="rounded-xl bg-slate-800/60 px-3 py-2.5 text-center">
                            <p class="text-[10px] text-slate-500 uppercase tracking-wide">Kilometraje</p>
                            <p class="text-sm font-black text-white mt-0.5 tabular-nums">{{ number_format((int)$auto->kilometraje) }}</p>
                        </div>
                        @endif
                        @if($auto->transmision)
                        <div class="rounded-xl bg-slate-800/60 px-3 py-2.5 text-center">
                            <p class="text-[10px] text-slate-500 uppercase tracking-wide">Transmisión</p>
                            <p class="text-sm font-black text-white mt-0.5">{{ ucfirst($auto->transmision) }}</p>
                        </div>
                        @endif
                        @if($auto->tipo_combustible)
                        <div class="rounded-xl bg-slate-800/60 px-3 py-2.5 text-center">
                            <p class="text-[10px] text-slate-500 uppercase tracking-wide">Combustible</p>
                            <p class="text-sm font-black text-white mt-0.5">{{ ucfirst($auto->tipo_combustible) }}</p>
                        </div>
                        @endif
                    </div>

                    {{-- CTA WhatsApp --}}
                    <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode($mensajeWa) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="mt-5 flex w-full items-center justify-center gap-2.5 rounded-xl px-5 py-4 text-sm font-bold text-white shadow-lg transition hover:opacity-90 active:scale-[0.98]"
                       style="background-color: var(--color-secundario)">
                        <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        Quiero este auto
                    </a>

                    <a href="{{ $catUrl }}"
                       class="mt-2 flex w-full items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/[0.04] px-5 py-3 text-sm font-semibold text-slate-300 transition hover:bg-white/[0.08] hover:text-white">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M9.78 4.22a.75.75 0 0 1 0 1.06L7.06 8l2.72 2.72a.75.75 0 1 1-1.06 1.06L5.47 8.53a.75.75 0 0 1 0-1.06l3.25-3.25a.75.75 0 0 1 1.06 0Zm-4.56 8.75a.75.75 0 0 0 0 1.5h9.56a.75.75 0 0 0 0-1.5H5.22Z" clip-rule="evenodd"/></svg>
                        Ver más autos
                    </a>
                </div>

                {{-- TRUST CARD --}}
                <div class="rounded-2xl border border-white/[0.07] bg-slate-900/50 p-5">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-4">¿Por qué elegirnos?</p>
                    <ul class="space-y-3" role="list">
                        @foreach([
                            ['icon' => 'M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z', 'text' => 'Autos verificados y en buen estado'],
                            ['icon' => 'M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z', 'text' => 'Atención personalizada 6 días'],
                            ['icon' => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'text' => 'Respuesta en menos de 24 horas'],
                            ['icon' => 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z', 'text' => 'Planes de financiamiento flexibles'],
                        ] as $trust)
                        <li class="flex items-start gap-3">
                            <div class="mt-0.5 h-6 w-6 shrink-0 rounded-lg bg-emerald-500/15 flex items-center justify-center" aria-hidden="true">
                                <svg class="h-3.5 w-3.5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $trust['icon'] }}"/>
                                </svg>
                            </div>
                            <p class="text-sm text-slate-300">{{ $trust['text'] }}</p>
                        </li>
                        @endforeach
                    </ul>
                </div>

            </aside>{{-- /SIDEBAR --}}

        </div>{{-- /grid --}}
    </main>

    {{-- ======================================================
         CTA BANNER
         ====================================================== --}}
    <section class="relative overflow-hidden border-t border-white/[0.06]" aria-labelledby="cta-detalle-heading">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/40 via-slate-950 to-emerald-900/30" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(59,130,246,0.12),_transparent_60%)]" aria-hidden="true"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h2 id="cta-detalle-heading" class="text-3xl md:text-4xl font-black text-white">
                ¿Listo para estrenar?
            </h2>
            <p class="mt-3 text-slate-400 text-lg max-w-xl mx-auto">
                Contáctanos ahora y te ayudamos a encontrar el plan de financiamiento ideal para ti.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode($mensajeWa) }}"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center justify-center gap-2.5 rounded-xl px-7 py-4 text-sm font-bold text-white shadow-lg transition hover:opacity-90 active:scale-[0.97]"
                   style="background-color: var(--color-secundario)">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    Quiero este auto por WhatsApp
                </a>
                <a href="{{ $catUrl }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/15 bg-white/[0.07] px-7 py-4 text-sm font-semibold text-white transition hover:bg-white/12">
                    Ver más autos
                </a>
            </div>
        </div>
    </section>

    {{-- ======================================================
         AUTOS RELACIONADOS
         ====================================================== --}}
    @if($relacionados->count())
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16" aria-labelledby="relacionados-heading">

        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-emerald-400 font-semibold mb-2">Más opciones</p>
                <h2 id="relacionados-heading" class="text-2xl md:text-3xl font-black text-white">
                    También te puede interesar
                </h2>
            </div>
            <a href="{{ $catUrl }}"
               class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-slate-400 hover:text-white transition">
                Ver catálogo completo
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($relacionados as $rel)
            @php
                $relMarca  = $rel->marca?->nombre  ?? '';
                $relModelo = $rel->modelo?->nombre ?? '';
                $relTitulo = trim($relMarca . ' ' . $relModelo);

                $relImgRuta = $rel->imagenPortada?->ruta ?? null;
                $relImgUrl  = null;
                if ($relImgRuta) {
                    if (Str::startsWith($relImgRuta, ['http://', 'https://'])) {
                        $relImgUrl = $relImgRuta;
                    } elseif (Str::startsWith($relImgRuta, ['/storage/'])) {
                        $relImgUrl = asset(ltrim($relImgRuta, '/'));
                    } elseif (Str::startsWith($relImgRuta, ['storage/'])) {
                        $relImgUrl = asset($relImgRuta);
                    } elseif (Str::startsWith($relImgRuta, ['public/'])) {
                        $relImgUrl = asset('storage/' . Str::after($relImgRuta, 'public/'));
                    } else {
                        $relImgUrl = asset('storage/' . $relImgRuta);
                    }
                }
            @endphp

            <a href="{{ route('public.autos.show', $rel) }}"
               class="group block overflow-hidden rounded-2xl border border-white/[0.07] bg-[#0e1725] card-hover-glow"
               aria-label="Ver {{ $relTitulo }}">

                {{-- Imagen --}}
                <div class="relative aspect-[4/3] overflow-hidden bg-slate-800">
                    @if($relImgUrl)
                        <img src="{{ $relImgUrl }}"
                             alt="{{ $relTitulo }}"
                             width="400" height="300"
                             loading="lazy"
                             class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    @else
                        <div class="h-full w-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center" aria-hidden="true">
                            <svg class="h-12 w-12 text-slate-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/>
                                <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/>
                                <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="absolute inset-x-0 bottom-0 h-12 bg-gradient-to-t from-[#0e1725] to-transparent"></div>
                </div>

                {{-- Info --}}
                <div class="p-4">
                    <p class="text-xs text-slate-500 mb-0.5">{{ $rel->marca?->nombre }}</p>
                    <h3 class="text-sm font-bold text-white leading-snug truncate">
                        {{ $relModelo ?: $relTitulo }}
                        <span class="text-slate-400 font-normal">{{ $rel->anio }}</span>
                    </h3>

                    <div class="mt-3 flex items-center justify-between">
                        @if($rel->precio_contado > 0)
                        <p class="text-base font-black text-white tabular-nums">
                            ${{ number_format((float)$rel->precio_contado, 0) }}
                        </p>
                        @endif
                        @if($rel->precio_financiado > 0)
                        <p class="text-xs text-emerald-400 font-semibold">
                            Fin. ${{ number_format((float)$rel->precio_financiado, 0) }}
                        </p>
                        @endif
                    </div>

                    @if($rel->transmision || $rel->kilometraje !== null)
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        @if($rel->transmision)
                        <span class="rounded-md bg-slate-800 border border-white/[0.05] px-2 py-0.5 text-[10px] font-medium text-slate-400">
                            {{ ucfirst($rel->transmision) }}
                        </span>
                        @endif
                        @if($rel->kilometraje !== null)
                        <span class="rounded-md bg-slate-800 border border-white/[0.05] px-2 py-0.5 text-[10px] font-medium text-slate-400 tabular-nums">
                            {{ number_format((int)$rel->kilometraje) }} km
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-6 text-center sm:hidden">
            <a href="{{ $catUrl }}"
               class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-400 hover:text-white transition">
                Ver catálogo completo
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
            </a>
        </div>

    </section>
    @endif

    {{-- ======================================================
         FOOTER
         ====================================================== --}}
    <footer class="border-t border-white/[0.06] bg-[#04070f] py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                <a href="{{ $homeUrl }}" class="flex items-center gap-3 group" aria-label="{{ config('app.name') }}">
                    @if($logoUrl)
                        <img src="{{ Storage::url($logoUrl) }}" alt="{{ config('app.name') }}" class="h-9 w-auto max-w-[100px] object-contain shrink-0">
                    @else
                        <div class="h-9 w-9 rounded-xl flex items-center justify-center shadow-lg shrink-0"
                             style="background: linear-gradient(to bottom right, var(--color-primario), var(--color-secundario))">
                            <svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/>
                                <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/>
                                <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <p class="font-black text-white text-sm leading-none">{{ config('app.name', 'AutoLote') }}</p>
                        <p class="text-[11px] font-medium mt-0.5 leading-none" style="color: var(--color-secundario); opacity: 0.8">{{ \App\Models\Configuracion::obtener('branding.tagline', 'Autos financiados') }}</p>
                    </div>
                </a>

                <nav class="flex items-center gap-5 text-sm text-slate-400" aria-label="Footer">
                    <a href="{{ $homeUrl }}" class="hover:text-white transition">Inicio</a>
                    <a href="{{ $catUrl }}" class="hover:text-white transition">Catálogo</a>
                    <a href="{{ $homeUrl }}#contacto" class="hover:text-white transition">Contacto</a>
                </nav>

                <div class="flex items-center gap-3">
                    @php
                        $instagram = \App\Models\Configuracion::obtener('contact.instagram', '');
                        $facebook  = \App\Models\Configuracion::obtener('contact.facebook', '');
                    @endphp
                    @if($instagram)
                    <a href="{{ $instagram }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"
                       class="h-8 w-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center text-slate-500 hover:text-pink-400 transition">
                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    @endif
                    @if($facebook)
                    <a href="{{ $facebook }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"
                       class="h-8 w-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center text-slate-500 hover:text-blue-400 transition">
                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    @endif
                    <p class="text-xs text-slate-600">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                </div>
            </div>
        </div>
    </footer>

    {{-- WhatsApp FAB --}}
    <div class="fixed bottom-6 right-6 z-50 group" role="complementary" aria-label="Contactar por WhatsApp">
        <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode($mensajeWa) }}"
           target="_blank" rel="noopener noreferrer"
           class="flex h-14 w-14 items-center justify-center rounded-full shadow-xl transition hover:scale-110 hover:opacity-90 active:scale-95"
           style="background-color: var(--color-secundario)">
            <svg class="h-7 w-7 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
        </a>
        <span class="pointer-events-none absolute right-full mr-3 top-1/2 -translate-y-1/2 whitespace-nowrap rounded-lg bg-slate-900 border border-white/10 px-3 py-1.5 text-xs font-semibold text-white opacity-0 transition group-hover:opacity-100" aria-hidden="true">
            Quiero este auto
        </span>
    </div>

</div>
