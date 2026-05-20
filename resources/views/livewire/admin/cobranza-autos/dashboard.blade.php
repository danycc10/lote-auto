<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900">
                Dashboard de cobranza
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Control de contratos, cuotas vencidas, próximos vencimientos y cobranza realizada.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.contratos-financiamiento.index') }}"
               class="inline-flex items-center px-4 py-2.5 rounded-2xl border border-gray-300 bg-white text-sm font-semibold hover:bg-gray-50 transition">
                Contratos
            </a>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white border border-gray-200 rounded-3xl p-4 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
            <div class="xl:col-span-2">
                <label class="text-xs font-bold text-gray-500 uppercase">Buscar</label>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="q"
                    placeholder="Cliente, folio, VIN, placas..."
                    class="mt-1 w-full rounded-2xl border-gray-300 focus:ring-black focus:border-black"
                >
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Estatus</label>
                <select wire:model.live="estatus"
                    class="mt-1 w-full rounded-2xl border-gray-300 focus:ring-black focus:border-black">
                    <option value="activos">Activos</option>
                    <option value="atrasados">Atrasados</option>
                    <option value="liquidados">Liquidados</option>
                    <option value="todos">Todos</option>
                </select>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Desde</label>
                <input type="date" wire:model.live="fechaDesde"
                    class="mt-1 w-full rounded-2xl border-gray-300 focus:ring-black focus:border-black">
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Hasta</label>
                <input type="date" wire:model.live="fechaHasta"
                    class="mt-1 w-full rounded-2xl border-gray-300 focus:ring-black focus:border-black">
            </div>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        <div class="bg-red-50 border border-red-200 rounded-3xl p-5">
            <div class="text-sm font-semibold text-red-700">Total vencido</div>
            <div class="mt-2 text-3xl font-black text-red-900">
                ${{ number_format($kpis['total_vencido'], 2) }}
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-3xl p-5">
            <div class="text-sm font-semibold text-amber-700">Por vencer en 7 días</div>
            <div class="mt-2 text-3xl font-black text-amber-900">
                ${{ number_format($kpis['total_por_vencer'], 2) }}
            </div>
        </div>

        <div class="bg-emerald-50 border border-emerald-200 rounded-3xl p-5">
            <div class="text-sm font-semibold text-emerald-700">Cobrado del mes</div>
            <div class="mt-2 text-3xl font-black text-emerald-900">
                ${{ number_format($kpis['cobrado_mes'], 2) }}
            </div>
        </div>

        <div class="bg-slate-50 border border-slate-200 rounded-3xl p-5">
            <div class="text-sm font-semibold text-slate-700">Contratos activos</div>
            <div class="mt-2 text-3xl font-black text-slate-900">
                {{ number_format($kpis['contratos_activos']) }}
            </div>
        </div>

        <div class="bg-rose-50 border border-rose-200 rounded-3xl p-5">
            <div class="text-sm font-semibold text-rose-700">Contratos con atraso</div>
            <div class="mt-2 text-3xl font-black text-rose-900">
                {{ number_format($kpis['contratos_con_atraso']) }}
            </div>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded-3xl p-5">
            <div class="text-sm font-semibold text-orange-700">Cuotas vencidas</div>
            <div class="mt-2 text-3xl font-black text-orange-900">
                {{ number_format($kpis['cuotas_vencidas']) }}
            </div>
        </div>
    </div>

    {{-- Gráfico + top atraso --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white border border-gray-200 rounded-3xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-black text-gray-900">Cobranza por día</h2>
                    <p class="text-sm text-gray-500">Pagos registrados en el rango seleccionado.</p>
                </div>
            </div>

            <div class="h-80">
                <canvas id="cobranzaChart"></canvas>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-3xl p-5 shadow-sm">
            <h2 class="text-lg font-black text-gray-900">Contratos con más atraso</h2>
            <p class="text-sm text-gray-500 mb-4">Prioridad para cobranza.</p>

            <div class="space-y-3">
                @forelse($contratosTopAtraso as $contrato)
                    <div class="rounded-2xl border border-gray-200 p-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-bold text-gray-900">
                                    {{ $contrato->cliente?->nombres }} {{ $contrato->cliente?->apellidos }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $contrato->auto?->marca?->nombre ?? '—' }}
                                    {{ $contrato->auto?->modelo?->nombre ?? '' }}
                                </div>
                            </div>

                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                {{ $contrato->cuotas_atrasadas_count }} venc.
                            </span>
                        </div>

                        <div class="mt-3 text-sm">
                            <div class="text-gray-500">Atrasado</div>
                            <div class="font-black text-red-700">
                                ${{ number_format((float) $contrato->total_atrasado, 2) }}
                            </div>
                        </div>

                        <a href="{{ route('admin.contratos-financiamiento.show', $contrato) }}"
                           class="mt-3 inline-flex text-sm font-bold text-black hover:underline">
                            Ver contrato
                        </a>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">
                        No hay contratos con atraso.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Próximos vencimientos + vencidas --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-white border border-gray-200 rounded-3xl p-5 shadow-sm">
            <h2 class="text-lg font-black text-gray-900">Próximos vencimientos</h2>
            <p class="text-sm text-gray-500 mb-4">Cuotas que vencen en los siguientes 7 días.</p>

            <div class="space-y-3">
                @forelse($proximosVencimientos as $cuota)
                    <div class="flex items-center justify-between gap-4 rounded-2xl border border-gray-200 p-3">
                        <div>
                            <div class="font-bold text-gray-900">
                                {{ $cuota->contrato?->cliente?->nombres }} {{ $cuota->contrato?->cliente?->apellidos }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $cuota->contrato?->auto?->marca?->nombre ?? '—' }}
                                {{ $cuota->contrato?->auto?->modelo?->nombre ?? '' }}
                                · Cuota #{{ $cuota->numero }}
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($cuota->fecha_vencimiento)->format('d/m/Y') }}
                            </div>
                            <div class="font-black text-amber-700">
                                ${{ number_format((float) ($cuota->saldo ?? $cuota->monto), 2) }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">No hay próximos vencimientos.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-3xl p-5 shadow-sm">
            <h2 class="text-lg font-black text-gray-900">Cuotas vencidas</h2>
            <p class="text-sm text-gray-500 mb-4">Pendientes prioritarias de cobranza.</p>

            <div class="space-y-3">
                @forelse($cuotasVencidas as $cuota)
                    <div class="flex items-center justify-between gap-4 rounded-2xl border border-red-200 bg-red-50 p-3">
                        <div>
                            <div class="font-bold text-gray-900">
                                {{ $cuota->contrato?->cliente?->nombres }} {{ $cuota->contrato?->cliente?->apellidos }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $cuota->contrato?->auto?->marca?->nombre ?? '—' }}
                                {{ $cuota->contrato?->auto?->modelo?->nombre ?? '' }}
                                · Cuota #{{ $cuota->numero }}
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-xs text-red-700 font-semibold">
                                Venció {{ \Carbon\Carbon::parse($cuota->fecha_vencimiento)->format('d/m/Y') }}
                            </div>
                            <div class="font-black text-red-800">
                                ${{ number_format((float) ($cuota->saldo ?? $cuota->monto), 2) }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">No hay cuotas vencidas.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Tabla contratos --}}
    <div class="bg-white border border-gray-200 rounded-3xl p-5 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div>
                <h2 class="text-lg font-black text-gray-900">Contratos</h2>
                <p class="text-sm text-gray-500">Listado general para seguimiento de cobranza.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="py-3 pr-4 font-bold">Folio</th>
                        <th class="py-3 pr-4 font-bold">Cliente</th>
                        <th class="py-3 pr-4 font-bold">Vehículo</th>
                        <th class="py-3 pr-4 font-bold">Estatus</th>
                        <th class="py-3 pr-4 font-bold">Mensualidad</th>
                        <th class="py-3 pr-4 font-bold">Pendiente</th>
                        <th class="py-3 pr-4 font-bold">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contratos as $contrato)
                        @php
                            $cuotaPendiente = $contrato->cuotas()
                                ->whereIn('estatus', ['pendiente', 'parcial', 'vencida'])
                                ->orderBy('fecha_vencimiento')
                                ->first();

                            $saldoPendiente = $contrato->cuotas()
                                ->whereIn('estatus', ['pendiente', 'parcial', 'vencida'])
                                ->sum(DB::raw('COALESCE(saldo, monto)'));
                        @endphp

                        <tr class="border-b last:border-b-0">
                            <td class="py-3 pr-4 font-semibold text-gray-900">
                                {{ $contrato->folio }}
                            </td>

                            <td class="py-3 pr-4">
                                <div class="font-semibold text-gray-900">
                                    {{ $contrato->cliente?->nombres }} {{ $contrato->cliente?->apellidos }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $contrato->cliente?->telefono ?? 'Sin teléfono' }}
                                </div>
                            </td>

                            <td class="py-3 pr-4">
                                <div class="font-semibold text-gray-900">
                                    {{ $contrato->auto?->marca?->nombre ?? '—' }}
                                    {{ $contrato->auto?->modelo?->nombre ?? '' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $contrato->auto?->vin ?? $contrato->auto?->placas ?? 'Sin referencia' }}
                                </div>
                            </td>

                            <td class="py-3 pr-4">
                                @if($contrato->estatus === 'activo')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                        Activo
                                    </span>
                                @elseif($contrato->estatus === 'liquidado')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                        Liquidado
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                        {{ ucfirst($contrato->estatus) }}
                                    </span>
                                @endif
                            </td>

                            <td class="py-3 pr-4">
                                ${{ number_format((float) ($cuotaPendiente->monto ?? 0), 2) }}
                            </td>

                            <td class="py-3 pr-4 font-black {{ $saldoPendiente > 0 ? 'text-red-700' : 'text-emerald-700' }}">
                                ${{ number_format((float) $saldoPendiente, 2) }}
                            </td>

                            <td class="py-3 pr-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.contratos-financiamiento.show', $contrato) }}"
                                       class="inline-flex px-3 py-1.5 rounded-xl bg-black text-white text-xs font-bold hover:opacity-90">
                                        Ver
                                    </a>

                                    <a href="{{ route('admin.contratos-financiamiento.registrar-pago', $contrato) }}"
                                       class="inline-flex px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700">
                                        Cobrar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-gray-500">
                                No se encontraron contratos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $contratos->links() }}
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            let cobranzaChart;

            function renderCobranzaChart(labels, data) {
                const ctx = document.getElementById('cobranzaChart');
                if (!ctx) return;

                if (cobranzaChart) {
                    cobranzaChart.destroy();
                }

                cobranzaChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Cobrado',
                            data: data,
                            borderRadius: 10,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            document.addEventListener('livewire:init', () => {
                renderCobranzaChart(
                    @json($cobranzaPorDia['labels']),
                    @json($cobranzaPorDia['data'])
                );

                Livewire.hook('morph.updated', () => {
                    renderCobranzaChart(
                        @json($cobranzaPorDia['labels']),
                        @json($cobranzaPorDia['data'])
                    );
                });
            });
        </script>
    @endpush
</div>