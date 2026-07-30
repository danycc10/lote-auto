<div
    x-data="{ open: false }"
    x-on:livewire:navigated.window="open = false"
    x-on:keydown.escape.window="
        if (open) {
            open = false;
            $nextTick(() => $refs.helpTrigger.focus());
        }
    "
>
    <button
        x-ref="helpTrigger"
        type="button"
        class="inline-flex min-h-11 items-center justify-center gap-2 rounded-lg px-3 text-sm font-semibold text-slate-600 transition-colors duration-200 hover:bg-indigo-50 hover:text-indigo-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 focus-visible:ring-offset-2"
        x-on:click="
            open = true;
            $nextTick(() => $refs.closeHelp.focus());
        "
        x-bind:aria-expanded="open.toString()"
        aria-controls="ayuda-contextual"
        aria-haspopup="dialog"
        aria-label="Abrir ayuda de esta pantalla"
    >
        <svg
            aria-hidden="true"
            class="h-5 w-5"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
        >
            <circle cx="12" cy="12" r="9"></circle>
            <path stroke-linecap="round" d="M9.75 9a2.35 2.35 0 1 1 3.37 2.12c-.75.38-1.12.88-1.12 1.63v.25"></path>
            <path stroke-linecap="round" d="M12 16.75h.01"></path>
        </svg>
        <span class="hidden sm:inline">Ayuda</span>
    </button>

    <div
        x-cloak
        x-show="open"
        id="ayuda-contextual"
        class="fixed inset-0 z-[100] flex items-end justify-center p-0 sm:items-center sm:p-6"
        role="dialog"
        aria-modal="true"
        aria-labelledby="ayuda-contextual-titulo"
        aria-describedby="ayuda-contextual-proposito"
    >
        <div
            x-show="open"
            class="absolute inset-0 bg-slate-950/60"
            x-on:click="
                open = false;
                $nextTick(() => $refs.helpTrigger.focus());
            "
            x-transition:enter="transition-opacity ease-out duration-200 motion-reduce:transition-none"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-150 motion-reduce:transition-none"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            aria-hidden="true"
        ></div>

        <section
            x-show="open"
            x-trap.inert.noscroll="open"
            class="relative flex max-h-[calc(100dvh-1rem)] w-full max-w-2xl flex-col overflow-hidden rounded-t-2xl bg-white shadow-2xl sm:max-h-[calc(100dvh-3rem)] sm:rounded-2xl"
            x-transition:enter="transition ease-out duration-200 motion-reduce:transition-none"
            x-transition:enter-start="translate-y-6 opacity-0 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100"
            x-transition:leave="transition ease-in duration-150 motion-reduce:transition-none"
            x-transition:leave-start="translate-y-0 opacity-100 sm:scale-100"
            x-transition:leave-end="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
        >
            <header class="flex items-start justify-between gap-4 border-b border-slate-200 px-5 py-4 sm:px-6">
                <div class="flex min-w-0 gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-700">
                        <svg
                            aria-hidden="true"
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3.75h8.25A2.25 2.25 0 0 1 18 6v12.75l-6-3-6 3V5.25A1.5 1.5 0 0 1 7.5 3.75Z"></path>
                        </svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-700">Guía de pantalla</p>
                        <h2 id="ayuda-contextual-titulo" class="mt-1 text-lg font-bold text-slate-950 sm:text-xl">
                            {{ $help['title'] }}
                        </h2>
                    </div>
                </div>

                <button
                    x-ref="closeHelp"
                    type="button"
                    class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-lg text-slate-500 transition-colors duration-200 hover:bg-slate-100 hover:text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 focus-visible:ring-offset-2"
                    x-on:click="
                        open = false;
                        $nextTick(() => $refs.helpTrigger.focus());
                    "
                    aria-label="Cerrar ayuda"
                >
                    <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" d="m6.75 6.75 10.5 10.5m0-10.5-10.5 10.5"></path>
                    </svg>
                </button>
            </header>

            <div class="overflow-y-auto overscroll-contain px-5 py-5 sm:px-6 sm:py-6">
                <p id="ayuda-contextual-proposito" class="text-base leading-7 text-slate-700">
                    {{ $help['purpose'] }}
                </p>

                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    <section aria-labelledby="ayuda-pasos">
                        <h3 id="ayuda-pasos" class="text-sm font-bold text-slate-950">Cómo usar esta pantalla</h3>
                        <ol class="mt-3 grid gap-3">
                            @foreach ($help['steps'] as $step)
                                <li class="flex gap-3 text-sm leading-6 text-slate-700">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">
                                        {{ $loop->iteration }}
                                    </span>
                                    <span>{{ $step }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </section>

                    <section aria-labelledby="ayuda-informacion">
                        <h3 id="ayuda-informacion" class="text-sm font-bold text-slate-950">
                            {{ $help['information_title'] }}
                        </h3>
                        <ul class="mt-3 grid gap-3">
                            @foreach ($help['information'] as $item)
                                <li class="flex gap-3 text-sm leading-6 text-slate-700">
                                    <svg aria-hidden="true" class="mt-1 h-4 w-4 shrink-0 text-indigo-600" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 10 3 3 7-7"></path>
                                    </svg>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                </div>

                @isset($help['tip'])
                    <div class="mt-6 flex gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-950">
                        <svg aria-hidden="true" class="mt-0.5 h-5 w-5 shrink-0 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 18h6m-5 3h4m3.25-9.75a5.25 5.25 0 1 0-8.7 3.95c.92.8 1.45 1.7 1.45 2.8h4c0-1.1.53-2 1.45-2.8a5.23 5.23 0 0 0 1.8-3.95Z"></path>
                        </svg>
                        <p><span class="font-bold">Consejo:</span> {{ $help['tip'] }}</p>
                    </div>
                @endisset
            </div>
        </section>
    </div>
</div>
