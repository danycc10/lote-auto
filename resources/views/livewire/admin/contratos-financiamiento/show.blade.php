<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-xl font-semibold text-slate-900">Contrato {{ $contrato->folio }}</h1>
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                    @if($contrato->estatus === 'activo') bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200
                    @elseif($contrato->estatus === 'atrasado') bg-amber-50 text-amber-700 ring-1 ring-amber-200
                    @elseif($contrato->estatus === 'liquidado') bg-sky-50 text-sky-700 ring-1 ring-sky-200
                    @elseif($contrato->estatus === 'cancelado') bg-red-50 text-red-700 ring-1 ring-red-200
                    @else bg-slate-100 text-slate-600 ring-1 ring-slate-200 @endif">
                    {{ ucfirst($contrato->estatus) }}
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-0.5">Detalle del contrato, cuotas, pagos y recibos.</p>
        </div>

        <div class="flex flex-wrap gap-2 shrink-0">
            <a href="{{ route('admin.contratos-financiamiento.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 010 1.06l-6.22 6.22H21a.75.75 0 010 1.5H4.81l6.22 6.22a.75.75 0 11-1.06 1.06l-7.5-7.5a.75.75 0 010-1.06l7.5-7.5a.75.75 0 011.06 0z" clip-rule="evenodd"/>
                </svg>
                Volver
            </a>
            <a href="{{ route('admin.contratos-financiamiento.registrar-pago', $contrato) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z"/>
                    <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 14.625v-9.75zM8.25 9.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM18.75 9a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V9.75a.75.75 0 00-.75-.75h-.008zM4.5 9.75A.75.75 0 015.25 9h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75V9.75z" clip-rule="evenodd"/>
                    <path d="M2.25 18a.75.75 0 000 1.5c5.4 0 10.63.722 15.6 2.1 1.01.292 2.148-.282 2.148-1.332V18H2.25z"/>
                </svg>
                Registrar pago
            </a>
            @if($contrato->ruta_contrato_firmado)
                <a href="{{ route('admin.contratos-financiamiento.archivo', $contrato) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                    Ver archivo
                </a>
            @endif
        </div>
    </div>

    @if(session('ok'))
        <div class="flex items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            <svg class="h-4 w-4 text-emerald-500 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
            </svg>
            {{ session('ok') }}
        </div>
    @endif

    {{-- KPIs --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Cliente</p>
            <p class="text-sm font-semibold text-slate-900 mt-2 leading-snug">{{ $contrato->cliente?->nombre_completo ?: '—' }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ $contrato->cliente?->telefono ?: 'Sin teléfono' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Auto</p>
            <p class="text-sm font-semibold text-slate-900 mt-2 leading-snug">{{ $contrato->auto?->nombre_completo ?: '—' }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ $contrato->auto?->codigo_inventario ?: 'Sin código' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total a pagar</p>
            <p class="text-xl font-semibold text-slate-900 mt-2 tabular-nums">${{ number_format((float) $contrato->total_pagar, 2) }}</p>
            <p class="text-xs text-slate-400 mt-1 tabular-nums">Cuota: ${{ number_format((float) $contrato->monto_cuota, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Saldo actual</p>
            <p class="text-xl font-semibold text-slate-900 mt-2 tabular-nums">${{ number_format((float) $contrato->saldo_actual, 2) }}</p>
            <p class="text-xs text-slate-400 mt-1 tabular-nums">Pagado: ${{ number_format((float) $contrato->total_pagado, 2) }}</p>
        </div>
    </div>

    {{-- Plan de cuotas + Pagos --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                <h2 class="text-sm font-semibold text-slate-900">Plan de cuotas</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Vencimiento</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Monto</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Pagado</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Saldo</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estatus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($contrato->cuotas as $cuota)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ $cuota->numero }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ optional($cuota->fecha_vencimiento)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600 text-right tabular-nums">${{ number_format((float) $cuota->monto, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600 text-right tabular-nums">${{ number_format((float) $cuota->monto_pagado, 2) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-slate-900 text-right tabular-nums">${{ number_format((float) $cuota->saldo, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                        @if($cuota->estatus === 'pagada') bg-emerald-50 text-emerald-700
                                        @elseif($cuota->estatus === 'parcial') bg-amber-50 text-amber-700
                                        @elseif($cuota->estatus === 'vencida') bg-red-50 text-red-700
                                        @else bg-slate-100 text-slate-600 @endif">
                                        {{ ucfirst($cuota->estatus) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                <h2 class="text-sm font-semibold text-slate-900">Pagos recientes</h2>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($contrato->pagos->sortByDesc('id')->take(8) as $pago)
                    <div class="px-5 py-3.5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900 tabular-nums">${{ number_format((float) $pago->monto, 2) }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">Cuota #{{ $pago->cuota?->numero ?: '—' }} · {{ optional($pago->fecha_pago)->format('d/m/Y') }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ ucfirst($pago->forma_pago) }}@if($pago->referencia) · Ref: {{ $pago->referencia }}@endif</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-slate-400">Aún no hay pagos registrados.</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Recibos --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
            <h2 class="text-sm font-semibold text-slate-900">Recibos</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Folio</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Cuota</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Monto</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Saldo anterior</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Saldo posterior</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($contrato->recibos->sortByDesc('id') as $recibo)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 text-sm font-medium text-slate-900">{{ $recibo->folio }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ optional($recibo->fecha_recibo)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $recibo->cuota?->numero ? '#'.$recibo->cuota->numero : '—' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600 text-right tabular-nums">${{ number_format((float) $recibo->monto, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600 text-right tabular-nums">${{ number_format((float) $recibo->saldo_anterior, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600 text-right tabular-nums">${{ number_format((float) $recibo->saldo_posterior, 2) }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.recibos-financiamiento.pdf', $recibo) }}" target="_blank"
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                    PDF
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-400">Aún no hay recibos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
