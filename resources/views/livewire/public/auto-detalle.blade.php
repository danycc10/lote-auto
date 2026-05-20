@php
    use Illuminate\Support\Str;

    $whatsapp = '5210000000000';

    $marca = $auto->marca?->nombre ?? 'Marca';
    $modelo = $auto->modelo?->nombre ?? 'Modelo';
    $titulo = trim($marca . ' ' . $modelo);

    $precio = $auto->precio_contado ?? $auto->precio_financiado ?? 0;

    $imagenes = $auto->imagenes ?? collect();

    $mensajeWhatsapp = 'Hola, me interesa el auto ' . $titulo . ' ' . ($auto->anio ?? '');
@endphp


<div class="min-h-screen bg-[#f4f7fb]">

<x-public-navbar :whatsapp="$whatsapp" />

    <section class="bg-slate-950 text-white pt-20">
        <div class="max-w-7xl mx-auto px-4 py-8">


            <h1 class="mt-6 text-4xl md:text-6xl font-black">
                {{ $titulo }}
            </h1>

            <p class="mt-3 text-slate-300 text-lg">
                {{ $auto->anio ?? 'Año no definido' }}
                @if(!empty($auto->version))
                    · {{ $auto->version }}
                @endif
            </p>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">

        <section class="lg:col-span-2 space-y-5">
            <div class="rounded-[2rem] overflow-hidden bg-white border shadow-xl">
                @php
                    $principal = $auto->portada?->ruta ?? $imagenes->first()?->ruta ?? null;

                    $principalUrl = null;

                    if ($principal) {
                        if (Str::startsWith($principal, ['http://', 'https://'])) {
                            $principalUrl = $principal;
                        } elseif (Str::startsWith($principal, ['storage/'])) {
                            $principalUrl = asset($principal);
                        } elseif (Str::startsWith($principal, ['/storage/'])) {
                            $principalUrl = asset(ltrim($principal, '/'));
                        } elseif (Str::startsWith($principal, ['public/'])) {
                            $principalUrl = asset('storage/' . Str::after($principal, 'public/'));
                        } else {
                            $principalUrl = asset('storage/' . $principal);
                        }
                    }
                @endphp

                @if($principalUrl)
                    <img src="{{ $principalUrl }}"
                         alt="{{ $titulo }}"
                         class="w-full aspect-[16/10] object-cover">
                @else
                    <div class="aspect-[16/10] flex items-center justify-center bg-slate-200 text-slate-500 font-black">
                        Sin imagen
                    </div>
                @endif
            </div>

            @if($imagenes->count())
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($imagenes as $img)
                        @php
                            $url = Str::startsWith($img->ruta, ['http://', 'https://'])
                                ? $img->ruta
                                : asset('storage/' . $img->ruta);
                        @endphp

                        <div class="rounded-2xl overflow-hidden bg-white border shadow-sm">
                            <img src="{{ $url }}"
                                 alt="{{ $titulo }}"
                                 class="w-full aspect-[4/3] object-cover">
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <aside class="space-y-5">
            <div class="bg-white rounded-[2rem] border shadow-xl p-6 sticky top-24">
                <span class="inline-flex rounded-full bg-emerald-100 text-emerald-700 px-4 py-2 text-xs font-black">
                    Disponible
                </span>

                <div class="mt-5">
                    <p class="text-sm text-slate-500">Precio contado</p>
                    <p class="text-4xl font-black">
                        ${{ number_format((float) $precio, 2) }}
                    </p>

                    @if(!empty($auto->precio_financiado))
                        <p class="mt-2 text-sm text-slate-500">
                            Financiado: ${{ number_format((float) $auto->precio_financiado, 2) }}
                        </p>
                    @endif
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-slate-500">Año</p>
                        <p class="font-black">{{ $auto->anio ?? 'N/D' }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-slate-500">KM</p>
                        <p class="font-black">{{ number_format((float) ($auto->kilometraje ?? 0)) }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-slate-500">Transmisión</p>
                        <p class="font-black">{{ ucfirst($auto->transmision ?? 'N/D') }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-slate-500">Combustible</p>
                        <p class="font-black">{{ ucfirst($auto->tipo_combustible ?? 'N/D') }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-slate-500">Color</p>
                        <p class="font-black">{{ ucfirst($auto->color ?? 'N/D') }}</p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-slate-500">Inventario</p>
                        <p class="font-black">{{ $auto->codigo_inventario ?? 'N/D' }}</p>
                    </div>
                </div>

                <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode($mensajeWhatsapp) }}"
                   target="_blank"
                   class="mt-6 flex w-full items-center justify-center rounded-2xl bg-emerald-500 px-5 py-4 font-black text-white hover:bg-emerald-400 transition">
                    Pedir información por WhatsApp
                </a>
            </div>
        </aside>

        @if(!empty($auto->descripcion))
            <section class="lg:col-span-2 bg-white rounded-[2rem] border shadow-sm p-6">
                <h2 class="text-2xl font-black">Descripción</h2>
                <p class="mt-3 text-slate-600 leading-relaxed whitespace-pre-line">
                    {{ $auto->descripcion }}
                </p>
            </section>
        @endif
    </main>
</div>