<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black">Contratos de financiamiento</h1>
            <p class="text-gray-500">Consulta y administra los contratos activos del sistema.</p>
        </div>

        <a href="{{ route('admin.contratos-financiamiento.create') }}"
           class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-black text-white font-bold shadow-sm hover:opacity-90 transition">
            + Nuevo contrato
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-700 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="rounded-2xl border bg-white p-5 shadow-sm">
            <div class="text-sm text-gray-500 font-medium">Total contratos</div>
            <div class="text-3xl font-black mt-2">{{ number_format($this->totalContratos) }}</div>
        </div>

        <div class="rounded-2xl border bg-white p-5 shadow-sm">
            <div class="text-sm text-gray-500 font-medium">Activos</div>
            <div class="text-3xl font-black mt-2 text-emerald-600">{{ number_format($this->totalActivos) }}</div>
        </div>

        <div class="rounded-2xl border bg-white p-5 shadow-sm">
            <div class="text-sm text-gray-500 font-medium">Atrasados</div>
            <div class="text-3xl font-black mt-2 text-amber-600">{{ number_format($this->totalAtrasados) }}</div>
        </div>

        <div class="rounded-2xl border bg-white p-5 shadow-sm">
            <div class="text-sm text-gray-500 font-medium">Liquidado</div>
            <div class="text-3xl font-black mt-2 text-sky-600">{{ number_format($this->totalLiquidado) }}</div>
        </div>
    </div>

    <div class="bg-white border rounded-2xl p-5 shadow-sm space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Buscar</label>
                <input type="text"
                       wire:model.live.debounce.400ms="busqueda"
                       placeholder="Folio, cliente, auto, VIN, placa..."
                       class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Estatus</label>
                <select wire:model.live="estatus"
                        class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black">
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
                <label class="block text-xs font-semibold text-gray-500 mb-1">Frecuencia</label>
                <select wire:model.live="frecuencia"
                        class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    <option value="">Todas</option>
                    <option value="semanal">Semanal</option>
                    <option value="quincenal">Quincenal</option>
                    <option value="mensual">Mensual</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Ordenar por</label>
                <select wire:model.live="orden"
                        class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    <option value="recientes">Más recientes</option>
                    <option value="antiguos">Más antiguos</option>
                    <option value="saldo_mayor">Saldo mayor</option>
                    <option value="saldo_menor">Saldo menor</option>
                    <option value="fecha_contrato">Fecha contrato</option>
                </select>
            </div>

            <div class="md:col-span-2 flex flex-wrap items-end gap-2">
                <button wire:click="limpiarFiltros"
                        type="button"
                        class="px-4 py-2 rounded-2xl border font-semibold text-sm hover:bg-gray-50">
                    Limpiar filtros
                </button>

                <div class="text-sm text-gray-500">
                    Mostrando {{ $contratos->total() }} contrato(s)
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
        @if($contratos->count())
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr class="text-left text-gray-500">
                            <th class="px-4 py-3 font-bold">Contrato</th>
                            <th class="px-4 py-3 font-bold">Cliente</th>
                            <th class="px-4 py-3 font-bold">Auto</th>
                            <th class="px-4 py-3 font-bold">Condiciones</th>
                            <th class="px-4 py-3 font-bold">Saldo</th>
                            <th class="px-4 py-3 font-bold">Archivo</th>
                            <th class="px-4 py-3 font-bold">Estado</th>
                            <th class="px-4 py-3 font-bold text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @foreach($contratos as $contrato)
                            <tr class="align-top">
                                <td class="px-4 py-4 min-w-[180px]">
                                    <div class="font-black text-gray-900">{{ $contrato->folio }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ optional($contrato->fecha_contrato)->format('d/m/Y') }}</div>
                                </td>

                                <td class="px-4 py-4 min-w-[220px]">
                                    <div class="font-semibold">{{ $contrato->cliente?->nombre_completo ?: '—' }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $contrato->cliente?->telefono ?: 'Sin teléfono' }}</div>
                                </td>

                                <td class="px-4 py-4 min-w-[240px]">
                                    <div class="font-semibold">{{ $contrato->auto?->nombre_completo ?: '—' }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $contrato->auto?->codigo_inventario ?: 'Sin código' }}</div>
                                </td>

                                <td class="px-4 py-4 min-w-[220px]">
                                    <div><span class="font-semibold text-gray-700">Frecuencia:</span> {{ ucfirst($contrato->frecuencia) }}</div>
                                    <div><span class="font-semibold text-gray-700">Plazo:</span> {{ $contrato->plazo }}</div>
                                    <div><span class="font-semibold text-gray-700">Cuota:</span> ${{ number_format((float) $contrato->monto_cuota, 2) }}</div>
                                </td>

                                <td class="px-4 py-4 min-w-[220px]">
                                    <div><span class="font-semibold text-gray-700">Total:</span> ${{ number_format((float) $contrato->total_pagar, 2) }}</div>
                                    <div><span class="font-semibold text-gray-700">Pagado:</span> ${{ number_format((float) $contrato->total_pagado, 2) }}</div>
                                    <div><span class="font-semibold text-gray-700">Saldo:</span> ${{ number_format((float) $contrato->saldo_actual, 2) }}</div>
                                </td>

                                <td class="px-4 py-4 min-w-[140px]">
                                    @if($contrato->ruta_contrato_firmado)
                                        <a href="{{ route('admin.contratos-financiamiento.archivo', $contrato) }}" target="_blank"
                                           class="inline-flex px-3 py-1.5 rounded-xl border text-xs font-semibold hover:bg-gray-50">Ver archivo</a>
                                    @else
                                        <span class="text-xs text-gray-400">Sin archivo</span>
                                    @endif
                                </td>

                                <td class="px-4 py-4 min-w-[150px]">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold
                                        @if($contrato->estatus === 'activo') bg-emerald-100 text-emerald-700
                                        @elseif($contrato->estatus === 'atrasado') bg-amber-100 text-amber-700
                                        @elseif($contrato->estatus === 'liquidado') bg-sky-100 text-sky-700
                                        @elseif($contrato->estatus === 'cancelado') bg-red-100 text-red-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ ucfirst($contrato->estatus) }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 min-w-[260px]">
                                    <div class="flex justify-end gap-2 flex-wrap">
                                        <a href="{{ route('admin.contratos-financiamiento.show', $contrato) }}" class="px-3 py-2 rounded-xl border text-sm font-semibold hover:bg-gray-50">Ver</a>
                                        <a href="{{ route('admin.contratos-financiamiento.registrar-pago', $contrato) }}" class="px-3 py-2 rounded-xl border text-sm font-semibold hover:bg-gray-50">Pago</a>
                                        <a href="{{ route('admin.contratos-financiamiento.edit', $contrato) }}" class="px-3 py-2 rounded-xl bg-black text-white text-sm font-semibold hover:opacity-90">Editar</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t bg-gray-50">
                {{ $contratos->links() }}
            </div>
        @else
            <div class="p-10 text-center">
                <div class="text-lg font-bold text-gray-800">No hay contratos registrados</div>
                <p class="text-sm text-gray-500 mt-1">Empieza creando tu primer contrato.</p>
                <a href="{{ route('admin.contratos-financiamiento.create') }}" class="inline-flex mt-4 px-5 py-3 rounded-2xl bg-black text-white font-bold">+ Nuevo contrato</a>
            </div>
        @endif
    </div>
</div>
