@php
use Illuminate\Support\Str;

$whatsapp = '5210000000000';
@endphp

<div class="min-h-screen bg-[#f4f7fb]">

    <x-public-navbar :whatsapp="$whatsapp" />

    {{-- HERO --}}
    <section class="relative overflow-hidden bg-slate-950 text-white pt-20">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.30),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(16,185,129,0.18),_transparent_30%)]"></div>

        <div class="relative max-w-7xl mx-auto px-4 py-14 md:py-20">
            <div class="max-w-3xl">
                <p class="text-sm uppercase tracking-[0.25em] text-blue-300 font-semibold">
                    Catálogo disponible
                </p>

                <h1 class="mt-3 text-4xl md:text-6xl font-black leading-tight">
                    Encuentra tu próximo auto
                </h1>

                <p class="mt-5 text-slate-300 text-lg leading-relaxed">
                    Explora autos disponibles, compara precios y solicita información fácilmente desde cualquier dispositivo.
                </p>
            </div>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 py-8">

        {{-- FILTROS --}}
        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-200 p-5 md:p-7 mb-8">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-2xl font-black">Filtros</h2>
                    <p class="text-sm text-slate-500">
                        Busca por marca, modelo, transmisión o precio.
                    </p>
                </div>

                <button
                    wire:click="limpiarFiltros"
                    type="button"
                    class="rounded-2xl border border-slate-300 px-5 py-3 text-sm font-bold hover:bg-slate-50 transition">
                    Limpiar filtros
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4">

                {{-- SEARCH --}}
                <div class="xl:col-span-2">
                    <label class="text-sm font-bold text-slate-700">
                        Buscar
                    </label>

                    <input
                        type="text"
                        wire:model.live.debounce.400ms="search"
                        placeholder="Marca, modelo, año..."
                        class="mt-1 w-full rounded-2xl border-slate-300 focus:border-slate-950 focus:ring-slate-950">
                </div>

                {{-- MARCA --}}
                <div>
                    <label class="text-sm font-bold text-slate-700">
                        Marca
                    </label>

                    <select
                        wire:model.live="marca"
                        class="mt-1 w-full rounded-2xl border-slate-300 focus:border-slate-950 focus:ring-slate-950">

                        <option value="">Todas</option>

                        @foreach($marcas as $m)
                        <option value="{{ $m->id }}">
                            {{ $m->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- TRANSMISION --}}
                <div>
                    <label class="text-sm font-bold text-slate-700">
                        Transmisión
                    </label>

                    <select
                        wire:model.live="transmision"
                        class="mt-1 w-full rounded-2xl border-slate-300 focus:border-slate-950 focus:ring-slate-950">

                        <option value="">Todas</option>
                        <option value="automatica">Automática</option>
                        <option value="manual">Manual</option>
                    </select>
                </div>

                {{-- MIN --}}
                <div>
                    <label class="text-sm font-bold text-slate-700">
                        Precio mínimo
                    </label>

                    <input
                        type="number"
                        wire:model.live.debounce.500ms="precioMin"
                        class="mt-1 w-full rounded-2xl border-slate-300 focus:border-slate-950 focus:ring-slate-950">
                </div>

                {{-- MAX --}}
                <div>
                    <label class="text-sm font-bold text-slate-700">
                        Precio máximo
                    </label>

                    <input
                        type="number"
                        wire:model.live.debounce.500ms="precioMax"
                        class="mt-1 w-full rounded-2xl border-slate-300 focus:border-slate-950 focus:ring-slate-950">
                </div>
            </div>
        </div>

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-2xl md:text-3xl font-black">
                    Autos disponibles
                </h2>

                <p class="text-slate-500 text-sm mt-1">
                    {{ $autos->total() }} resultados encontrados
                </p>
            </div>

            <div wire:loading class="text-sm text-slate-500 font-semibold">
                Cargando...
            </div>
        </div>

        {{-- GRID --}}
        @if($autos->count())

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-7">

            @foreach($autos as $auto)

            @php
            $marcaNombre = $auto->marca?->nombre ?? 'Marca';
            $modeloNombre = $auto->modelo?->nombre ?? 'Modelo';

            $tituloAuto = trim($marcaNombre . ' ' . $modeloNombre);

            $precio = $auto->precio_contado
            ?? $auto->precio_financiado
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

            $mensajeWhatsapp =
            'Hola, me interesa el auto ' .
            $tituloAuto .
            ' ' .
            ($auto->anio ?? '');
            @endphp

            <article class="group overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-lg shadow-slate-200/60 transition hover:-translate-y-1 hover:shadow-2xl">

                {{-- FOTO --}}
                <div class="relative aspect-[16/10] overflow-hidden bg-slate-200">

                    @if($imagenUrl)

                    <img
                        src="{{ $imagenUrl }}"
                        alt="{{ $tituloAuto }}"
                        class="h-full w-full object-cover transition duration-700 group-hover:scale-105">

                    @else

                    <div class="h-full w-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center">

                        <div class="text-center">
                            <div class="mx-auto h-16 w-16 rounded-3xl bg-white/70 flex items-center justify-center text-3xl">
                                🚘
                            </div>

                            <p class="mt-3 text-sm font-black text-slate-500">
                                Sin imagen
                            </p>
                        </div>
                    </div>

                    @endif

                    <div class="absolute left-4 top-4 rounded-full bg-emerald-500 px-4 py-2 text-xs font-black text-white shadow-lg">
                        Disponible
                    </div>
                </div>

                {{-- BODY --}}
                <div class="p-6">

                    <div class="flex items-start justify-between gap-3">

                        <div>
                            <h3 class="text-2xl font-black leading-tight">
                                {{ $tituloAuto }}
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ $auto->anio ?? 'Año no definido' }}

                                @if(!empty($auto->version))
                                · {{ $auto->version }}
                                @endif
                            </p>
                        </div>

                    </div>

                    {{-- PRECIO --}}
                    <div class="mt-6 flex items-end justify-between gap-4">

                        <div>
                            <p class="text-sm text-slate-500">
                                Precio contado
                            </p>

                            <p class="text-3xl font-black text-slate-950">
                                ${{ number_format((float) $precio, 2) }}
                            </p>

                            @if(!empty($auto->precio_financiado))
                            <p class="text-sm text-slate-500 mt-1">
                                Financiado:
                                ${{ number_format((float) $auto->precio_financiado, 2) }}
                            </p>
                            @endif
                        </div>

                        <div class="rounded-2xl bg-slate-100 px-4 py-3 text-right">
                            <p class="text-xs text-slate-500">
                                Kilometraje
                            </p>

                            <p class="font-black">
                                {{ number_format((float) ($auto->kilometraje ?? 0)) }} km
                            </p>
                        </div>

                    </div>

                    {{-- INFO --}}
                    <div class="mt-6 grid grid-cols-2 gap-3 text-sm">

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-slate-500">
                                Transmisión
                            </p>

                            <p class="font-black">
                                {{ ucfirst($auto->transmision ?? 'N/D') }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-slate-500">
                                Combustible
                            </p>

                            <p class="font-black">
                                {{ ucfirst($auto->tipo_combustible ?? 'N/D') }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-slate-500">
                                Color
                            </p>

                            <p class="font-black">
                                {{ ucfirst($auto->color ?? 'N/D') }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-slate-500">
                                Inventario
                            </p>

                            <p class="font-black truncate">
                                {{ $auto->codigo_inventario ?? 'N/D' }}
                            </p>
                        </div>

                    </div>

                    {{-- BOTONES --}}
                    <div class="mt-6 flex gap-3">

                        <a
                            href="{{ route('public.autos.show', $auto) }}"
                            class="flex-1 text-center rounded-2xl bg-slate-950 text-white px-4 py-4 text-sm font-black hover:bg-slate-800 transition">
                            Ver detalles
                        </a>

                        <a
                            target="_blank"
                            href="https://wa.me/{{ $whatsapp }}?text={{ urlencode($mensajeWhatsapp) }}"
                            class="rounded-2xl bg-emerald-500 text-white px-5 py-4 text-sm font-black hover:bg-emerald-400 transition">
                            WhatsApp
                        </a>

                    </div>

                </div>
            </article>

            @endforeach

        </div>

        {{-- PAGINATION --}}
        <div class="mt-10">
            {{ $autos->links() }}
        </div>

        @else

        <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white p-12 text-center">

            <div class="mx-auto h-16 w-16 rounded-3xl bg-slate-100 flex items-center justify-center text-3xl">
                🚘
            </div>

            <h3 class="mt-5 text-2xl font-black">
                No encontramos autos disponibles
            </h3>

            <p class="text-slate-500 mt-2">
                Intenta cambiar los filtros de búsqueda.
            </p>
        </div>

        @endif

    </main>
</div>