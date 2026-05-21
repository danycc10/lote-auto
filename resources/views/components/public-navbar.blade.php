@php
    use Illuminate\Support\Facades\Route;

    $whatsapp   = $whatsapp   ?? '5210000000000';
    $homeUrl    = Route::has('public.home') ? route('public.home') : url('/');
    $catalogoUrl = Route::has('public.autos') ? route('public.autos') : url('/autos');
@endphp

{{--
    Navbar pública premium
    - Transparente en tope del hero, se solidifica al hacer scroll
    - Glassmorphism con border visible tras el scroll
    - Mobile: menú full-overlay con slide-down + backdrop
--}}
<header
    x-data="{ open: false, scrolled: false }"
    x-init="scrolled = window.scrollY > 48"
    @scroll.window="scrolled = window.scrollY > 48"
    :class="scrolled
        ? 'border-white/[0.08] bg-slate-950/95 shadow-xl shadow-black/30'
        : 'border-transparent bg-slate-950/30'"
    class="fixed top-0 inset-x-0 z-50 border-b backdrop-blur-xl transition-all duration-300"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[68px] flex items-center justify-between gap-6">

        {{-- Logo --}}
        <a href="{{ $homeUrl }}" class="flex items-center gap-3 group min-w-0 shrink-0" aria-label="{{ config('app.name') }}">
            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-600 to-emerald-500 flex items-center justify-center shadow-lg shadow-blue-900/40 shrink-0 transition group-hover:shadow-emerald-900/40">
                {{-- Car SVG icon --}}
                <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/>
                    <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/>
                    <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="font-black text-white text-[15px] leading-none tracking-tight truncate">
                    {{ config('app.name', 'AutoLote') }}
                </p>
                <p class="text-[11px] text-emerald-400/80 font-medium mt-0.5 leading-none">
                    Autos financiados
                </p>
            </div>
        </a>

        {{-- Desktop nav --}}
        <nav class="hidden lg:flex items-center gap-1 text-sm font-semibold text-slate-300" aria-label="Navegación principal">
            <a href="{{ $homeUrl }}#autos"
               class="rounded-lg px-3 py-2 transition-colors hover:bg-white/8 hover:text-white">
                Inventario
            </a>
            <a href="{{ $homeUrl }}#financiamiento"
               class="rounded-lg px-3 py-2 transition-colors hover:bg-white/8 hover:text-white">
                Financiamiento
            </a>
            <a href="{{ $homeUrl }}#proceso"
               class="rounded-lg px-3 py-2 transition-colors hover:bg-white/8 hover:text-white">
                Proceso
            </a>
            <a href="{{ $homeUrl }}#contacto"
               class="rounded-lg px-3 py-2 transition-colors hover:bg-white/8 hover:text-white">
                Contacto
            </a>
            <a href="{{ $catalogoUrl }}"
               class="rounded-lg px-3 py-2 transition-colors hover:bg-white/8 hover:text-white">
                Catálogo
            </a>
        </nav>

        {{-- Desktop CTA --}}
        <div class="hidden sm:flex items-center gap-2.5 shrink-0">
            <a href="{{ $catalogoUrl }}"
               class="hidden xl:inline-flex items-center gap-1.5 rounded-xl border border-white/15 bg-white/[0.07] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/12">
                <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M8 10a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"/>
                    <path fill-rule="evenodd" d="M4.5 2A1.5 1.5 0 003 3.5v13A1.5 1.5 0 004.5 18h11a1.5 1.5 0 001.5-1.5V7.621a1.5 1.5 0 00-.44-1.06l-4.12-4.122A1.5 1.5 0 0011.378 2H4.5zm5 5a3 3 0 100 6 3 3 0 000-6z" clip-rule="evenodd"/>
                </svg>
                Catálogo
            </a>
            <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hola, quiero información sobre los autos disponibles') }}"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-900/30 transition hover:bg-emerald-400 active:scale-[0.97]">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                WhatsApp
            </a>
        </div>

        {{-- Mobile menu button --}}
        <button
            type="button"
            @click="open = !open"
            :aria-expanded="open"
            aria-controls="mobile-menu"
            aria-label="Abrir menú de navegación"
            class="lg:hidden flex h-11 w-11 items-center justify-center rounded-xl border border-white/10 bg-white/[0.07] text-white transition hover:bg-white/12 active:scale-95"
        >
            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
            </svg>
            <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Mobile menu --}}
    <div
        id="mobile-menu"
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        @click.outside="open = false"
        class="lg:hidden border-t border-white/[0.08] bg-slate-950/98 backdrop-blur-xl"
        role="navigation"
        aria-label="Menú móvil"
    >
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">
            @foreach([
                ['label' => 'Inventario',     'href' => $homeUrl . '#autos'],
                ['label' => 'Financiamiento', 'href' => $homeUrl . '#financiamiento'],
                ['label' => 'Proceso',        'href' => $homeUrl . '#proceso'],
                ['label' => 'Contacto',       'href' => $homeUrl . '#contacto'],
                ['label' => 'Catálogo',       'href' => $catalogoUrl],
            ] as $link)
            <a @click="open = false"
               href="{{ $link['href'] }}"
               class="flex items-center justify-between rounded-xl px-4 py-3.5 text-sm font-semibold text-slate-200 transition hover:bg-white/8 hover:text-white active:bg-white/12">
                {{ $link['label'] }}
                <svg class="h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                </svg>
            </a>
            @endforeach

            <div class="pt-2 pb-1">
                <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode('Hola, quiero información sobre los autos disponibles') }}"
                   target="_blank" rel="noopener noreferrer"
                   class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-5 py-4 text-sm font-bold text-white transition hover:bg-emerald-400 active:scale-[0.98]">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    Cotizar por WhatsApp
                </a>
            </div>
        </div>
    </div>
</header>
