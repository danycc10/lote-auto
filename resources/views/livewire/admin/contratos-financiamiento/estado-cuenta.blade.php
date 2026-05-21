<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Estado de cuenta · {{ $contrato->folio }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">Resumen de pagos, vencimientos, saldo y trazabilidad.</p>
        </div>
        <div class="flex flex-wrap gap-2 shrink-0">
            <a href="{{ route('admin.contratos-financiamiento.estado-cuenta.pdf', $contrato) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.875 1.5C6.839 1.5 6 2.34 6 3.375v2.99c-.426.053-.851.11-1.274.174-1.454.218-2.476 1.483-2.476 2.917v6.294a3 3 0 003 3h.27l-.155 1.705A1.875 1.875 0 007.232 22.5h9.536a1.875 1.875 0 001.867-2.045l-.155-1.705h.27a3 3 0 003-3V9.456c0-1.434-1.022-2.7-2.476-2.917A48.716 48.716 0 0018 6.366V3.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM16.5 6.205v-2.83A.375.375 0 0016.125 3h-8.25a.375.375 0 00-.375.375v2.83a49.353 49.353 0 018.99 0zm-9.38 6.157a.75.75 0 00-1.06 1.06L7.72 14.78a.75.75 0 001.414-.478l-.034-4.25a.75.75 0 00-.75-.745zm6.5 0a.75.75 0 01.75.745l-.034 4.25a.75.75 0 001.414.478l1.66-1.358a.75.75 0 10-.96-1.152l-.12.098.028-3.556a.75.75 0 00-.738-.757z" clip-rule="evenodd"/>
                </svg>
                PDF estado de cuenta
            </a>
            <a href="{{ route('admin.contratos-financiamiento.show', $contrato) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 010 1.06l-6.22 6.22H21a.75.75 0 010 1.5H4.81l6.22 6.22a.75.75 0 11-1.06 1.06l-7.5-7.5a.75.75 0 010-1.06l7.5-7.5a.75.75 0 011.06 0z" clip-rule="evenodd"/>
                </svg>
                Volver al contrato
            </a>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500">Saldo actual</p>
            <p class="mt-2 text-xl font-semibold text-slate-900 tabular-nums">${{ number_format((float) ($resumen['saldo_actual'] ?? 0), 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500">Total pagado</p>
            <p class="mt-2 text-xl font-semibold text-emerald-600 tabular-nums">${{ number_format((float) ($resumen['total_pagado'] ?? 0), 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500">Cuotas vencidas</p>
            <p class="mt-2 text-xl font-semibold text-amber-600 tabular-nums">{{ $resumen['cuotas_vencidas'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500">Capital pendiente</p>
            <p class="mt-2 text-xl font-semibold text-sky-600 tabular-nums">${{ number_format((float) ($resumen['capital_pendiente'] ?? 0), 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 col-span-2 md:col-span-1">
            <p class="text-xs font-medium text-slate-500">Interés pendiente</p>
            <p class="mt-2 text-xl font-semibold text-violet-600 tabular-nums">${{ number_format((float) ($resumen['interes_pendiente'] ?? 0), 2) }}</p>
        </div>
    </div>

    {{-- Main layout --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Tabla de cuotas --}}
        <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                <h2 class="text-sm font-semibold text-slate-900">Cuotas del estado de cuenta</h2>
                <p class="text-xs text-slate-500 mt-0.5">Para cobranza, aclaraciones y seguimiento.</p>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/40">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Vencimiento</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Monto</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Pagado</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Saldo</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Estatus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($cuotas as $cuota)
                        @php
                            $badgeCuota = match($cuota->estatus) {
                                'pagada'   => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
                                'vencida'  => 'bg-red-50 text-red-700 ring-1 ring-red-200',
                                'parcial'  => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
                                default    => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-slate-900">{{ $cuota->numero }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ optional($cuota->fecha_vencimiento)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-right tabular-nums text-slate-700">${{ number_format((float) $cuota->monto, 2) }}</td>
                            <td class="px-4 py-3 text-right tabular-nums text-emerald-600 font-medium">${{ number_format((float) $cuota->monto_pagado, 2) }}</td>
                            <td class="px-4 py-3 text-right tabular-nums text-red-600 font-medium">${{ number_format((float) $cuota->saldo, 2) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $badgeCuota }}">
                                    {{ ucfirst($cuota->estatus) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5 xl:sticky xl:top-[4.5rem] xl:self-start">

            {{-- Próximo vencimiento --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Próximo vencimiento</h2>
                </div>
                <div class="p-5">
                    @php($proxima = $resumen['proxima_cuota'] ?? null)
                    @if($proxima)
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-500">Cuota</span>
                                <span class="text-xs font-semibold text-slate-900">#{{ $proxima->numero }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-slate-500">Fecha</span>
                                <span class="text-xs font-semibold text-slate-900">{{ optional($proxima->fecha_vencimiento)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center border-t border-slate-100 pt-3">
                                <span class="text-xs text-slate-500">Saldo</span>
                                <span class="text-sm font-semibold text-red-600 tabular-nums">${{ number_format((float) $proxima->saldo, 2) }}</span>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-slate-400 text-center py-2">No hay cuotas pendientes.</p>
                    @endif
                </div>
            </div>

            {{-- Pagos recientes --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Pagos recientes</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($pagos->take(6) as $pago)
                        <div class="px-5 py-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-medium text-slate-900">{{ optional($pago->fecha_pago)->format('d/m/Y') }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ ucfirst($pago->forma_pago) }} · {{ $pago->referencia ?: '—' }}</p>
                                </div>
                                <span class="text-sm font-semibold text-emerald-600 tabular-nums">${{ number_format((float) $pago->monto_aplicado, 2) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="px-5 py-6 text-sm text-slate-400 text-center">No hay pagos registrados.</p>
                    @endforelse
                </div>
            </div>

            {{-- Historial --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Historial reciente</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($historiales->take(6) as $item)
                        <div class="px-5 py-3">
                            <p class="text-xs font-medium text-slate-900">{{ str_replace('_', ' ', ucfirst($item->evento)) }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ optional($item->created_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    @empty
                        <p class="px-5 py-6 text-sm text-slate-400 text-center">Sin movimientos en historial.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

</div>
