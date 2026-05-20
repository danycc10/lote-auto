<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">
    <div class="flex flex-col lg:flex-row lg:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-2xl font-black">Contrato {{ $contrato->folio }}</h1>
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold
                    @if($contrato->estatus === 'activo') bg-emerald-100 text-emerald-700
                    @elseif($contrato->estatus === 'atrasado') bg-amber-100 text-amber-700
                    @elseif($contrato->estatus === 'liquidado') bg-sky-100 text-sky-700
                    @elseif($contrato->estatus === 'cancelado') bg-red-100 text-red-700
                    @else bg-gray-100 text-gray-700 @endif">
                    {{ ucfirst($contrato->estatus) }}
                </span>
            </div>
            <p class="text-gray-500 mt-1">Detalle del contrato, cuotas, pagos y recibos.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.contratos-financiamiento.index') }}" class="px-4 py-2 rounded-2xl border font-semibold hover:bg-gray-50">Volver</a>
            <a href="{{ route('admin.contratos-financiamiento.registrar-pago', $contrato) }}" class="px-4 py-2 rounded-2xl bg-black text-white font-semibold hover:opacity-90">Registrar pago</a>
            @if($contrato->ruta_contrato_firmado)
                <a href="{{ route('admin.contratos-financiamiento.archivo', $contrato) }}" target="_blank" class="px-4 py-2 rounded-2xl border font-semibold hover:bg-gray-50">Ver archivo</a>
            @endif
        </div>
    </div>

    @if(session('ok'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-700 font-medium">{{ session('ok') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="rounded-2xl border bg-white p-5 shadow-sm"><div class="text-xs text-gray-500 font-bold uppercase">Cliente</div><div class="text-lg font-black mt-2">{{ $contrato->cliente?->nombre_completo ?: '—' }}</div><div class="text-sm text-gray-500 mt-1">{{ $contrato->cliente?->telefono ?: 'Sin teléfono' }}</div></div>
        <div class="rounded-2xl border bg-white p-5 shadow-sm"><div class="text-xs text-gray-500 font-bold uppercase">Auto</div><div class="text-lg font-black mt-2">{{ $contrato->auto?->nombre_completo ?: '—' }}</div><div class="text-sm text-gray-500 mt-1">{{ $contrato->auto?->codigo_inventario ?: 'Sin código' }}</div></div>
        <div class="rounded-2xl border bg-white p-5 shadow-sm"><div class="text-xs text-gray-500 font-bold uppercase">Total pagar</div><div class="text-2xl font-black mt-2">${{ number_format((float) $contrato->total_pagar, 2) }}</div><div class="text-sm text-gray-500 mt-1">Cuota: ${{ number_format((float) $contrato->monto_cuota, 2) }}</div></div>
        <div class="rounded-2xl border bg-white p-5 shadow-sm"><div class="text-xs text-gray-500 font-bold uppercase">Saldo actual</div><div class="text-2xl font-black mt-2">${{ number_format((float) $contrato->saldo_actual, 2) }}</div><div class="text-sm text-gray-500 mt-1">Pagado: ${{ number_format((float) $contrato->total_pagado, 2) }}</div></div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white border rounded-2xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b bg-gray-50">
                <h2 class="font-black">Plan de cuotas</h2>
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
                            <th class="px-4 py-3 text-left font-bold">Estatus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($contrato->cuotas as $cuota)
                            <tr>
                                <td class="px-4 py-3 font-semibold">{{ $cuota->numero }}</td>
                                <td class="px-4 py-3">{{ optional($cuota->fecha_vencimiento)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-right">${{ number_format((float) $cuota->monto, 2) }}</td>
                                <td class="px-4 py-3 text-right">${{ number_format((float) $cuota->monto_pagado, 2) }}</td>
                                <td class="px-4 py-3 text-right font-semibold">${{ number_format((float) $cuota->saldo, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold
                                        @if($cuota->estatus === 'pagada') bg-emerald-100 text-emerald-700
                                        @elseif($cuota->estatus === 'parcial') bg-amber-100 text-amber-700
                                        @elseif($cuota->estatus === 'vencida') bg-red-100 text-red-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ ucfirst($cuota->estatus) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b bg-gray-50">
                <h2 class="font-black">Pagos recientes</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($contrato->pagos->sortByDesc('id')->take(8) as $pago)
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-semibold">${{ number_format((float) $pago->monto, 2) }}</div>
                                <div class="text-sm text-gray-500">Cuota #{{ $pago->cuota?->numero ?: '—' }} · {{ optional($pago->fecha_pago)->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-400 mt-1">{{ ucfirst($pago->forma_pago) }} @if($pago->referencia)· Ref: {{ $pago->referencia }}@endif</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-sm text-gray-500">Aún no hay pagos registrados.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b bg-gray-50"><h2 class="font-black">Recibos</h2></div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 border-b text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold">Folio</th>
                        <th class="px-4 py-3 text-left font-bold">Fecha</th>
                        <th class="px-4 py-3 text-left font-bold">Cuota</th>
                        <th class="px-4 py-3 text-right font-bold">Monto</th>
                        <th class="px-4 py-3 text-right font-bold">Saldo anterior</th>
                        <th class="px-4 py-3 text-right font-bold">Saldo posterior</th>
                        <th class="px-4 py-3 text-right font-bold">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($contrato->recibos->sortByDesc('id') as $recibo)
                        <tr>
                            <td class="px-4 py-3 font-semibold">{{ $recibo->folio }}</td>
                            <td class="px-4 py-3">{{ optional($recibo->fecha_recibo)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $recibo->cuota?->numero ? '#'.$recibo->cuota->numero : '—' }}</td>
                            <td class="px-4 py-3 text-right">${{ number_format((float) $recibo->monto, 2) }}</td>
                            <td class="px-4 py-3 text-right">${{ number_format((float) $recibo->saldo_anterior, 2) }}</td>
                            <td class="px-4 py-3 text-right">${{ number_format((float) $recibo->saldo_posterior, 2) }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.recibos-financiamiento.pdf', $recibo) }}" target="_blank" class="px-3 py-2 rounded-xl border text-sm font-semibold hover:bg-gray-50">PDF</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">Aún no hay recibos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
