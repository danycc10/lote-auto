<div class="p-4 sm:p-6 max-w-7xl">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.sistema.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-900 transition-colors">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/>
            </svg>
            Sistema
        </a>
    </div>
    <div class="mb-8">
        <h1 class="text-xl font-semibold text-slate-900">Contenido del sitio</h1>
        <p class="text-sm text-slate-500 mt-0.5">Gestiona todos los textos, colores y ajustes visibles en las páginas públicas.</p>
    </div>

    {{-- Two-column layout: form | live preview --}}
    <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_360px] gap-8 items-start">

        {{-- ╔══════════════════════════════════╗
             ║           FORMULARIO             ║
             ╚══════════════════════════════════╝ --}}
        <div class="space-y-10">

            {{-- ── 0. LOGO ── --}}
            <section class="space-y-4">
                <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Logo</h2>
                <p class="text-xs text-slate-500 -mt-2">Si subes una imagen, reemplaza el ícono SVG en el navbar y el footer. Formatos: PNG, JPG, WEBP, SVG. Máx. 2 MB.</p>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">

                    {{-- Current logo preview --}}
                    <div class="flex items-center gap-5 mb-5">
                        <div class="h-16 w-16 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden shrink-0">
                            @if($logoUrl)
                                <img src="{{ Storage::url($logoUrl) }}" alt="Logo actual" class="h-full w-full object-contain p-1">
                            @elseif($logoTemp)
                                <img src="{{ $logoTemp->temporaryUrl() }}" alt="Preview" class="h-full w-full object-contain p-1">
                            @else
                                <div class="h-10 w-10 rounded-lg flex items-center justify-center"
                                     style="background: linear-gradient(to bottom right, {{ $colorPrimario }}, {{ $colorSecundario }})">
                                    <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/>
                                        <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/>
                                        <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            @if($logoUrl)
                                <p class="text-sm font-medium text-slate-700">Logo personalizado activo</p>
                                <p class="text-xs text-slate-400 mt-0.5">Se muestra en navbar y footer del sitio público.</p>
                            @else
                                <p class="text-sm font-medium text-slate-500">Sin logo personalizado</p>
                                <p class="text-xs text-slate-400 mt-0.5">Se usa el ícono SVG con tu color primario.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Upload area --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <label class="flex-1 flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 px-4 py-5 cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/40 transition group">
                            <svg class="h-6 w-6 text-slate-400 group-hover:text-indigo-500 transition" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M1 8a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 018.07 3h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0016.07 6H17a2 2 0 012 2v7a2 2 0 01-2 2H3a2 2 0 01-2-2V8zm13.5 3a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM10 14a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-slate-500 group-hover:text-indigo-600 transition">
                                {{ $logoTemp ? 'Imagen seleccionada ✓' : 'Seleccionar imagen' }}
                            </span>
                            <input type="file" wire:model="logoTemp" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="sr-only">
                        </label>

                        <div class="flex flex-col gap-2 sm:w-36">
                            <button wire:click="subirLogo" type="button"
                                    wire:loading.attr="disabled" wire:target="subirLogo,logoTemp"
                                    class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50 transition">
                                <svg wire:loading wire:target="subirLogo,logoTemp" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="subirLogo,logoTemp">Subir logo</span>
                                <span wire:loading wire:target="subirLogo,logoTemp">Subiendo…</span>
                            </button>
                            @if($logoUrl)
                            <button wire:click="eliminarLogo" type="button"
                                    wire:confirm="¿Eliminar el logo personalizado? Se volverá a usar el ícono SVG."
                                    class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-100 transition">
                                Eliminar
                            </button>
                            @endif
                        </div>
                    </div>

                    @error('logoTemp')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    {{-- Logo para ticket térmico --}}
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <p class="text-sm font-semibold text-slate-700 mb-1">Logo para ticket térmico</p>
                        <p class="text-xs text-slate-400 mb-4">Versión <strong>negra sobre blanco</strong> (PNG/JPG). Las impresoras térmicas solo imprimen en negro — usa un logo monocromático.</p>

                        <div class="flex items-center gap-4 mb-4">
                            <div class="h-14 w-14 rounded-lg border border-slate-200 bg-white flex items-center justify-center overflow-hidden shrink-0">
                                @if($logoTicketUrl)
                                    <img src="{{ Storage::url($logoTicketUrl) }}" alt="Logo ticket" class="h-full w-full object-contain p-1">
                                @elseif($logoTicketTemp)
                                    <img src="{{ $logoTicketTemp->temporaryUrl() }}" alt="Preview" class="h-full w-full object-contain p-1">
                                @else
                                    <svg class="h-7 w-7 text-slate-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/>
                                    </svg>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500">
                                @if($logoTicketUrl) Logo de ticket configurado @else Sin logo de ticket — el PDF mostrará solo el nombre de la empresa @endif
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <label class="flex-1 flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 px-4 py-4 cursor-pointer hover:border-slate-400 hover:bg-slate-100 transition group">
                                <svg class="h-5 w-5 text-slate-400 group-hover:text-slate-600 transition" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M1 8a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 018.07 3h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0016.07 6H17a2 2 0 012 2v7a2 2 0 01-2 2H3a2 2 0 01-2-2V8zm13.5 3a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM10 14a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs text-slate-500">{{ $logoTicketTemp ? 'Seleccionado ✓' : 'Seleccionar imagen B&N' }}</span>
                                <input type="file" wire:model="logoTicketTemp" accept="image/png,image/jpeg" class="sr-only">
                            </label>
                            <div class="flex flex-col gap-2 sm:w-36">
                                <button wire:click="subirLogoTicket" type="button"
                                        wire:loading.attr="disabled" wire:target="subirLogoTicket,logoTicketTemp"
                                        class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-xl bg-slate-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-900 disabled:opacity-50 transition">
                                    <svg wire:loading wire:target="subirLogoTicket,logoTicketTemp" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    <span wire:loading.remove wire:target="subirLogoTicket,logoTicketTemp">Subir</span>
                                    <span wire:loading wire:target="subirLogoTicket,logoTicketTemp">Subiendo…</span>
                                </button>
                                @if($logoTicketUrl)
                                <button wire:click="eliminarLogoTicket" type="button"
                                        wire:confirm="¿Eliminar el logo del ticket?"
                                        class="flex-1 inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-100 transition">
                                    Eliminar
                                </button>
                                @endif
                            </div>
                        </div>
                        @error('logoTicketTemp')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- ── 1. COLORES ── --}}
            <section class="space-y-4">
                <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Colores</h2>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Color primario <span class="text-slate-400 font-normal">(gradientes, logo)</span>
                            </label>
                            <div class="flex items-center gap-3">
                                <input type="color" wire:model.live="colorPrimario"
                                       class="h-10 w-14 rounded-lg border border-slate-300 cursor-pointer p-0.5 bg-white">
                                <input type="text" wire:model.live="colorPrimario" maxlength="7" placeholder="#3b82f6"
                                       class="flex-1 rounded-lg border border-slate-300 py-2 px-3 text-sm font-mono text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('colorPrimario') border-red-400 @enderror">
                            </div>
                            @error('colorPrimario') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Color secundario <span class="text-slate-400 font-normal">(WhatsApp, CTAs)</span>
                            </label>
                            <div class="flex items-center gap-3">
                                <input type="color" wire:model.live="colorSecundario"
                                       class="h-10 w-14 rounded-lg border border-slate-300 cursor-pointer p-0.5 bg-white">
                                <input type="text" wire:model.live="colorSecundario" maxlength="7" placeholder="#10b981"
                                       class="flex-1 rounded-lg border border-slate-300 py-2 px-3 text-sm font-mono text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('colorSecundario') border-red-400 @enderror">
                            </div>
                            @error('colorSecundario') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </section>

            {{-- ── 2. IDENTIDAD ── --}}
            <section class="space-y-4">
                <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Identidad</h2>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Tagline del logo <span class="text-slate-400 font-normal">(bajo el nombre en navbar y footer)</span>
                        </label>
                        <input type="text" wire:model.live.debounce.400ms="tagline" maxlength="80" placeholder="Autos financiados"
                               class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('tagline') border-red-400 bg-red-50 @enderror">
                        @error('tagline') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Descripción del footer <span class="text-slate-400 font-normal">(párrafo bajo el logo al pie)</span>
                        </label>
                        <textarea wire:model.live.debounce.400ms="descripcionFooter" rows="2" maxlength="200"
                                  placeholder="Financiamiento directo, sin banco ni burocracia..."
                                  class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none"></textarea>
                    </div>
                </div>
            </section>

            {{-- ── 3. HERO ── --}}
            <section class="space-y-4">
                <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Hero (sección principal)</h2>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Badge / eyebrow <span class="text-slate-400 font-normal">(chip animado sobre el título)</span>
                        </label>
                        <input type="text" wire:model.live.debounce.400ms="badgeHero" maxlength="60" placeholder="Autos disponibles hoy"
                               class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Título <span class="text-slate-400 font-normal">(línea 1)</span></label>
                            <input type="text" wire:model.live.debounce.400ms="heroTitulo" maxlength="100" placeholder="Tu próximo auto."
                                   class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('heroTitulo') border-red-400 bg-red-50 @enderror">
                            @error('heroTitulo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Texto resaltado <span class="text-slate-400 font-normal">(gradiente)</span></label>
                            <input type="text" wire:model.live.debounce.400ms="heroAcento" maxlength="80" placeholder="Financiado."
                                   class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('heroAcento') border-red-400 bg-red-50 @enderror">
                            @error('heroAcento') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
                        <textarea wire:model.live.debounce.400ms="heroDescripcion" rows="2" maxlength="300"
                                  placeholder="Explora nuestro inventario, conoce los planes de pago y cotiza en minutos."
                                  class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none @error('heroDescripcion') border-red-400 bg-red-50 @enderror"></textarea>
                        @error('heroDescripcion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Botón principal <span class="text-slate-400 font-normal">(blanco)</span></label>
                            <input type="text" wire:model.live.debounce.400ms="ctaHeroPrimario" maxlength="50" placeholder="Ver autos disponibles"
                                   class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Botón secundario <span class="text-slate-400 font-normal">(WhatsApp)</span></label>
                            <input type="text" wire:model.live.debounce.400ms="ctaHeroSecundario" maxlength="50" placeholder="Cotizar por WhatsApp"
                                   class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
            </section>

            {{-- ── 4. ESTADÍSTICAS ── --}}
            <section class="space-y-4">
                <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Estadísticas del hero</h2>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <div class="grid grid-cols-3 gap-4">
                        @foreach([
                            ['valor' => 'stat1Valor', 'label' => 'stat1Label', 'phVal' => '200+', 'phLabel' => 'Clientes atendidos'],
                            ['valor' => 'stat2Valor', 'label' => 'stat2Label', 'phVal' => '24h',  'phLabel' => 'Respuesta garantizada'],
                            ['valor' => 'stat3Valor', 'label' => 'stat3Label', 'phVal' => '100%', 'phLabel' => 'Proceso transparente'],
                        ] as $s)
                        <div class="space-y-2">
                            <input type="text" wire:model.live.debounce.400ms="{{ $s['valor'] }}" maxlength="20" placeholder="{{ $s['phVal'] }}"
                                   class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm font-bold text-slate-900 text-center focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <input type="text" wire:model.live.debounce.400ms="{{ $s['label'] }}" maxlength="40" placeholder="{{ $s['phLabel'] }}"
                                   class="block w-full rounded-lg border border-slate-200 bg-slate-50 py-1.5 px-3 text-xs text-slate-600 text-center focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        @endforeach
                    </div>
                    <p class="mt-3 text-xs text-slate-400">Arriba: valor destacado · Abajo: etiqueta descriptiva</p>
                </div>
            </section>

            {{-- ── 5. BENEFICIOS ── --}}
            <section class="space-y-4">
                <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Beneficios / Trust badges</h2>
                <p class="text-xs text-slate-500 -mt-2">Chips con ✓ en el banner de conversión central. Dejar vacío para ocultarlo.</p>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <div class="grid grid-cols-2 gap-3">
                        @foreach([
                            ['field' => 'trust1', 'ph' => 'Sin buró'],
                            ['field' => 'trust2', 'ph' => 'Enganche desde 10%'],
                            ['field' => 'trust3', 'ph' => 'Plazos hasta 36 meses'],
                            ['field' => 'trust4', 'ph' => 'Proceso en días'],
                        ] as $t)
                        <div class="flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                            <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                            </svg>
                            <input type="text" wire:model.live.debounce.400ms="{{ $t['field'] }}" maxlength="50" placeholder="{{ $t['ph'] }}"
                                   class="flex-1 bg-transparent text-sm text-slate-900 focus:outline-none min-w-0">
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- ── 6. BANNER CTA ── --}}
            <section class="space-y-4">
                <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Banner de conversión (CTA)</h2>
                <p class="text-xs text-slate-500 -mt-2">Sección oscura con gradiente a mitad de la landing.</p>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Eyebrow <span class="text-slate-400 font-normal">(texto pequeño sobre el título)</span></label>
                        <input type="text" wire:model.live.debounce.400ms="ctaEyebrow" maxlength="80" placeholder="¿Listo para empezar?"
                               class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Título <span class="text-slate-400 font-normal">(blanco)</span></label>
                            <input type="text" wire:model.live.debounce.400ms="ctaTitulo" maxlength="80" placeholder="Empieza hoy."
                                   class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Subtítulo <span class="text-slate-400 font-normal">(slate)</span></label>
                            <input type="text" wire:model.live.debounce.400ms="ctaSubtitulo" maxlength="80" placeholder="Sin compromiso."
                                   class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
                        <textarea wire:model.live.debounce.400ms="ctaDescripcion" rows="2" maxlength="300"
                                  placeholder="Más de 200 familias ya eligieron su auto con nosotros..."
                                  class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none"></textarea>
                    </div>
                </div>
            </section>

            {{-- ── 7. WHATSAPP ── --}}
            <section class="space-y-4">
                <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Mensajes de WhatsApp</h2>
                <p class="text-xs text-slate-500 -mt-2">Texto pre-escrito que se abre al hacer clic en cada botón.</p>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Mensaje general <span class="text-slate-400 font-normal">(botones informativos)</span></label>
                        <input type="text" wire:model="waMensajeGeneral" maxlength="200"
                               placeholder="Hola, quiero información sobre los autos disponibles"
                               class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Mensaje de cotización <span class="text-slate-400 font-normal">(botón "Cotizar")</span></label>
                        <input type="text" wire:model="waMensajeCotizar" maxlength="200"
                               placeholder="Hola, quiero cotizar un auto"
                               class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                </div>
            </section>

            {{-- ── 8. SEO ── --}}
            <section class="space-y-4">
                <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">SEO</h2>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Meta title <span class="text-slate-400 font-normal">(vacío = usa el nombre de la app)</span>
                        </label>
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.400ms="seoTitulo" maxlength="120"
                                   placeholder="{{ config('app.name') }} · Autos Financiados"
                                   class="block w-full rounded-lg border border-slate-300 py-2 px-3 pr-16 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 tabular-nums">{{ strlen($seoTitulo) }}/120</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Meta description <span class="text-slate-400 font-normal">(máx 300)</span></label>
                        <div class="relative">
                            <textarea wire:model.live.debounce.400ms="seoDescripcion" rows="2" maxlength="300"
                                      placeholder="Encuentra tu auto ideal con planes de financiamiento accesibles..."
                                      class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none pb-6"></textarea>
                            <span class="absolute right-3 bottom-2 text-xs text-slate-400 tabular-nums">{{ strlen($seoDescripcion) }}/300</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ── 9. CONTACTO ── --}}
            <section class="space-y-4">
                <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Contacto</h2>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Horario de atención</label>
                        <input type="text" wire:model="horario" maxlength="100" placeholder="Lun–Sáb · 9:00 AM – 7:00 PM"
                               class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Dirección</label>
                        <input type="text" wire:model="direccion" maxlength="200" placeholder="Tu Ciudad, Estado, México"
                               class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                </div>
            </section>

            {{-- ── 10. BARRA DE ANUNCIO ── --}}
            <section class="space-y-4">
                <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Barra de anuncio</h2>
                <p class="text-xs text-slate-500 -mt-2">Franja fija en la parte superior del sitio público.</p>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-700">Mostrar barra</p>
                            <p class="text-xs text-slate-400 mt-0.5">Activa o desactiva la barra superior del sitio.</p>
                        </div>
                        <button wire:click="$toggle('anuncioActivo')" type="button"
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 {{ $anuncioActivo ? 'bg-indigo-600' : 'bg-slate-200' }}"
                                role="switch" aria-checked="{{ $anuncioActivo ? 'true' : 'false' }}">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $anuncioActivo ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Texto del anuncio</label>
                        <input type="text" wire:model.live.debounce.400ms="anuncioTexto" maxlength="200"
                               placeholder="¡Disponemos de nuevas unidades! Consúltanos hoy."
                               class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 {{ !$anuncioActivo ? 'opacity-50' : '' }}"
                               {{ !$anuncioActivo ? 'disabled' : '' }}>
                    </div>
                </div>
            </section>

            {{-- ── 11. SECCIÓN BENEFICIOS ── --}}
            <section class="space-y-4">
                <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Sección Beneficios</h2>
                <p class="text-xs text-slate-500 -mt-2">Sección "¿Por qué elegirnos?" con las 3 tarjetas de ventajas.</p>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Eyebrow</label>
                            <input type="text" wire:model.live.debounce.400ms="beneficiosEyebrow" maxlength="80" placeholder="Por qué elegirnos"
                                   class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-slate-500 mb-1">Título</label>
                            <input type="text" wire:model.live.debounce.400ms="beneficiosTitulo" maxlength="120" placeholder="La forma más sencilla de tener tu auto"
                                   class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Subtítulo</label>
                        <input type="text" wire:model="beneficiosSubtitulo" maxlength="300" placeholder="Sin banco, sin burocracia. Financiamiento directo con nosotros."
                               class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="border-t border-slate-100 pt-4 space-y-4">
                        @foreach([
                            ['tit' => 'beneficio1Titulo', 'desc' => 'beneficio1Desc', 'n' => '1', 'phT' => 'Inventario verificado', 'phD' => 'Unidades en buen estado, con historial revisado.'],
                            ['tit' => 'beneficio2Titulo', 'desc' => 'beneficio2Desc', 'n' => '2', 'phT' => 'Planes flexibles',       'phD' => 'Enganche y mensualidades adaptadas a tu presupuesto.'],
                            ['tit' => 'beneficio3Titulo', 'desc' => 'beneficio3Desc', 'n' => '3', 'phT' => 'Atención directa',       'phD' => 'Sin intermediarios. Hablas directo con nosotros.'],
                        ] as $b)
                        <div class="rounded-lg border border-slate-100 bg-slate-50/60 p-4 space-y-2">
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Beneficio {{ $b['n'] }}</p>
                            <input type="text" wire:model="{{$b['tit']}}" maxlength="80" placeholder="{{ $b['phT'] }}"
                                   class="block w-full rounded-lg border border-slate-200 py-2 px-3 text-sm font-medium text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white">
                            <textarea wire:model="{{$b['desc']}}" rows="2" maxlength="300" placeholder="{{ $b['phD'] }}"
                                      class="block w-full rounded-lg border border-slate-200 py-2 px-3 text-sm text-slate-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none bg-white"></textarea>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- ── 12. SECCIÓN PROCESO ── --}}
            <section class="space-y-4">
                <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Sección Proceso</h2>
                <p class="text-xs text-slate-500 -mt-2">Los 4 pasos para obtener un auto.</p>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Eyebrow</label>
                            <input type="text" wire:model="procesoEyebrow" maxlength="80" placeholder="Proceso"
                                   class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-slate-500 mb-1">Título</label>
                            <input type="text" wire:model="procesoTitulo" maxlength="120" placeholder="Tu auto en 4 pasos"
                                   class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Subtítulo</label>
                        <input type="text" wire:model="procesoSubtitulo" maxlength="300" placeholder="Sin papeleo complicado. Sin esperas largas."
                               class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="border-t border-slate-100 pt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach([
                            ['tit' => 'paso1Titulo', 'desc' => 'paso1Desc', 'n' => '1', 'phT' => 'Elige tu auto',        'phD' => 'Explora el catálogo y encuentra el auto que se adapta a ti.'],
                            ['tit' => 'paso2Titulo', 'desc' => 'paso2Desc', 'n' => '2', 'phT' => 'Cotiza en WhatsApp',   'phD' => 'Escríbenos y recibe tu plan de pagos en minutos.'],
                            ['tit' => 'paso3Titulo', 'desc' => 'paso3Desc', 'n' => '3', 'phT' => 'Presenta documentos', 'phD' => 'Solo los básicos. Te guiamos en cada paso del trámite.'],
                            ['tit' => 'paso4Titulo', 'desc' => 'paso4Desc', 'n' => '4', 'phT' => 'Estrena tu auto',     'phD' => 'Entrega rápida. Tu auto listo en días, no en meses.'],
                        ] as $p)
                        <div class="rounded-lg border border-slate-100 bg-slate-50/60 p-4 space-y-2">
                            <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Paso {{ $p['n'] }}</p>
                            <input type="text" wire:model="{{$p['tit']}}" maxlength="80" placeholder="{{ $p['phT'] }}"
                                   class="block w-full rounded-lg border border-slate-200 py-2 px-3 text-sm font-medium text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white">
                            <textarea wire:model="{{$p['desc']}}" rows="2" maxlength="200" placeholder="{{ $p['phD'] }}"
                                      class="block w-full rounded-lg border border-slate-200 py-2 px-3 text-sm text-slate-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none bg-white"></textarea>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- ── 13. SECCIÓN CATÁLOGO ── --}}
            <section class="space-y-4">
                <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Sección Catálogo</h2>
                <p class="text-xs text-slate-500 -mt-2">Encabezado de la sección de inventario de autos.</p>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Eyebrow</label>
                            <input type="text" wire:model="autosEyebrow" maxlength="80" placeholder="Inventario"
                                   class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-slate-500 mb-1">Título</label>
                            <input type="text" wire:model="autosTitulo" maxlength="120" placeholder="Autos disponibles"
                                   class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Descripción</label>
                        <textarea wire:model="autosDescripcion" rows="2" maxlength="300"
                                  placeholder="Unidades listas para cotizar. Escríbenos para conocer el plan que más te conviene."
                                  class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none"></textarea>
                    </div>
                </div>
            </section>

            {{-- ── 14. SECCIÓN CONTACTO (landing) ── --}}
            <section class="space-y-4">
                <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Sección Contacto</h2>
                <p class="text-xs text-slate-500 -mt-2">Encabezado de la sección de contacto al final de la landing.</p>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Título</label>
                            <input type="text" wire:model="contactoTitulo" maxlength="80" placeholder="¿Tienes dudas?"
                                   class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Subtítulo</label>
                            <input type="text" wire:model="contactoSubtitulo" maxlength="80" placeholder="Te ayudamos."
                                   class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Descripción</label>
                        <textarea wire:model="contactoDescripcion" rows="2" maxlength="300"
                                  placeholder="Escríbenos y con gusto te asesoramos sobre disponibilidad, planes de pago y más."
                                  class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none"></textarea>
                    </div>
                </div>
            </section>

            {{-- ── 15. ANALYTICS ── --}}
            <section class="space-y-4">
                <h2 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Analytics</h2>
                <p class="text-xs text-slate-500 -mt-2">Los scripts se inyectan solo cuando el campo tiene un valor.</p>
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Google Analytics ID
                            <span class="text-slate-400 font-normal">(formato G-XXXXXXXXXX)</span>
                        </label>
                        <input type="text" wire:model="gaId" maxlength="30" placeholder="G-XXXXXXXXXX"
                               class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm font-mono text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('gaId') border-red-400 bg-red-50 @enderror">
                        @error('gaId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Google Tag Manager ID
                            <span class="text-slate-400 font-normal">(formato GTM-XXXXXXX)</span>
                        </label>
                        <input type="text" wire:model="gtmId" maxlength="20" placeholder="GTM-XXXXXXX"
                               class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm font-mono text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('gtmId') border-red-400 bg-red-50 @enderror">
                        @error('gtmId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Meta / Facebook Pixel ID
                            <span class="text-slate-400 font-normal">(solo números)</span>
                        </label>
                        <input type="text" wire:model="pixelId" maxlength="20" placeholder="123456789012345"
                               class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm font-mono text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('pixelId') border-red-400 bg-red-50 @enderror">
                        @error('pixelId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="rounded-lg bg-amber-50 border border-amber-200 px-4 py-3 text-xs text-amber-800">
                        Los scripts se inyectan en <code class="font-mono bg-amber-100 px-1 rounded">guest.blade.php</code>.
                        Dejar un campo vacío deshabilita ese script. Google Analytics y GTM no deben usarse simultáneamente, prefiere GTM si ya lo tienes configurado.
                    </div>
                </div>
            </section>

            {{-- Guardar --}}
            <div class="flex justify-end pb-8">
                <button wire:click="guardar"
                        wire:loading.attr="disabled" wire:target="guardar"
                        type="button"
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-60 transition">
                    <svg wire:loading wire:target="guardar" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Guardar cambios
                </button>
            </div>

        </div>{{-- /form --}}

        {{-- ╔══════════════════════════════════╗
             ║         LIVE PREVIEW             ║
             ╚══════════════════════════════════╝ --}}
        <div class="hidden xl:block sticky top-6 space-y-3">

            <div class="flex items-center justify-between">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest">Vista previa</p>
                <a href="{{ route('public.home') }}" target="_blank"
                   class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:text-indigo-800 transition font-medium">
                    Abrir sitio
                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 00-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 00.75-.75v-4a.75.75 0 011.5 0v4A2.25 2.25 0 0112.75 17h-8.5A2.25 2.25 0 012 14.75v-8.5A2.25 2.25 0 014.25 4h5a.75.75 0 010 1.5h-5z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M6.194 12.753a.75.75 0 001.06.053L16.5 4.44v2.81a.75.75 0 001.5 0v-4.5a.75.75 0 00-.75-.75h-4.5a.75.75 0 000 1.5h2.553l-9.056 8.194a.75.75 0 00-.053 1.06z" clip-rule="evenodd"/></svg>
                </a>
            </div>

            {{-- Browser frame --}}
            <div class="rounded-2xl border border-slate-200 shadow-xl overflow-hidden overflow-y-auto text-left" style="max-height: calc(100vh - 7rem)">

                {{-- Browser toolbar --}}
                <div class="bg-slate-100 px-4 py-2.5 flex items-center gap-3 border-b border-slate-200">
                    <div class="flex gap-1.5 shrink-0">
                        <div class="h-2.5 w-2.5 rounded-full bg-red-400"></div>
                        <div class="h-2.5 w-2.5 rounded-full bg-yellow-400"></div>
                        <div class="h-2.5 w-2.5 rounded-full bg-green-400"></div>
                    </div>
                    <div class="flex-1 bg-white rounded-md px-3 py-1 text-[10px] text-slate-400 truncate border border-slate-200">
                        {{ url('/') }}
                    </div>
                </div>

                {{-- ── Announcement bar mock ── --}}
                @if($anuncioActivo && $anuncioTexto)
                <div class="px-3 py-1.5 text-center text-white font-semibold truncate" style="font-size:8px; background-color: {{ $colorPrimario }}">
                    {{ $anuncioTexto }}
                </div>
                @endif

                {{-- ── Navbar mock ── --}}
                <div class="bg-slate-950 px-4 py-2.5 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 min-w-0">
                        @if($logoUrl)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($logoUrl) }}" alt="" class="h-7 w-auto max-w-[28px] object-contain shrink-0">
                        @else
                            <div class="h-7 w-7 rounded-lg flex items-center justify-center shrink-0"
                                 style="background: linear-gradient(to bottom right, {{ $colorPrimario }}, {{ $colorSecundario }})">
                                <svg class="h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/>
                                    <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/>
                                    <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p class="text-white text-[11px] font-black leading-none truncate">{{ config('app.name', 'AutoLote') }}</p>
                            <p class="text-[9px] font-semibold mt-0.5 leading-none truncate" style="color: {{ $colorSecundario }}">{{ $tagline }}</p>
                        </div>
                    </div>
                    <div class="rounded-lg px-2.5 py-1 text-[9px] font-bold text-white shrink-0"
                         style="background-color: {{ $colorSecundario }}">WhatsApp</div>
                </div>

                {{-- ── Hero mock ── --}}
                <div class="bg-[#06091a] px-4 py-5 space-y-3">
                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/25 bg-emerald-500/10 px-2.5 py-1 text-[9px] font-semibold text-emerald-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
                        {{ $badgeHero ?: 'Autos disponibles hoy' }}
                    </div>
                    {{-- Title --}}
                    <div class="text-white font-black leading-[1.1]" style="font-size:17px">
                        {{ $heroTitulo ?: 'Tu próximo auto.' }}<br>
                        <span style="background: linear-gradient(to right, {{ $colorPrimario }}, {{ $colorSecundario }}); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            {{ $heroAcento ?: 'Financiado.' }}
                        </span>
                    </div>
                    {{-- Description --}}
                    @if($heroDescripcion)
                    <p class="text-slate-400 leading-relaxed line-clamp-2" style="font-size:10px">{{ $heroDescripcion }}</p>
                    @endif
                    {{-- CTAs --}}
                    <div class="flex gap-2 flex-wrap">
                        <div class="rounded-lg bg-white px-3 py-1.5 font-bold text-slate-900 leading-none" style="font-size:9px">
                            {{ $ctaHeroPrimario ?: 'Ver autos' }}
                        </div>
                        <div class="rounded-lg px-3 py-1.5 font-bold text-white leading-none" style="font-size:9px; background-color: {{ $colorSecundario }}">
                            {{ $ctaHeroSecundario ?: 'Cotizar' }}
                        </div>
                    </div>
                    {{-- Stats --}}
                    <div class="grid grid-cols-3 gap-1.5 pt-1">
                        @foreach([[$stat1Valor,$stat1Label],[$stat2Valor,$stat2Label],[$stat3Valor,$stat3Label]] as [$v,$l])
                        <div class="rounded-lg border border-white/10 bg-white/5 p-2 text-center">
                            <p class="text-white font-black leading-none" style="font-size:12px">{{ $v ?: '—' }}</p>
                            <p class="text-slate-500 mt-0.5 leading-snug" style="font-size:7px">{{ $l ?: '...' }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ── Trust badges mock ── --}}
                @php $badges = array_filter([$trust1, $trust2, $trust3, $trust4]); @endphp
                @if($badges)
                <div class="bg-[#0a0f23] border-t border-white/[0.06] px-4 py-3 flex flex-wrap gap-1.5">
                    @foreach($badges as $b)
                    <span class="inline-flex items-center gap-1 rounded-full border border-white/10 bg-white/5 px-2 py-0.5 text-slate-300 leading-none" style="font-size:8px">
                        <svg class="h-2.5 w-2.5 shrink-0 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                        {{ $b }}
                    </span>
                    @endforeach
                </div>
                @endif

                {{-- ── Beneficios section mock ── --}}
                <div class="bg-white px-4 py-4 border-t border-slate-100">
                    <div class="text-center mb-3">
                        @if($beneficiosEyebrow)
                        <p class="font-semibold uppercase tracking-widest mb-1" style="font-size:7px; color: {{ $colorPrimario }}">{{ $beneficiosEyebrow }}</p>
                        @endif
                        <p class="font-black text-slate-900 leading-tight" style="font-size:12px">{{ $beneficiosTitulo ?: 'La forma más sencilla de tener tu auto' }}</p>
                        @if($beneficiosSubtitulo)
                        <p class="text-slate-500 mt-0.5 line-clamp-1" style="font-size:8px">{{ $beneficiosSubtitulo }}</p>
                        @endif
                    </div>
                    <div class="grid grid-cols-3 gap-1.5">
                        @foreach([
                            [$beneficio1Titulo, $beneficio1Desc, 'Inventario verificado', 'Unidades revisadas.'],
                            [$beneficio2Titulo, $beneficio2Desc, 'Planes flexibles',      'A tu presupuesto.'],
                            [$beneficio3Titulo, $beneficio3Desc, 'Atención directa',      'Sin intermediarios.'],
                        ] as [$t, $d, $phT, $phD])
                        <div class="rounded-lg border border-slate-100 bg-slate-50 p-2 text-center">
                            <div class="h-5 w-5 rounded-md mx-auto mb-1.5 flex items-center justify-center" style="background: linear-gradient(to bottom right, {{ $colorPrimario }}22, {{ $colorSecundario }}22)">
                                <svg class="h-2.5 w-2.5" style="color: {{ $colorPrimario }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                            </div>
                            <p class="font-bold text-slate-900 leading-tight line-clamp-1" style="font-size:8px">{{ $t ?: $phT }}</p>
                            <p class="text-slate-500 mt-0.5 line-clamp-2 leading-snug" style="font-size:7px">{{ $d ?: $phD }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ── Proceso section mock ── --}}
                <div class="bg-[#06091a] px-4 py-4 border-t border-white/[0.06]">
                    <div class="text-center mb-3">
                        @if($procesoEyebrow)
                        <p class="font-semibold uppercase tracking-widest mb-1 text-emerald-400" style="font-size:7px">{{ $procesoEyebrow }}</p>
                        @endif
                        <p class="font-black text-white leading-tight" style="font-size:12px">{{ $procesoTitulo ?: 'Tu auto en 4 pasos' }}</p>
                        @if($procesoSubtitulo)
                        <p class="text-slate-400 mt-0.5 line-clamp-1" style="font-size:8px">{{ $procesoSubtitulo }}</p>
                        @endif
                    </div>
                    <div class="space-y-1.5">
                        @foreach([
                            [$paso1Titulo, $paso1Desc, '1', 'Elige tu auto',        'Explora el catálogo.'],
                            [$paso2Titulo, $paso2Desc, '2', 'Cotiza por WhatsApp',  'Plan en minutos.'],
                            [$paso3Titulo, $paso3Desc, '3', 'Presenta documentos',  'Solo los básicos.'],
                            [$paso4Titulo, $paso4Desc, '4', 'Estrena tu auto',      'Entrega en días.'],
                        ] as [$t, $d, $n, $phT, $phD])
                        <div class="flex items-center gap-2 rounded-lg border border-white/[0.07] bg-white/[0.04] px-2.5 py-1.5">
                            <div class="h-5 w-5 rounded-full flex items-center justify-center text-white font-black shrink-0" style="font-size:8px; background-color: {{ $colorPrimario }}">{{ $n }}</div>
                            <p class="text-white font-semibold line-clamp-1 flex-1" style="font-size:8.5px">{{ $t ?: $phT }}</p>
                            <p class="text-slate-500 line-clamp-1 text-right max-w-[70px]" style="font-size:7px">{{ $d ?: $phD }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ── Catálogo section mock ── --}}
                <div class="bg-slate-50 px-4 py-4 border-t border-slate-100">
                    <div class="text-center mb-2.5">
                        @if($autosEyebrow)
                        <p class="font-semibold uppercase tracking-widest mb-1" style="font-size:7px; color: {{ $colorPrimario }}">{{ $autosEyebrow }}</p>
                        @endif
                        <p class="font-black text-slate-900 leading-tight" style="font-size:12px">{{ $autosTitulo ?: 'Autos disponibles' }}</p>
                        @if($autosDescripcion)
                        <p class="text-slate-500 mt-0.5 line-clamp-1" style="font-size:7.5px">{{ $autosDescripcion }}</p>
                        @endif
                    </div>
                    <div class="grid grid-cols-3 gap-1.5">
                        @foreach(['', '', ''] as $_)
                        <div class="rounded-lg bg-white border border-slate-100 overflow-hidden">
                            <div class="h-9 w-full flex items-center justify-center" style="background: linear-gradient(to bottom right, {{ $colorPrimario }}15, {{ $colorSecundario }}15)">
                                <svg class="h-4 w-4 text-slate-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/><path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/></svg>
                            </div>
                            <div class="p-1.5 space-y-1">
                                <div class="h-1.5 rounded bg-slate-200 w-3/4"></div>
                                <div class="h-1 rounded bg-slate-100 w-1/2"></div>
                                <div class="h-3 rounded mt-1" style="background-color: {{ $colorPrimario }}30"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ── Contacto section mock ── --}}
                <div class="bg-white px-4 py-4 border-t border-slate-100 text-center">
                    <p class="font-black text-slate-900 leading-tight" style="font-size:13px">
                        {{ $contactoTitulo ?: '¿Tienes dudas?' }}
                        <span style="color: {{ $colorPrimario }}"> {{ $contactoSubtitulo ?: 'Te ayudamos.' }}</span>
                    </p>
                    @if($contactoDescripcion)
                    <p class="text-slate-500 mt-1 line-clamp-2 leading-relaxed" style="font-size:8px">{{ $contactoDescripcion }}</p>
                    @endif
                    <div class="mt-2.5 inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-white font-bold" style="font-size:9px; background-color: {{ $colorSecundario }}">
                        <svg class="h-2.5 w-2.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        Escribir por WhatsApp
                    </div>
                </div>

                {{-- ── CTA Banner mock ── --}}
                <div class="bg-gradient-to-br from-[#06091a] via-blue-950/40 to-[#06091a] px-4 py-4 text-center border-t border-white/[0.06]">
                    @if($ctaEyebrow)
                    <p class="text-emerald-400 font-semibold uppercase tracking-widest leading-none" style="font-size:7px">{{ $ctaEyebrow }}</p>
                    @endif
                    <p class="text-white font-black mt-1.5 leading-tight" style="font-size:14px">
                        {{ $ctaTitulo ?: 'Empieza hoy.' }}
                        <span class="text-slate-400"> {{ $ctaSubtitulo ?: 'Sin compromiso.' }}</span>
                    </p>
                    @if($ctaDescripcion)
                    <p class="text-slate-400 mt-1 line-clamp-2 leading-relaxed" style="font-size:8px">{{ $ctaDescripcion }}</p>
                    @endif
                </div>

                {{-- ── Footer mock ── --}}
                <div class="bg-[#04070f] border-t border-white/[0.06] px-4 py-3">
                    <div class="flex items-center gap-2 mb-1.5">
                        @if($logoUrl)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($logoUrl) }}" alt="" class="h-6 w-auto max-w-[24px] object-contain shrink-0">
                        @else
                            <div class="h-6 w-6 rounded-lg flex items-center justify-center shrink-0"
                                 style="background: linear-gradient(to bottom right, {{ $colorPrimario }}, {{ $colorSecundario }})">
                                <svg class="h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/></svg>
                            </div>
                        @endif
                        <div>
                            <p class="text-white font-black leading-none" style="font-size:10px">{{ config('app.name', 'AutoLote') }}</p>
                            <p class="font-semibold mt-0.5 leading-none" style="font-size:8px; color: {{ $colorSecundario }}; opacity:.8">{{ $tagline }}</p>
                        </div>
                    </div>
                    @if($descripcionFooter)
                    <p class="text-slate-500 line-clamp-2 leading-relaxed" style="font-size:8px">{{ $descripcionFooter }}</p>
                    @endif
                </div>

                {{-- ── SEO snippet ── --}}
                <div class="bg-white border-t border-slate-100 px-4 py-3">
                    <p class="text-[8px] font-semibold text-slate-400 uppercase tracking-widest mb-1.5">Vista en Google</p>
                    <p class="text-blue-600 font-medium leading-snug truncate" style="font-size:11px">
                        {{ $seoTitulo ?: config('app.name') . ' · Autos Financiados' }}
                    </p>
                    <p class="text-green-700 mt-0.5 truncate" style="font-size:9px">{{ url('/') }}</p>
                    @if($seoDescripcion)
                    <p class="text-slate-500 mt-1 line-clamp-2 leading-relaxed" style="font-size:9px">{{ $seoDescripcion }}</p>
                    @endif
                </div>

            </div>{{-- /browser frame --}}

            <p class="text-[10px] text-slate-400 text-center">
                Se actualiza automáticamente · Los cambios en el sitio aplican al guardar
            </p>

        </div>{{-- /preview --}}

    </div>{{-- /grid --}}
</div>
