<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Reportes</h1>
            <p class="text-sm text-slate-500 mt-0.5">Selecciona un reporte y descárgalo en Excel (.xlsx).</p>
        </div>
    </div>

    @if(session('status'))
        <div role="status" aria-live="polite" class="flex gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
            <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
            </svg>
            <p>{{ session('status') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <p class="font-semibold">Revisa los datos del reporte.</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.reportes.export') }}">
        @csrf
        <input type="hidden" name="tipo" value="{{ $tipo }}">

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
                        <label for="desde" class="block text-xs font-medium text-slate-700 mb-1.5">Desde</label>
                        <input id="desde" type="date" name="desde" wire:model="desde"
                               class="block min-h-11 w-full rounded-lg border-slate-300 text-base sm:text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="hasta" class="block text-xs font-medium text-slate-700 mb-1.5">Hasta</label>
                        <input id="hasta" type="date" name="hasta" wire:model="hasta"
                               class="block min-h-11 w-full rounded-lg border-slate-300 text-base sm:text-sm focus:border-indigo-500 focus:ring-indigo-500">
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

                    <button type="submit"
                            class="min-h-11 w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M12 2.25a.75.75 0 01.75.75v11.69l3.22-3.22a.75.75 0 111.06 1.06l-4.5 4.5a.75.75 0 01-1.06 0l-4.5-4.5a.75.75 0 111.06-1.06l3.22 3.22V3a.75.75 0 01.75-.75zm-9 13.5a.75.75 0 01.75.75v2.25a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5V16.5a.75.75 0 011.5 0v2.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V16.5a.75.75 0 01.75-.75z" clip-rule="evenodd"/>
                            </svg>
                            Solicitar Excel
                        </span>
                    </button>

                    <p class="text-xs leading-relaxed text-slate-500 text-center">El archivo se procesa en segundo plano para no bloquear la pantalla.</p>
                </div>
            </div>

        </div>
    </div>
    </form>

    @php
        $hayReportesEnProceso = $reportesGenerados->contains(
            fn ($reporte) => in_array($reporte->estatus->value, ['pendiente', 'procesando'], true)
        );
    @endphp

    <section aria-labelledby="reportes-recientes-titulo" @if($hayReportesEnProceso) wire:poll.5s @endif
             class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between gap-4 border-b border-slate-100 bg-slate-50/70 px-5 py-4">
            <div>
                <h2 id="reportes-recientes-titulo" class="text-sm font-semibold text-slate-900">Reportes recientes</h2>
                <p class="mt-0.5 text-xs text-slate-500">Los archivos están disponibles durante {{ config('reportes.expiration_hours') }} horas.</p>
            </div>
            @if($hayReportesEnProceso)
                <span role="status" class="inline-flex items-center gap-2 text-xs font-medium text-indigo-700">
                    <svg class="h-4 w-4 animate-spin motion-reduce:animate-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Actualizando
                </span>
            @endif
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($reportesGenerados as $reporte)
                @php
                    $expirado = $reporte->expires_at?->isPast() ?? false;
                    $tituloReporte = $reportes[$reporte->tipo->value]['titulo'] ?? ucfirst($reporte->tipo->value);
                @endphp
                <article class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900">{{ $tituloReporte }}</p>
                        <p class="mt-1 text-xs text-slate-500">
                            Solicitado {{ $reporte->created_at->diffForHumans() }}
                            @if($reporte->desde && $reporte->hasta)
                                · {{ $reporte->desde->format('d/m/Y') }}–{{ $reporte->hasta->format('d/m/Y') }}
                            @endif
                        </p>
                        @if($reporte->estatus->value === 'fallido')
                            <p class="mt-1 text-xs text-red-700">{{ $reporte->error }}</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        @if($expirado)
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">Expirado</span>
                        @elseif($reporte->estatus->value === 'listo')
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">Listo</span>
                            <a href="{{ route('admin.reportes.download', $reporte) }}"
                               class="inline-flex min-h-11 items-center justify-center rounded-lg border border-slate-300 px-4 text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Descargar
                            </a>
                        @elseif($reporte->estatus->value === 'fallido')
                            <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700">Falló</span>
                        @else
                            <span class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-indigo-500" aria-hidden="true"></span>
                                En proceso
                            </span>
                        @endif
                    </div>
                </article>
            @empty
                <div class="px-5 py-10 text-center">
                    <p class="text-sm font-medium text-slate-700">Aún no has solicitado reportes.</p>
                    <p class="mt-1 text-xs text-slate-500">Selecciona un tipo y un período para generar el primero.</p>
                </div>
            @endforelse
        </div>
    </section>

</div>
