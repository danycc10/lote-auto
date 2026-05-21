<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Contratos de financiamiento</h1>
            <p class="text-sm text-slate-500 mt-0.5">Consulta y administra los contratos del sistema.</p>
        </div>
        <a href="{{ route('admin.contratos-financiamiento.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition shrink-0">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
            </svg>
            Nuevo contrato
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            <svg class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- KPIs --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total</p>
            <p class="mt-2 text-2xl font-semibold text-slate-900 tabular-nums">{{ number_format($this->totalContratos) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Activos</p>
            <p class="mt-2 text-2xl font-semibold text-emerald-600 tabular-nums">{{ number_format($this->totalActivos) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Atrasados</p>
            <p class="mt-2 text-2xl font-semibold text-amber-600 tabular-nums">{{ number_format($this->totalAtrasados) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Liquidados</p>
            <p class="mt-2 text-2xl font-semibold text-sky-600 tabular-nums">{{ number_format($this->totalLiquidado) }}</p>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Buscar</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.400ms="busqueda"
                        placeholder="Folio, cliente, auto, VIN, placa..."
                        class="w-full pl-9 rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Estatus</label>
                <select wire:model.live="estatus"
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todos</option>
                    <option value="borrador">Borrador</option>
                    <option value="activo">Activo</option>
                    <option value="atrasado">Atrasado</option>
                    <option value="liquidado">Liquidado</option>
                    <option value="cancelado">Cancelado</option>
                    <option value="reestructurado">Reestructurado</option>
                    <option value="recuperado">Recuperado</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Frecuencia</label>
                <select wire:model.live="frecuencia"
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todas</option>
                    <option value="semanal">Semanal</option>
                    <option value="quincenal">Quincenal</option>
                    <option value="mensual">Mensual</option>
                </select>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="w-48">
                <select wire:model.live="orden"
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="recientes">Más recientes</option>
                    <option value="antiguos">Más antiguos</option>
                    <option value="saldo_mayor">Saldo mayor</option>
                    <option value="saldo_menor">Saldo menor</option>
                    <option value="fecha_contrato">Fecha contrato</option>
                </select>
            </div>
            <button wire:click="limpiarFiltros" type="button"
                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
                Limpiar
            </button>
            <span class="text-xs text-slate-400 ml-auto">{{ $contratos->total() }} resultado(s)</span>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        @if($contratos->count())
            <div class="overflow-x-auto" wire:loading.class="opacity-60 pointer-events-none">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/70">
                            <th class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Contrato</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Auto</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Condiciones</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Saldo</th>
                            <th class="px-5 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($contratos as $contrato)
                            <tr class="hover:bg-slate-50/60 transition-colors align-top">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900">{{ $contrato->folio }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ optional($contrato->fecha_contrato)->format('d/m/Y') }}</p>
                                    @if($contrato->ruta_contrato_firmado)
                                        <a href="{{ route('admin.contratos-financiamiento.archivo', $contrato) }}" target="_blank"
                                            class="inline-flex items-center gap-1 mt-1.5 text-xs font-medium text-indigo-600 hover:text-indigo-700">
                                            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13"/></svg>
                                            Ver archivo
                                        </a>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-medium text-slate-900">{{ $contrato->cliente?->nombre_completo ?: '—' }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $contrato->cliente?->telefono ?: 'Sin teléfono' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-medium text-slate-900">{{ $contrato->auto?->nombre_completo ?: '—' }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $contrato->auto?->codigo_inventario ?: 'Sin código' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="space-y-0.5 text-xs text-slate-600">
                                        <p><span class="text-slate-400">Frecuencia:</span> {{ ucfirst($contrato->frecuencia) }}</p>
                                        <p><span class="text-slate-400">Plazo:</span> {{ $contrato->plazo }}</p>
                                        <p><span class="text-slate-400">Cuota:</span> <span class="tabular-nums font-medium">${{ number_format((float) $contrato->monto_cuota, 2) }}</span></p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="space-y-0.5 text-xs">
                                        <p class="text-slate-400">Total: <span class="text-slate-700 tabular-nums font-medium">${{ number_format((float) $contrato->total_pagar, 2) }}</span></p>
                                        <p class="text-slate-400">Pagado: <span class="text-emerald-600 tabular-nums font-medium">${{ number_format((float) $contrato->total_pagado, 2) }}</span></p>
                                        <p class="text-slate-400">Saldo: <span class="text-slate-900 tabular-nums font-semibold">${{ number_format((float) $contrato->saldo_actual, 2) }}</span></p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium
                                        @if($contrato->estatus === 'activo') bg-emerald-50 text-emerald-700 border border-emerald-200
                                        @elseif($contrato->estatus === 'atrasado') bg-amber-50 text-amber-700 border border-amber-200
                                        @elseif($contrato->estatus === 'liquidado') bg-sky-50 text-sky-700 border border-sky-200
                                        @elseif($contrato->estatus === 'cancelado') bg-red-50 text-red-700 border border-red-200
                                        @else bg-slate-100 text-slate-600 @endif">
                                        <span class="h-1.5 w-1.5 rounded-full
                                            @if($contrato->estatus === 'activo') bg-emerald-500
                                            @elseif($contrato->estatus === 'atrasado') bg-amber-500
                                            @elseif($contrato->estatus === 'liquidado') bg-sky-500
                                            @elseif($contrato->estatus === 'cancelado') bg-red-500
                                            @else bg-slate-400 @endif">
                                        </span>
                                        {{ ucfirst($contrato->estatus) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.contratos-financiamiento.show', $contrato) }}"
                                            class="px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-700 hover:bg-slate-50 transition">Ver</a>
                                        <a href="{{ route('admin.contratos-financiamiento.registrar-pago', $contrato) }}"
                                            class="px-2.5 py-1.5 rounded-lg bg-emerald-600 text-xs font-medium text-white hover:bg-emerald-700 transition">Pago</a>
                                        <a href="{{ route('admin.contratos-financiamiento.edit', $contrato) }}"
                                            class="px-2.5 py-1.5 rounded-lg bg-indigo-600 text-xs font-medium text-white hover:bg-indigo-700 transition">Editar</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/60">
                {{ $contratos->links() }}
            </div>
        @else
            <div class="py-16 text-center">
                <div class="mx-auto h-12 w-12 rounded-xl bg-slate-100 flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-900">No hay contratos registrados</p>
                <p class="text-xs text-slate-500 mt-1">Crea el primer contrato para comenzar.</p>
                <a href="{{ route('admin.contratos-financiamiento.create') }}"
                    class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-lg bg-indigo-600 text-sm font-medium text-white hover:bg-indigo-700 transition">
                    + Nuevo contrato
                </a>
            </div>
        @endif
    </div>
</div>
