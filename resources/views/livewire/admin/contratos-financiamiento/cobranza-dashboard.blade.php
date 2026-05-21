<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Dashboard de cobranza</h1>
            <p class="text-sm text-slate-500 mt-0.5">Indicadores de contratos, mora y cobranza del módulo de financiamiento.</p>
        </div>
        <a href="{{ route('admin.contratos-financiamiento.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shrink-0">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM15.75 9.75a3 3 0 116 0 3 3 0 01-6 0zM2.25 9.75a3 3 0 116 0 3 3 0 01-6 0zM6.31 15.117A6.745 6.745 0 0112 12a6.745 6.745 0 016.709 7.498.75.75 0 01-.372.568A12.696 12.696 0 0112 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 01-.372-.568 6.787 6.787 0 011.019-4.38z" clip-rule="evenodd"/>
            </svg>
            Ver contratos
        </a>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500">Activos</p>
            <p class="mt-2 text-2xl font-semibold text-slate-900 tabular-nums">{{ number_format($contratosActivos) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500">Atrasados</p>
            <p class="mt-2 text-2xl font-semibold text-amber-600 tabular-nums">{{ number_format($contratosAtrasados) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500">Saldo pendiente</p>
            <p class="mt-2 text-2xl font-semibold text-slate-900 tabular-nums">${{ number_format($saldoPendiente, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500">Cobrado del mes</p>
            <p class="mt-2 text-2xl font-semibold text-emerald-600 tabular-nums">${{ number_format($cobradoMes, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500">Pagos de hoy</p>
            <p class="mt-2 text-2xl font-semibold text-sky-600 tabular-nums">${{ number_format($pagosHoy, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500">Mora total</p>
            <p class="mt-2 text-2xl font-semibold text-red-600 tabular-nums">${{ number_format($moraTotal, 2) }}</p>
        </div>
    </div>

    {{-- Cuotas vencidas --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
            <h2 class="text-sm font-semibold text-slate-900">Cuotas vencidas más urgentes</h2>
            <p class="text-xs text-slate-500 mt-0.5">Ordenadas por fecha de vencimiento ascendente.</p>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/40">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Contrato</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Cliente</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden md:table-cell">Auto</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Vencimiento</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Saldo</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($cuotasVencidas as $cuota)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $cuota->contrato?->folio }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $cuota->contrato?->cliente?->nombre_completo ?: '—' }}</td>
                        <td class="px-4 py-3 text-slate-500 hidden md:table-cell">{{ $cuota->contrato?->auto?->nombre_completo ?: '—' }}</td>
                        <td class="px-4 py-3 text-red-600 font-medium">{{ optional($cuota->fecha_vencimiento)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-red-600 tabular-nums">${{ number_format((float) $cuota->saldo, 2) }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.contratos-financiamiento.show', $cuota->contrato) }}"
                               class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                Ver contrato
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-sm text-slate-400">
                            No hay cuotas vencidas por el momento.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
