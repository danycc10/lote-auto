<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black">Estado de cuenta · {{ $contrato->folio }}</h1>
            <p class="text-gray-500">Resumen ejecutivo de pagos, vencimientos, saldo y trazabilidad.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.contratos-financiamiento.show', $contrato) }}" class="px-4 py-2 rounded-2xl border bg-white hover:bg-gray-50 font-semibold">Volver al contrato</a>
            <a href="{{ route('admin.contratos-financiamiento.estado-cuenta.pdf', $contrato) }}" target="_blank" class="px-4 py-2 rounded-2xl bg-black text-white font-semibold">PDF estado de cuenta</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="rounded-3xl border bg-white p-5 shadow-sm"><div class="text-xs uppercase tracking-wide text-gray-500 font-bold">Saldo</div><div class="text-3xl font-black mt-2">${{ number_format((float) ($resumen['saldo_actual'] ?? 0), 2) }}</div></div>
        <div class="rounded-3xl border bg-white p-5 shadow-sm"><div class="text-xs uppercase tracking-wide text-gray-500 font-bold">Pagado</div><div class="text-3xl font-black mt-2 text-emerald-600">${{ number_format((float) ($resumen['total_pagado'] ?? 0), 2) }}</div></div>
        <div class="rounded-3xl border bg-white p-5 shadow-sm"><div class="text-xs uppercase tracking-wide text-gray-500 font-bold">Vencidas</div><div class="text-3xl font-black mt-2 text-amber-600">{{ $resumen['cuotas_vencidas'] ?? 0 }}</div></div>
        <div class="rounded-3xl border bg-white p-5 shadow-sm"><div class="text-xs uppercase tracking-wide text-gray-500 font-bold">Capital pendiente</div><div class="text-3xl font-black mt-2 text-sky-600">${{ number_format((float) ($resumen['capital_pendiente'] ?? 0), 2) }}</div></div>
        <div class="rounded-3xl border bg-white p-5 shadow-sm"><div class="text-xs uppercase tracking-wide text-gray-500 font-bold">Interés pendiente</div><div class="text-3xl font-black mt-2 text-fuchsia-600">${{ number_format((float) ($resumen['interes_pendiente'] ?? 0), 2) }}</div></div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 rounded-3xl border bg-white shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b bg-gray-50">
                <h2 class="font-black text-gray-900">Cuotas del estado de cuenta</h2>
                <p class="text-sm text-gray-500">Puedes usar esta vista para cobranza, aclaraciones y seguimiento.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-b text-gray-500">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold">#</th>
                            <th class="px-4 py-3 text-left font-bold">Vencimiento</th>
                            <th class="px-4 py-3 text-right font-bold">Monto</th>
                            <th class="px-4 py-3 text-right font-bold">Pagado</th>
                            <th class="px-4 py-3 text-right font-bold">Saldo</th>
                            <th class="px-4 py-3 text-center font-bold">Estatus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($cuotas as $cuota)
                            <tr>
                                <td class="px-4 py-3 font-semibold">{{ $cuota->numero }}</td>
                                <td class="px-4 py-3">{{ optional($cuota->fecha_vencimiento)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-right">${{ number_format((float) $cuota->monto, 2) }}</td>
                                <td class="px-4 py-3 text-right text-emerald-600">${{ number_format((float) $cuota->monto_pagado, 2) }}</td>
                                <td class="px-4 py-3 text-right text-red-600">${{ number_format((float) $cuota->saldo, 2) }}</td>
                                <td class="px-4 py-3 text-center">{{ ucfirst($cuota->estatus) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-3xl border bg-white p-5 shadow-sm">
                <h2 class="font-black text-gray-900">Próximo vencimiento</h2>
                @php($proxima = $resumen['proxima_cuota'] ?? null)
                @if($proxima)
                    <div class="mt-4 text-sm space-y-2">
                        <div><span class="font-semibold">Cuota:</span> {{ $proxima->numero }}</div>
                        <div><span class="font-semibold">Fecha:</span> {{ optional($proxima->fecha_vencimiento)->format('d/m/Y') }}</div>
                        <div><span class="font-semibold">Saldo:</span> ${{ number_format((float) $proxima->saldo, 2) }}</div>
                    </div>
                @else
                    <div class="mt-4 text-sm text-gray-500">No hay cuotas pendientes.</div>
                @endif
            </div>

            <div class="rounded-3xl border bg-white p-5 shadow-sm">
                <h2 class="font-black text-gray-900">Pagos recientes</h2>
                <div class="mt-4 space-y-3">
                    @forelse($pagos->take(6) as $pago)
                        <div class="rounded-2xl border p-3">
                            <div class="font-semibold">{{ optional($pago->fecha_pago)->format('d/m/Y') }}</div>
                            <div class="text-sm text-gray-500">{{ ucfirst($pago->forma_pago) }} · Ref {{ $pago->referencia ?: '—' }}</div>
                            <div class="font-black text-emerald-700 mt-1">${{ number_format((float) $pago->monto_aplicado, 2) }}</div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500">No hay pagos registrados.</div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl border bg-white p-5 shadow-sm">
                <h2 class="font-black text-gray-900">Historial reciente</h2>
                <div class="mt-4 space-y-3">
                    @forelse($historiales->take(6) as $item)
                        <div class="rounded-2xl border p-3">
                            <div class="font-semibold">{{ str_replace('_', ' ', ucfirst($item->evento)) }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ optional($item->created_at)->format('d/m/Y H:i') }}</div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500">Sin movimientos en historial.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
