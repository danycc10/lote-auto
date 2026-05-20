<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight">Nuevo contrato</h1>
            <p class="text-sm text-gray-500 mt-1">
                Captura el contrato de financiamiento, calcula totales y genera cuotas automáticamente.
            </p>
        </div>

        <a href="{{ route('admin.contratos-financiamiento.index') }}"
            class="inline-flex items-center justify-center px-4 py-2.5 rounded-2xl border border-gray-300 bg-white text-sm font-semibold hover:bg-gray-50 transition">
            Volver al listado
        </a>
    </div>

    @if($apartado_auto_id && $apartadoActual)
    <div class="bg-blue-50 border border-blue-200 rounded-3xl p-4">
        <div class="text-sm font-bold text-blue-900">
            Contrato generado desde apartado {{ $apartadoActual->folio }}
        </div>
        <div class="text-sm text-blue-700 mt-1">
            El auto y el cliente fueron precargados y no se pueden modificar.
            Anticipo aplicado al enganche:
            <span class="font-black">${{ number_format((float) $anticipo_apartado, 2) }}</span>
        </div>
    </div>
    @endif

    <form wire:submit="guardar" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        @if($bloquear_auto_cliente)
        <input type="hidden" wire:model="auto_id">
        <input type="hidden" wire:model="cliente_id">
        @endif

        <div class="xl:col-span-2 space-y-6">

            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50/80">
                    <h2 class="text-lg font-black">Datos principales</h2>
                    <p class="text-sm text-gray-500">Auto, cliente y fechas del contrato.</p>
                </div>

                <div class="p-5 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Folio *</label>
                            <input type="text"
                                value="{{ $folio }}"
                                readonly
                                class="w-full mt-1.5 rounded-2xl border-gray-300 bg-gray-50 text-gray-700 cursor-not-allowed focus:border-black focus:ring-black">
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
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Auto *</label>

                            @if($bloquear_auto_cliente && $apartadoActual?->auto)

                            @php
                            $autoTexto = trim(
                            ($apartadoActual->auto->marca->nombre ?? '') . ' ' .
                            ($apartadoActual->auto->modelo->nombre ?? '') . ' ' .
                            ($apartadoActual->auto->anio ?? '')
                            );

                            if (!empty($apartadoActual->auto->codigo_inventario)) {
                            $autoTexto .= ' | Código: ' . $apartadoActual->auto->codigo_inventario;
                            }

                            if (!empty($apartadoActual->auto->placa)) {
                            $autoTexto .= ' | Placa: ' . $apartadoActual->auto->placa;
                            }

                            if (!empty($apartadoActual->auto->vin)) {
                            $autoTexto .= ' | VIN: ' . $apartadoActual->auto->vin;
                            }
                            @endphp

                            <input type="text"
                                readonly
                                value="{{ $autoTexto }}"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 bg-gray-100 text-gray-700 cursor-not-allowed focus:border-black focus:ring-black">

                            <div class="mt-2 text-xs text-gray-500">
                                Auto bloqueado por apartado:
                                <span class="font-semibold text-gray-800">
                                    {{ $apartadoActual->auto->nombre_completo }}
                                </span>
                            </div>

                            @else
                            <select wire:model.live="auto_id"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                                <option value="">Selecciona un auto</option>
                                @foreach($autos as $auto)
                                <option value="{{ $auto->id }}">
                                    {{ $auto->label ?? ($auto->nombre_completo . ' | ' . ($auto->codigo_inventario ?: 'Sin código')) }}
                                </option>
                                @endforeach
                            </select>
                            @endif

                            @error('auto_id') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Cliente *</label>

                            @if($bloquear_auto_cliente && $apartadoActual?->cliente)

                            @php
                            $clienteTexto = $apartadoActual->cliente->nombre_completo;

                            if (!empty($apartadoActual->cliente->telefono)) {
                            $clienteTexto .= ' | ' . $apartadoActual->cliente->telefono;
                            }
                            @endphp

                            <input type="text"
                                readonly
                                value="{{ $clienteTexto }}"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 bg-gray-100 text-gray-700 cursor-not-allowed focus:border-black focus:ring-black">

                            <div class="mt-2 text-xs text-gray-500">
                                Cliente bloqueado por apartado:
                                <span class="font-semibold text-gray-800">
                                    {{ $apartadoActual->cliente->nombre_completo }}
                                </span>
                            </div>

                            @else
                            <select wire:model="cliente_id"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                                <option value="">Selecciona un cliente</option>
                                @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">
                                    {{ $cliente->nombre_completo }} | {{ $cliente->telefono ?: 'Sin teléfono' }}
                                </option>
                                @endforeach
                            </select>
                            @endif

                            @error('cliente_id') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
            </div>

            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50/80">
                    <h2 class="text-lg font-black">Valores financieros</h2>
                    <p class="text-sm text-gray-500">Base del crédito y cargos adicionales.</p>
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

                        @if($apartado_auto_id)
                        <div class="mt-2 text-xs text-blue-700">
                            Incluye anticipo del apartado:
                            <span class="font-bold">${{ number_format((float) $anticipo_apartado, 2) }}</span>
                        </div>
                        @endif

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
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50/80">
                    <h2 class="text-lg font-black">Estructura del crédito</h2>
                    <p class="text-sm text-gray-500">Interés, plazo, frecuencia y cuota.</p>
                </div>

                <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Monto financiado *</label>
                        <input type="number" step="0.01" wire:model="monto_financiado"
                            readonly
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
                        <input type="number" step="0.01" wire:model="total_pagar"
                            readonly
                            class="w-full mt-1.5 rounded-2xl border-gray-300 bg-gray-50 focus:border-black focus:ring-black">
                        @error('total_pagar') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50/80">
                    <h2 class="text-lg font-black">Cobro y contrato firmado</h2>
                    <p class="text-sm text-gray-500">Gracia, recargo y archivo privado.</p>
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

                    <div class="rounded-2xl border p-4">
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Contrato firmado</label>
                        <input type="file"
                            wire:model="contrato_firmado"
                            accept=".pdf,.jpg,.jpeg,.png,.webp"
                            class="block w-full mt-2 text-sm text-gray-700 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-black file:text-white file:font-semibold hover:file:bg-gray-800">
                        <p class="text-xs text-gray-500 mt-2">Se guarda en disco privado. PDF o imagen. Máx. 10 MB.</p>
                        <div wire:loading wire:target="contrato_firmado" class="text-sm text-gray-500 mt-2">
                            Cargando archivo...
                        </div>
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
        </div>

        <div class="space-y-6">
            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50/80">
                    <h2 class="text-lg font-black">Resumen</h2>
                    <p class="text-sm text-gray-500">Vista rápida del contrato.</p>
                </div>

                <div class="p-5 space-y-4 text-sm">
                    @if($apartado_auto_id)
                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Anticipo aplicado</span>
                        <span class="font-black text-blue-700">${{ number_format((float) $anticipo_apartado, 2) }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Monto financiado</span>
                        <span class="font-black">${{ number_format((float) $monto_financiado, 2) }}</span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Total a pagar</span>
                        <span class="font-black">${{ number_format((float) $total_pagar, 2) }}</span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Cuota estimada</span>
                        <span class="font-black">${{ number_format((float) $monto_cuota, 2) }}</span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-gray-500">Saldo actual</span>
                        <span class="font-black">${{ number_format((float) $saldo_actual, 2) }}</span>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            wire:loading.attr="disabled"
                            wire:target="guardar"
                            class="w-full px-5 py-3 rounded-2xl bg-black text-white font-bold shadow-sm hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="guardar">Guardar contrato</span>
                            <span wire:loading wire:target="guardar">Guardando...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>