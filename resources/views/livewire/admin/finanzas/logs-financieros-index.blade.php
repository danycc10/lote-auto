<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Log financiero</h1>
                <p class="text-sm text-gray-500">
                    Timeline de movimientos críticos de dinero, contratos, pagos y recibos.
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
                        placeholder="Folio, tipo, descripción..."
                        class="mt-1 w-full rounded-xl border-gray-300 text-sm"
                    >
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500">Tipo</label>
                    <select wire:model.live="tipo" class="mt-1 w-full rounded-xl border-gray-300 text-sm">
                        <option value="">Todos</option>
                        @foreach ($tipos as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500">Nivel</label>
                    <select wire:model.live="nivel" class="mt-1 w-full rounded-xl border-gray-300 text-sm">
                        <option value="">Todos</option>
                        @foreach ($niveles as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500">Desde</label>
                    <input
                        type="date"
                        wire:model.live="fechaInicio"
                        class="mt-1 w-full rounded-xl border-gray-300 text-sm"
                    >
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-500">Hasta</label>
                    <input
                        type="date"
                        wire:model.live="fechaFin"
                        class="mt-1 w-full rounded-xl border-gray-300 text-sm"
                    >
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Fecha</th>
                            <th class="px-4 py-3 text-left">Movimiento</th>
                            <th class="px-4 py-3 text-left">Referencia</th>
                            <th class="px-4 py-3 text-right">Monto</th>
                            <th class="px-4 py-3 text-right">Saldo</th>
                            <th class="px-4 py-3 text-left">Usuario</th>
                            <th class="px-4 py-3 text-right">Acción</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($logs as $log)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <p class="font-semibold text-gray-900">
                                        {{ $log->created_at?->format('d/m/Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $log->created_at?->format('H:i:s') }}
                                    </p>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-start gap-3">
                                        <span class="mt-1 h-2.5 w-2.5 rounded-full
                                            @if($log->nivel === 'success') bg-green-500
                                            @elseif($log->nivel === 'warning') bg-yellow-500
                                            @elseif($log->nivel === 'danger') bg-red-500
                                            @else bg-gray-400
                                            @endif">
                                        </span>

                                        <div>
                                            <p class="font-bold text-gray-900">{{ $log->titulo }}</p>
                                            <p class="text-xs text-gray-500">{{ $log->tipo }}</p>
                                            @if($log->descripcion)
                                                <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                                                    {{ $log->descripcion }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-900">
                                        {{ $log->referencia ?: '—' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $log->modulo ?: '—' }}
                                    </p>
                                </td>

                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    @if(!is_null($log->monto))
                                        <span class="font-bold text-gray-900">
                                            ${{ number_format((float) $log->monto, 2) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    @if(!is_null($log->saldo_anterior) || !is_null($log->saldo_nuevo))
                                        <p class="text-xs text-gray-500">
                                            Antes: ${{ number_format((float) $log->saldo_anterior, 2) }}
                                        </p>
                                        <p class="font-semibold text-gray-900">
                                            Nuevo: ${{ number_format((float) $log->saldo_nuevo, 2) }}
                                        </p>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-900">
                                        {{ $log->usuario?->name ?: 'Sistema' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $log->ip ?: '—' }}
                                    </p>
                                </td>

                                <td class="px-4 py-3 text-right">
                                    <button
                                        type="button"
                                        wire:click="verDetalle({{ $log->id }})"
                                        class="text-sm font-semibold text-black hover:underline">
                                        Ver
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    No hay logs financieros registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-gray-100">
                {{ $logs->links() }}
            </div>
        </div>

        @if($logSeleccionado)
            <div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
                <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">
                                {{ $logSeleccionado->titulo }}
                            </h2>
                            <p class="text-sm text-gray-500">
                                {{ $logSeleccionado->created_at?->format('d/m/Y H:i:s') }}
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
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="rounded-xl bg-gray-50 p-4">
                                <p class="text-xs text-gray-500">Tipo</p>
                                <p class="font-bold text-gray-900">{{ $logSeleccionado->tipo }}</p>
                            </div>

                            <div class="rounded-xl bg-gray-50 p-4">
                                <p class="text-xs text-gray-500">Referencia</p>
                                <p class="font-bold text-gray-900">{{ $logSeleccionado->referencia ?: '—' }}</p>
                            </div>

                            <div class="rounded-xl bg-gray-50 p-4">
                                <p class="text-xs text-gray-500">Nivel</p>
                                <p class="font-bold text-gray-900">{{ $logSeleccionado->nivel }}</p>
                            </div>

                            <div class="rounded-xl bg-gray-50 p-4">
                                <p class="text-xs text-gray-500">Monto</p>
                                <p class="font-bold text-gray-900">
                                    {{ !is_null($logSeleccionado->monto) ? '$'.number_format((float)$logSeleccionado->monto, 2) : '—' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-gray-50 p-4">
                                <p class="text-xs text-gray-500">Saldo anterior</p>
                                <p class="font-bold text-gray-900">
                                    {{ !is_null($logSeleccionado->saldo_anterior) ? '$'.number_format((float)$logSeleccionado->saldo_anterior, 2) : '—' }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-gray-50 p-4">
                                <p class="text-xs text-gray-500">Saldo nuevo</p>
                                <p class="font-bold text-gray-900">
                                    {{ !is_null($logSeleccionado->saldo_nuevo) ? '$'.number_format((float)$logSeleccionado->saldo_nuevo, 2) : '—' }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-bold text-gray-900 mb-2">Descripción</h3>
                            <div class="rounded-xl border border-gray-200 p-4 text-sm text-gray-700">
                                {{ $logSeleccionado->descripcion ?: 'Sin descripción.' }}
                            </div>
                        </div>

                        <div>
                            <h3 class="font-bold text-gray-900 mb-2">Usuario</h3>
                            <div class="rounded-xl border border-gray-200 p-4 text-sm text-gray-700">
                                <p><strong>Usuario:</strong> {{ $logSeleccionado->usuario?->name ?: 'Sistema' }}</p>
                                <p><strong>Email:</strong> {{ $logSeleccionado->usuario?->email ?: '—' }}</p>
                                <p><strong>IP:</strong> {{ $logSeleccionado->ip ?: '—' }}</p>
                                <p><strong>User Agent:</strong> {{ $logSeleccionado->user_agent ?: '—' }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-bold text-gray-900 mb-2">Metadata</h3>
                            <pre class="rounded-xl bg-gray-950 text-gray-100 p-4 text-xs overflow-x-auto">{{ json_encode($logSeleccionado->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div wire:loading.flex
            class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm items-center justify-center">
            <div class="bg-white rounded-2xl shadow-2xl px-6 py-5 flex flex-col items-center gap-3">
                <svg class="animate-spin h-8 w-8 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-80" fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                    </path>
                </svg>

                <div class="text-center">
                    <p class="font-bold text-sm">Cargando...</p>
                    <p class="text-xs text-gray-500">Espera un momento.</p>
                </div>
            </div>
        </div>
    </div>
</div>