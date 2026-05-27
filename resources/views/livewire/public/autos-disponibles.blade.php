@php
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

$whatsapp = \App\Models\Configuracion::obtener('contact.whatsapp', '5210000000000');
$homeUrl  = Route::has('public.home') ? route('public.home') : url('/');
$logoUrl  = \App\Models\Configuracion::obtener('branding.logo_url', '');
@endphp

@section('title', 'Catálogo de Autos')
@section('meta_description', 'Explora todos los autos disponibles con financiamiento accesible. Filtra por marca, transmisión y precio. ' . config('app.name'))

<div class="bg-[#06091a] text-white overflow-x-hidden min-h-screen" style="background-color:#06091a">

    <x-public-navbar :whatsapp="$whatsapp" />

    {{-- ======================================================
         HERO
         ====================================================== --}}
    <section class="relative overflow-hidden pt-[68px]" aria-label="Encabezado catálogo">
        <div class="absolute inset-0 bg-[#06091a] bg-dot-grid"></div>
        <div class="absolute -left-32 top-0 h-[500px] w-[500px] rounded-full bg-blue-600/20 blur-[120px] pointer-events-none" aria-hidden="true"></div>
        <div class="absolute right-0 bottom-0 h-[300px] w-[400px] rounded-full bg-emerald-600/12 blur-[100px] pointer-events-none" aria-hidden="true"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-slate-400 mb-6" aria-label="Migas de pan">
                <a href="{{ $homeUrl }}" class="hover:text-white transition">Inicio</a>
                <svg class="h-3.5 w-3.5 text-slate-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                <span class="text-white font-medium">Catálogo</span>
            </nav>

            <div class="max-w-3xl">
                <p class="text-xs uppercase tracking-[0.25em] text-emerald-400 font-semibold mb-3">
                    Inventario disponible
                </p>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black leading-tight">
                    Encuentra tu próximo
                    <span class="bg-clip-text text-transparent"
                          style="background-image: linear-gradient(to right, var(--color-primario), var(--color-secundario))"> auto</span>
                </h1>
                <p class="mt-4 text-slate-400 text-lg leading-relaxed">
                    Todos los autos vienen con opción de financiamiento. Filtra, compara y contáctanos.
                </p>
            </div>
        </div>
    </section>

    {{-- ======================================================
         CONTENIDO PRINCIPAL
         ====================================================== --}}
    @php
        $filtrosActivos = collect([$search ?? '', $marca ?? '', $transmision ?? '', $precioMin ?? '', $precioMax ?? '', $kmMin ?? '', $kmMax ?? '', $anio ?? '', $color ?? ''])->filter(fn($v) => $v !== '' && $v !== null)->count();
    @endphp

    <style>
    /* Quita las flechas de los inputs number en los filtros */
    .filtro-number::-webkit-outer-spin-button,
    .filtro-number::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .filtro-number { -moz-appearance: textfield; }
    </style>

    <main
        x-data="{ filtrosAbiertos: window.innerWidth >= 1024 }"
        class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">

        {{-- BARRA MÓVIL: toggle filtros --}}
        <div class="lg:hidden flex items-center justify-between gap-3 mb-4">
            <button
                @click="filtrosAbiertos = !filtrosAbiertos"
                type="button"
                class="inline-flex items-center gap-2 rounded-xl border border-white/[0.1] bg-slate-900/80 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/[0.08]">
                <svg class="h-4 w-4 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 0 1 .628.74v2.288a2.25 2.25 0 0 1-.659 1.59l-4.682 4.683a2.25 2.25 0 0 0-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 0 1 8 18.25v-5.757a2.25 2.25 0 0 0-.659-1.591L2.659 6.22A2.25 2.25 0 0 1 2 4.629V2.34a.75.75 0 0 1 .628-.74Z" clip-rule="evenodd"/>
                </svg>
                Filtros
                @if($filtrosActivos > 0)
                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-[10px] font-bold text-white">
                    {{ $filtrosActivos }}
                </span>
                @endif
                <svg :class="filtrosAbiertos ? 'rotate-180' : ''" class="h-4 w-4 text-slate-500 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                </svg>
            </button>

            <p class="text-sm text-slate-400">
                <span class="text-emerald-400 font-semibold tabular-nums">{{ $autos->total() }}</span>
                resultado{{ $autos->total() !== 1 ? 's' : '' }}
            </p>
        </div>

        <div class="lg:grid lg:grid-cols-[256px_1fr] lg:gap-8 lg:items-start">

        {{-- ── SIDEBAR FILTROS ──────────────────────────── --}}
        <aside
            x-show="filtrosAbiertos"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="mb-6 lg:mb-0 lg:sticky lg:top-[calc(68px+1.5rem)]"
            aria-label="Filtros de búsqueda">

            <div class="rounded-2xl border border-white/[0.14] bg-[#0c1220] shadow-2xl shadow-black/50 overflow-hidden" style="border-left: 2px solid rgba(59,130,246,0.5)">

                {{-- Header sidebar --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-white/[0.08] bg-blue-500/[0.06]">
                    <div class="flex items-center gap-2.5">
                        <svg class="h-4 w-4 text-blue-400 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 0 1 .628.74v2.288a2.25 2.25 0 0 1-.659 1.59l-4.682 4.683a2.25 2.25 0 0 0-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 0 1 8 18.25v-5.757a2.25 2.25 0 0 0-.659-1.591L2.659 6.22A2.25 2.25 0 0 1 2 4.629V2.34a.75.75 0 0 1 .628-.74Z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-bold text-white">Filtros</span>
                        @if($filtrosActivos > 0)
                        <span class="inline-flex h-5 px-1.5 items-center justify-center rounded-full bg-blue-600 text-[10px] font-bold text-white">
                            {{ $filtrosActivos }}
                        </span>
                        @endif
                    </div>
                    @if($filtrosActivos > 0)
                    <button wire:click="limpiarFiltros" type="button"
                        class="text-xs font-semibold text-slate-400 hover:text-white transition">
                        Limpiar
                    </button>
                    @endif
                </div>

                <div class="p-5 space-y-5">

                    {{-- SEARCH --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Buscar</label>
                        <div class="relative">
                            <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd"/></svg>
                            <input
                                type="search"
                                wire:model.live.debounce.400ms="search"
                                placeholder="Marca, modelo, año..."
                                class="w-full rounded-xl border border-white/[0.14] bg-slate-800 pl-9 pr-3 py-2.5 text-sm text-white placeholder-slate-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/40 focus:outline-none transition">
                        </div>
                    </div>

                    {{-- MARCA --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Marca</label>
                        <select wire:model.live="marca"
                            class="w-full rounded-xl border border-white/[0.14] bg-slate-800 py-2.5 px-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/40 focus:outline-none transition">
                            <option value="" class="bg-slate-900">Todas las marcas</option>
                            @foreach($marcas as $id => $nombre)
                            <option value="{{ $id }}" class="bg-slate-900">{{ $nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- AÑO --}}
                    @if($anios->isNotEmpty())
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Año</label>
                        <select wire:model.live="anio"
                            class="w-full rounded-xl border border-white/[0.14] bg-slate-800 py-2.5 px-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/40 focus:outline-none transition">
                            <option value="" class="bg-slate-900">Todos los años</option>
                            @foreach($anios as $a)
                            <option value="{{ $a }}" class="bg-slate-900">{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- TRANSMISION --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Transmisión</label>
                        <select wire:model.live="transmision"
                            class="w-full rounded-xl border border-white/[0.14] bg-slate-800 py-2.5 px-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/40 focus:outline-none transition">
                            <option value="" class="bg-slate-900">Todas</option>
                            <option value="automatica" class="bg-slate-900">Automática</option>
                            <option value="manual" class="bg-slate-900">Manual</option>
                        </select>
                    </div>

                    {{-- COLOR --}}
                    @if($colores->isNotEmpty())
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1.5">Color</label>
                        <select wire:model.live="color"
                            class="w-full rounded-xl border border-white/[0.14] bg-slate-800 py-2.5 px-3 text-sm text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500/40 focus:outline-none transition">
                            <option value="" class="bg-slate-900">Todos los colores</option>
                            @foreach($colores as $c)
                            <option value="{{ $c }}" class="bg-slate-900">{{ ucfirst($c) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- PRECIO --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-semibold text-slate-400">Precio</label>
                            @if($precioMin || $precioMax)
                            <span class="text-[11px] text-blue-400 tabular-nums">
                                @if($precioMin && $precioMax)
                                    ${{ number_format((int)$precioMin) }} – ${{ number_format((int)$precioMax) }}
                                @elseif($precioMin)
                                    desde ${{ number_format((int)$precioMin) }}
                                @else
                                    hasta ${{ number_format((int)$precioMax) }}
                                @endif
                            </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <span class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs">$</span>
                                <input type="number" wire:model.live.debounce.600ms="precioMin"
                                       placeholder="Mínimo" min="0"
                                       class="filtro-number w-full rounded-xl border border-white/[0.14] bg-slate-800 pl-5 pr-2 py-2.5 text-sm text-white placeholder-slate-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/40 focus:outline-none transition">
                            </div>
                            <span class="text-slate-600 shrink-0">–</span>
                            <div class="relative flex-1">
                                <span class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-500 text-xs">$</span>
                                <input type="number" wire:model.live.debounce.600ms="precioMax"
                                       placeholder="Máximo" min="0"
                                       class="filtro-number w-full rounded-xl border border-white/[0.14] bg-slate-800 pl-5 pr-2 py-2.5 text-sm text-white placeholder-slate-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/40 focus:outline-none transition">
                            </div>
                        </div>
                    </div>

                    {{-- KILOMETRAJE --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-semibold text-slate-400">Kilometraje</label>
                            @if($kmMin || $kmMax)
                            <span class="text-[11px] text-blue-400 tabular-nums">
                                @if($kmMin && $kmMax)
                                    {{ number_format((int)$kmMin) }} – {{ number_format((int)$kmMax) }} km
                                @elseif($kmMin)
                                    desde {{ number_format((int)$kmMin) }} km
                                @else
                                    hasta {{ number_format((int)$kmMax) }} km
                                @endif
                            </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="number" wire:model.live.debounce.600ms="kmMin"
                                   placeholder="Mín km" min="0"
                                   class="filtro-number flex-1 w-full rounded-xl border border-white/[0.14] bg-slate-800 px-3 py-2.5 text-sm text-white placeholder-slate-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/40 focus:outline-none transition">
                            <span class="text-slate-600 shrink-0">–</span>
                            <input type="number" wire:model.live.debounce.600ms="kmMax"
                                   placeholder="Máx km" min="0"
                                   class="filtro-number flex-1 w-full rounded-xl border border-white/[0.14] bg-slate-800 px-3 py-2.5 text-sm text-white placeholder-slate-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/40 focus:outline-none transition">
                        </div>
                    </div>

                </div>
            </div>
        </aside>

        {{-- ── COLUMNA RESULTADOS ───────────────────────── --}}
        <div class="min-w-0">

        {{-- RESULTADOS HEADER --}}
        <div class="hidden lg:flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-white">Autos disponibles</h2>
                <p class="text-sm text-slate-400 mt-0.5">
                    <span class="text-emerald-400 font-semibold tabular-nums">{{ $autos->total() }}</span>
                    resultado{{ $autos->total() !== 1 ? 's' : '' }} encontrado{{ $autos->total() !== 1 ? 's' : '' }}
                </p>
            </div>
            <div wire:loading wire:target="search,marca,transmision,precioMin,precioMax,kmMin,kmMax,anio,color,limpiarFiltros"
                 class="flex items-center gap-2 text-sm text-slate-400">
                <svg class="h-4 w-4 animate-spin text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Buscando...
            </div>
        </div>

        {{-- GRID DE AUTOS --}}
        @if($autos->count())

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6" wire:loading.class="opacity-60 pointer-events-none" wire:target="search,marca,transmision,precioMin,precioMax,kmMin,kmMax,anio,color,limpiarFiltros">

            @foreach($autos as $auto)
            @php
                $marcaNombre  = $auto->marca?->nombre  ?? 'Marca';
                $modeloNombre = $auto->modelo?->nombre ?? 'Modelo';
                $tituloAuto   = trim($marcaNombre . ' ' . $modeloNombre);

                $imagen = $auto->imagenPortada?->ruta ?? $auto->imagenes?->first()?->ruta ?? null;

                $imagenUrl = null;
                if ($imagen) {
                    if (Str::startsWith($imagen, ['http://', 'https://'])) {
                        $imagenUrl = $imagen;
                    } elseif (Str::startsWith($imagen, ['/storage/'])) {
                        $imagenUrl = asset(ltrim($imagen, '/'));
                    } elseif (Str::startsWith($imagen, ['storage/'])) {
                        $imagenUrl = asset($imagen);
                    } elseif (Str::startsWith($imagen, ['public/'])) {
                        $imagenUrl = asset('storage/' . Str::after($imagen, 'public/'));
                    } else {
                        $imagenUrl = asset('storage/' . $imagen);
                    }
                }

                $mensajeWa = 'Hola, me interesa el ' . $tituloAuto . ($auto->anio ? ' ' . $auto->anio : '');
            @endphp

            <article class="group overflow-hidden rounded-2xl border border-white/[0.07] bg-[#0e1725] card-hover-glow"
                     aria-label="{{ $tituloAuto }}">

                {{-- IMAGEN --}}
                <a href="{{ route('public.autos.show', $auto) }}" class="block relative aspect-[16/10] overflow-hidden bg-slate-800" aria-label="Ver {{ $tituloAuto }}">

                    @if($imagenUrl)
                        <img src="{{ $imagenUrl }}"
                             alt="{{ $tituloAuto }} {{ $auto->anio }}"
                             width="800" height="500"
                             loading="lazy"
                             class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    @else
                        <div class="h-full w-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center" aria-hidden="true">
                            <svg class="h-16 w-16 text-slate-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/>
                                <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/>
                                <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Badge destacado --}}
                    @if($auto->destacado)
                    <div class="absolute left-3 top-3 flex items-center gap-1.5 rounded-full bg-amber-500 px-3 py-1 text-xs font-bold text-white shadow-lg shadow-amber-900/40" aria-label="Auto destacado">
                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z" clip-rule="evenodd"/></svg>
                        Destacado
                    </div>
                    @else
                    <div class="absolute left-3 top-3 rounded-full bg-emerald-500/90 px-3 py-1 text-xs font-bold text-white shadow-lg" aria-label="Disponible">
                        Disponible
                    </div>
                    @endif

                    {{-- Gradient overlay bottom --}}
                    <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-[#0e1725] to-transparent"></div>
                </a>

                {{-- BODY --}}
                <div class="p-5">

                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <h3 class="text-lg font-bold text-white leading-snug truncate">{{ $tituloAuto }}</h3>
                            <p class="text-sm text-slate-400 mt-0.5">
                                {{ $auto->anio ?? '—' }}
                                @if(!empty($auto->version))
                                    <span class="text-slate-600">·</span> {{ Str::limit($auto->version, 30) }}
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- SPECS CHIPS --}}
                    <div class="mt-4 flex flex-wrap gap-2">
                        @if($auto->transmision)
                        <span class="inline-flex items-center gap-1 rounded-lg bg-slate-800 border border-white/[0.06] px-2.5 py-1 text-xs font-medium text-slate-300">
                            <svg class="h-3 w-3 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 0 1-9.201 2.466l-.312-.311h2.433a.75.75 0 0 0 0-1.5H3.989a.75.75 0 0 0-.75.75v4.242a.75.75 0 0 0 1.5 0v-2.43l.31.31a7 7 0 0 0 11.712-3.138.75.75 0 0 0-1.449-.39Zm1.23-3.723a.75.75 0 0 0 .219-.53V2.929a.75.75 0 0 0-1.5 0V5.36l-.31-.31A7 7 0 0 0 3.239 8.188a.75.75 0 1 0 1.448.389A5.5 5.5 0 0 1 13.89 6.11l.311.31h-2.432a.75.75 0 0 0 0 1.5h4.243a.75.75 0 0 0 .53-.219Z" clip-rule="evenodd"/></svg>
                            {{ ucfirst($auto->transmision) }}
                        </span>
                        @endif

                        @if($auto->tipo_combustible)
                        <span class="inline-flex items-center gap-1 rounded-lg bg-slate-800 border border-white/[0.06] px-2.5 py-1 text-xs font-medium text-slate-300">
                            <svg class="h-3 w-3 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M13.2 2.24a.75.75 0 0 0 .04 1.06l2.1 1.95H6.75a.75.75 0 0 0 0 1.5h1.96l-1.66 1.55a3.25 3.25 0 0 0-1.05 2.4v6.55a.75.75 0 0 0 1.5 0v-6.55c0-.52.21-1.01.58-1.38l3.37-3.12v5.8a3 3 0 0 0 3 3h.04a3 3 0 0 0 3-3V8.24a2.25 2.25 0 0 0-.66-1.59L17.5 3.3a.75.75 0 0 0-1.06-.04l-1.94 1.8a.75.75 0 0 0-.04 1.06l1.98 1.84V8.24a.75.75 0 0 1-.75.75H15a.75.75 0 0 1-.75-.75V5.06l-.97-.9a.75.75 0 0 0-1.06.08Z" clip-rule="evenodd"/></svg>
                            {{ ucfirst($auto->tipo_combustible) }}
                        </span>
                        @endif

                        @if($auto->kilometraje !== null)
                        <span class="inline-flex items-center gap-1 rounded-lg bg-slate-800 border border-white/[0.06] px-2.5 py-1 text-xs font-medium text-slate-300">
                            <svg class="h-3 w-3 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd"/></svg>
                            {{ number_format((int)$auto->kilometraje) }} km
                        </span>
                        @endif
                    </div>

                    {{-- PRECIO --}}
                    <div class="mt-5 flex items-end justify-between gap-3 pt-4 border-t border-white/[0.06]">
                        <div>
                            @if($auto->precio_contado > 0)
                            <p class="text-xs text-slate-500 mb-0.5">Contado</p>
                            <p class="text-2xl font-black text-white tabular-nums">
                                ${{ number_format((float)$auto->precio_contado, 0) }}
                            </p>
                            @endif
                            @if($auto->precio_financiado > 0)
                            <p class="text-xs text-emerald-400 mt-1">
                                Financiado: ${{ number_format((float)$auto->precio_financiado, 0) }}
                            </p>
                            @endif
                        </div>
                    </div>

                    {{-- BOTONES --}}
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('public.autos.show', $auto) }}"
                           class="flex-1 text-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-blue-500 active:scale-[0.98]">
                            Ver detalles
                        </a>
                        <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode($mensajeWa) }}"
                           target="_blank" rel="noopener noreferrer"
                           aria-label="Cotizar por WhatsApp"
                           class="rounded-xl px-4 py-2.5 text-white transition hover:opacity-90 active:scale-[0.98]"
                           style="background-color: var(--color-secundario)">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                        </a>
                    </div>

                </div>
            </article>
            @endforeach
        </div>

        {{-- PAGINACIÓN --}}
        <div class="mt-10 flex justify-center">
            {{ $autos->links() }}
        </div>

        @else

        {{-- ESTADO VACÍO --}}
        <div class="rounded-2xl border border-dashed border-white/[0.1] bg-slate-900/40 p-16 text-center">
            <div class="mx-auto h-20 w-20 rounded-2xl bg-slate-800/80 flex items-center justify-center mb-5" aria-hidden="true">
                <svg class="h-10 w-10 text-slate-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/>
                    <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/>
                    <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                </svg>
            </div>
            <h3 class="text-2xl font-black text-white">Sin resultados</h3>
            <p class="text-slate-400 mt-2 mb-6">No encontramos autos con esos filtros. Intenta con otros criterios.</p>
            <button wire:click="limpiarFiltros" type="button"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-blue-500">
                Limpiar filtros
            </button>
        </div>

        @endif

        </div>{{-- fin columna resultados --}}
        </div>{{-- fin lg:grid --}}

    </main>

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
                    <a href="{{ $homeUrl }}#financiamiento" class="hover:text-white transition">Financiamiento</a>
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
        <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hola, quiero información sobre los autos disponibles') }}"
           target="_blank" rel="noopener noreferrer"
           class="flex h-14 w-14 items-center justify-center rounded-full shadow-xl transition hover:scale-110 hover:opacity-90 active:scale-95"
           style="background-color: var(--color-secundario)">
            <svg class="h-7 w-7 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
        </a>
        <span class="pointer-events-none absolute right-full mr-3 top-1/2 -translate-y-1/2 whitespace-nowrap rounded-lg bg-slate-900 border border-white/10 px-3 py-1.5 text-xs font-semibold text-white opacity-0 transition group-hover:opacity-100" aria-hidden="true">
            Cotizar por WhatsApp
        </span>
    </div>

</div>
