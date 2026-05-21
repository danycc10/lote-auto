<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Apartados de autos</h1>
            <p class="text-sm text-slate-500 mt-0.5">Administra apartados y anticipos de unidades.</p>
        </div>
        <a href="{{ route('admin.apartados-autos.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors shrink-0">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd"/>
            </svg>
            Nuevo apartado
        </a>
    </div>

    @if (session('ok'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('ok') }}
        </div>
    @endif

    {{-- Filtros --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 sm:p-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-slate-700 mb-1">Buscar</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="q"
                           placeholder="Folio, cliente, VIN, placa..."
                           class="block w-full rounded-lg border-slate-300 pl-9 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Estatus</label>
                <select wire:model.live="estatus"
                        class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="todos">Todos</option>
                    <option value="activo">Activo</option>
                    <option value="convertido">Convertido</option>
                    <option value="vencido">Vencido</option>
                    <option value="cancelado">Cancelado</option>
                </select>
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
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider cursor-pointer hover:text-slate-700"
                            wire:click="sort('id')">Folio</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Auto</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Fechas</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Anticipo</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Estatus</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($apartados as $apartado)
                        @php
                            $statusMap = [
                                'activo'     => ['badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200',   'dot' => 'bg-indigo-500'],
                                'convertido' => ['badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500'],
                                'vencido'    => ['badge' => 'bg-amber-50 text-amber-700 border-amber-200',       'dot' => 'bg-amber-500'],
                                'cancelado'  => ['badge' => 'bg-red-50 text-red-700 border-red-200',             'dot' => 'bg-red-500'],
                            ];
                            $s = $statusMap[$apartado->estatus] ?? ['badge' => 'bg-slate-100 text-slate-600 border-slate-200', 'dot' => 'bg-slate-400'];
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3.5">
                                <span class="text-sm font-semibold text-slate-900">{{ $apartado->folio }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="text-sm font-medium text-slate-900">{{ $apartado->auto?->nombre_completo ?? '—' }}</div>
                                @if($apartado->auto?->vin)
                                    <div class="text-xs text-slate-400 tabular-nums">{{ $apartado->auto->vin }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="text-sm font-medium text-slate-900">{{ $apartado->cliente_mostrable }}</div>
                                @if($apartado->telefono_cliente_temporal ?? $apartado->cliente?->telefono)
                                    <div class="text-xs text-slate-400">{{ $apartado->telefono_cliente_temporal ?? $apartado->cliente?->telefono }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="text-sm text-slate-700">{{ optional($apartado->fecha_apartado)->format('d/m/Y') }}</div>
                                <div class="text-xs text-slate-400">Vence: {{ optional($apartado->fecha_vencimiento)->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <span class="text-sm font-semibold text-slate-900 tabular-nums">${{ number_format((float) $apartado->monto_anticipo, 2) }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border {{ $s['badge'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $s['dot'] }}"></span>
                                    {{ ucfirst($apartado->estatus) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    @if($apartado->estatus === 'activo')
                                        <a href="{{ route('admin.contratos-financiamiento.create', ['apartado_auto_id' => $apartado->id]) }}"
                                           class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-medium hover:bg-emerald-700 transition-colors">
                                            Convertir
                                        </a>
                                        <button type="button"
                                                wire:click="confirmarCancelacion({{ $apartado->id }})"
                                                class="inline-flex items-center px-2.5 py-1.5 rounded-lg border border-red-200 bg-red-50 text-xs font-medium text-red-700 hover:bg-red-100 transition-colors">
                                            Cancelar
                                        </button>
                                    @else
                                        <span class="text-xs text-slate-400">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-slate-500">No hay apartados registrados</p>
                                    <a href="{{ route('admin.apartados-autos.create') }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition-colors">
                                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd"/>
                                        </svg>
                                        Crear primer apartado
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-100">
            {{ $apartados->links() }}
        </div>
    </div>

    {{-- Modal cancelar --}}
    @if($cancelId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative w-full max-w-md rounded-xl bg-white shadow-2xl border border-slate-200 p-6 space-y-4">
                <div class="flex items-start gap-4">
                    <div class="shrink-0 h-10 w-10 rounded-lg bg-red-50 flex items-center justify-center">
                        <svg class="h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Cancelar apartado</h2>
                        <p class="text-sm text-slate-500 mt-1">Esta acción liberará el auto si no existe contrato ligado y no se puede deshacer.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Motivo de cancelación <span class="text-red-500">*</span></label>
                    <textarea wire:model="motivoCancelacion" rows="3"
                              placeholder="Describe el motivo de la cancelación..."
                              class="block w-full rounded-lg border-slate-300 text-sm focus:border-red-500 focus:ring-red-500"></textarea>
                    @error('motivoCancelacion')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-1">
                    <button type="button" wire:click="cerrarModalCancelacion"
                            class="px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                        Cerrar
                    </button>
                    <button type="button" wire:click="cancelar"
                            wire:loading.attr="disabled"
                            wire:target="cancelar"
                            class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        <span wire:loading.remove wire:target="cancelar">Confirmar cancelación</span>
                        <span wire:loading wire:target="cancelar">Cancelando...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
