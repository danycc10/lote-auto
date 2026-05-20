<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black">Dashboard de cobranza</h1>
            <p class="text-gray-500">Indicadores rápidos de contratos, mora y cobranza del módulo de financiamiento.</p>
        </div>
        <a href="{{ route('admin.contratos-financiamiento.index') }}" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl border bg-white font-bold shadow-sm hover:bg-gray-50 transition">Ver contratos</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-4">
        <div class="rounded-2xl border bg-white p-5 shadow-sm"><div class="text-sm text-gray-500 font-medium">Activos</div><div class="text-3xl font-black mt-2">{{ number_format($contratosActivos) }}</div></div>
        <div class="rounded-2xl border bg-white p-5 shadow-sm"><div class="text-sm text-gray-500 font-medium">Atrasados</div><div class="text-3xl font-black mt-2 text-amber-600">{{ number_format($contratosAtrasados) }}</div></div>
        <div class="rounded-2xl border bg-white p-5 shadow-sm"><div class="text-sm text-gray-500 font-medium">Saldo pendiente</div><div class="text-3xl font-black mt-2">${{ number_format($saldoPendiente, 2) }}</div></div>
        <div class="rounded-2xl border bg-white p-5 shadow-sm"><div class="text-sm text-gray-500 font-medium">Cobrado del mes</div><div class="text-3xl font-black mt-2 text-emerald-600">${{ number_format($cobradoMes, 2) }}</div></div>
        <div class="rounded-2xl border bg-white p-5 shadow-sm"><div class="text-sm text-gray-500 font-medium">Pagos de hoy</div><div class="text-3xl font-black mt-2 text-sky-600">${{ number_format($pagosHoy, 2) }}</div></div>
        <div class="rounded-2xl border bg-white p-5 shadow-sm"><div class="text-sm text-gray-500 font-medium">Mora total</div><div class="text-3xl font-black mt-2 text-red-600">${{ number_format($moraTotal, 2) }}</div></div>
    </div>

    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b bg-gray-50">
            <h2 class="font-black text-gray-900">Cuotas vencidas más urgentes</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 border-b text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold">Contrato</th>
                        <th class="px-4 py-3 text-left font-bold">Cliente</th>
                        <th class="px-4 py-3 text-left font-bold">Auto</th>
                        <th class="px-4 py-3 text-left font-bold">Vencimiento</th>
                        <th class="px-4 py-3 text-right font-bold">Saldo</th>
                        <th class="px-4 py-3 text-right font-bold">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($cuotasVencidas as $cuota)
                        <tr>
                            <td class="px-4 py-3 font-semibold">{{ $cuota->contrato?->folio }}</td>
                            <td class="px-4 py-3">{{ $cuota->contrato?->cliente?->nombre_completo ?: '—' }}</td>
                            <td class="px-4 py-3">{{ $cuota->contrato?->auto?->nombre_completo ?: '—' }}</td>
                            <td class="px-4 py-3">{{ optional($cuota->fecha_vencimiento)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-right text-red-600 font-bold">${{ number_format((float) $cuota->saldo, 2) }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.contratos-financiamiento.show', $cuota->contrato) }}" class="px-3 py-2 rounded-xl border text-sm font-semibold hover:bg-gray-50">Ver contrato</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-gray-500">No hay cuotas vencidas por el momento.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
