<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Editar contrato</h1>
            <p class="text-sm text-slate-500 mt-0.5">Actualiza los datos financieros, el archivo firmado y regenera las cuotas.</p>
        </div>
        <a href="{{ route('admin.contratos-financiamiento.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shrink-0">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 010 1.06l-6.22 6.22H21a.75.75 0 010 1.5H4.81l6.22 6.22a.75.75 0 11-1.06 1.06l-7.5-7.5a.75.75 0 010-1.06l7.5-7.5a.75.75 0 011.06 0z" clip-rule="evenodd"/>
            </svg>
            Volver al listado
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            <svg class="h-4 w-4 text-emerald-500 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="guardar" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-5">

            {{-- Datos principales --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Datos principales</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Auto, cliente y fechas del contrato.</p>
                </div>
                <div class="p-5 space-y-4">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Folio <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="folio"
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('folio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Estatus <span class="text-red-500">*</span></label>
                            <select wire:model="estatus"
                                    class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="borrador">Borrador</option>
                                <option value="activo">Activo</option>
                                <option value="atrasado">Atrasado</option>
                                <option value="liquidado">Liquidado</option>
                                <option value="cancelado">Cancelado</option>
                                <option value="reestructurado">Reestructurado</option>
                                <option value="recuperado">Recuperado</option>
                            </select>
                            @error('estatus') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Auto <span class="text-red-500">*</span></label>
                        <select wire:model="auto_id"
                                class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona un auto</option>
                            @foreach($autos as $auto)
                                <option value="{{ $auto->id }}">{{ $auto->nombre_completo }} | {{ $auto->codigo_inventario ?: 'Sin código' }}</option>
                            @endforeach
                        </select>
                        @error('auto_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Cliente <span class="text-red-500">*</span></label>
                        <select wire:model="cliente_id"
                                class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre_completo }} | {{ $cliente->telefono ?: 'Sin teléfono' }}</option>
                            @endforeach
                        </select>
                        @error('cliente_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Fecha contrato <span class="text-red-500">*</span></label>
                            <input type="date" wire:model="fecha_contrato"
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('fecha_contrato') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Fecha primer pago</label>
                            <input type="date" wire:model="fecha_primer_pago"
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('fecha_primer_pago') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                </div>
            </div>

            {{-- Valores financieros --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Valores financieros</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Montos, tasa, plazo y saldos del contrato.</p>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Precio contado <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                            <input type="number" step="0.01" wire:model="precio_contado"
                                   class="block w-full rounded-lg border-slate-300 pl-6 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                        </div>
                        @error('precio_contado') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Precio venta <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                            <input type="number" step="0.01" wire:model.live="precio_venta"
                                   class="block w-full rounded-lg border-slate-300 pl-6 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                        </div>
                        @error('precio_venta') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Enganche</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                            <input type="number" step="0.01" wire:model.live="enganche"
                                   class="block w-full rounded-lg border-slate-300 pl-6 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                        </div>
                        @error('enganche') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Comisión apertura</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                            <input type="number" step="0.01" wire:model.live="comision_apertura"
                                   class="block w-full rounded-lg border-slate-300 pl-6 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                        </div>
                        @error('comision_apertura') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Monto seguro</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                            <input type="number" step="0.01" wire:model.live="monto_seguro"
                                   class="block w-full rounded-lg border-slate-300 pl-6 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                        </div>
                        @error('monto_seguro') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Monto GPS</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                            <input type="number" step="0.01" wire:model.live="monto_gps"
                                   class="block w-full rounded-lg border-slate-300 pl-6 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                        </div>
                        @error('monto_gps') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Monto financiado <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                            <input type="number" step="0.01" wire:model="monto_financiado" readonly
                                   class="block w-full rounded-lg border-slate-300 bg-slate-50 text-slate-500 pl-6 text-sm cursor-not-allowed focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                        </div>
                        @error('monto_financiado') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Tasa interés %</label>
                        <input type="number" step="0.01" wire:model.live="tasa_interes"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                        @error('tasa_interes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Plazo <span class="text-red-500">*</span></label>
                        <input type="number" wire:model.live="plazo"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('plazo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Frecuencia <span class="text-red-500">*</span></label>
                        <select wire:model="frecuencia"
                                class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="semanal">Semanal</option>
                            <option value="quincenal">Quincenal</option>
                            <option value="mensual">Mensual</option>
                        </select>
                        @error('frecuencia') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Monto cuota <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                            <input type="number" step="0.01" wire:model="monto_cuota"
                                   class="block w-full rounded-lg border-slate-300 bg-slate-50 pl-6 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                        </div>
                        @error('monto_cuota') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Total a pagar <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                            <input type="number" step="0.01" wire:model="total_pagar" readonly
                                   class="block w-full rounded-lg border-slate-300 bg-slate-50 text-slate-500 pl-6 text-sm cursor-not-allowed focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                        </div>
                        @error('total_pagar') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Total pagado</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                            <input type="number" step="0.01" wire:model="total_pagado"
                                   class="block w-full rounded-lg border-slate-300 pl-6 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                        </div>
                        @error('total_pagado') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Saldo actual</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                            <input type="number" step="0.01" wire:model="saldo_actual"
                                   class="block w-full rounded-lg border-slate-300 bg-slate-50 pl-6 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                        </div>
                        @error('saldo_actual') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Recargo, archivo y observaciones --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Recargo, archivo y observaciones</h2>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Días de gracia</label>
                            <input type="number" wire:model="dias_gracia"
                                   placeholder="0"
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('dias_gracia') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Tipo recargo</label>
                            <select wire:model="tipo_recargo"
                                    class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Sin recargo</option>
                                <option value="fijo">Fijo</option>
                                <option value="porcentaje">Porcentaje</option>
                            </select>
                            @error('tipo_recargo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Valor recargo</label>
                            <input type="number" step="0.01" wire:model="valor_recargo"
                                   placeholder="0.00"
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                            @error('valor_recargo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 space-y-3">
                        <label class="block text-xs font-medium text-slate-700">Contrato firmado</label>
                        @if($contratoActual->ruta_contrato_firmado)
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.contratos-financiamiento.show', $contratoActual) }}"
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                    Ver detalle
                                </a>
                                <a href="{{ route('admin.contratos-financiamiento.archivo', $contratoActual) }}"
                                   target="_blank"
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                    Ver archivo actual
                                </a>
                                <button type="button"
                                        wire:click="eliminarArchivoContrato"
                                        wire:confirm="¿Seguro que deseas eliminar el contrato firmado?"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg bg-red-600 text-white text-xs font-medium hover:bg-red-700 transition-colors">
                                    Eliminar archivo
                                </button>
                            </div>
                        @else
                            <p class="text-xs text-slate-400">No hay archivo cargado.</p>
                        @endif
                        <input type="file"
                               wire:model="contrato_firmado"
                               accept=".pdf,.jpg,.jpeg,.png,.webp"
                               class="block w-full text-sm text-slate-500
                                      file:mr-3 file:py-2 file:px-4
                                      file:rounded-lg file:border-0
                                      file:bg-indigo-600 file:text-white file:text-sm file:font-medium
                                      hover:file:bg-indigo-700 file:cursor-pointer file:transition-colors">
                        @error('contrato_firmado') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Observaciones</label>
                        <textarea wire:model="observaciones" rows="4"
                                  class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 resize-none"></textarea>
                        @error('observaciones') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Cuotas generadas --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Cuotas generadas</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Al guardar se reemplazan por el nuevo cálculo.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50">
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">#</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Vence</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Monto</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Pagado</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Saldo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estatus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($contratoActual->cuotas->sortBy('numero') as $cuota)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3 text-slate-700">{{ $cuota->numero }}</td>
                                    <td class="px-4 py-3 text-slate-700">{{ optional($cuota->fecha_vencimiento)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-slate-700 tabular-nums">${{ number_format((float) $cuota->monto, 2) }}</td>
                                    <td class="px-4 py-3 text-slate-700 tabular-nums">${{ number_format((float) $cuota->monto_pagado, 2) }}</td>
                                    <td class="px-4 py-3 text-slate-700 tabular-nums">${{ number_format((float) $cuota->saldo, 2) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                            @if($cuota->estatus === 'pagada') bg-emerald-50 text-emerald-700
                                            @elseif($cuota->estatus === 'vencida') bg-red-50 text-red-700
                                            @elseif($cuota->estatus === 'parcial') bg-amber-50 text-amber-700
                                            @else bg-slate-100 text-slate-600 @endif">
                                            {{ ucfirst($cuota->estatus) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-400">No hay cuotas generadas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-5 xl:sticky xl:top-[4.5rem] xl:self-start">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Resumen del contrato</h2>
                </div>
                <div class="p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Folio</span>
                        <span class="text-xs font-semibold text-slate-900">{{ $folio }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Monto financiado</span>
                        <span class="text-xs font-semibold text-slate-900 tabular-nums">${{ number_format((float) $monto_financiado, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Total a pagar</span>
                        <span class="text-xs font-semibold text-slate-900 tabular-nums">${{ number_format((float) $total_pagar, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Cuota</span>
                        <span class="text-xs font-semibold text-slate-900 tabular-nums">${{ number_format((float) $monto_cuota, 2) }}</span>
                    </div>
                    <div class="border-t border-slate-100 pt-3 flex items-center justify-between">
                        <span class="text-xs text-slate-500">Total pagado</span>
                        <span class="text-xs font-semibold text-emerald-700 tabular-nums">${{ number_format((float) $total_pagado, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Saldo</span>
                        <span class="text-xs font-semibold text-slate-900 tabular-nums">${{ number_format((float) $saldo_actual, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-3">
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="guardar"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <span wire:loading.remove wire:target="guardar" class="flex items-center gap-2">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd"/>
                        </svg>
                        Guardar cambios
                    </span>
                    <span wire:loading wire:target="guardar" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Guardando...
                    </span>
                </button>
                <a href="{{ route('admin.contratos-financiamiento.index') }}"
                   class="w-full inline-flex items-center justify-center px-5 py-2.5 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                    Cancelar
                </a>
            </div>
        </div>

    </form>
</div>
