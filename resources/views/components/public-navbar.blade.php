@php
    use Illuminate\Support\Facades\Route;

    $whatsapp = $whatsapp ?? '5210000000000';
    $homeUrl = Route::has('public.home') ? route('public.home') : url('/');
    $catalogoUrl = Route::has('public.autos') ? route('public.autos') : url('/autos');
@endphp

<header
    x-data="{ open: false }"
    class="fixed top-0 inset-x-0 z-50 border-b border-white/10 bg-slate-950/90 backdrop-blur-xl"
>
    <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
        <a href="{{ $homeUrl }}" class="flex items-center gap-3 text-white">
            <div class="h-11 w-11 rounded-2xl bg-white text-slate-950 flex items-center justify-center font-black text-xl shadow-lg">
                A
            </div>

            <div>
                <p class="font-black text-base leading-none">AutoLote</p>
                <p class="text-xs text-slate-300 mt-1">Autos financiados</p>
            </div>
        </a>

        {{-- Desktop --}}
        <nav class="hidden lg:flex items-center gap-8 text-sm font-bold text-slate-300">
            <a href="{{ $homeUrl }}#autos" class="hover:text-white transition">Inventario</a>
            <a href="{{ $homeUrl }}#financiamiento" class="hover:text-white transition">Financiamiento</a>
            <a href="{{ $homeUrl }}#proceso" class="hover:text-white transition">Proceso</a>
            <a href="{{ $homeUrl }}#contacto" class="hover:text-white transition">Contacto</a>
            <a href="{{ $catalogoUrl }}" class="hover:text-white transition">Catálogo</a>
        </nav>

        <div class="hidden sm:flex items-center gap-3">
            <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hola, quiero información sobre los autos disponibles') }}"
               target="_blank"
               class="rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-black text-white shadow-lg shadow-emerald-900/20 hover:bg-emerald-400 transition">
                WhatsApp
            </a>
        </div>

        {{-- Mobile button --}}
        <button
            type="button"
            @click="open = !open"
            class="lg:hidden inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/10 text-white"
            aria-label="Abrir menú"
        >
            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
            </svg>

            <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Mobile menu --}}
    <div
        x-show="open"
        x-cloak
        x-transition
        @click.outside="open = false"
        class="lg:hidden border-t border-white/10 bg-slate-950/95 backdrop-blur-xl"
    >
        <nav class="max-w-7xl mx-auto px-4 py-4 space-y-2">
            <a @click="open = false"
               href="{{ $homeUrl }}#autos"
               class="block rounded-2xl px-4 py-3 text-sm font-black text-slate-200 hover:bg-white/10">
                Inventario
            </a>

            <a @click="open = false"
               href="{{ $homeUrl }}#financiamiento"
               class="block rounded-2xl px-4 py-3 text-sm font-black text-slate-200 hover:bg-white/10">
                Financiamiento
            </a>

            <a @click="open = false"
               href="{{ $homeUrl }}#proceso"
               class="block rounded-2xl px-4 py-3 text-sm font-black text-slate-200 hover:bg-white/10">
                Proceso
            </a>

            <a @click="open = false"
               href="{{ $homeUrl }}#contacto"
               class="block rounded-2xl px-4 py-3 text-sm font-black text-slate-200 hover:bg-white/10">
                Contacto
            </a>

            <a @click="open = false"
               href="{{ $catalogoUrl }}"
               class="block rounded-2xl px-4 py-3 text-sm font-black text-slate-200 hover:bg-white/10">
                Catálogo
            </a>

            <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hola, quiero información sobre los autos disponibles') }}"
               target="_blank"
               class="mt-3 flex w-full items-center justify-center rounded-2xl bg-emerald-500 px-5 py-4 text-sm font-black text-white hover:bg-emerald-400">
                WhatsApp
            </a>
        </nav>
    </div>
</header>