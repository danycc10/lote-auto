<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Nuevo contrato</h1>
            <p class="text-sm text-slate-500 mt-0.5">Captura el contrato de financiamiento, calcula totales y genera cuotas automáticamente.</p>
        </div>
        <a href="{{ route('admin.contratos-financiamiento.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shrink-0">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 010 1.06l-6.22 6.22H21a.75.75 0 010 1.5H4.81l6.22 6.22a.75.75 0 11-1.06 1.06l-7.5-7.5a.75.75 0 010-1.06l7.5-7.5a.75.75 0 011.06 0z" clip-rule="evenodd"/>
            </svg>
            Volver al listado
        </a>
    </div>

    @if($apartado_auto_id && $apartadoActual)
        <div class="flex items-start gap-3 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
            <svg class="h-4 w-4 text-blue-500 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 01.67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 11-.671-1.34l.041-.022zM12 9a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="font-medium">Contrato generado desde apartado {{ $apartadoActual->folio }}</p>
                <p class="mt-0.5 text-blue-700">El auto y el cliente fueron precargados y no se pueden modificar. Anticipo aplicado al enganche: <span class="font-semibold">${{ number_format((float) $anticipo_apartado, 2) }}</span></p>
            </div>
        </div>
    @endif

    <form wire:submit="guardar" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        @if($bloquear_auto_cliente)
            <input type="hidden" wire:model="auto_id">
            <input type="hidden" wire:model="cliente_id">
        @endif

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
                            <input type="text" value="{{ $folio }}" readonly
                                   class="block w-full rounded-lg border-slate-300 bg-slate-50 text-slate-500 text-sm cursor-not-allowed focus:border-indigo-500 focus:ring-indigo-500">
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

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Auto <span class="text-red-500">*</span></label>
                        @if($bloquear_auto_cliente && $apartadoActual?->auto)
                            @php
                                $autoTexto = trim(($apartadoActual->auto->marca->nombre ?? '') . ' ' . ($apartadoActual->auto->modelo->nombre ?? '') . ' ' . ($apartadoActual->auto->anio ?? ''));
                                if (!empty($apartadoActual->auto->codigo_inventario)) $autoTexto .= ' | Código: ' . $apartadoActual->auto->codigo_inventario;
                                if (!empty($apartadoActual->auto->placa)) $autoTexto .= ' | Placa: ' . $apartadoActual->auto->placa;
                                if (!empty($apartadoActual->auto->vin)) $autoTexto .= ' | VIN: ' . $apartadoActual->auto->vin;
                            @endphp
                            <input type="text" readonly value="{{ $autoTexto }}"
                                   class="block w-full rounded-lg border-slate-300 bg-slate-50 text-slate-500 text-sm cursor-not-allowed focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-blue-600">Bloqueado por apartado: <span class="font-medium">{{ $apartadoActual->auto->nombre_completo }}</span></p>
                        @else
                            <select wire:model.live="auto_id"
                                    class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecciona un auto</option>
                                @foreach($autos as $auto)
                                    <option value="{{ $auto->id }}">{{ $auto->label ?? ($auto->nombre_completo . ' | ' . ($auto->codigo_inventario ?: 'Sin código')) }}</option>
                                @endforeach
                            </select>
                        @endif
                        @error('auto_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Cliente <span class="text-red-500">*</span></label>
                        @if($bloquear_auto_cliente && $apartadoActual?->cliente)
                            @php
                                $clienteTexto = $apartadoActual->cliente->nombre_completo;
                                if (!empty($apartadoActual->cliente->telefono)) $clienteTexto .= ' | ' . $apartadoActual->cliente->telefono;
                            @endphp
                            <input type="text" readonly value="{{ $clienteTexto }}"
                                   class="block w-full rounded-lg border-slate-300 bg-slate-50 text-slate-500 text-sm cursor-not-allowed focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-blue-600">Bloqueado por apartado: <span class="font-medium">{{ $apartadoActual->cliente->nombre_completo }}</span></p>
                        @else
                            <select wire:model="cliente_id"
                                    class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecciona un cliente</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nombre_completo }} | {{ $cliente->telefono ?: 'Sin teléfono' }}</option>
                                @endforeach
                            </select>
                        @endif
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
                    <p class="text-xs text-slate-500 mt-0.5">Base del crédito y cargos adicionales.</p>
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
                        @if($apartado_auto_id)
                            <p class="mt-1 text-xs text-blue-600">Incluye anticipo: <span class="font-medium">${{ number_format((float) $anticipo_apartado, 2) }}</span></p>
                        @endif
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
                </div>
            </div>

            {{-- Estructura del crédito --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Estructura del crédito</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Interés, plazo, frecuencia y cuota.</p>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4">
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
                               placeholder="0.00"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                        @error('tasa_interes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Plazo <span class="text-red-500">*</span></label>
                        <input type="number" wire:model.live="plazo"
                               placeholder="Número de cuotas"
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
                </div>
            </div>

            {{-- Cobro y contrato firmado --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Cobro y contrato firmado</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Gracia, recargo y archivo privado.</p>
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
                        <input type="file"
                               wire:model="contrato_firmado"
                               accept=".pdf,.jpg,.jpeg,.png,.webp"
                               class="block w-full text-sm text-slate-500
                                      file:mr-3 file:py-2 file:px-4
                                      file:rounded-lg file:border-0
                                      file:bg-indigo-600 file:text-white file:text-sm file:font-medium
                                      hover:file:bg-indigo-700 file:cursor-pointer file:transition-colors">
                        <p class="text-xs text-slate-400">Se guarda en disco privado. PDF o imagen. Máx. 10 MB.</p>
                        <div wire:loading wire:target="contrato_firmado" class="flex items-center gap-2 text-xs text-slate-500">
                            <svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Cargando archivo...
                        </div>
                        @error('contrato_firmado') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Observaciones</label>
                        <textarea wire:model="observaciones" rows="4"
                                  placeholder="Notas internas, condiciones especiales..."
                                  class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 resize-none"></textarea>
                        @error('observaciones') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-5 xl:sticky xl:top-6 xl:self-start">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Resumen del contrato</h2>
                </div>
                <div class="p-5 space-y-3">
                    @if($apartado_auto_id)
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Anticipo aplicado</span>
                            <span class="text-xs font-semibold text-blue-700 tabular-nums">${{ number_format((float) $anticipo_apartado, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Monto financiado</span>
                        <span class="text-xs font-semibold text-slate-900 tabular-nums">${{ number_format((float) $monto_financiado, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Total a pagar</span>
                        <span class="text-xs font-semibold text-slate-900 tabular-nums">${{ number_format((float) $total_pagar, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Cuota estimada</span>
                        <span class="text-xs font-semibold text-slate-900 tabular-nums">${{ number_format((float) $monto_cuota, 2) }}</span>
                    </div>
                    <div class="border-t border-slate-100 pt-3 flex items-center justify-between">
                        <span class="text-xs text-slate-500">Saldo actual</span>
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
                        Guardar contrato
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
