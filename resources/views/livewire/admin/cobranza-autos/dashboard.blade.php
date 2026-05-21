<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Dashboard de cobranza</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                Control de contratos, cuotas vencidas y cobranza del período.
            </p>
        </div>

        <a href="{{ route('admin.contratos-financiamiento.index') }}"
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 transition">
            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
            </svg>
            Ver contratos
        </a>
    </div>

    {{-- Filtros --}}
    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
            <div class="xl:col-span-2">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Buscar cliente o contrato</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.400ms="q"
                        placeholder="Nombre, folio, VIN, placas..."
                        class="w-full pl-9 rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Estatus</label>
                <select wire:model.live="estatus"
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="activos">Activos</option>
                    <option value="atrasados">Atrasados</option>
                    <option value="liquidados">Liquidados</option>
                    <option value="todos">Todos</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Desde</label>
                <input type="date" wire:model.live="fechaDesde"
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Hasta</label>
                <input type="date" wire:model.live="fechaHasta"
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">

        {{-- Total vencido --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total vencido</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 tabular-nums">
                        ${{ number_format($kpis['total_vencido'], 2) }}
                    </p>
                </div>
                <div class="h-10 w-10 rounded-lg bg-red-50 flex items-center justify-center shrink-0">
                    <svg class="h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Por vencer --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Por vencer (7 días)</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 tabular-nums">
                        ${{ number_format($kpis['total_por_vencer'], 2) }}
                    </p>
                </div>
                <div class="h-10 w-10 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                    <svg class="h-5 w-5 text-amber-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Cobrado del mes --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Cobrado del mes</p>
                    <p class="mt-2 text-2xl font-semibold text-emerald-600 tabular-nums">
                        ${{ number_format($kpis['cobrado_mes'], 2) }}
                    </p>
                </div>
                <div class="h-10 w-10 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                    <svg class="h-5 w-5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Contratos activos --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Contratos activos</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 tabular-nums">
                        {{ number_format($kpis['contratos_activos']) }}
                    </p>
                </div>
                <div class="h-10 w-10 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                    <svg class="h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625zM7.5 15a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 017.5 15zm.75-6.75a.75.75 0 000 1.5H12a.75.75 0 000-1.5H8.25z" clip-rule="evenodd"/>
                        <path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Contratos atrasados --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Con atraso</p>
                    <p class="mt-2 text-2xl font-semibold {{ $kpis['contratos_con_atraso'] > 0 ? 'text-red-600' : 'text-slate-900' }} tabular-nums">
                        {{ number_format($kpis['contratos_con_atraso']) }}
                    </p>
                </div>
                <div class="h-10 w-10 rounded-lg bg-rose-50 flex items-center justify-center shrink-0">
                    <svg class="h-5 w-5 text-rose-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Cuotas vencidas --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Cuotas vencidas</p>
                    <p class="mt-2 text-2xl font-semibold {{ $kpis['cuotas_vencidas'] > 0 ? 'text-orange-600' : 'text-slate-900' }} tabular-nums">
                        {{ number_format($kpis['cuotas_vencidas']) }}
                    </p>
                </div>
                <div class="h-10 w-10 rounded-lg bg-orange-50 flex items-center justify-center shrink-0">
                    <svg class="h-5 w-5 text-orange-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- Chart + Top atraso --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Chart --}}
        <div class="xl:col-span-2 bg-white border border-slate-200 rounded-xl shadow-sm p-5">
            <div class="flex items-start justify-between mb-5">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">Cobranza por día</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Pagos registrados en el rango seleccionado.</p>
                </div>
            </div>
            <div class="h-72">
                <canvas id="cobranzaChart"></canvas>
            </div>
        </div>

        {{-- Top atraso --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
            <div class="mb-4">
                <h2 class="text-sm font-semibold text-slate-900">Mayor atraso</h2>
                <p class="text-xs text-slate-500 mt-0.5">Prioridad de cobranza.</p>
            </div>

            <div class="space-y-2">
                @forelse($contratosTopAtraso as $contrato)
                    <div class="rounded-lg border border-slate-200 p-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-900 truncate">
                                    {{ $contrato->cliente?->nombres }} {{ $contrato->cliente?->apellidos }}
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5 truncate">
                                    {{ $contrato->auto?->marca?->nombre ?? '—' }}
                                    {{ $contrato->auto?->modelo?->nombre ?? '' }}
                                </p>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200 shrink-0">
                                {{ $contrato->cuotas_atrasadas_count }} venc.
                            </span>
                        </div>

                        <div class="mt-2 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-slate-500">Atrasado</p>
                                <p class="text-sm font-bold text-red-600 tabular-nums">
                                    ${{ number_format((float) $contrato->total_atrasado, 2) }}
                                </p>
                            </div>
                            <a href="{{ route('admin.contratos-financiamiento.show', $contrato) }}"
                                class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 hover:underline">
                                Ver →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center">
                        <p class="text-sm text-slate-400">Sin contratos con atraso.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Vencimientos + Cuotas vencidas --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Próximos vencimientos --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
            <div class="mb-4">
                <h2 class="text-sm font-semibold text-slate-900">Próximos vencimientos</h2>
                <p class="text-xs text-slate-500 mt-0.5">Cuotas que vencen en los siguientes 7 días.</p>
            </div>

            <div class="space-y-2">
                @forelse($proximosVencimientos as $cuota)
                    <div class="flex items-center justify-between gap-4 p-3 rounded-lg border border-slate-200 hover:bg-slate-50/60 transition">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-900 truncate">
                                {{ $cuota->contrato?->cliente?->nombres }} {{ $cuota->contrato?->cliente?->apellidos }}
                            </p>
                            <p class="text-xs text-slate-500 truncate">
                                {{ $cuota->contrato?->auto?->marca?->nombre ?? '—' }}
                                {{ $cuota->contrato?->auto?->modelo?->nombre ?? '' }}
                                · Cuota #{{ $cuota->numero }}
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($cuota->fecha_vencimiento)->format('d/m/Y') }}
                            </p>
                            <p class="text-sm font-bold text-amber-600 tabular-nums">
                                ${{ number_format((float) ($cuota->saldo ?? $cuota->monto), 2) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center">
                        <p class="text-sm text-slate-400">No hay vencimientos próximos.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Cuotas vencidas --}}
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
            <div class="mb-4">
                <h2 class="text-sm font-semibold text-slate-900">Cuotas vencidas</h2>
                <p class="text-xs text-slate-500 mt-0.5">Pendientes prioritarias de cobranza.</p>
            </div>

            <div class="space-y-2">
                @forelse($cuotasVencidas as $cuota)
                    <div class="flex items-center justify-between gap-4 p-3 rounded-lg border border-red-200 bg-red-50/40 hover:bg-red-50/70 transition">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-900 truncate">
                                {{ $cuota->contrato?->cliente?->nombres }} {{ $cuota->contrato?->cliente?->apellidos }}
                            </p>
                            <p class="text-xs text-slate-500 truncate">
                                {{ $cuota->contrato?->auto?->marca?->nombre ?? '—' }}
                                · Cuota #{{ $cuota->numero }}
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs text-red-600 font-medium">
                                {{ \Carbon\Carbon::parse($cuota->fecha_vencimiento)->format('d/m/Y') }}
                            </p>
                            <p class="text-sm font-bold text-red-700 tabular-nums">
                                ${{ number_format((float) ($cuota->saldo ?? $cuota->monto), 2) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center">
                        <p class="text-sm text-slate-400">Sin cuotas vencidas.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Tabla de contratos --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-4 border-b border-slate-200">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Contratos</h2>
                <p class="text-xs text-slate-500 mt-0.5">Listado para seguimiento de cobranza.</p>
            </div>
            <div wire:loading class="flex items-center gap-1.5 text-xs text-slate-400">
                <svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Cargando...
            </div>
        </div>

        <div class="overflow-x-auto" wire:loading.class="opacity-60">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70">
                        <th class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Folio</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Vehículo</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Mensualidad</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Pendiente</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($contratos as $contrato)
                        @php
                            $cuotaPendiente = $contrato->cuotas()
                                ->whereIn('estatus', ['pendiente', 'parcial', 'vencida'])
                                ->orderBy('fecha_vencimiento')
                                ->first();
                            $saldoPendiente = $contrato->cuotas()
                                ->whereIn('estatus', ['pendiente', 'parcial', 'vencida'])
                                ->sum(DB::raw('COALESCE(saldo, monto)'));
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="font-semibold text-slate-900">{{ $contrato->folio }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-slate-900">
                                    {{ $contrato->cliente?->nombres }} {{ $contrato->cliente?->apellidos }}
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $contrato->cliente?->telefono ?? 'Sin teléfono' }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-slate-900">
                                    {{ $contrato->auto?->marca?->nombre ?? '—' }}
                                    {{ $contrato->auto?->modelo?->nombre ?? '' }}
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $contrato->auto?->vin ?? $contrato->auto?->placas ?? 'Sin referencia' }}
                                </p>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($contrato->estatus === 'activo')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Activo
                                    </span>
                                @elseif($contrato->estatus === 'atrasado')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>Atrasado
                                    </span>
                                @elseif($contrato->estatus === 'liquidado')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-sky-50 text-sky-700 border border-sky-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-sky-500"></span>Liquidado
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        {{ ucfirst($contrato->estatus) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <span class="text-slate-700 tabular-nums">${{ number_format((float) ($cuotaPendiente->monto ?? 0), 2) }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <span class="font-semibold tabular-nums {{ $saldoPendiente > 0 ? 'text-red-600' : 'text-emerald-600' }}">
                                    ${{ number_format((float) $saldoPendiente, 2) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.contratos-financiamiento.show', $contrato) }}"
                                        class="inline-flex items-center px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">
                                        Ver
                                    </a>
                                    <a href="{{ route('admin.contratos-financiamiento.registrar-pago', $contrato) }}"
                                        class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-indigo-600 text-xs font-medium text-white hover:bg-indigo-700 transition">
                                        Cobrar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-14 text-center">
                                <p class="text-sm font-medium text-slate-900">Sin contratos</p>
                                <p class="text-xs text-slate-500 mt-1">Ajusta los filtros para encontrar resultados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contratos->hasPages())
        <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/60">
            {{ $contratos->links() }}
        </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let cobranzaChart;

        function renderCobranzaChart(labels, data) {
            const ctx = document.getElementById('cobranzaChart');
            if (!ctx) return;
            if (cobranzaChart) cobranzaChart.destroy();

            cobranzaChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Cobrado',
                        data,
                        backgroundColor: 'rgba(99, 102, 241, 0.85)',
                        hoverBackgroundColor: 'rgba(79, 70, 229, 1)',
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#94a3b8',
                            bodyColor: '#f1f5f9',
                            bodyFont: { size: 13, weight: '600' },
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: (ctx) => '  $' + Number(ctx.raw).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: { color: '#94a3b8', font: { size: 11, family: 'Figtree' } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9', drawBorder: false },
                            border: { display: false, dash: [4, 4] },
                            ticks: {
                                color: '#94a3b8',
                                font: { size: 11, family: 'Figtree' },
                                callback: (v) => '$' + Number(v).toLocaleString('es-MX')
                            }
                        }
                    }
                }
            });
        }

        document.addEventListener('livewire:init', () => {
            renderCobranzaChart(
                @json($cobranzaPorDia['labels']),
                @json($cobranzaPorDia['data'])
            );
            Livewire.hook('morph.updated', () => {
                renderCobranzaChart(
                    @json($cobranzaPorDia['labels']),
                    @json($cobranzaPorDia['data'])
                );
            });
        });
    </script>
    @endpush
</div>
