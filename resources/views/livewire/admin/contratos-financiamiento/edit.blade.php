<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight">Editar contrato</h1>
            <p class="text-sm text-gray-500 mt-1">
                Actualiza los datos financieros, el archivo firmado y regenera las cuotas.
            </p>
        </div>

        <a href="{{ route('admin.contratos-financiamiento.index') }}"
           class="inline-flex items-center justify-center px-4 py-2.5 rounded-2xl border border-gray-300 bg-white text-sm font-semibold hover:bg-gray-50 transition">
            Volver al listado
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-700 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="guardar" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">

            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50/80">
                    <h2 class="text-lg font-black">Datos principales</h2>
                </div>

                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Folio *</label>
                        <input type="text" wire:model="folio"
                               class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                        @error('folio') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Estatus *</label>
                        <select wire:model="estatus"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                            <option value="borrador">Borrador</option>
                            <option value="activo">Activo</option>
                            <option value="atrasado">Atrasado</option>
                            <option value="liquidado">Liquidado</option>
                            <option value="cancelado">Cancelado</option>
                            <option value="reestructurado">Reestructurado</option>
                            <option value="recuperado">Recuperado</option>
                        </select>
                        @error('estatus') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Auto *</label>
                        <select wire:model="auto_id"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                            <option value="">Selecciona un auto</option>
                            @foreach($autos as $auto)
                                <option value="{{ $auto->id }}">
                                    {{ $auto->nombre_completo }} | {{ $auto->codigo_inventario ?: 'Sin código' }}
                                </option>
                            @endforeach
                        </select>
                        @error('auto_id') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Cliente *</label>
                        <select wire:model="cliente_id"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                            <option value="">Selecciona un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">
                                    {{ $cliente->nombre_completo }} | {{ $cliente->telefono ?: 'Sin teléfono' }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Fecha contrato *</label>
                        <input type="date" wire:model="fecha_contrato"
                               class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                        @error('fecha_contrato') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Fecha primer pago</label>
                        <input type="date" wire:model="fecha_primer_pago"
                               class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                        @error('fecha_primer_pago') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50/80">
                    <h2 class="text-lg font-black">Valores financieros</h2>
                </div>

                <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Precio contado *</label>
                        <input type="number" step="0.01" wire:model="precio_contado"
                               class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                        @error('precio_contado') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Precio venta *</label>
                        <input type="number" step="0.01" wire:model.live="precio_venta"
                               class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                        @error('precio_venta') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Enganche</label>
                        <input type="number" step="0.01" wire:model.live="enganche"
                               class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                        @error('enganche') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Comisión apertura</label>
                        <input type="number" step="0.01" wire:model.live="comision_apertura"
                               class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                        @error('comision_apertura') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Monto seguro</label>
                        <input type="number" step="0.01" wire:model.live="monto_seguro"
                               class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                        @error('monto_seguro') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Monto GPS</label>
                        <input type="number" step="0.01" wire:model.live="monto_gps"
                               class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                        @error('monto_gps') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Monto financiado *</label>
                        <input type="number" step="0.01" wire:model="monto_financiado" readonly
                               class="w-full mt-1.5 rounded-2xl border-gray-300 bg-gray-50 focus:border-black focus:ring-black">
                        @error('monto_financiado') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Tasa interés %</label>
                        <input type="number" step="0.01" wire:model.live="tasa_interes"
                               class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                        @error('tasa_interes') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Plazo *</label>
                        <input type="number" wire:model.live="plazo"
                               class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                        @error('plazo') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Frecuencia *</label>
                        <select wire:model="frecuencia"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                            <option value="semanal">Semanal</option>
                            <option value="quincenal">Quincenal</option>
                            <option value="mensual">Mensual</option>
                        </select>
                        @error('frecuencia') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Monto cuota *</label>
                        <input type="number" step="0.01" wire:model="monto_cuota"
                               class="w-full mt-1.5 rounded-2xl border-gray-300 bg-gray-50 focus:border-black focus:ring-black">
                        @error('monto_cuota') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Total pagar *</label>
                        <input type="number" step="0.01" wire:model="total_pagar" readonly
                               class="w-full mt-1.5 rounded-2xl border-gray-300 bg-gray-50 focus:border-black focus:ring-black">
                        @error('total_pagar') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Total pagado</label>
                        <input type="number" step="0.01" wire:model="total_pagado"
                               class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                        @error('total_pagado') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Saldo actual</label>
                        <input type="number" step="0.01" wire:model="saldo_actual"
                               class="w-full mt-1.5 rounded-2xl border-gray-300 bg-gray-50 focus:border-black focus:ring-black">
                        @error('saldo_actual') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50/80">
                    <h2 class="text-lg font-black">Recargo, archivo y observaciones</h2>
                </div>

                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Días de gracia</label>
                        <input type="number" wire:model="dias_gracia"
                               class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                        @error('dias_gracia') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Tipo recargo</label>
                        <select wire:model="tipo_recargo"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                            <option value="">Sin recargo</option>
                            <option value="fijo">Fijo</option>
                            <option value="porcentaje">Porcentaje</option>
                        </select>
                        @error('tipo_recargo') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Valor recargo</label>
                        <input type="number" step="0.01" wire:model="valor_recargo"
                               class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                        @error('valor_recargo') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="rounded-2xl border p-4 space-y-3">
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Contrato firmado</label>

                        @if($contratoActual->ruta_contrato_firmado)
                            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.contratos-financiamiento.show', $contratoActual) }}"
                   class="inline-flex items-center px-4 py-2 rounded-2xl border bg-white font-semibold hover:bg-gray-50">
                    Ver detalle
                </a>
                                <a href="{{ route('admin.contratos-financiamiento.archivo', $contratoActual) }}"
                                   target="_blank"
                                   class="px-3 py-2 rounded-xl border text-sm font-semibold hover:bg-gray-50">
                                    Ver archivo actual
                                </a>

                                <button type="button"
                                        wire:click="eliminarArchivoContrato"
                                        wire:confirm="¿Seguro que deseas eliminar el contrato firmado?"
                                        class="px-3 py-2 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700">
                                    Eliminar archivo
                                </button>
                            </div>
                        @else
                            <div class="text-sm text-gray-400">No hay archivo cargado.</div>
                        @endif

                        <input type="file"
                               wire:model="contrato_firmado"
                               accept=".pdf,.jpg,.jpeg,.png,.webp"
                               class="block w-full text-sm text-gray-700 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-black file:text-white file:font-semibold hover:file:bg-gray-800">

                        @error('contrato_firmado') <div class="text-red-600 text-sm mt-2">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="px-5 pb-5">
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Observaciones</label>
                    <textarea wire:model="observaciones" rows="4"
                              class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black"></textarea>
                    @error('observaciones') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50/80">
                    <h2 class="text-lg font-black">Cuotas generadas</h2>
                    <p class="text-sm text-gray-500">Al guardar se reemplazan por el nuevo cálculo.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr class="text-left text-gray-500">
                                <th class="px-4 py-3 font-bold">#</th>
                                <th class="px-4 py-3 font-bold">Vence</th>
                                <th class="px-4 py-3 font-bold">Monto</th>
                                <th class="px-4 py-3 font-bold">Pagado</th>
                                <th class="px-4 py-3 font-bold">Saldo</th>
                                <th class="px-4 py-3 font-bold">Estatus</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($contratoActual->cuotas->sortBy('numero') as $cuota)
                                <tr>
                                    <td class="px-4 py-3">{{ $cuota->numero }}</td>
                                    <td class="px-4 py-3">{{ optional($cuota->fecha_vencimiento)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3">${{ number_format((float) $cuota->monto, 2) }}</td>
                                    <td class="px-4 py-3">${{ number_format((float) $cuota->monto_pagado, 2) }}</td>
                                    <td class="px-4 py-3">${{ number_format((float) $cuota->saldo, 2) }}</td>
                                    <td class="px-4 py-3">{{ ucfirst($cuota->estatus) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                        No hay cuotas generadas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50/80">
                    <h2 class="text-lg font-black">Resumen</h2>
                </div>

                <div class="p-5 space-y-4 text-sm">
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Folio</span>
                        <span class="font-black">{{ $folio }}</span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Monto financiado</span>
                        <span class="font-black">${{ number_format((float) $monto_financiado, 2) }}</span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Total a pagar</span>
                        <span class="font-black">${{ number_format((float) $total_pagar, 2) }}</span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Cuota</span>
                        <span class="font-black">${{ number_format((float) $monto_cuota, 2) }}</span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Pagado</span>
                        <span class="font-black">${{ number_format((float) $total_pagado, 2) }}</span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Saldo</span>
                        <span class="font-black">${{ number_format((float) $saldo_actual, 2) }}</span>
                    </div>

                    <button type="submit"
                            class="w-full px-5 py-3 rounded-2xl bg-black text-white font-bold shadow-sm hover:opacity-90">
                        Guardar cambios
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>