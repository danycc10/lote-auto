<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Reportes</h1>
            <p class="text-sm text-slate-500 mt-0.5">Selecciona un reporte y descárgalo en Excel (.xlsx).</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Tarjetas de reportes --}}
        <div class="xl:col-span-2 space-y-5">

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Tipo de reporte</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Elige qué datos quieres exportar.</p>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($reportes as $key => $reporte)
                        <button type="button"
                                wire:click="$set('tipo', '{{ $key }}')"
                                class="text-left rounded-xl border-2 p-4 transition-all cursor-pointer
                                    {{ $tipo === $key
                                        ? 'border-indigo-500 bg-indigo-50'
                                        : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50' }}">
                            <div class="flex items-start gap-3">
                                <div class="shrink-0 mt-0.5 {{ $tipo === $key ? 'text-indigo-600' : 'text-slate-400' }}">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $reporte['icono'] }}"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold {{ $tipo === $key ? 'text-indigo-700' : 'text-slate-800' }}">
                                        {{ $reporte['titulo'] }}
                                    </p>
                                    <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">{{ $reporte['descripcion'] }}</p>
                                </div>
                                @if($tipo === $key)
                                    <div class="shrink-0 ml-auto">
                                        <svg class="h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Columnas incluidas --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Columnas incluidas</h2>
                </div>
                <div class="px-5 py-4">
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $reportes[$tipo]['columnas'] }}</p>
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-5 xl:sticky xl:top-[4.5rem] xl:self-start">

            {{-- Filtro de fechas --}}
            @if($reportes[$tipo]['usa_fechas'])
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Rango de fechas</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Filtra el período del reporte.</p>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Desde</label>
                        <input type="date" wire:model="desde"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Hasta</label>
                        <input type="date" wire:model="hasta"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
            </div>
            @else
            <div class="bg-amber-50 rounded-xl border border-amber-200 p-4">
                <div class="flex gap-3">
                    <svg class="h-4 w-4 text-amber-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 01.67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 11-.671-1.34l.041-.022zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-xs text-amber-800">Este reporte exporta todos los autos activos. El filtro de fechas no aplica.</p>
                </div>
            </div>
            @endif

            {{-- Resumen + Descarga --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Generar reporte</h2>
                </div>
                <div class="p-5 space-y-4">
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Reporte</span>
                            <span class="text-xs font-semibold text-slate-900">{{ $reportes[$tipo]['titulo'] }}</span>
                        </div>
                        @if($reportes[$tipo]['usa_fechas'])
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Desde</span>
                            <span class="text-xs font-semibold text-slate-900">{{ $desde ? \Carbon\Carbon::parse($desde)->format('d/m/Y') : '—' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-500">Hasta</span>
                            <span class="text-xs font-semibold text-slate-900">{{ $hasta ? \Carbon\Carbon::parse($hasta)->format('d/m/Y') : '—' }}</span>
                        </div>
                        @endif
                    </div>

                    <button type="button"
                            wire:click="descargar"
                            wire:loading.attr="disabled"
                            wire:target="descargar"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        <span wire:loading.remove wire:target="descargar" class="flex items-center gap-2">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M12 2.25a.75.75 0 01.75.75v11.69l3.22-3.22a.75.75 0 111.06 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06l3.22 3.22V3a.75.75 0 01.75-.75zm-9 13.5a.75.75 0 01.75.75v2.25a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5V16.5a.75.75 0 011.5 0v2.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V16.5a.75.75 0 01.75-.75z" clip-rule="evenodd"/>
                            </svg>
                            Descargar Excel
                        </span>
                        <span wire:loading wire:target="descargar" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Preparando...
                        </span>
                    </button>

                    <p class="text-xs text-slate-400 text-center">El archivo se abrirá en una nueva pestaña.</p>
                </div>
            </div>

        </div>
    </div>

</div>
