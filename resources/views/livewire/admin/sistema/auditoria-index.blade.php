<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Auditoría</h1>
            <p class="text-sm text-slate-500 mt-0.5">Consulta cambios críticos del sistema: valores antes/después, usuario, IP y fecha.</p>
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
                           placeholder="Acción, modelo, observación, IP..."
                           class="block w-full rounded-lg border-slate-300 pl-9 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Acción</label>
                <select wire:model.live="accion"
                        class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todas</option>
                    @foreach ($acciones as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Modelo</label>
                <select wire:model.live="modelo"
                        class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos</option>
                    @foreach ($modelos as $item)
                        <option value="{{ $item }}">{{ class_basename($item) }}</option>
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
         wire:loading.class="opacity-60"
         wire:target="limpiarFiltros,buscar,accion,modelo,fechaInicio,fechaFin">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Acción</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Modelo</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">IP</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($auditorias as $audit)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <p class="text-sm font-medium text-slate-900">{{ $audit->created_at?->format('d/m/Y') }}</p>
                                <p class="text-xs text-slate-400 tabular-nums">{{ $audit->created_at?->format('H:i:s') }}</p>
                            </td>
                            <td class="px-4 py-3.5 max-w-xs">
                                <p class="text-sm font-medium text-slate-900">{{ $audit->accion }}</p>
                                <p class="text-xs text-slate-400 line-clamp-1">{{ $audit->observaciones ?: 'Sin observaciones' }}</p>
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="text-sm text-slate-700">{{ $audit->modelo ? class_basename($audit->modelo) : '—' }}</p>
                                <p class="text-xs text-slate-400 tabular-nums">ID: {{ $audit->modelo_id ?: '—' }}</p>
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="text-sm font-medium text-slate-900">{{ $audit->usuario?->name ?: 'Sistema' }}</p>
                                <p class="text-xs text-slate-400">{{ $audit->usuario?->email ?: '—' }}</p>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="text-xs text-slate-500 tabular-nums">{{ $audit->ip ?: '—' }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <button type="button" wire:click="verDetalle({{ $audit->id }})"
                                        class="text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:underline transition-colors">
                                    Ver detalle
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-slate-500">No hay registros de auditoría</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-100">
            {{ $auditorias->links() }}
        </div>
    </div>

    {{-- Modal detalle --}}
    @if($auditoriaSeleccionada)
        <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-5xl rounded-xl shadow-2xl overflow-hidden border border-slate-200">
                <div class="px-6 py-4 border-b border-slate-100 flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">{{ $auditoriaSeleccionada->accion }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $auditoriaSeleccionada->created_at?->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <button type="button" wire:click="cerrarDetalle"
                            class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                        Cerrar
                    </button>
                </div>

                <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="rounded-lg bg-slate-50 border border-slate-200 p-3">
                            <p class="text-xs text-slate-500">Usuario</p>
                            <p class="text-sm font-semibold text-slate-900 mt-0.5">{{ $auditoriaSeleccionada->usuario?->name ?: 'Sistema' }}</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 border border-slate-200 p-3">
                            <p class="text-xs text-slate-500">Modelo</p>
                            <p class="text-sm font-semibold text-slate-900 mt-0.5">{{ $auditoriaSeleccionada->modelo ? class_basename($auditoriaSeleccionada->modelo) : '—' }}</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 border border-slate-200 p-3">
                            <p class="text-xs text-slate-500">ID modelo</p>
                            <p class="text-sm font-semibold text-slate-900 mt-0.5 tabular-nums">{{ $auditoriaSeleccionada->modelo_id ?: '—' }}</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 border border-slate-200 p-3">
                            <p class="text-xs text-slate-500">IP</p>
                            <p class="text-sm font-semibold text-slate-900 mt-0.5 tabular-nums">{{ $auditoriaSeleccionada->ip ?: '—' }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Observaciones</h3>
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                            {{ $auditoriaSeleccionada->observaciones ?: 'Sin observaciones.' }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Antes</h3>
                            <pre class="rounded-lg bg-slate-950 text-slate-100 p-4 text-xs overflow-x-auto min-h-[200px]">{{ json_encode($auditoriaSeleccionada->antes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                        <div>
                            <h3 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Después</h3>
                            <pre class="rounded-lg bg-slate-950 text-slate-100 p-4 text-xs overflow-x-auto min-h-[200px]">{{ json_encode($auditoriaSeleccionada->despues, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-2">Dispositivo</h3>
                        <div class="rounded-lg border border-slate-200 p-3 text-xs text-slate-500 break-all">
                            {{ $auditoriaSeleccionada->user_agent ?: '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
