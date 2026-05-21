<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Recibos</h1>
            <p class="text-sm text-slate-500 mt-0.5">Consulta, imprime y cancela recibos generados por pagos.</p>
        </div>
        <a href="{{ route('admin.contratos-financiamiento.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shrink-0">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625zM7.5 15a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 017.5 15zm.75-6.75a.75.75 0 000 1.5H12a.75.75 0 000-1.5H8.25z" clip-rule="evenodd"/>
                <path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z"/>
            </svg>
            Ver contratos
        </a>
    </div>

    @if (session('ok'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('ok') }}
        </div>
    @endif

    {{-- Filtros --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 sm:p-5 space-y-4">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-sm font-medium text-slate-700">Filtros</h2>
            <button type="button" wire:click="limpiarFiltros"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-medium text-slate-600 hover:bg-slate-50 transition-colors">
                <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0112.548-3.364l1.903 1.903h-3.183a.75.75 0 100 1.5h4.992a.75.75 0 00.75-.75V4.356a.75.75 0 00-1.5 0v3.18l-1.9-1.9A9 9 0 003.306 9.67a.75.75 0 101.45.388zm15.408 3.352a.75.75 0 00-.919.53 7.5 7.5 0 01-12.548 3.364l-1.902-1.903h3.183a.75.75 0 000-1.5H2.984a.75.75 0 00-.75.75v4.992a.75.75 0 001.5 0v-3.18l1.9 1.9a9 9 0 0015.059-4.035.75.75 0 00-.53-.918z" clip-rule="evenodd"/>
                </svg>
                Limpiar
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="xl:col-span-2">
                <label class="block text-xs font-medium text-slate-700 mb-1">Buscar</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.400ms="q"
                           placeholder="Folio, cliente, contrato, concepto..."
                           class="block w-full rounded-lg border-slate-300 pl-9 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Estatus</label>
                <select wire:model.live="estatus"
                        class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="todos">Todos</option>
                    <option value="vigente">Vigente</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Tipo</label>
                <select wire:model.live="tipoRelacion"
                        class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="todos">Todos</option>
                    <option value="con_cuota">Con cuota</option>
                    <option value="pago_general">Pago general</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Fecha desde</label>
                <input type="date" wire:model.live="fechaDesde"
                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Fecha hasta</label>
                <input type="date" wire:model.live="fechaHasta"
                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Monto mínimo</label>
                <input type="number" step="0.01" wire:model.live.debounce.400ms="montoMin"
                       placeholder="0.00"
                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Monto máximo</label>
                <input type="number" step="0.01" wire:model.live.debounce.400ms="montoMax"
                       placeholder="99,999.99"
                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>

        <p class="text-xs text-slate-500">
            <span class="font-medium text-slate-700">{{ $recibos->total() }}</span> recibos encontrados
        </p>
    </div>

    {{-- Tabla desktop --}}
    <div class="hidden lg:block bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden"
         wire:loading.class="opacity-60">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:text-slate-700"
                            wire:click="sort('folio')">Folio</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Contrato</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Cuota</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:text-slate-700"
                            wire:click="sort('fecha_recibo')">Fecha</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:text-slate-700"
                            wire:click="sort('monto')">Monto</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:text-slate-700"
                            wire:click="sort('estatus')">Estatus</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($recibos as $recibo)
                        @php
                            $isVigente = $recibo->estatus === 'vigente';
                            $badgeClass = $isVigente
                                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                : 'bg-red-50 text-red-700 border-red-200';
                            $dotClass = $isVigente ? 'bg-emerald-500' : 'bg-red-500';
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3.5">
                                <span class="text-sm font-semibold text-slate-900 tabular-nums">{{ $recibo->folio }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="text-sm text-slate-700">
                                    {{ trim(($recibo->cliente->nombre ?? '') . ' ' . ($recibo->cliente->apellido_paterno ?? '') . ' ' . ($recibo->cliente->apellido_materno ?? '')) ?: '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="text-sm text-slate-600 tabular-nums">{{ $recibo->contrato->folio ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                @if($recibo->cuota)
                                    <span class="text-sm text-slate-600">Cuota #{{ $recibo->cuota->numero }}</span>
                                @else
                                    <span class="text-xs text-slate-400">Pago general</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="text-sm text-slate-600">{{ $recibo->fecha_recibo?->format('d/m/Y') ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <span class="text-sm font-semibold text-slate-900 tabular-nums">${{ number_format((float) $recibo->monto, 2) }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border {{ $badgeClass }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $dotClass }}"></span>
                                    {{ ucfirst($recibo->estatus) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.recibos.show', $recibo->id) }}"
                                       class="inline-flex items-center px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                        Ver
                                    </a>
                                    <a href="{{ route('admin.recibos.pdf', $recibo->id) }}"
                                       target="_blank"
                                       title="{{ $recibo->estatus === 'cancelado' ? 'Este recibo está CANCELADO' : 'Imprimir PDF' }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border text-xs font-medium transition-colors {{ $recibo->estatus === 'cancelado' ? 'border-slate-200 bg-slate-50 text-slate-400' : 'border-sky-200 bg-sky-50 text-sky-700 hover:bg-sky-100' }}">
                                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.875 1.5C6.839 1.5 6 2.34 6 3.375v2.99c-.426.053-.851.11-1.274.174-1.454.218-2.476 1.483-2.476 2.917v6.294a3 3 0 003 3h.27l-.155 1.705A1.875 1.875 0 007.232 22.5h9.536a1.875 1.875 0 001.867-2.045l-.155-1.705h.27a3 3 0 003-3V9.456c0-1.434-1.022-2.7-2.476-2.917A48.716 48.716 0 0018 6.366V3.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM16.5 6.205v-2.83A.375.375 0 0016.125 3h-8.25a.375.375 0 00-.375.375v2.83a49.353 49.353 0 019 0zm-.217 8.265c.178.018.317.16.333.337l.526 5.784a.375.375 0 01-.374.409H7.232a.375.375 0 01-.374-.409l.526-5.784a.347.347 0 01.333-.337 41.741 41.741 0 018.566 0zm.967-3.97a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H18a.75.75 0 01-.75-.75V10.5zM15 9.75a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V10.5a.75.75 0 00-.75-.75H15z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $recibo->estatus === 'cancelado' ? 'PDF' : 'Imprimir' }}
                                    </a>
                                    @if($recibo->estatus !== 'cancelado')
                                        <button type="button"
                                                wire:click="abrirModalCancelar({{ $recibo->id }})"
                                                class="inline-flex items-center px-2.5 py-1.5 rounded-lg border border-red-200 bg-red-50 text-xs font-medium text-red-700 hover:bg-red-100 transition-colors">
                                            Cancelar
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-slate-500">No se encontraron recibos</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-100">
            {{ $recibos->links() }}
        </div>
    </div>

    {{-- Cards mobile --}}
    <div class="grid grid-cols-1 gap-3 lg:hidden">
        @forelse ($recibos as $recibo)
            @php
                $isVigente = $recibo->estatus === 'vigente';
                $badgeClass = $isVigente ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200';
                $dotClass = $isVigente ? 'bg-emerald-500' : 'bg-red-500';
            @endphp
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-sm font-semibold text-slate-900 tabular-nums">{{ $recibo->folio }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $recibo->fecha_recibo?->format('d/m/Y') ?? '—' }}</div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border {{ $badgeClass }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $dotClass }}"></span>
                        {{ ucfirst($recibo->estatus) }}
                    </span>
                </div>

                <div class="text-xs text-slate-600 space-y-1">
                    <div class="flex gap-2">
                        <span class="font-medium text-slate-500 w-16 shrink-0">Cliente</span>
                        <span>{{ trim(($recibo->cliente->nombre ?? '') . ' ' . ($recibo->cliente->apellido_paterno ?? '') . ' ' . ($recibo->cliente->apellido_materno ?? '')) ?: '—' }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="font-medium text-slate-500 w-16 shrink-0">Contrato</span>
                        <span class="tabular-nums">{{ $recibo->contrato->folio ?? '—' }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="font-medium text-slate-500 w-16 shrink-0">Cuota</span>
                        <span>{{ $recibo->cuota ? '#' . $recibo->cuota->numero : 'Pago general' }}</span>
                    </div>
                    <div class="flex gap-2">
                        <span class="font-medium text-slate-500 w-16 shrink-0">Monto</span>
                        <span class="font-semibold text-slate-900 tabular-nums">${{ number_format((float) $recibo->monto, 2) }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 pt-1">
                    <a href="{{ route('admin.recibos.show', $recibo->id) }}"
                       class="inline-flex justify-center items-center px-3 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                        Ver
                    </a>
                    <a href="{{ route('admin.recibos.pdf', $recibo->id) }}"
                       target="_blank"
                       class="inline-flex justify-center items-center px-3 py-2 rounded-lg border text-xs font-medium transition-colors {{ $recibo->estatus === 'cancelado' ? 'border-slate-200 bg-slate-50 text-slate-400' : 'border-sky-200 bg-sky-50 text-sky-700 hover:bg-sky-100' }}">
                        {{ $recibo->estatus === 'cancelado' ? 'PDF (cancelado)' : 'Imprimir PDF' }}
                    </a>
                    @if($recibo->estatus !== 'cancelado')
                        <button type="button"
                                wire:click="abrirModalCancelar({{ $recibo->id }})"
                                class="col-span-2 inline-flex justify-center items-center px-3 py-2 rounded-lg border border-red-200 bg-red-50 text-xs font-medium text-red-700 hover:bg-red-100 transition-colors">
                            Cancelar recibo
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-10 text-center text-sm text-slate-500">
                No se encontraron recibos.
            </div>
        @endforelse
        <div>{{ $recibos->links() }}</div>
    </div>

    {{-- Modal cancelar --}}
    @if($modalCancelar)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="cerrarModalCancelar"></div>
            <div class="relative w-full max-w-lg rounded-xl bg-white shadow-2xl border border-slate-200 p-6">
                <div class="flex items-start gap-4">
                    <div class="shrink-0 h-10 w-10 rounded-lg bg-red-50 flex items-center justify-center">
                        <svg class="h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-semibold text-slate-900">Cancelar recibo</h3>
                        <p class="mt-1.5 text-sm text-slate-600">
                            Se cancelará el recibo <span class="font-semibold text-slate-900">{{ $reciboCancelarFolio }}</span>,
                            se revertirá el pago relacionado y se recalcularán saldos del contrato.
                        </p>
                        <p class="mt-1.5 text-xs font-medium text-red-600">Esta acción afecta información financiera y no se puede deshacer.</p>
                    </div>
                </div>

                <div class="mt-5">
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Motivo de cancelación <span class="text-red-500">*</span></label>
                    <textarea wire:model.defer="motivoCancelacion" rows="3"
                              placeholder="Describe el motivo de la cancelación..."
                              class="block w-full rounded-lg border-slate-300 text-sm focus:border-red-500 focus:ring-red-500"></textarea>
                    @error('motivoCancelacion')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-5 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="button" wire:click="cerrarModalCancelar"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                        No, volver
                    </button>
                    <button type="button"
                            wire:click="confirmarCancelacion"
                            wire:loading.attr="disabled"
                            wire:target="confirmarCancelacion"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        <span wire:loading.remove wire:target="confirmarCancelacion">Sí, cancelar y revertir</span>
                        <span wire:loading wire:target="confirmarCancelacion" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Cancelando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
