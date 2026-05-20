<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Auditoría</h1>
                <p class="text-sm text-gray-500">
                    Consulta cambios críticos del sistema, valores antes/después, usuario, IP y fecha.
                </p>
            </div>

            <button
                type="button"
                wire:click="limpiarFiltros"
                class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Limpiar filtros
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-500">Buscar</label>
                    <input
                        type="text"
                        wire:model.live.debounce.400ms="buscar"
                        placeholder="Acción, modelo, observación, IP..."
                        class="mt-1 w-full rounded-xl border-gray-300 text-sm">
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500">Acción</label>
                    <select wire:model.live="accion" class="mt-1 w-full rounded-xl border-gray-300 text-sm">
                        <option value="">Todas</option>
                        @foreach ($acciones as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500">Modelo</label>
                    <select wire:model.live="modelo" class="mt-1 w-full rounded-xl border-gray-300 text-sm">
                        <option value="">Todos</option>
                        @foreach ($modelos as $item)
                            <option value="{{ $item }}">{{ class_basename($item) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500">Desde</label>
                    <input
                        type="date"
                        wire:model.live="fechaInicio"
                        class="mt-1 w-full rounded-xl border-gray-300 text-sm">
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500">Hasta</label>
                    <input
                        type="date"
                        wire:model.live="fechaFin"
                        class="mt-1 w-full rounded-xl border-gray-300 text-sm">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Fecha</th>
                            <th class="px-4 py-3 text-left">Acción</th>
                            <th class="px-4 py-3 text-left">Modelo</th>
                            <th class="px-4 py-3 text-left">Usuario</th>
                            <th class="px-4 py-3 text-left">IP</th>
                            <th class="px-4 py-3 text-right">Acción</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($auditorias as $audit)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <p class="font-semibold text-gray-900">
                                        {{ $audit->created_at?->format('d/m/Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $audit->created_at?->format('H:i:s') }}
                                    </p>
                                </td>

                                <td class="px-4 py-3">
                                    <p class="font-bold text-gray-900">{{ $audit->accion }}</p>
                                    <p class="text-xs text-gray-500 line-clamp-1">
                                        {{ $audit->observaciones ?: 'Sin observaciones' }}
                                    </p>
                                </td>

                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-900">
                                        {{ $audit->modelo ? class_basename($audit->modelo) : '—' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        ID: {{ $audit->modelo_id ?: '—' }}
                                    </p>
                                </td>

                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-900">
                                        {{ $audit->usuario?->name ?: 'Sistema' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $audit->usuario?->email ?: '—' }}
                                    </p>
                                </td>

                                <td class="px-4 py-3">
                                    <span class="text-xs text-gray-600">
                                        {{ $audit->ip ?: '—' }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-right">
                                    <button
                                        type="button"
                                        wire:click="verDetalle({{ $audit->id }})"
                                        class="text-sm font-semibold text-black hover:underline">
                                        Ver detalle
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    No hay registros de auditoría.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-gray-100">
                {{ $auditorias->links() }}
            </div>
        </div>

        @if($auditoriaSeleccionada)
            <div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
                <div class="bg-white w-full max-w-6xl rounded-2xl shadow-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">
                                {{ $auditoriaSeleccionada->accion }}
                            </h2>
                            <p class="text-sm text-gray-500">
                                {{ $auditoriaSeleccionada->created_at?->format('d/m/Y H:i:s') }}
                            </p>
                        </div>

                        <button
                            type="button"
                            wire:click="cerrarDetalle"
                            class="rounded-xl border border-gray-300 px-3 py-2 text-sm font-semibold">
                            Cerrar
                        </button>
                    </div>

                    <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="rounded-xl bg-gray-50 p-4">
                                <p class="text-xs text-gray-500">Usuario</p>
                                <p class="font-bold text-gray-900">
                                    {{ $auditoriaSeleccionada->usuario?->name ?: 'Sistema' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-gray-50 p-4">
                                <p class="text-xs text-gray-500">Modelo</p>
                                <p class="font-bold text-gray-900">
                                    {{ $auditoriaSeleccionada->modelo ? class_basename($auditoriaSeleccionada->modelo) : '—' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-gray-50 p-4">
                                <p class="text-xs text-gray-500">ID modelo</p>
                                <p class="font-bold text-gray-900">
                                    {{ $auditoriaSeleccionada->modelo_id ?: '—' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-gray-50 p-4">
                                <p class="text-xs text-gray-500">IP</p>
                                <p class="font-bold text-gray-900">
                                    {{ $auditoriaSeleccionada->ip ?: '—' }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-bold text-gray-900 mb-2">Observaciones</h3>
                            <div class="rounded-xl border border-gray-200 p-4 text-sm text-gray-700">
                                {{ $auditoriaSeleccionada->observaciones ?: 'Sin observaciones.' }}
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                            <div>
                                <h3 class="font-bold text-gray-900 mb-2">Antes</h3>
                                <pre class="rounded-xl bg-gray-950 text-gray-100 p-4 text-xs overflow-x-auto min-h-[260px]">{{ json_encode($auditoriaSeleccionada->antes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>

                            <div>
                                <h3 class="font-bold text-gray-900 mb-2">Después</h3>
                                <pre class="rounded-xl bg-gray-950 text-gray-100 p-4 text-xs overflow-x-auto min-h-[260px]">{{ json_encode($auditoriaSeleccionada->despues, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-bold text-gray-900 mb-2">Dispositivo</h3>
                            <div class="rounded-xl border border-gray-200 p-4 text-xs text-gray-700 break-all">
                                {{ $auditoriaSeleccionada->user_agent ?: '—' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div wire:loading.flex
            wire:target="limpiarFiltros,verDetalle,cerrarDetalle,buscar,accion,modelo,fechaInicio,fechaFin"
            class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm items-center justify-center pointer-events-none">
            <div class="bg-white rounded-2xl shadow-2xl px-6 py-5 flex flex-col items-center gap-3 pointer-events-auto">
                <svg class="animate-spin h-8 w-8 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-80" fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                    </path>
                </svg>

                <div class="text-center">
                    <p class="font-bold text-sm">Cargando auditoría...</p>
                    <p class="text-xs text-gray-500">Espera un momento.</p>
                </div>
            </div>
        </div>
    </div>
</div>