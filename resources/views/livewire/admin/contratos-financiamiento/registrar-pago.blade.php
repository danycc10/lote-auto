<div class="max-w-4xl mx-auto p-4 sm:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black">Registrar pago</h1>
            <p class="text-gray-500">El pago siempre debe asociarse a una cuota.</p>
        </div>

        <a href="{{ route('admin.contratos-financiamiento.show', $contrato) }}" class="px-4 py-2 rounded-2xl border font-semibold hover:bg-gray-50">Volver al contrato</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-2xl border bg-white p-4 shadow-sm">
            <div class="text-xs text-gray-500 font-bold uppercase">Contrato</div>
            <div class="mt-2 font-black">{{ $contrato->folio }}</div>
        </div>
        <div class="rounded-2xl border bg-white p-4 shadow-sm">
            <div class="text-xs text-gray-500 font-bold uppercase">Cliente</div>
            <div class="mt-2 font-black">{{ $contrato->cliente?->nombre_completo ?: '—' }}</div>
        </div>
        <div class="rounded-2xl border bg-white p-4 shadow-sm">
            <div class="text-xs text-gray-500 font-bold uppercase">Saldo actual</div>
            <div class="mt-2 font-black">${{ number_format((float) $contrato->saldo_actual, 2) }}</div>
        </div>
    </div>

    <form wire:submit="guardar" class="bg-white border rounded-3xl shadow-sm p-6 space-y-5">
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">Cuota a pagar *</label>
            <select wire:model.live="cuota_id" class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                <option value="">Selecciona una cuota</option>
                @foreach($cuotasDisponibles as $cuota)
                    <option value="{{ $cuota->id }}">
                        Cuota #{{ $cuota->numero }} · {{ optional($cuota->fecha_vencimiento)->format('d/m/Y') }} · Saldo ${{ number_format((float) $cuota->saldo, 2) }} · {{ ucfirst($cuota->estatus) }}
                    </option>
                @endforeach
            </select>
            @error('cuota_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Fecha de pago *</label>
                <input type="date" wire:model="fecha_pago" class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                @error('fecha_pago') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Monto *</label>
                <input type="number" step="0.01" wire:model="monto" class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                @error('monto') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Forma de pago *</label>
                <select wire:model="forma_pago" class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    <option value="efectivo">Efectivo</option>
                    <option value="transferencia">Transferencia</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="deposito">Depósito</option>
                    <option value="otro">Otro</option>
                </select>
                @error('forma_pago') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Referencia</label>
                <input type="text" wire:model="referencia" class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                @error('referencia') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Observaciones</label>
                <input type="text" wire:model="observaciones" class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                @error('observaciones') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('admin.contratos-financiamiento.show', $contrato) }}" class="px-4 py-2 rounded-2xl border font-semibold hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="px-5 py-3 rounded-2xl bg-black text-white font-bold hover:opacity-90">Guardar pago y generar recibo</button>
        </div>
    </form>
</div>
