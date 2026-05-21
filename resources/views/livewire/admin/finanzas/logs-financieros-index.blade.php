<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Log financiero</h1>
            <p class="text-sm text-slate-500 mt-0.5">Timeline de movimientos críticos: contratos, pagos y recibos.</p>
        </div>
        <button type="button" wire:click="limpiarFiltros"
                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shrink-0">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0112.548-3.364l1.903 1.903h-3.183a.75.75 0 100 1.5h4.992a.75.75 0 00.75-.75V4.356a.75.75 0 00-1.5 0v3.18l-1.9-1.9A9 9 0 003.306 9.67a.75.75 0 101.45.388zm15.408 3.352a.75.75 0 00-.919.53 7.5 7.5 0 01-12.548 3.364l-1.902-1.903h3.183a.75.75 0 000-1.5H2.984a.75.75 0 00-.75.75v4.992a.75.75 0 001.5 0v-3.18l1.9 1.9a9 9 0 0015.059-4.035.75.75 0 00-.53-.918z" clip-rule="evenodd"/>
            </svg>
            Limpiar filtros
        </button>
    </div>

    {{-- Filtros --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 sm:p-5">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-slate-700 mb-1">Buscar</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.400ms="buscar"
                           placeholder="Folio, tipo, descripción..."
                           class="block w-full rounded-lg border-slate-300 pl-9 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Tipo</label>
                <select wire:model.live="tipo"
                        class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos</option>
                    @foreach ($tipos as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Nivel</label>
                <select wire:model.live="nivel"
                        class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos</option>
                    @foreach ($niveles as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Desde</label>
                <input type="date" wire:model.live="fechaInicio"
                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Hasta</label>
                <input type="date" wire:model.live="fechaFin"
                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden"
         wire:loading.class="opacity-60">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Movimiento</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Referencia</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Monto</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Saldo</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $log)
                        @php
                            $dotColor = match($log->nivel) {
                                'success' => 'bg-emerald-500',
                                'warning' => 'bg-amber-500',
                                'danger'  => 'bg-red-500',
                                default   => 'bg-slate-400',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <p class="text-sm font-medium text-slate-900">{{ $log->created_at?->format('d/m/Y') }}</p>
                                <p class="text-xs text-slate-400 tabular-nums">{{ $log->created_at?->format('H:i:s') }}</p>
                            </td>
                            <td class="px-4 py-3.5 max-w-xs">
                                <div class="flex items-start gap-2.5">
                                    <span class="mt-1.5 h-2 w-2 rounded-full {{ $dotColor }} shrink-0"></span>
                                    <div>
                                        <p class="text-sm font-medium text-slate-900">{{ $log->titulo }}</p>
                                        <p class="text-xs text-slate-500">{{ $log->tipo }}</p>
                                        @if($log->descripcion)
                                            <p class="text-xs text-slate-400 mt-0.5 line-clamp-2">{{ $log->descripcion }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="text-sm text-slate-700 tabular-nums">{{ $log->referencia ?: '—' }}</p>
                                <p class="text-xs text-slate-400">{{ $log->modulo ?: '—' }}</p>
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                @if(!is_null($log->monto))
                                    <span class="text-sm font-semibold text-slate-900 tabular-nums">${{ number_format((float) $log->monto, 2) }}</span>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                @if(!is_null($log->saldo_anterior) || !is_null($log->saldo_nuevo))
                                    <p class="text-xs text-slate-400 tabular-nums">Antes: ${{ number_format((float) $log->saldo_anterior, 2) }}</p>
                                    <p class="text-sm font-medium text-slate-700 tabular-nums">Nuevo: ${{ number_format((float) $log->saldo_nuevo, 2) }}</p>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="text-sm font-medium text-slate-900">{{ $log->usuario?->name ?: 'Sistema' }}</p>
                                <p class="text-xs text-slate-400">{{ $log->ip ?: '—' }}</p>
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <button type="button" wire:click="verDetalle({{ $log->id }})"
                                        class="text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:underline transition-colors">
                                    Ver detalle
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-slate-500">No hay logs financieros registrados</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
    </div>

    {{-- Modal detalle --}}
    @if($logSeleccionado)
        <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-3xl rounded-xl shadow-2xl overflow-hidden border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-100 flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">{{ $logSeleccionado->titulo }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $logSeleccionado->created_at?->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <button type="button" wire:click="cerrarDetalle"
                            class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                        Cerrar
                    </button>
                </div>

                <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <div class="rounded-lg bg-slate-50 border border-slate-200 p-3">
                            <p class="text-xs text-slate-500">Tipo</p>
                            <p class="text-sm font-semibold text-slate-900 mt-0.5">{{ $logSeleccionado->tipo }}</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 border border-slate-200 p-3">
                            <p class="text-xs text-slate-500">Referencia</p>
                            <p class="text-sm font-semibold text-slate-900 mt-0.5 tabular-nums">{{ $logSeleccionado->referencia ?: '—' }}</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 border border-slate-200 p-3">
                            <p class="text-xs text-slate-500">Nivel</p>
                            <p class="text-sm font-semibold text-slate-900 mt-0.5">{{ $logSeleccionado->nivel }}</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 border border-slate-200 p-3">
                            <p class="text-xs text-slate-500">Monto</p>
                            <p class="text-sm font-semibold text-slate-900 mt-0.5 tabular-nums">
                                {{ !is_null($logSeleccionado->monto) ? '$'.number_format((float)$logSeleccionado->monto, 2) : '—' }}
                            </p>
                        </div>
                        <div class="rounded-lg bg-slate-50 border border-slate-200 p-3">
                            <p class="text-xs text-slate-500">Saldo anterior</p>
                            <p class="text-sm font-semibold text-slate-900 mt-0.5 tabular-nums">
                                {{ !is_null($logSeleccionado->saldo_anterior) ? '$'.number_format((float)$logSeleccionado->saldo_anterior, 2) : '—' }}
                            </p>
                        </div>
                        <div class="rounded-lg bg-slate-50 border border-slate-200 p-3">
                            <p class="text-xs text-slate-500">Saldo nuevo</p>
                            <p class="text-sm font-semibold text-slate-900 mt-0.5 tabular-nums">
                                {{ !is_null($logSeleccionado->saldo_nuevo) ? '$'.number_format((float)$logSeleccionado->saldo_nuevo, 2) : '—' }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Descripción</h3>
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                            {{ $logSeleccionado->descripcion ?: 'Sin descripción.' }}
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Usuario</h3>
                        <div class="rounded-lg border border-slate-200 p-4 space-y-1">
                            <div class="flex gap-3 text-sm">
                                <span class="text-slate-500 w-24 shrink-0">Usuario</span>
                                <span class="text-slate-900 font-medium">{{ $logSeleccionado->usuario?->name ?: 'Sistema' }}</span>
                            </div>
                            <div class="flex gap-3 text-sm">
                                <span class="text-slate-500 w-24 shrink-0">Email</span>
                                <span class="text-slate-700">{{ $logSeleccionado->usuario?->email ?: '—' }}</span>
                            </div>
                            <div class="flex gap-3 text-sm">
                                <span class="text-slate-500 w-24 shrink-0">IP</span>
                                <span class="text-slate-700 tabular-nums">{{ $logSeleccionado->ip ?: '—' }}</span>
                            </div>
                            <div class="flex gap-3 text-sm">
                                <span class="text-slate-500 w-24 shrink-0">Dispositivo</span>
                                <span class="text-slate-500 text-xs break-all">{{ $logSeleccionado->user_agent ?: '—' }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Metadata</h3>
                        <pre class="rounded-lg bg-slate-950 text-slate-100 p-4 text-xs overflow-x-auto">{{ json_encode($logSeleccionado->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
