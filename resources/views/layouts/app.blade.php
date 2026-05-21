<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', { open: false });
        });
    </script>
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900">
    <x-banner />

    <div class="min-h-screen">
        {{-- Sidebar (Livewire component) --}}
        @livewire('navigation-menu')

        {{-- Content area — offset by sidebar width on lg+ --}}
        <div class="lg:ml-64 flex flex-col min-h-screen">

            {{-- Sticky top bar --}}
            <header class="sticky top-0 z-10 bg-white border-b border-slate-200 h-14 flex items-center px-4 sm:px-6 gap-3 shrink-0">

                {{-- Mobile hamburger --}}
                <button x-data @click="$store.sidebar.open = true" type="button"
                    class="lg:hidden flex items-center justify-center h-9 w-9 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition"
                    aria-label="Abrir menú lateral">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <div class="flex-1"></div>

                {{-- User dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button"
                        class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-sm text-slate-700 hover:bg-slate-100 transition">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                            <img class="h-7 w-7 rounded-full object-cover ring-2 ring-slate-200"
                                src="{{ Auth::user()->profile_photo_url }}"
                                alt="{{ Auth::user()->name }}" />
                        @else
                            <div class="h-7 w-7 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-semibold shrink-0">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="hidden sm:block font-medium max-w-[140px] truncate">{{ Auth::user()->name }}</span>
                        <svg class="h-4 w-4 text-slate-400 hidden sm:block" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 -translate-y-1 scale-95"
                        class="absolute right-0 top-full mt-1 w-56 rounded-xl bg-white border border-slate-200 shadow-lg py-1 z-50"
                        style="display:none">
                        <div class="px-3 py-2.5 border-b border-slate-100">
                            <p class="text-sm font-semibold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.show') }}"
                            class="flex items-center gap-2.5 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 transition">
                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            Mi perfil
                        </a>
                        <div class="border-t border-slate-100 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2.5 px-3 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                    </svg>
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            @if (isset($header))
                <div class="bg-white border-b border-slate-200 px-4 sm:px-6 py-4">
                    {{ $header }}
                </div>
            @endif

            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('modals')

    @livewireScripts

    <div wire:ignore>
        <x-toast />
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            if (window.__toastLivewireBound) return;
            window.__toastLivewireBound = true;
            Livewire.on('toast', (payload) => {
                window.dispatchEvent(new CustomEvent('toast', { detail: payload }));
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
