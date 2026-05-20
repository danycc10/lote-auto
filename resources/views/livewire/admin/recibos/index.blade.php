<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-gray-900">Recibos</h1>
            <p class="text-sm text-gray-500 mt-1">
                Consulta, imprime y cancela recibos generados por pagos.
            </p>
        </div>

        <a href="{{ route('admin.contratos-financiamiento.index') }}"
            class="inline-flex items-center justify-center px-4 py-2.5 rounded-2xl bg-black text-white text-sm font-semibold hover:bg-gray-800 transition">
            Ir a contratos
        </a>
    </div>

    @if (session('ok'))
    <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
        {{ session('ok') }}
    </div>
    @endif

    {{-- Filtros --}}
    <div class="bg-white border border-gray-200 rounded-3xl shadow-sm p-4 sm:p-5 space-y-4">

        <div class="flex items-center justify-between gap-3 flex-wrap">
            <h2 class="text-lg font-black text-gray-900">Filtros</h2>

            <button type="button"
                wire:click="limpiarFiltros"
                class="inline-flex items-center px-4 py-2 rounded-2xl border border-gray-300 bg-white text-sm font-semibold hover:bg-gray-50 transition">
                Limpiar filtros
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="xl:col-span-2">
                <label class="text-sm font-semibold text-gray-700">Buscar</label>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="q"
                    placeholder="Folio, cliente, contrato, concepto..."
                    class="mt-1 w-full rounded-2xl border-gray-300 focus:ring-black focus:border-black">
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700">Estatus</label>
                <select
                    wire:model.live="estatus"
                    class="mt-1 w-full rounded-2xl border-gray-300 focus:ring-black focus:border-black">
                    <option value="todos">Todos</option>
                    <option value="vigente">Vigente</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700">Tipo</label>
                <select
                    wire:model.live="tipoRelacion"
                    class="mt-1 w-full rounded-2xl border-gray-300 focus:ring-black focus:border-black">
                    <option value="todos">Todos</option>
                    <option value="con_cuota">Con cuota</option>
                    <option value="pago_general">Pago general</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div>
                <label class="text-sm font-semibold text-gray-700">Fecha desde</label>
                <input
                    type="date"
                    wire:model.live="fechaDesde"
                    class="mt-1 w-full rounded-2xl border-gray-300 focus:ring-black focus:border-black">
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700">Fecha hasta</label>
                <input
                    type="date"
                    wire:model.live="fechaHasta"
                    class="mt-1 w-full rounded-2xl border-gray-300 focus:ring-black focus:border-black">
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700">Monto mínimo</label>
                <input
                    type="number"
                    step="0.01"
                    wire:model.live.debounce.400ms="montoMin"
                    class="mt-1 w-full rounded-2xl border-gray-300 focus:ring-black focus:border-black"
                    placeholder="0.00">
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700">Monto máximo</label>
                <input
                    type="number"
                    step="0.01"
                    wire:model.live.debounce.400ms="montoMax"
                    class="mt-1 w-full rounded-2xl border-gray-300 focus:ring-black focus:border-black"
                    placeholder="99999.99">
            </div>
        </div>

        <div class="text-sm text-gray-500">
            Total encontrados:
            <span class="font-bold text-gray-900">{{ $recibos->total() }}</span>
        </div>
    </div>

    {{-- Tabla desktop --}}
    <div class="hidden lg:block bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold cursor-pointer" wire:click="sort('folio')">
                            Folio
                        </th>
                        <th class="px-4 py-3 text-left font-semibold">
                            Cliente
                        </th>
                        <th class="px-4 py-3 text-left font-semibold">
                            Contrato
                        </th>
                        <th class="px-4 py-3 text-left font-semibold">
                            Cuota
                        </th>
                        <th class="px-4 py-3 text-left font-semibold cursor-pointer" wire:click="sort('fecha_recibo')">
                            Fecha
                        </th>
                        <th class="px-4 py-3 text-right font-semibold cursor-pointer" wire:click="sort('monto')">
                            Monto
                        </th>
                        <th class="px-4 py-3 text-center font-semibold cursor-pointer" wire:click="sort('estatus')">
                            Estatus
                        </th>
                        <th class="px-4 py-3 text-right font-semibold">
                            Acciones
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($recibos as $recibo)
                    @php
                    $badge = match($recibo->estatus) {
                    'vigente' => 'bg-green-100 text-green-700 border-green-200',
                    'cancelado' => 'bg-red-100 text-red-700 border-red-200',
                    default => 'bg-gray-100 text-gray-700 border-gray-200',
                    };
                    @endphp

                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4 font-bold text-gray-900">
                            {{ $recibo->folio }}
                        </td>

                        <td class="px-4 py-4">
                            {{ trim(($recibo->cliente->nombre ?? '') . ' ' . ($recibo->cliente->apellido_paterno ?? '') . ' ' . ($recibo->cliente->apellido_materno ?? '')) ?: '—' }}
                        </td>

                        <td class="px-4 py-4">
                            {{ $recibo->contrato->folio ?? '—' }}
                        </td>

                        <td class="px-4 py-4">
                            @if($recibo->cuota)
                            Cuota #{{ $recibo->cuota->numero }}
                            @else
                            <span class="text-gray-400">Pago general</span>
                            @endif
                        </td>

                        <td class="px-4 py-4">
                            {{ $recibo->fecha_recibo?->format('d/m/Y') ?? '—' }}
                        </td>

                        <td class="px-4 py-4 text-right font-semibold">
                            ${{ number_format((float) $recibo->monto, 2) }}
                        </td>

                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $badge }}">
                                {{ ucfirst($recibo->estatus) }}
                            </span>
                        </td>

                        <td class="px-4 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.recibos.show', $recibo->id) }}"
                                    class="inline-flex items-center px-3 py-2 rounded-xl border border-gray-300 text-xs font-semibold hover:bg-gray-50">
                                    Ver
                                </a>

                                <a href="{{ route('admin.recibos.pdf', $recibo->id) }}"
                                    target="_blank"
                                    title="{{ $recibo->estatus === 'cancelado' ? 'Este recibo está CANCELADO' : 'Imprimir PDF' }}"
                                    class="inline-flex items-center px-3 py-2 rounded-xl border text-xs font-semibold {{ $recibo->estatus === 'cancelado' ? 'border-gray-300 bg-gray-100 text-gray-500 hover:bg-gray-200' : 'border-blue-300 bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                                    {{ $recibo->estatus === 'cancelado' ? 'PDF (cancelado)' : 'Imprimir' }}
                                </a>

                                @if($recibo->estatus !== 'cancelado')
                                <button
                                    type="button"
                                    wire:click="abrirModalCancelar({{ $recibo->id }})"
                                    class="inline-flex items-center px-3 py-2 rounded-xl border border-red-300 bg-red-50 text-red-700 text-xs font-semibold hover:bg-red-100">
                                    Cancelar
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-gray-500">
                            No se encontraron recibos.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-4 border-t border-gray-100">
            {{ $recibos->links() }}
        </div>
    </div>

    {{-- Cards mobile --}}
    <div class="grid grid-cols-1 gap-4 lg:hidden">
        @forelse ($recibos as $recibo)
        @php
        $badge = match($recibo->estatus) {
        'vigente' => 'bg-green-100 text-green-700 border-green-200',
        'cancelado' => 'bg-red-100 text-red-700 border-red-200',
        default => 'bg-gray-100 text-gray-700 border-gray-200',
        };
        @endphp

        <div class="bg-white border border-gray-200 rounded-3xl shadow-sm p-4 space-y-3">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <div class="text-lg font-black text-gray-900">{{ $recibo->folio }}</div>
                    <div class="text-sm text-gray-500">
                        {{ $recibo->fecha_recibo?->format('d/m/Y') ?? '—' }}
                    </div>
                </div>

                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $badge }}">
                    {{ ucfirst($recibo->estatus) }}
                </span>
            </div>

            <div class="text-sm text-gray-700 space-y-1">
                <div>
                    <span class="font-semibold">Cliente:</span>
                    {{ trim(($recibo->cliente->nombre ?? '') . ' ' . ($recibo->cliente->apellido_paterno ?? '') . ' ' . ($recibo->cliente->apellido_materno ?? '')) ?: '—' }}
                </div>

                <div>
                    <span class="font-semibold">Contrato:</span>
                    {{ $recibo->contrato->folio ?? '—' }}
                </div>

                <div>
                    <span class="font-semibold">Cuota:</span>
                    @if($recibo->cuota)
                    #{{ $recibo->cuota->numero }}
                    @else
                    Pago general
                    @endif
                </div>

                <div>
                    <span class="font-semibold">Monto:</span>
                    ${{ number_format((float) $recibo->monto, 2) }}
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('admin.recibos.show', $recibo->id) }}"
                    class="inline-flex justify-center items-center px-3 py-2 rounded-2xl border border-gray-300 text-sm font-semibold hover:bg-gray-50">
                    Ver
                </a>

                <a href="{{ route('admin.recibos.pdf', $recibo->id) }}"
                    target="_blank"
                    title="{{ $recibo->estatus === 'cancelado' ? 'Este recibo está CANCELADO' : 'Imprimir PDF' }}"
                    class="inline-flex justify-center items-center px-3 py-2 rounded-2xl border text-sm font-semibold {{ $recibo->estatus === 'cancelado' ? 'border-gray-300 bg-gray-100 text-gray-500 hover:bg-gray-200' : 'border-blue-300 bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                    {{ $recibo->estatus === 'cancelado' ? 'PDF (cancelado)' : 'Imprimir' }}
                </a>

                @if($recibo->estatus !== 'cancelado')
                <button
                    type="button"
                    wire:click="abrirModalCancelar({{ $recibo->id }})"
                    class="col-span-2 inline-flex justify-center items-center px-3 py-2 rounded-2xl border border-red-300 bg-red-50 text-red-700 text-sm font-semibold hover:bg-red-100">
                    Cancelar recibo
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white border border-gray-200 rounded-3xl shadow-sm p-8 text-center text-gray-500">
            No se encontraron recibos.
        </div>
        @endforelse

        <div>
            {{ $recibos->links() }}
        </div>
    </div>

    {{-- Modal cancelar --}}
    @if($modalCancelar)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" wire:click="cerrarModalCancelar"></div>

        <div class="relative w-full max-w-lg rounded-3xl bg-white shadow-2xl border border-gray-200 p-6">
            <div class="flex items-start gap-4">
                <div class="shrink-0 w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-7.5 13A2 2 0 004.5 20h15a2 2 0 001.71-3.14l-7.5-13a2 2 0 00-3.42 0z" />
                    </svg>
                </div>

                <div class="flex-1">
                    <h3 class="text-xl font-black text-gray-900">
                        Cancelar recibo
                    </h3>

                    <p class="mt-2 text-sm text-gray-600">
                        Se cancelará el recibo
                        <span class="font-bold text-gray-900">{{ $reciboCancelarFolio }}</span>,
                        también se revertirá el pago relacionado, se recalcularán saldos y se registrará en historial.
                    </p>

                    <p class="mt-2 text-sm text-red-600 font-medium">
                        Esta acción afecta información financiera.
                    </p>
                </div>
            </div>

            <div class="mt-5">
                <label class="text-sm font-semibold text-gray-700">
                    Motivo de cancelación
                </label>

                <textarea
                    wire:model.defer="motivoCancelacion"
                    rows="4"
                    class="mt-2 w-full rounded-2xl border-gray-300 focus:ring-red-500 focus:border-red-500"
                    placeholder="Describe el motivo de la cancelación..."></textarea>

                @error('motivoCancelacion')
                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button type="button"
                    wire:click="cerrarModalCancelar"
                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-2xl border border-gray-300 bg-white text-sm font-semibold hover:bg-gray-50 transition">
                    No, volver
                </button>

                <button type="button"
                    wire:click="confirmarCancelacion"
                    wire:loading.attr="disabled"
                    wire:target="confirmarCancelacion"
                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-2xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
                    <span wire:loading.remove wire:target="confirmarCancelacion">Sí, cancelar y revertir</span>
                    <span wire:loading wire:target="confirmarCancelacion">Cancelando...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

</div>