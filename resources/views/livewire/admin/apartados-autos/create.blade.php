<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Nuevo apartado</h1>
            <p class="text-sm text-slate-500 mt-0.5">Registra un apartado con anticipo y fecha límite.</p>
        </div>
        <a href="{{ route('admin.apartados-autos.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shrink-0">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 010 1.06l-6.22 6.22H21a.75.75 0 010 1.5H4.81l6.22 6.22a.75.75 0 11-1.06 1.06l-7.5-7.5a.75.75 0 010-1.06l7.5-7.5a.75.75 0 011.06 0z" clip-rule="evenodd"/>
            </svg>
            Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <p class="font-medium mb-1.5">Hay errores por corregir:</p>
            <ul class="list-disc pl-5 space-y-0.5 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit="save" class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <div class="xl:col-span-2 space-y-5">

            {{-- Auto --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Auto</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Busca y selecciona el vehículo a apartar.</p>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Buscar auto</label>
                        <input type="text"
                               wire:model.live.debounce.300ms="buscarAuto"
                               placeholder="Marca, modelo, VIN, placa..."
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Seleccionar auto <span class="text-red-500">*</span></label>
                        <select wire:model="auto_id"
                                class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">— Selecciona un auto —</option>
                            @foreach($autosDisponibles as $auto)
                                <option value="{{ $auto->id }}">
                                    {{ $auto->nombre_completo }} | VIN: {{ $auto->vin }} | Estatus: {{ $auto->estatus }}
                                </option>
                            @endforeach
                        </select>
                        @error('auto_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Cliente --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Cliente</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Busca un cliente registrado o captura uno temporal.</p>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Buscar cliente existente</label>
                        <input type="text"
                               wire:model.live.debounce.300ms="buscarCliente"
                               placeholder="Nombre, teléfono, correo..."
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Seleccionar cliente</label>
                        <select wire:model="cliente_id"
                                class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">— Sin cliente registrado —</option>
                            @foreach($clientesDisponibles as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre_completo }} | {{ $cliente->telefono ?? 'Sin teléfono' }}</option>
                            @endforeach
                        </select>
                        @error('cliente_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs font-medium text-slate-700 mb-3">O captura cliente temporal</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1.5">Nombre</label>
                                <input type="text" wire:model="nombre_cliente_temporal"
                                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1.5">Teléfono</label>
                                <input type="text" wire:model="telefono_cliente_temporal"
                                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1.5">Correo</label>
                                <input type="email" wire:model="correo_cliente_temporal"
                                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Observaciones --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Observaciones</h2>
                </div>
                <div class="p-5">
                    <textarea wire:model="observaciones" rows="4"
                              placeholder="Notas del apartado, condiciones, comentarios..."
                              class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 resize-none"></textarea>
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-5 xl:sticky xl:top-6 xl:self-start">

            {{-- Datos del apartado --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Datos del apartado</h2>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Fecha de apartado</label>
                        <input type="date" wire:model="fecha_apartado"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('fecha_apartado') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Fecha de vencimiento</label>
                        <input type="date" wire:model="fecha_vencimiento"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('fecha_vencimiento') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Monto de anticipo</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                            <input type="number" step="0.01" min="0" wire:model="monto_anticipo"
                                   placeholder="0.00"
                                   class="block w-full rounded-lg border-slate-300 pl-6 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                        </div>
                        @error('monto_anticipo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Forma de pago</label>
                        <select wire:model="forma_pago"
                                class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">— Selecciona —</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="deposito">Depósito</option>
                        </select>
                        @error('forma_pago') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Referencia</label>
                        <input type="text" wire:model="referencia"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('referencia') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Resumen + Guardar --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Resumen</h2>
                </div>
                <div class="p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Anticipo</span>
                        <span class="text-xs font-semibold text-slate-900 tabular-nums">${{ number_format((float) ($monto_anticipo ?: 0), 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Vence</span>
                        <span class="text-xs font-semibold text-slate-900">{{ $fecha_vencimiento ? \Carbon\Carbon::parse($fecha_vencimiento)->format('d/m/Y') : '—' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-3">
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="save"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <span wire:loading.remove wire:target="save" class="flex items-center gap-2">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd"/>
                        </svg>
                        Guardar apartado
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Guardando...
                    </span>
                </button>
                <a href="{{ route('admin.apartados-autos.index') }}"
                   class="w-full inline-flex items-center justify-center px-5 py-2.5 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                    Cancelar
                </a>
            </div>

        </div>

    </form>
</div>
