<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Plantillas del sitio</h1>
            <p class="text-sm text-slate-500 mt-0.5">Selecciona el diseño visual de tu página pública.</p>
        </div>
        <a href="{{ route('public.home') }}" target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-600 hover:bg-slate-50 transition shadow-sm">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
            </svg>
            Ver sitio
        </a>
    </div>

    {{-- Plantilla activa --}}
    <div class="rounded-xl border border-indigo-200 bg-indigo-50/60 p-4 flex items-center gap-3">
        <div class="h-9 w-9 rounded-lg bg-indigo-600 flex items-center justify-center shrink-0">
            <svg class="h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-indigo-900">Plantilla activa: <span class="font-black">{{ $templates[$templateActual]['nombre'] ?? $templateActual }}</span></p>
            <p class="text-xs text-indigo-600 mt-0.5">{{ $templates[$templateActual]['descripcion'] ?? '' }}</p>
        </div>
    </div>

    {{-- Grid de plantillas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($templates as $key => $tpl)
        @php $isActive = $templateActual === $key; @endphp
        <div class="group relative rounded-2xl border-2 overflow-hidden transition-all duration-200
                    {{ $isActive ? 'border-indigo-500 shadow-lg shadow-indigo-100' : 'border-slate-200 hover:border-slate-300 hover:shadow-md' }}
                    bg-white">

            {{-- Active badge --}}
            @if($isActive)
            <div class="absolute top-3 right-3 z-10">
                <span class="inline-flex items-center gap-1 rounded-full bg-indigo-600 px-2.5 py-1 text-xs font-bold text-white shadow">
                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                    Activa
                </span>
            </div>
            @endif

            {{-- Preview visual --}}
            <div class="h-36 {{ $tpl['estilo'] }} border-b border-slate-100 relative overflow-hidden flex flex-col">
                {{-- Simulated navbar --}}
                <div class="h-7 flex items-center px-3 gap-2" style="background: {{ $tpl['preview'][0] }}; opacity: 0.95">
                    <div class="h-2.5 w-2.5 rounded-full" style="background: {{ $tpl['preview'][1] }}"></div>
                    <div class="h-1.5 w-16 rounded-full bg-white/20"></div>
                    <div class="ml-auto h-1.5 w-10 rounded-full" style="background: {{ $tpl['preview'][1] }}; opacity: 0.7"></div>
                </div>
                {{-- Simulated hero content --}}
                <div class="flex-1 flex items-center px-4 gap-4" style="background: {{ $tpl['preview'][0] }}">
                    <div class="flex-1 space-y-2">
                        <div class="h-2 w-20 rounded-full" style="background: {{ $tpl['preview'][1] }}; opacity: 0.5"></div>
                        <div class="h-4 w-32 rounded" style="background: white; opacity: 0.15"></div>
                        <div class="h-4 w-24 rounded" style="background: {{ $tpl['preview'][1] }}; opacity: 0.6"></div>
                        <div class="flex gap-2 mt-3">
                            <div class="h-5 w-16 rounded" style="background: white; opacity: 0.9"></div>
                            <div class="h-5 w-16 rounded border" style="border-color: {{ $tpl['preview'][1] }}; opacity: 0.6"></div>
                        </div>
                    </div>
                    {{-- Mock car card --}}
                    <div class="hidden sm:block w-20 h-16 rounded-lg shrink-0" style="background: {{ $tpl['preview'][2] }}; border: 1px solid rgba(255,255,255,0.1)">
                        <div class="h-full flex items-center justify-center">
                            <svg class="h-6 w-6" style="color: {{ $tpl['preview'][1] }}; opacity: 0.6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/>
                                <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/>
                                <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                {{-- Simulated stats bar --}}
                <div class="h-6 flex items-center gap-4 px-4" style="background: {{ $tpl['preview'][2] }}; opacity: 0.8">
                    @foreach(['200+','24h','100%'] as $s)
                    <div class="flex items-center gap-1">
                        <div class="h-1.5 w-4 rounded-full" style="background: {{ $tpl['preview'][1] }}"></div>
                        <div class="h-1 w-8 rounded-full bg-white/20"></div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Card content --}}
            <div class="p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">{{ $tpl['nombre'] }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5 leading-snug">{{ $tpl['descripcion'] }}</p>
                    </div>
                    <span class="shrink-0 rounded-full px-2.5 py-0.5 text-[10px] font-bold {{ $tpl['badge'] }}">
                        {{ $key }}
                    </span>
                </div>

                <div class="mt-4 flex items-center gap-2">
                    {{-- Color preview dots --}}
                    <div class="flex gap-1 flex-1">
                        @foreach($tpl['preview'] as $color)
                        <div class="h-4 w-4 rounded-full border border-white shadow-sm" style="background: {{ $color }}"></div>
                        @endforeach
                    </div>

                    @if($isActive)
                    <button disabled
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-xs font-medium text-slate-400 cursor-not-allowed">
                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                        Activa
                    </button>
                    @else
                    <button wire:click="seleccionar('{{ $key }}')"
                            wire:loading.attr="disabled"
                            wire:target="seleccionar('{{ $key }}')"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-xs font-semibold text-white transition shadow-sm disabled:opacity-60">
                        <svg wire:loading wire:target="seleccionar('{{ $key }}')" class="h-3.5 w-3.5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="seleccionar('{{ $key }}')">Activar</span>
                        <span wire:loading wire:target="seleccionar('{{ $key }}')">Activando…</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Nota --}}
    <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 flex items-start gap-3">
        <svg class="h-4 w-4 text-slate-400 mt-0.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/>
        </svg>
        <p class="text-xs text-slate-500 leading-relaxed">
            El cambio de plantilla es instantáneo. El contenido (textos, colores, logo) se configura desde
            <a href="{{ route('admin.sistema.apariencia') }}" class="text-indigo-600 hover:underline font-medium">Apariencia</a>.
            Todos los diseños son responsivos y usan los mismos datos del sistema.
        </p>
    </div>

</div>
