@php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

$whatsapp   = '5210000000000';
$homeUrl    = Route::has('public.home') ? route('public.home') : url('/');
$catalogoUrl = Route::has('public.autos') ? route('public.autos') : '#autos';

$waBase     = 'https://wa.me/' . $whatsapp . '?text=';
$waGeneral  = $waBase . urlencode('Hola, quiero información sobre los autos disponibles');
$waCotizar  = $waBase . urlencode('Hola, quiero cotizar un auto');
@endphp

@section('title', 'Inicio')
@section('meta_description', 'Encuentra tu auto ideal con planes de financiamiento accesibles. Sin complicaciones, respuesta en 24 horas. ' . config('app.name'))

{{-- ============================================================
     LANDING PAGE — diseño premium dark fintech automotriz
     ============================================================ --}}
<div class="bg-[#06091a] text-white overflow-x-hidden" style="background-color:#06091a">

    <x-public-navbar :whatsapp="$whatsapp" />

    {{-- ========================================================
         HERO
         ======================================================== --}}
    <section id="inicio" class="relative min-h-screen flex items-center overflow-hidden pt-[68px]"
             aria-labelledby="hero-heading">

        {{-- Background layers --}}
        <div class="absolute inset-0 bg-[#06091a] bg-dot-grid"></div>
        <div class="absolute -left-40 -top-40 h-[600px] w-[600px] rounded-full bg-blue-600/20 blur-[130px] pointer-events-none" aria-hidden="true"></div>
        <div class="absolute -right-20 bottom-0 h-[400px] w-[400px] rounded-full bg-emerald-600/15 blur-[100px] pointer-events-none" aria-hidden="true"></div>
        <div class="absolute right-1/4 top-1/3 h-[200px] w-[200px] rounded-full bg-blue-400/8 blur-[80px] pointer-events-none" aria-hidden="true"></div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">

            {{-- Left column: content --}}
            <div>
                {{-- Eyebrow badge --}}
                <div class="anim-d0 inline-flex items-center gap-2.5 rounded-full border border-emerald-500/25 bg-emerald-500/10 px-4 py-2 text-sm font-semibold text-emerald-300">
                    <span class="relative flex h-2 w-2" aria-hidden="true">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                    </span>
                    Autos disponibles hoy
                </div>

                {{-- Headline --}}
                <h1 id="hero-heading"
                    class="anim-d1 mt-6 text-5xl sm:text-6xl lg:text-[4.25rem] font-black leading-[0.93] tracking-tight">
                    Tu próximo auto.<br>
                    <span class="bg-gradient-to-r from-blue-400 via-emerald-300 to-emerald-400 bg-clip-text text-transparent">
                        Financiado.
                    </span>
                </h1>

                {{-- Description --}}
                <p class="anim-d2 mt-6 max-w-lg text-lg text-slate-300 leading-relaxed">
                    Explora nuestro inventario, conoce los planes de pago y cotiza en minutos. Sin letra chica, sin trámites complicados.
                </p>

                {{-- CTAs --}}
                <div class="anim-d3 mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="#autos"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-7 py-4 text-base font-bold text-slate-950 shadow-xl transition-colors duration-200 hover:bg-slate-100 active:scale-[0.97]">
                        Ver autos disponibles
                        <svg class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="{{ $waCotizar }}"
                       target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center justify-center gap-2.5 rounded-xl border border-white/15 bg-white/[0.07] px-7 py-4 text-base font-bold text-white backdrop-blur-sm transition-colors duration-200 hover:bg-white/12 active:scale-[0.97]">
                        <svg class="h-5 w-5 shrink-0 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        Cotizar por WhatsApp
                    </a>
                </div>

                {{-- Stats --}}
                <div class="anim-d4 mt-10 hidden sm:grid grid-cols-3 gap-3 max-w-lg">
                    @foreach([
                        ['value' => '200+', 'label' => 'Clientes atendidos'],
                        ['value' => '24h',  'label' => 'Respuesta garantizada'],
                        ['value' => '100%', 'label' => 'Proceso transparente'],
                    ] as $stat)
                    <div class="rounded-xl border border-white/[0.08] bg-white/[0.04] p-4">
                        <p class="text-2xl font-black tabular-nums">{{ $stat['value'] }}</p>
                        <p class="mt-1 text-xs text-slate-400 leading-snug">{{ $stat['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Right column: hero carousel --}}
            @if($heroAutos->count())
            <div
                x-data="{
                    active: 0,
                    total: {{ $heroAutos->count() }},
                    timer: null,
                    start() { this.timer = setInterval(() => this.next(), 4500) },
                    next() { this.active = (this.active + 1) % this.total },
                    prev() { this.active = (this.active - 1 + this.total) % this.total }
                }"
                x-init="start()"
                class="anim-d2 relative"
            >
                {{-- Glow halo --}}
                <div class="absolute -inset-6 rounded-[3rem] bg-blue-500/15 blur-3xl pointer-events-none" aria-hidden="true"></div>

                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/[0.04] shadow-2xl backdrop-blur-sm">
                    <div class="relative aspect-[16/10] lg:aspect-[4/3] max-h-[500px] overflow-hidden">

                        @foreach($heroAutos as $index => $heroAuto)
                        @php
                            $heroMarcaRaw  = $heroAuto->marca  ?? null;
                            $heroModeloRaw = $heroAuto->modelo ?? null;
                            $heroMarca  = is_object($heroMarcaRaw)  ? ($heroMarcaRaw->nombre  ?? $heroMarcaRaw->name  ?? '') : ($heroMarcaRaw  ?? '');
                            $heroModelo = is_object($heroModeloRaw) ? ($heroModeloRaw->nombre ?? $heroModeloRaw->name ?? '') : ($heroModeloRaw ?? '');
                            $heroTitulo = trim($heroMarca . ' ' . $heroModelo) ?: 'Auto disponible';
                            $heroImagen = $heroAuto->imagenPortada?->ruta ?? $heroAuto->imagenes?->first()?->ruta ?? null;
                            $heroImagenUrl = null;
                            if ($heroImagen) {
                                if (Str::startsWith($heroImagen, ['http://', 'https://']))    { $heroImagenUrl = $heroImagen; }
                                elseif (Str::startsWith($heroImagen, ['storage/']))            { $heroImagenUrl = asset($heroImagen); }
                                elseif (Str::startsWith($heroImagen, ['/storage/']))           { $heroImagenUrl = asset(ltrim($heroImagen, '/')); }
                                elseif (Str::startsWith($heroImagen, ['public/']))             { $heroImagenUrl = asset('storage/' . Str::after($heroImagen, 'public/')); }
                                else                                                            { $heroImagenUrl = asset('storage/' . $heroImagen); }
                            }
                            $heroPrecio = $heroAuto->precio_venta ?? $heroAuto->precio_contado ?? $heroAuto->precio_financiado ?? $heroAuto->precio ?? 0;
                        @endphp

                        @if($heroImagenUrl)
                        <div
                            x-show="active === {{ $index }}"
                            x-transition:enter="transition ease-out duration-700"
                            x-transition:enter-start="opacity-0 scale-[1.04]"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-400"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="absolute inset-0"
                        >
                            <img src="{{ $heroImagenUrl }}"
                                 alt="{{ $heroTitulo }}"
                                 width="1200" height="750"
                                 class="h-full w-full object-cover"
                                 loading="eager">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" aria-hidden="true"></div>

                            {{-- Car info overlay --}}
                            <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6">
                                <div class="flex items-end justify-between gap-4">
                                    <div class="min-w-0">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/90 backdrop-blur-sm px-3 py-1 text-xs font-bold text-white">
                                            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                                            </svg>
                                            Disponible
                                        </span>
                                        <h3 class="mt-2 text-2xl sm:text-3xl font-black text-white truncate drop-shadow">
                                            {{ $heroTitulo }}
                                        </h3>
                                        <p class="mt-1 text-sm text-slate-300">
                                            {{ $heroAuto->anio ?? '' }}
                                            @if(!empty($heroAuto->transmision)) · {{ ucfirst($heroAuto->transmision) }} @endif
                                        </p>
                                    </div>

                                    @if($heroPrecio)
                                    <div class="hidden sm:block shrink-0 rounded-xl bg-black/50 backdrop-blur-md border border-white/10 p-4 text-right">
                                        <p class="text-xs text-slate-400">Precio</p>
                                        <p class="text-xl font-black text-white tabular-nums">${{ number_format((float)$heroPrecio, 0) }}</p>
                                        <p class="mt-1 text-xs text-slate-400">{{ number_format((float)($heroAuto->kilometraje ?? 0)) }} km</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach

                        {{-- Prev button --}}
                        <button type="button" @click="prev()"
                                class="absolute left-3 top-1/2 -translate-y-1/2 h-10 w-10 sm:h-11 sm:w-11 rounded-full bg-black/40 backdrop-blur-sm text-white flex items-center justify-center border border-white/10 transition hover:bg-black/60 active:scale-90 z-10"
                                aria-label="Imagen anterior">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/>
                            </svg>
                        </button>

                        {{-- Next button --}}
                        <button type="button" @click="next()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 h-10 w-10 sm:h-11 sm:w-11 rounded-full bg-black/40 backdrop-blur-sm text-white flex items-center justify-center border border-white/10 transition hover:bg-black/60 active:scale-90 z-10"
                                aria-label="Imagen siguiente">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                            </svg>
                        </button>

                        {{-- Dots --}}
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-10" role="tablist" aria-label="Seleccionar imagen">
                            @foreach($heroAutos as $index => $dot)
                            <button type="button"
                                    @click="active = {{ $index }}"
                                    :class="active === {{ $index }} ? 'bg-white w-7' : 'bg-white/40 w-2'"
                                    class="h-2 rounded-full transition-all duration-300"
                                    :aria-selected="active === {{ $index }}"
                                    role="tab"
                                    aria-label="Imagen {{ $index + 1 }}">
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            @else
            {{-- Fallback placeholder --}}
            <div class="anim-d2 relative hidden lg:block">
                <div class="absolute -inset-6 rounded-[3rem] bg-blue-500/12 blur-3xl pointer-events-none" aria-hidden="true"></div>
                <div class="relative rounded-2xl border border-white/8 bg-white/[0.03] p-8 backdrop-blur-sm">
                    <div class="aspect-[4/3] rounded-xl border border-white/5 bg-gradient-to-br from-slate-800/40 to-slate-900/60 flex flex-col items-center justify-center text-center">
                        <div class="h-20 w-20 rounded-2xl bg-white/[0.07] flex items-center justify-center">
                            <svg class="h-10 w-10 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                            </svg>
                        </div>
                        <p class="mt-4 font-bold text-slate-300">Agrega fotos a tus autos</p>
                        <p class="mt-1 text-sm text-slate-500">El carrusel aparece automáticamente</p>
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Scroll cue --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 hidden md:flex flex-col items-center gap-2 text-slate-500 select-none" aria-hidden="true">
            <span class="text-[10px] font-semibold tracking-[0.22em] uppercase">Explorar</span>
            <svg class="h-5 w-5 animate-bounce" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z" clip-rule="evenodd"/>
            </svg>
        </div>
    </section>


    {{-- ========================================================
         TRUST METRICS BAR
         ======================================================== --}}
    <div class="border-y border-white/[0.06] bg-slate-950/80 backdrop-blur-sm" aria-label="Indicadores de confianza">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <div class="grid grid-cols-2 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-white/[0.06]">
                @foreach([
                    ['value' => '200+',  'label' => 'Autos entregados',      'icon' => '<path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',      'color' => 'text-emerald-400'],
                    ['value' => '24h',   'label' => 'Respuesta garantizada',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>','color' => 'text-blue-400'],
                    ['value' => '100%',  'label' => 'Proceso transparente',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>','color' => 'text-amber-400'],
                    ['value' => '0',     'label' => 'Letra chica',            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>', 'color' => 'text-violet-400'],
                ] as $metric)
                <div class="flex items-center gap-3 py-4 md:py-3 px-4 md:px-6 first:pl-4 last:pr-4">
                    <div class="shrink-0 h-10 w-10 rounded-xl bg-white/[0.06] flex items-center justify-center" aria-hidden="true">
                        <svg class="h-5 w-5 {{ $metric['color'] }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            {!! $metric['icon'] !!}
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-black tabular-nums leading-none">{{ $metric['value'] }}</p>
                        <p class="text-xs text-slate-400 mt-1 leading-snug">{{ $metric['label'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>


    {{-- ========================================================
         BENEFICIOS / FINANCIAMIENTO
         id="financiamiento"
         ======================================================== --}}
    <section id="financiamiento" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24" aria-labelledby="financiamiento-heading">

        <div class="text-center mb-14">
            <p class="text-xs font-semibold tracking-[0.22em] uppercase text-emerald-400">Por qué elegirnos</p>
            <h2 id="financiamiento-heading" class="mt-3 text-4xl md:text-5xl font-black tracking-tight">
                La forma más sencilla<br class="hidden sm:block">
                de tener tu auto
            </h2>
            <p class="mt-4 text-slate-400 max-w-xl mx-auto text-lg">
                Sin banco, sin burocracia. Financiamiento directo con nosotros.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                [
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>',
                    'color' => 'from-blue-600/30 to-blue-500/10',
                    'icon_color' => 'text-blue-400',
                    'title' => 'Inventario verificado',
                    'desc'  => 'Unidades en buen estado, con historial revisado. Lo que ves es lo que obtienes.',
                ],
                [
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>',
                    'color' => 'from-emerald-600/30 to-emerald-500/10',
                    'icon_color' => 'text-emerald-400',
                    'title' => 'Planes flexibles',
                    'desc'  => 'Enganche y mensualidades adaptadas a tu presupuesto. Cotiza sin compromiso.',
                ],
                [
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/>',
                    'color' => 'from-violet-600/30 to-violet-500/10',
                    'icon_color' => 'text-violet-400',
                    'title' => 'Atención directa',
                    'desc'  => 'Sin intermediarios. Hablas directo con nosotros por WhatsApp para resolver cualquier duda.',
                ],
            ] as $benefit)
            <div class="group relative overflow-hidden rounded-2xl border border-white/[0.08] bg-white/[0.03] p-7 transition-all duration-300 hover:border-white/[0.15] hover:bg-white/[0.05]">
                {{-- Gradient accent top --}}
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r {{ $benefit['color'] }}" aria-hidden="true"></div>

                <div class="h-14 w-14 rounded-xl bg-gradient-to-br {{ $benefit['color'] }} flex items-center justify-center border border-white/8">
                    <svg class="h-7 w-7 {{ $benefit['icon_color'] }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        {!! $benefit['icon'] !!}
                    </svg>
                </div>

                <h3 class="mt-5 text-xl font-bold text-white">{{ $benefit['title'] }}</h3>
                <p class="mt-2 text-slate-400 leading-relaxed">{{ $benefit['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>


    {{-- ========================================================
         CÓMO FUNCIONA — PROCESO
         id="proceso"
         ======================================================== --}}
    <section id="proceso" class="relative overflow-hidden py-24" aria-labelledby="proceso-heading">

        {{-- Section background --}}
        <div class="absolute inset-0 bg-slate-950/60" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-dot-grid opacity-50" aria-hidden="true"></div>
        <div class="absolute left-1/4 top-0 h-[300px] w-[300px] rounded-full bg-blue-600/10 blur-[100px] pointer-events-none" aria-hidden="true"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <p class="text-xs font-semibold tracking-[0.22em] uppercase text-emerald-400">Proceso</p>
                <h2 id="proceso-heading" class="mt-3 text-4xl md:text-5xl font-black tracking-tight">
                    Tu auto en 4 pasos
                </h2>
                <p class="mt-4 text-slate-400 max-w-xl mx-auto">
                    Sin papeleo complicado. Sin esperas largas. Con acompañamiento en cada etapa.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    [
                        'num'   => '01',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>',
                        'title' => 'Elige tu auto',
                        'desc'  => 'Explora el catálogo y encuentra el auto que se adapta a ti.',
                        'color' => 'from-blue-600 to-blue-400',
                    ],
                    [
                        'num'   => '02',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/>',
                        'title' => 'Cotiza en WhatsApp',
                        'desc'  => 'Escríbenos y recibe tu plan de pagos en minutos.',
                        'color' => 'from-emerald-600 to-emerald-400',
                    ],
                    [
                        'num'   => '03',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>',
                        'title' => 'Presenta documentos',
                        'desc'  => 'Solo los básicos. Te guiamos en cada paso del trámite.',
                        'color' => 'from-amber-600 to-amber-400',
                    ],
                    [
                        'num'   => '04',
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>',
                        'title' => 'Estrena tu auto',
                        'desc'  => 'Entrega rápida. Tu auto listo en días, no en meses.',
                        'color' => 'from-violet-600 to-violet-400',
                    ],
                ] as $i => $step)
                <div class="relative">
                    {{-- Connector line (desktop only) --}}
                    @if($i < 3)
                    <div class="absolute top-[28px] left-[calc(50%+36px)] hidden lg:block w-[calc(100%-72px)] h-px bg-gradient-to-r from-white/15 to-white/5" aria-hidden="true"></div>
                    @endif

                    <div class="relative z-10 rounded-2xl border border-white/[0.08] bg-white/[0.03] p-6 text-center transition-all duration-300 hover:border-white/[0.15] hover:bg-white/[0.05]">
                        {{-- Step number badge --}}
                        <div class="mx-auto h-14 w-14 rounded-xl bg-gradient-to-br {{ $step['color'] }} flex items-center justify-center shadow-lg">
                            <svg class="h-7 w-7 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                {!! $step['icon'] !!}
                            </svg>
                        </div>

                        <div class="mt-1 text-[10px] font-black tracking-widest text-slate-600 uppercase">Paso {{ $step['num'] }}</div>
                        <h3 class="mt-3 text-lg font-bold text-white">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm text-slate-400 leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- ========================================================
         AUTOS DISPONIBLES
         id="autos"
         ======================================================== --}}
    <section id="autos" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24" aria-labelledby="autos-heading">

        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-12">
            <div>
                <p class="text-xs font-semibold tracking-[0.22em] uppercase text-emerald-400">Inventario</p>
                <h2 id="autos-heading" class="mt-3 text-4xl md:text-5xl font-black tracking-tight">Autos disponibles</h2>
                <p class="mt-3 text-slate-400 max-w-xl leading-relaxed">
                    Unidades listas para cotizar. Escríbenos para conocer el plan que más te conviene.
                </p>
            </div>
            <a href="{{ $catalogoUrl }}"
               class="shrink-0 inline-flex items-center justify-center gap-2 rounded-xl border border-white/15 bg-white/[0.06] px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-white/10 active:scale-[0.97]">
                Ver catálogo completo
                <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/>
                </svg>
            </a>
        </div>

        @if($autosDestacados->count())
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($autosDestacados as $auto)
            @php
                $marcaRaw    = $auto->marca   ?? null;
                $modeloRaw   = $auto->modelo  ?? null;
                $versionRaw  = $auto->version ?? null;
                $marcaNombre   = is_object($marcaRaw)   ? ($marcaRaw->nombre   ?? $marcaRaw->name   ?? null) : $marcaRaw;
                $modeloNombre  = is_object($modeloRaw)  ? ($modeloRaw->nombre  ?? $modeloRaw->name  ?? null) : $modeloRaw;
                $versionNombre = is_object($versionRaw) ? ($versionRaw->nombre ?? $versionRaw->name ?? null) : $versionRaw;
                $tituloAuto = trim(($marcaNombre ?: 'Marca') . ' ' . ($modeloNombre ?: 'Modelo'));
                $precioContado   = (float)($auto->precio_contado   ?? $auto->precio_venta ?? $auto->precio ?? 0);
                $precioFinanciado = (float)($auto->precio_financiado ?? 0);
                $precioMostrar   = $precioContado ?: $precioFinanciado;
                $imagen = $auto->imagenPortada?->ruta ?? null;
                $imagenUrl = null;
                if ($imagen) {
                    if (Str::startsWith($imagen, ['http://', 'https://']))    { $imagenUrl = $imagen; }
                    elseif (Str::startsWith($imagen, ['storage/']))            { $imagenUrl = asset($imagen); }
                    elseif (Str::startsWith($imagen, ['/storage/']))           { $imagenUrl = asset(ltrim($imagen, '/')); }
                    elseif (Str::startsWith($imagen, ['public/']))             { $imagenUrl = asset('storage/' . Str::after($imagen, 'public/')); }
                    else                                                        { $imagenUrl = asset('storage/' . $imagen); }
                }
                $mensajeWa = $waBase . urlencode('Hola, me interesa el ' . $tituloAuto . ' ' . ($auto->anio ?? ''));
                $detalleUrl = route('public.autos.show', $auto->uuid);
            @endphp

            <article class="group overflow-hidden rounded-2xl border border-white/[0.08] bg-[#0e1725] card-hover-glow" aria-label="{{ $tituloAuto }}">

                {{-- Image — clickeable al detalle --}}
                <a href="{{ $detalleUrl }}" class="block relative aspect-[16/10] overflow-hidden bg-slate-900" tabindex="-1" aria-hidden="true">
                    @if($imagenUrl)
                    <img src="{{ $imagenUrl }}"
                         alt="{{ $tituloAuto }}"
                         width="800" height="500"
                         class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                         loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0e1725]/70 via-transparent to-transparent" aria-hidden="true"></div>
                    @else
                    <div class="h-full w-full bg-gradient-to-br from-slate-800 to-slate-900 flex flex-col items-center justify-center">
                        <svg class="h-12 w-12 text-slate-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                        </svg>
                        <p class="mt-3 text-sm font-medium text-slate-500">Sin imagen</p>
                    </div>
                    @endif

                    {{-- Badges --}}
                    <div class="absolute left-3 top-3 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/90 backdrop-blur-sm px-3 py-1 text-xs font-bold text-white shadow">
                            <span class="h-1.5 w-1.5 rounded-full bg-white" aria-hidden="true"></span>
                            Disponible
                        </span>
                        @if(!empty($auto->destacado))
                        <span class="rounded-full bg-amber-400/90 backdrop-blur-sm px-3 py-1 text-xs font-bold text-slate-950 shadow">
                            Destacado
                        </span>
                        @endif
                    </div>
                </a>

                {{-- Content --}}
                <div class="p-5">
                    <p class="text-xs text-slate-500 font-medium">
                        {{ $auto->anio ?? '' }}
                        @if($versionNombre) · {{ $versionNombre }} @endif
                    </p>
                    <a href="{{ $detalleUrl }}" class="block mt-1 group/title">
                        <h3 class="text-xl font-bold text-white leading-tight group-hover/title:text-blue-300 transition-colors">
                            {{ $tituloAuto }}
                        </h3>
                    </a>

                    {{-- Price block --}}
                    <div class="mt-5 flex items-end justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-semibold tracking-[0.15em] uppercase text-slate-500">
                                @if($precioContado) Contado @else Precio @endif
                            </p>
                            <p class="text-2xl font-black text-white tabular-nums leading-tight">
                                ${{ number_format($precioMostrar, 0) }}
                            </p>
                            @if($precioFinanciado > 0 && $precioFinanciado !== $precioContado)
                            <p class="mt-1 text-sm text-emerald-400 font-semibold">
                                Financiado: ${{ number_format($precioFinanciado, 0) }}
                            </p>
                            @endif
                        </div>

                        <div class="shrink-0 rounded-xl bg-white/[0.06] border border-white/[0.07] px-3 py-2 text-right">
                            <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wide">Km</p>
                            <p class="text-sm font-bold text-slate-200 tabular-nums">{{ number_format((float)($auto->kilometraje ?? 0)) }}</p>
                        </div>
                    </div>

                    {{-- Specs chips --}}
                    @if($auto->transmision || $auto->color || $auto->tipo_combustible)
                    <div class="mt-4 flex flex-wrap gap-2" aria-label="Especificaciones">
                        @if($auto->transmision)
                        <span class="rounded-lg bg-white/[0.06] border border-white/[0.07] px-3 py-1.5 text-xs text-slate-300">
                            {{ ucfirst($auto->transmision) }}
                        </span>
                        @endif
                        @if($auto->color)
                        <span class="rounded-lg bg-white/[0.06] border border-white/[0.07] px-3 py-1.5 text-xs text-slate-300">
                            {{ ucfirst($auto->color) }}
                        </span>
                        @endif
                        @if($auto->tipo_combustible)
                        <span class="rounded-lg bg-white/[0.06] border border-white/[0.07] px-3 py-1.5 text-xs text-slate-300">
                            {{ ucfirst($auto->tipo_combustible) }}
                        </span>
                        @endif
                    </div>
                    @endif

                    {{-- CTAs --}}
                    <div class="mt-5 flex gap-2">
                        <a href="{{ $detalleUrl }}"
                           class="flex-1 flex items-center justify-center gap-1.5 rounded-xl border border-white/[0.12] bg-white/[0.05] px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10 active:scale-[0.97]">
                            <svg class="h-4 w-4 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/>
                                <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            Ver detalles
                        </a>
                        <a href="{{ $mensajeWa }}"
                           target="_blank" rel="noopener noreferrer"
                           class="flex-1 flex items-center justify-center gap-1.5 rounded-xl bg-emerald-500 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-400 active:scale-[0.97]"
                           aria-label="Preguntar por {{ $tituloAuto }} por WhatsApp">
                            <svg class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            WhatsApp
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        {{-- View all link (mobile) --}}
        <div class="mt-10 text-center md:hidden">
            <a href="{{ $catalogoUrl }}"
               class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/[0.06] px-6 py-3.5 text-sm font-semibold text-white transition hover:bg-white/10">
                Ver catálogo completo
                <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/>
                </svg>
            </a>
        </div>

        @else
        {{-- Empty state --}}
        <div class="rounded-2xl border border-dashed border-white/[0.1] bg-white/[0.02] p-16 text-center">
            <div class="mx-auto h-16 w-16 rounded-2xl bg-white/[0.06] flex items-center justify-center">
                <svg class="h-8 w-8 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                </svg>
            </div>
            <h3 class="mt-5 text-xl font-bold text-white">Pronto habrá autos disponibles</h3>
            <p class="mt-2 text-slate-400">Registra autos en el sistema y aparecerán aquí automáticamente.</p>
        </div>
        @endif
    </section>


    {{-- ========================================================
         CTA BANNER — MID-PAGE CONVERSION
         ======================================================== --}}
    <section class="relative overflow-hidden py-20 sm:py-28" aria-labelledby="cta-heading">
        {{-- Background --}}
        <div class="absolute inset-0 bg-gradient-to-br from-[#06091a] via-blue-950/40 to-[#06091a]" aria-hidden="true"></div>
        <div class="absolute inset-0" style="background: radial-gradient(ellipse 80% 60% at 50% 50%, rgba(16,185,129,.12) 0%, transparent 70%)" aria-hidden="true"></div>
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent" aria-hidden="true"></div>
        <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent" aria-hidden="true"></div>

        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 text-center">
            <p class="text-xs font-semibold tracking-[0.22em] uppercase text-emerald-400">¿Listo para empezar?</p>
            <h2 id="cta-heading" class="mt-4 text-4xl sm:text-5xl font-black tracking-tight">
                Empieza hoy.<br class="hidden sm:block">
                <span class="text-slate-400">Sin compromiso.</span>
            </h2>
            <p class="mt-5 text-lg text-slate-300 leading-relaxed">
                Más de 200 familias ya eligieron su auto con nosotros. Cotiza en minutos, estrena pronto.
            </p>

            {{-- Trust badges --}}
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                @foreach(['Sin buró', 'Enganche desde 10%', 'Plazos hasta 36 meses', 'Proceso en días'] as $badge)
                <span class="inline-flex items-center gap-1.5 rounded-full border border-white/10 bg-white/[0.06] px-4 py-1.5 text-sm text-slate-300">
                    <svg class="h-3.5 w-3.5 shrink-0 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                    </svg>
                    {{ $badge }}
                </span>
                @endforeach
            </div>

            <div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="#autos"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-8 py-4 text-base font-bold text-slate-950 shadow-xl transition hover:bg-slate-100 active:scale-[0.97]">
                    Ver autos disponibles
                </a>
                <a href="{{ $waGeneral }}"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-500 px-8 py-4 text-base font-bold text-white shadow-xl shadow-emerald-900/30 transition hover:bg-emerald-400 active:scale-[0.97]">
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    Cotizar ahora
                </a>
            </div>
        </div>
    </section>


    {{-- ========================================================
         CONTACTO
         id="contacto"
         ======================================================== --}}
    <section id="contacto" class="relative overflow-hidden bg-slate-950 py-24" aria-labelledby="contacto-heading">

        <div class="absolute inset-0 bg-dot-grid opacity-40" aria-hidden="true"></div>
        <div class="absolute right-0 top-0 h-[350px] w-[350px] rounded-full bg-emerald-600/10 blur-[100px] pointer-events-none" aria-hidden="true"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

                {{-- Left: info + WA buttons --}}
                <div>
                    <p class="text-xs font-semibold tracking-[0.22em] uppercase text-emerald-400">Contacto</p>
                    <h2 id="contacto-heading" class="mt-3 text-4xl md:text-5xl font-black tracking-tight">
                        ¿Tienes dudas?<br>
                        <span class="text-slate-400">Te ayudamos.</span>
                    </h2>
                    <p class="mt-5 text-lg text-slate-300 leading-relaxed max-w-md">
                        Escríbenos y con gusto te asesoramos sobre disponibilidad, planes de pago y más.
                    </p>

                    {{-- Contact info cards --}}
                    <div class="mt-8 space-y-3">
                        <div class="flex items-center gap-4 rounded-xl border border-white/[0.08] bg-white/[0.03] p-4">
                            <div class="shrink-0 h-11 w-11 rounded-xl bg-emerald-500/15 flex items-center justify-center" aria-hidden="true">
                                <svg class="h-5 w-5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">WhatsApp</p>
                                <p class="text-base font-bold text-white">+52 1 000 000 0000</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 rounded-xl border border-white/[0.08] bg-white/[0.03] p-4">
                            <div class="shrink-0 h-11 w-11 rounded-xl bg-blue-500/15 flex items-center justify-center" aria-hidden="true">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Horario</p>
                                <p class="text-base font-bold text-white">Lun–Sáb · 9:00 AM – 7:00 PM</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 rounded-xl border border-white/[0.08] bg-white/[0.03] p-4">
                            <div class="shrink-0 h-11 w-11 rounded-xl bg-violet-500/15 flex items-center justify-center" aria-hidden="true">
                                <svg class="h-5 w-5 text-violet-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Ubicación</p>
                                {{-- Actualiza esta dirección con la del negocio --}}
                                <p class="text-base font-bold text-white">Tu Ciudad, Estado</p>
                            </div>
                        </div>
                    </div>

                    {{-- WA action buttons --}}
                    <div class="mt-6 space-y-3">
                        <a href="{{ $waGeneral }}"
                           target="_blank" rel="noopener noreferrer"
                           class="flex w-full items-center gap-4 rounded-xl bg-emerald-500 px-6 py-4 font-bold text-white transition hover:bg-emerald-400 active:scale-[0.98] group">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-white/20 flex items-center justify-center" aria-hidden="true">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold leading-none">Información general</p>
                                <p class="mt-0.5 text-sm text-emerald-100 font-normal">Sobre autos disponibles</p>
                            </div>
                            <svg class="h-5 w-5 ml-auto shrink-0 transition group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/>
                            </svg>
                        </a>

                        <a href="{{ $waCotizar }}"
                           target="_blank" rel="noopener noreferrer"
                           class="flex w-full items-center gap-4 rounded-xl border border-white/[0.1] bg-white/[0.05] px-6 py-4 font-bold text-white transition hover:bg-white/10 active:scale-[0.98] group">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-white/10 flex items-center justify-center" aria-hidden="true">
                                <svg class="h-5 w-5 text-slate-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold leading-none">Cotizar mi auto</p>
                                <p class="mt-0.5 text-sm text-slate-400 font-normal">Planes de financiamiento</p>
                            </div>
                            <svg class="h-5 w-5 ml-auto shrink-0 text-slate-500 transition group-hover:translate-x-1 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Right: Mapa --}}
                <div class="rounded-2xl overflow-hidden border border-white/[0.08] bg-slate-900 h-full min-h-[420px] lg:min-h-[520px]">
                    {{--
                        INSTRUCCIONES PARA EL MAPA:
                        1. Ve a Google Maps y busca la dirección del negocio
                        2. Haz clic en "Compartir" → "Insertar mapa"
                        3. Copia la URL del atributo src del iframe y pégala abajo
                        El formato del src es: https://www.google.com/maps/embed?pb=...
                    --}}
                    <iframe
                        {{-- Reemplaza este src con el embed de Google Maps de tu dirección --}}
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d120000!2d-99.1332!3d19.4326!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTnCsDI1JzU3LjQiTiA5OcKwMDcnNTkuNSJX!5e0!3m2!1ses!2smx!4v1700000000000"
                        width="100%"
                        height="100%"
                        style="border:0; min-height: 420px; filter: grayscale(30%) invert(5%) contrast(110%) brightness(90%);"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Ubicación del negocio"
                        aria-label="Mapa con la ubicación del negocio">
                    </iframe>
                </div>

            </div>
        </div>
    </section>


    {{-- ========================================================
         FOOTER
         ======================================================== --}}
    <footer class="bg-[#04070f] border-t border-white/[0.06]" role="contentinfo">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Main footer grid --}}
            <div class="py-14 grid grid-cols-1 md:grid-cols-3 gap-10 lg:gap-16">

                {{-- Col 1: Brand --}}
                <div class="md:col-span-1">
                    <a href="{{ $homeUrl }}" class="inline-flex items-center gap-3" aria-label="{{ config('app.name') }}">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-600 to-emerald-500 flex items-center justify-center shrink-0" aria-hidden="true">
                            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/>
                                <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/>
                                <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-black text-white text-[15px] leading-none">{{ config('app.name', 'AutoLote') }}</p>
                            <p class="text-xs text-emerald-400/70 mt-0.5">Autos financiados</p>
                        </div>
                    </a>
                    <p class="mt-4 text-sm text-slate-500 leading-relaxed max-w-xs">
                        Financiamiento directo, sin banco ni burocracia. Tu próximo auto más cerca de lo que crees.
                    </p>
                    {{-- WhatsApp contact --}}
                    <a href="{{ $waGeneral }}" target="_blank" rel="noopener noreferrer"
                       class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-emerald-400 hover:text-emerald-300 transition">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        Escríbenos por WhatsApp
                    </a>
                </div>

                {{-- Col 2: Navegación --}}
                <div>
                    <p class="text-[11px] font-semibold tracking-[0.18em] uppercase text-slate-600 mb-4">Navegación</p>
                    <nav class="space-y-2.5" aria-label="Navegación del pie de página">
                        @foreach([
                            ['label' => 'Inicio',         'href' => $homeUrl],
                            ['label' => 'Ver autos',      'href' => $catalogoUrl],
                            ['label' => 'Financiamiento', 'href' => $homeUrl . '#financiamiento'],
                            ['label' => 'Proceso',        'href' => $homeUrl . '#proceso'],
                            ['label' => 'Contacto',       'href' => $homeUrl . '#contacto'],
                        ] as $link)
                        <a href="{{ $link['href'] }}"
                           class="block text-sm text-slate-400 hover:text-white transition-colors">
                            {{ $link['label'] }}
                        </a>
                        @endforeach
                    </nav>
                </div>

                {{-- Col 3: Contacto --}}
                <div>
                    <p class="text-[11px] font-semibold tracking-[0.18em] uppercase text-slate-600 mb-4">Contacto</p>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li class="flex items-start gap-2.5">
                            <svg class="h-4 w-4 text-slate-600 mt-0.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd"/>
                            </svg>
                            {{-- Actualiza con la dirección real del negocio --}}
                            <span>Tu Ciudad, Estado, México</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="h-4 w-4 text-slate-600 mt-0.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd"/>
                            </svg>
                            <span>Lun–Sáb · 9:00 AM – 7:00 PM</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg class="h-4 w-4 text-slate-600 mt-0.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            <span>+52 1 000 000 0000</span>
                        </li>
                    </ul>
                </div>

            </div>

            {{-- Bottom bar --}}
            <div class="border-t border-white/[0.05] py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-slate-600">
                    &copy; {{ date('Y') }} {{ config('app.name', 'AutoLote') }}. Todos los derechos reservados.
                </p>
                <p class="text-xs text-slate-700">
                    Venta y financiamiento de autos · México
                </p>
            </div>

        </div>
    </footer>


    {{-- ========================================================
         WHATSAPP FLOATING ACTION BUTTON
         ======================================================== --}}
    <a href="{{ $waGeneral }}"
       target="_blank" rel="noopener noreferrer"
       aria-label="Contactar por WhatsApp"
       class="group fixed bottom-6 right-6 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-500 text-white shadow-xl shadow-emerald-900/50 transition-all duration-300 hover:scale-110 hover:bg-emerald-400 hover:shadow-emerald-900/60 active:scale-100"
    >
        <svg class="h-7 w-7 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>

        {{-- Tooltip --}}
        <span class="pointer-events-none absolute right-full mr-3 whitespace-nowrap rounded-lg border border-white/10 bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white opacity-0 shadow-xl transition-opacity group-hover:opacity-100" role="tooltip">
            Cotizar ahora
        </span>
    </a>

</div>
