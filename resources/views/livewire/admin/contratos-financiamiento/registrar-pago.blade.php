<div class="p-4 sm:p-6 space-y-6 max-w-3xl">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Registrar pago</h1>
            <p class="text-sm text-slate-500 mt-0.5">El pago siempre debe asociarse a una cuota del contrato.</p>
        </div>
        <a href="{{ route('admin.contratos-financiamiento.show', $contrato) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shrink-0">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 010 1.06l-6.22 6.22H21a.75.75 0 010 1.5H4.81l6.22 6.22a.75.75 0 11-1.06 1.06l-7.5-7.5a.75.75 0 010-1.06l7.5-7.5a.75.75 0 011.06 0z" clip-rule="evenodd"/>
            </svg>
            Volver al contrato
        </a>
    </div>

    {{-- Contexto del contrato --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Contrato</p>
            <p class="mt-1.5 text-sm font-semibold text-slate-900 tabular-nums">{{ $contrato->folio }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Cliente</p>
            <p class="mt-1.5 text-sm font-semibold text-slate-900">{{ $contrato->cliente?->nombre_completo ?: '—' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Saldo actual</p>
            <p class="mt-1.5 text-sm font-semibold text-slate-900 tabular-nums">${{ number_format((float) $contrato->saldo_actual, 2) }}</p>
        </div>
    </div>

    {{-- Formulario --}}
    <form wire:submit="guardar" class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 sm:p-6 space-y-5">

        {{-- Cuota --}}
        <div>
            <label class="block text-xs font-medium text-slate-700 mb-1.5">
                Cuota a pagar <span class="text-red-500">*</span>
            </label>
            <select wire:model.live="cuota_id"
                    class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Selecciona una cuota</option>
                @foreach($cuotasDisponibles as $cuota)
                    <option value="{{ $cuota->id }}">
                        Cuota #{{ $cuota->numero }} · {{ optional($cuota->fecha_vencimiento)->format('d/m/Y') }} · Saldo ${{ number_format((float) $cuota->saldo, 2) }} · {{ ucfirst($cuota->estatus) }}
                    </option>
                @endforeach
            </select>
            @error('cuota_id')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Fecha, monto y forma de pago --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1.5">
                    Fecha de pago <span class="text-red-500">*</span>
                </label>
                <input type="date" wire:model="fecha_pago"
                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('fecha_pago')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1.5">
                    Monto <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                    <input type="number" step="0.01" wire:model="monto"
                           class="block w-full rounded-lg border-slate-300 pl-6 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                </div>
                @error('monto')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                @if($recargoSugerido > 0)
                    <div class="mt-2 flex items-start gap-2 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2">
                        <svg class="h-4 w-4 text-amber-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-xs font-semibold text-amber-800">Recargo por mora</p>
                            <p class="text-xs text-amber-700 mt-0.5">
                                Se sugiere incluir <span class="font-semibold tabular-nums">${{ number_format($recargoSugerido, 2) }}</span> de recargo. El monto ingresado no lo incluye automáticamente.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1.5">
                    Forma de pago <span class="text-red-500">*</span>
                </label>
                <select wire:model="forma_pago"
                        class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="efectivo">Efectivo</option>
                    <option value="transferencia">Transferencia</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="deposito">Depósito</option>
                    <option value="otro">Otro</option>
                </select>
                @error('forma_pago')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Referencia y observaciones --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1.5">Referencia</label>
                <input type="text" wire:model="referencia"
                       placeholder="Número de transferencia, cheque, etc."
                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('referencia')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1.5">Observaciones</label>
                <input type="text" wire:model="observaciones"
                       placeholder="Notas adicionales opcionales"
                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('observaciones')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Acciones --}}
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 border-t border-slate-100">
            <a href="{{ route('admin.contratos-financiamiento.show', $contrato) }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    wire:loading.attr="disabled"
                    wire:target="guardar"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                <span wire:loading.remove wire:target="guardar" class="flex items-center gap-2">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM9 7.5A.75.75 0 009 9h1.5c.98 0 1.813.626 2.122 1.5H9A.75.75 0 009 12h3.622a2.251 2.251 0 01-2.122 1.5H9a.75.75 0 00-.53 1.28l3 3a.75.75 0 101.06-1.06l-1.7-1.7A3.75 3.75 0 0013.5 12H15a.75.75 0 000-1.5h-1.5a3.75 3.75 0 00-1.033-2.554A3.75 3.75 0 0015 7.5H9z" clip-rule="evenodd"/>
                    </svg>
                    Guardar pago y generar recibo
                </span>
                <span wire:loading wire:target="guardar" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Procesando...
                </span>
            </button>
        </div>

    </form>
</div>
