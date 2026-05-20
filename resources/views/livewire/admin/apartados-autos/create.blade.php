<div class="max-w-6xl mx-auto p-6 space-y-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Nuevo apartado</h1>
            <p class="text-gray-500">Registra un apartado con anticipo y fecha límite.</p>
        </div>

        <a href="{{ route('admin.apartados-autos.index') }}"
           class="hidden sm:inline-flex px-4 py-2 rounded-xl border bg-white hover:bg-gray-50 font-semibold">
            Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
            <div class="font-bold mb-2">Hay errores por corregir:</div>
            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">
                <div class="bg-white border rounded-2xl p-5 space-y-4">
                    <h2 class="text-lg font-bold text-gray-900">Auto</h2>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Buscar auto</label>
                        <input type="text"
                               wire:model.live.debounce.300ms="buscarAuto"
                               placeholder="Marca, modelo, VIN, placa..."
                               class="mt-1 w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Seleccionar auto</label>
                        <select wire:model="auto_id"
                                class="mt-1 w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                            <option value="">-- Selecciona un auto --</option>
                            @foreach($autosDisponibles as $auto)
                                <option value="{{ $auto->id }}">
                                    {{ $auto->nombre_completo }} | VIN: {{ $auto->vin }} | Estatus: {{ $auto->estatus }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="bg-white border rounded-2xl p-5 space-y-4">
                    <h2 class="text-lg font-bold text-gray-900">Cliente</h2>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Buscar cliente existente</label>
                        <input type="text"
                               wire:model.live.debounce.300ms="buscarCliente"
                               placeholder="Nombre, teléfono, correo..."
                               class="mt-1 w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Seleccionar cliente</label>
                        <select wire:model="cliente_id"
                                class="mt-1 w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                            <option value="">-- Sin cliente registrado --</option>
                            @foreach($clientesDisponibles as $cliente)
                                <option value="{{ $cliente->id }}">
                                    {{ $cliente->nombre_completo }} | {{ $cliente->telefono ?? 'Sin teléfono' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="border-t pt-4">
                        <div class="text-sm font-semibold text-gray-700 mb-3">O captura cliente temporal</div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-sm text-gray-700">Nombre</label>
                                <input type="text"
                                       wire:model="nombre_cliente_temporal"
                                       class="mt-1 w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                            </div>

                            <div>
                                <label class="text-sm text-gray-700">Teléfono</label>
                                <input type="text"
                                       wire:model="telefono_cliente_temporal"
                                       class="mt-1 w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                            </div>

                            <div>
                                <label class="text-sm text-gray-700">Correo</label>
                                <input type="email"
                                       wire:model="correo_cliente_temporal"
                                       class="mt-1 w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border rounded-2xl p-5 space-y-4">
                    <h2 class="text-lg font-bold text-gray-900">Observaciones</h2>

                    <div>
                        <textarea wire:model="observaciones"
                                  rows="4"
                                  class="w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900"
                                  placeholder="Notas del apartado, condiciones, comentarios..."></textarea>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white border rounded-2xl p-5 space-y-4">
                    <h2 class="text-lg font-bold text-gray-900">Datos del apartado</h2>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Fecha de apartado</label>
                        <input type="date"
                               wire:model="fecha_apartado"
                               class="mt-1 w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Fecha de vencimiento</label>
                        <input type="date"
                               wire:model="fecha_vencimiento"
                               class="mt-1 w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Monto de anticipo</label>
                        <input type="number"
                               step="0.01"
                               min="0"
                               wire:model="monto_anticipo"
                               class="mt-1 w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Forma de pago</label>
                        <select wire:model="forma_pago"
                                class="mt-1 w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                            <option value="">-- Selecciona --</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="deposito">Depósito</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700">Referencia</label>
                        <input type="text"
                               wire:model="referencia"
                               class="mt-1 w-full rounded-xl border-gray-300 focus:border-gray-900 focus:ring-gray-900">
                    </div>
                </div>

                <div class="bg-white border rounded-2xl p-5 space-y-3">
                    <div class="text-sm text-gray-500">Resumen</div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Anticipo</span>
                        <span class="font-bold text-gray-900">
                            ${{ number_format((float) ($monto_anticipo ?: 0), 2) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Vence</span>
                        <span class="font-semibold text-gray-900">
                            {{ $fecha_vencimiento ? \Carbon\Carbon::parse($fecha_vencimiento)->format('d/m/Y') : '—' }}
                        </span>
                    </div>

                    <button type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-3 rounded-xl bg-gray-900 text-white font-bold hover:bg-black">
                        Guardar apartado
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>