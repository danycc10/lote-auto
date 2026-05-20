<div class="max-w-7xl mx-auto p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Apartados de autos</h1>
            <p class="text-sm text-gray-500">Administra apartados y anticipos de unidades.</p>
        </div>

        <a href="{{ route('admin.apartados-autos.create') }}"
           class="inline-flex items-center px-4 py-2 rounded-xl bg-gray-900 text-white font-semibold hover:bg-black">
            Nuevo apartado
        </a>
    </div>

    @if (session('ok'))
        <div class="rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3">
            {{ session('ok') }}
        </div>
    @endif

    <div class="bg-white border rounded-2xl p-4 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="text-sm font-semibold text-gray-700">Buscar</label>
                <input type="text"
                       wire:model.live.debounce.300ms="q"
                       placeholder="Folio, cliente, VIN, placa..."
                       class="mt-1 w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900">
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700">Estatus</label>
                <select wire:model.live="estatus"
                        class="mt-1 w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                    <option value="todos">Todos</option>
                    <option value="activo">Activo</option>
                    <option value="convertido">Convertido</option>
                    <option value="vencido">Vencido</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white border rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-left text-gray-600">
                        <th class="px-4 py-3 cursor-pointer" wire:click="sort('id')">#</th>
                        <th class="px-4 py-3">Folio</th>
                        <th class="px-4 py-3">Auto</th>
                        <th class="px-4 py-3">Cliente</th>
                        <th class="px-4 py-3">Fechas</th>
                        <th class="px-4 py-3 text-right">Anticipo</th>
                        <th class="px-4 py-3">Estatus</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($apartados as $apartado)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500">{{ $apartado->id }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $apartado->folio }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">
                                    {{ $apartado->auto?->nombre_completo ?? '—' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    VIN: {{ $apartado->auto?->vin ?? '—' }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">
                                    {{ $apartado->cliente_mostrable }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $apartado->telefono_cliente_temporal ?? $apartado->cliente?->telefono ?? '—' }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-gray-900">
                                    Apartado: {{ optional($apartado->fecha_apartado)->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Vence: {{ optional($apartado->fecha_vencimiento)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-900">
                                ${{ number_format((float) $apartado->monto_anticipo, 2) }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $colors = [
                                        'activo' => 'bg-blue-100 text-blue-800',
                                        'convertido' => 'bg-green-100 text-green-800',
                                        'vencido' => 'bg-yellow-100 text-yellow-800',
                                        'cancelado' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $colors[$apartado->estatus] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($apartado->estatus) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    @if($apartado->estatus === 'activo')
                                        <a href="{{ route('admin.contratos-financiamiento.create', ['apartado_auto_id' => $apartado->id]) }}"
                                           class="inline-flex px-3 py-2 rounded-xl bg-green-600 text-white text-xs font-bold hover:bg-green-700">
                                            Convertir
                                        </a>

                                        <button type="button"
                                                wire:click="confirmarCancelacion({{ $apartado->id }})"
                                                class="inline-flex px-3 py-2 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700">
                                            Cancelar
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400">Sin acciones</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-gray-500">
                                No hay apartados registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-4 border-t">
            {{ $apartados->links() }}
        </div>
    </div>

    @if($cancelId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl p-6 space-y-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Cancelar apartado</h2>
                    <p class="text-sm text-gray-500">Esta acción liberará el auto si no existe contrato ligado.</p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Motivo de cancelación</label>
                    <textarea wire:model="motivoCancelacion"
                              rows="4"
                              class="mt-1 w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"></textarea>
                    @error('motivoCancelacion')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button"
                            wire:click="cerrarModalCancelacion"
                            class="px-4 py-2 rounded-xl border bg-white hover:bg-gray-50 font-semibold">
                        Cerrar
                    </button>

                    <button type="button"
                            wire:click="cancelar"
                            class="px-4 py-2 rounded-xl bg-red-600 text-white hover:bg-red-700 font-semibold">
                        Confirmar cancelación
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>