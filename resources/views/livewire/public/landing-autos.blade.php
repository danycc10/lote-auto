@php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

$whatsapp = '5210000000000';

$homeUrl = Route::has('public.home') ? route('public.home') : url('/');
$catalogoUrl = Route::has('public.autos') ? route('public.autos') : '#autos';
@endphp

<div class="min-h-screen bg-[#f4f7fb] text-slate-950">

    {{-- NAVBAR --}}
    <x-public-navbar :whatsapp="$whatsapp" />

    {{-- HERO --}}
    <section class="relative min-h-screen overflow-hidden bg-slate-950 pt-20 text-white flex items-center">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.35),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(16,185,129,0.22),_transparent_30%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-950 to-slate-900"></div>

        <div class="relative w-full max-w-7xl mx-auto px-4 py-8 md:py-10 grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-4 py-2 text-xs sm:text-sm font-bold text-blue-100">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    Autos disponibles con planes de financiamiento
                </div>

                <h1 class="mt-5 text-4xl sm:text-5xl md:text-6xl font-black leading-[0.95] tracking-tight">
                    Estrena auto sin complicarte.
                </h1>

                <p class="mt-5 max-w-xl text-base md:text-lg text-slate-300 leading-relaxed">
                    Encuentra unidades disponibles, recibe atención directa y cotiza opciones de pago claras desde el primer contacto.
                </p>

                <div class="mt-7 flex flex-col sm:flex-row gap-3">
                    <a href="#autos"
                        class="rounded-2xl bg-white px-6 py-4 text-center font-black text-slate-950 shadow-xl hover:bg-slate-100 transition">
                        Ver autos disponibles
                    </a>

                    <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hola, quiero cotizar un auto') }}"
                        target="_blank"
                        class="rounded-2xl bg-emerald-500 px-6 py-4 text-center font-black text-white shadow-xl shadow-emerald-900/30 hover:bg-emerald-400 transition">
                        Cotizar por WhatsApp
                    </a>
                </div>

                <div class="hidden sm:grid mt-7 grid-cols-3 gap-3 max-w-xl">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-3">
                        <p class="text-2xl font-black">24h</p>
                        <p class="mt-1 text-xs text-slate-300">Respuesta rápida</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/10 p-3">
                        <p class="text-2xl font-black">100%</p>
                        <p class="mt-1 text-xs text-slate-300">Proceso claro</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/10 p-3">
                        <p class="text-2xl font-black">+50</p>
                        <p class="mt-1 text-xs text-slate-300">Clientes atendidos</p>
                    </div>
                </div>
            </div>

            {{-- CARRUSEL HERO --}}
            @if($heroAutos->count())
            <div
                x-data="{
                        active: 0,
                        total: {{ $heroAutos->count() }},
                        autoplay: null,
                        start() {
                            this.autoplay = setInterval(() => {
                                this.next()
                            }, 4500)
                        },
                        next() {
                            this.active = (this.active + 1) % this.total
                        },
                        prev() {
                            this.active = (this.active - 1 + this.total) % this.total
                        }
                    }"
                x-init="start()"
                class="relative">
                <div class="absolute -inset-4 rounded-[3rem] bg-blue-500/20 blur-3xl"></div>

                <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/10 shadow-2xl backdrop-blur">

                    <div class="relative aspect-[16/10] lg:aspect-[4/3] max-h-[520px] overflow-hidden">
                        @foreach($heroAutos as $index => $heroAuto)
                        @php
                        $heroMarcaRaw = $heroAuto->marca ?? null;
                        $heroModeloRaw = $heroAuto->modelo ?? null;

                        $heroMarca = is_object($heroMarcaRaw)
                        ? ($heroMarcaRaw->nombre ?? $heroMarcaRaw->name ?? '')
                        : ($heroMarcaRaw ?? '');

                        $heroModelo = is_object($heroModeloRaw)
                        ? ($heroModeloRaw->nombre ?? $heroModeloRaw->name ?? '')
                        : ($heroModeloRaw ?? '');

                        $heroTitulo = trim($heroMarca . ' ' . $heroModelo) ?: 'Auto disponible';

                        $heroImagen = $heroAuto->portada?->ruta
                        ?? $heroAuto->imagenes?->first()?->ruta
                        ?? null;

                        $heroImagenUrl = null;

                        if ($heroImagen) {
                        if (Str::startsWith($heroImagen, ['http://', 'https://'])) {
                        $heroImagenUrl = $heroImagen;
                        } elseif (Str::startsWith($heroImagen, ['storage/'])) {
                        $heroImagenUrl = asset($heroImagen);
                        } elseif (Str::startsWith($heroImagen, ['/storage/'])) {
                        $heroImagenUrl = asset(ltrim($heroImagen, '/'));
                        } elseif (Str::startsWith($heroImagen, ['public/'])) {
                        $heroImagenUrl = asset('storage/' . Str::after($heroImagen, 'public/'));
                        } else {
                        $heroImagenUrl = asset('storage/' . $heroImagen);
                        }
                        }

                        $heroPrecio = $heroAuto->precio_venta
                        ?? $heroAuto->precio_contado
                        ?? $heroAuto->precio_financiado
                        ?? $heroAuto->precio
                        ?? 0;
                        @endphp

                        @if($heroImagenUrl)
                        <div
                            x-show="active === {{ $index }}"
                            x-transition:enter="transition ease-out duration-700"
                            x-transition:enter-start="opacity-0 scale-105"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-500"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="absolute inset-0">
                            <img
                                src="{{ $heroImagenUrl }}"
                                alt="{{ $heroTitulo }}"
                                class="h-full w-full object-cover">

                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/35 to-transparent"></div>

                            <div class="absolute inset-x-0 bottom-0 p-5 sm:p-8">
                                <div class="flex items-end justify-between gap-5">
                                    <div>
                                        <div class="inline-flex items-center rounded-full bg-emerald-500 px-4 py-2 text-xs font-black text-white shadow-lg">
                                            Disponible
                                        </div>

                                        <h3 class="mt-3 text-2xl sm:text-3xl md:text-4xl font-black text-white drop-shadow-lg">
                                            {{ $heroTitulo }}
                                        </h3>

                                        <p class="mt-1 text-sm sm:text-base text-slate-200">
                                            {{ $heroAuto->anio ?? 'Año no definido' }}

                                            @if(!empty($heroAuto->transmision))
                                            · {{ ucfirst($heroAuto->transmision) }}
                                            @endif
                                        </p>
                                    </div>

                                    <div class="hidden md:block rounded-[1.5rem] bg-white/10 backdrop-blur-xl border border-white/10 p-5 text-right min-w-[190px]">
                                        <p class="text-sm text-slate-300">Precio</p>

                                        <p class="mt-1 text-2xl font-black text-white">
                                            ${{ number_format((float) $heroPrecio, 2) }}
                                        </p>

                                        <p class="mt-2 text-xs text-slate-300">
                                            {{ number_format((float) ($heroAuto->kilometraje ?? 0)) }} km
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach

                        <button
                            type="button"
                            @click="prev()"
                            class="absolute left-3 top-1/2 -translate-y-1/2 h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-black/40 backdrop-blur text-white flex items-center justify-center hover:bg-black/60 transition">
                            ←
                        </button>

                        <button
                            type="button"
                            @click="next()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 h-10 w-10 sm:h-12 sm:w-12 rounded-full bg-black/40 backdrop-blur text-white flex items-center justify-center hover:bg-black/60 transition">
                            →
                        </button>

                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-2">
                            @foreach($heroAutos as $index => $heroAuto)
                            <button
                                type="button"
                                @click="active = {{ $index }}"
                                :class="active === {{ $index }} ? 'bg-white w-9' : 'bg-white/40 w-3'"
                                class="h-3 rounded-full transition-all duration-300"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="relative hidden lg:block">
                <div class="rounded-[2rem] border border-white/10 bg-white/10 p-8 shadow-2xl backdrop-blur">
                    <div class="aspect-[16/10] rounded-[1.5rem] bg-gradient-to-br from-slate-800 to-slate-950 flex items-center justify-center">
                        <div class="text-center">
                            <div class="mx-auto h-20 w-20 rounded-3xl bg-white/10 flex items-center justify-center text-4xl">
                                🚘
                            </div>
                            <p class="mt-4 font-black">Agrega fotos a tus autos destacados</p>
                            <p class="mt-1 text-sm text-slate-400">El carrusel aparecerá automáticamente.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    {{-- BENEFICIOS --}}
    <section class="max-w-7xl mx-auto px-4 -mt-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="rounded-[2rem] border border-slate-200 bg-white p-7 shadow-xl shadow-slate-200/70">
                <div class="h-14 w-14 rounded-2xl bg-blue-100 flex items-center justify-center text-2xl">🚗</div>
                <h3 class="mt-5 text-xl font-black">Inventario disponible</h3>
                <p class="mt-2 text-slate-500">Muestra tus unidades listas para venta de forma limpia y confiable.</p>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-7 shadow-xl shadow-slate-200/70">
                <div class="h-14 w-14 rounded-2xl bg-emerald-100 flex items-center justify-center text-2xl">💳</div>
                <h3 class="mt-5 text-xl font-black">Opciones de pago</h3>
                <p class="mt-2 text-slate-500">Comunica enganches, planes y cotización personalizada por WhatsApp.</p>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white p-7 shadow-xl shadow-slate-200/70">
                <div class="h-14 w-14 rounded-2xl bg-orange-100 flex items-center justify-center text-2xl">🤝</div>
                <h3 class="mt-5 text-xl font-black">Contacto directo</h3>
                <p class="mt-2 text-slate-500">Lleva al cliente directo a la conversación para cerrar más rápido.</p>
            </div>
        </div>
    </section>

    {{-- AUTOS --}}
    <section id="autos" class="max-w-7xl mx-auto px-4 py-20">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-10">
            <div>
                <p class="text-sm font-black text-blue-700 uppercase tracking-[0.25em]">Inventario</p>
                <h2 class="mt-3 text-4xl md:text-5xl font-black tracking-tight">Autos disponibles</h2>
                <p class="mt-3 text-slate-500 max-w-2xl">Estas son algunas unidades listas para cotizar. El cliente puede pedir información inmediata por WhatsApp.</p>
            </div>

            <a href="{{ $catalogoUrl }}"
                class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-6 py-4 text-sm font-black text-white hover:bg-slate-800 transition">
                Ver catálogo completo
            </a>
        </div>

        @if($autosDestacados->count())
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-7">
            @foreach($autosDestacados as $auto)
            @php
            $marcaRaw = $auto->marca ?? null;
            $modeloRaw = $auto->modelo ?? null;
            $versionRaw = $auto->version ?? null;

            $marcaNombre = is_object($marcaRaw) ? ($marcaRaw->nombre ?? $marcaRaw->name ?? null) : $marcaRaw;
            $modeloNombre = is_object($modeloRaw) ? ($modeloRaw->nombre ?? $modeloRaw->name ?? null) : $modeloRaw;
            $versionNombre = is_object($versionRaw) ? ($versionRaw->nombre ?? $versionRaw->name ?? null) : $versionRaw;

            $tituloAuto = trim(($marcaNombre ?: 'Marca no definida') . ' ' . ($modeloNombre ?: 'Modelo no definido'));

            $precio = $auto->precio_venta
            ?? $auto->precio_contado
            ?? $auto->precio_financiado
            ?? $auto->precio
            ?? 0;

            $imagen = $auto->portada?->ruta
            ?? $auto->imagenes?->first()?->ruta
            ?? null;

            $imagenUrl = null;

            if ($imagen) {
            if (Str::startsWith($imagen, ['http://', 'https://'])) {
            $imagenUrl = $imagen;
            } elseif (Str::startsWith($imagen, ['storage/'])) {
            $imagenUrl = asset($imagen);
            } elseif (Str::startsWith($imagen, ['/storage/'])) {
            $imagenUrl = asset(ltrim($imagen, '/'));
            } elseif (Str::startsWith($imagen, ['public/'])) {
            $imagenUrl = asset('storage/' . Str::after($imagen, 'public/'));
            } else {
            $imagenUrl = asset('storage/' . $imagen);
            }
            }

            $mensajeWhatsapp = 'Hola, me interesa el auto ' . $tituloAuto . ' ' . ($auto->anio ?? '');
            @endphp

            <article class="group overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-lg shadow-slate-200/60 transition hover:-translate-y-1 hover:shadow-2xl">
                <div class="relative aspect-[16/10] overflow-hidden bg-slate-200">
                    @if($imagenUrl)
                    <img src="{{ $imagenUrl }}"
                        alt="{{ $tituloAuto }}"
                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    @else
                    <div class="h-full w-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center">
                        <div class="text-center">
                            <div class="mx-auto h-16 w-16 rounded-3xl bg-white/70 flex items-center justify-center text-3xl">🚘</div>
                            <p class="mt-3 text-sm font-black text-slate-500">Sin imagen</p>
                        </div>
                    </div>
                    @endif

                    <div class="absolute left-4 top-4 rounded-full bg-emerald-500 px-4 py-2 text-xs font-black text-white shadow-lg">
                        Disponible
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="text-2xl font-black leading-tight">{{ $tituloAuto }}</h3>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $auto->anio ?? 'Año no definido' }}
                        @if($versionNombre)
                        · {{ $versionNombre }}
                        @endif
                    </p>

                    <div class="mt-6 flex items-end justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-500">Precio</p>
                            <p class="text-3xl font-black">${{ number_format((float) $precio, 2) }}</p>
                        </div>

                        <div class="rounded-2xl bg-slate-100 px-4 py-3 text-right">
                            <p class="text-xs text-slate-500">Kilometraje</p>
                            <p class="font-black">{{ number_format((float) ($auto->kilometraje ?? 0)) }} km</p>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3 text-sm">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-slate-500">Transmisión</p>
                            <p class="font-black">{{ ucfirst($auto->transmision ?? 'N/D') }}</p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-slate-500">Color</p>
                            <p class="font-black">{{ ucfirst($auto->color ?? 'N/D') }}</p>
                        </div>
                    </div>

                    <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode($mensajeWhatsapp) }}"
                        target="_blank"
                        class="mt-6 flex w-full items-center justify-center rounded-2xl bg-emerald-500 px-5 py-4 font-black text-white hover:bg-emerald-400 transition">
                        Pedir información
                    </a>
                </div>
            </article>
            @endforeach
        </div>
        @else
        <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white p-12 text-center">
            <div class="mx-auto h-16 w-16 rounded-3xl bg-slate-100 flex items-center justify-center text-3xl">🚘</div>
            <h3 class="mt-5 text-2xl font-black">Aún no hay autos disponibles</h3>
            <p class="mt-2 text-slate-500">Cuando registres autos disponibles aparecerán aquí automáticamente.</p>
        </div>
        @endif
    </section>

    {{-- CONTACTO --}}
    <section id="contacto" class="bg-slate-950 text-white">
        <div class="max-w-7xl mx-auto px-4 py-20 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div>
                <p class="text-sm font-black text-emerald-300 uppercase tracking-[0.25em]">Contacto</p>
                <h2 class="mt-3 text-4xl md:text-5xl font-black tracking-tight">¿Listo para encontrar tu próximo auto?</h2>
                <p class="mt-5 text-lg text-slate-300">Escríbenos y recibe información de las unidades disponibles.</p>
            </div>

            <div class="rounded-[2rem] border border-white/10 bg-white/10 p-8">
                <div class="space-y-5">
                    <div>
                        <p class="text-sm text-slate-400">WhatsApp</p>
                        <p class="text-xl font-black">+52 1 000 000 0000</p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-400">Horario</p>
                        <p class="text-xl font-black">Lunes a sábado · 9:00 AM a 7:00 PM</p>
                    </div>

                    <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hola, quiero información de los autos disponibles') }}"
                        target="_blank"
                        class="flex w-full items-center justify-center rounded-2xl bg-emerald-500 px-5 py-4 font-black text-white hover:bg-emerald-400 transition">
                        Enviar WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-950 border-t border-white/10 text-slate-400">
        <div class="max-w-7xl mx-auto px-4 py-7 flex flex-col md:flex-row md:items-center md:justify-between gap-3 text-sm">
            <p>© {{ date('Y') }} AutoLote. Todos los derechos reservados.</p>
            <p>Venta y financiamiento de autos.</p>
        </div>
    </footer>
</div>