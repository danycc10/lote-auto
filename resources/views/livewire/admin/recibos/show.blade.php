<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-3xl font-black tracking-tight text-gray-900">
                    {{ $recibo->folio }}
                </h1>

                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $this->badgeColor }}">
                    {{ strtoupper($recibo->estatus) }}
                </span>
            </div>

            <p class="text-sm text-gray-500 mt-1">
                Detalle completo del recibo emitido.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">

            <a href="{{ route('admin.recibos.pdf', $recibo->id) }}"
               target="_blank"
               class="inline-flex items-center px-4 py-2.5 rounded-2xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                Imprimir PDF
            </a>

            <a href="{{ route('admin.recibos.edit', $recibo->id) }}"
               class="inline-flex items-center px-4 py-2.5 rounded-2xl border border-amber-300 bg-amber-50 text-amber-700 text-sm font-semibold hover:bg-amber-100 transition">
                Editar
            </a>

            <a href="{{ route('admin.recibos.index') }}"
               class="inline-flex items-center px-4 py-2.5 rounded-2xl border border-gray-300 bg-white text-sm font-semibold hover:bg-gray-50 transition">
                Volver
            </a>

        </div>
    </div>

    {{-- Cards superiores --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

        <div class="bg-white rounded-3xl border border-gray-200 p-5 shadow-sm">
            <div class="text-sm text-gray-500">Monto</div>
            <div class="mt-2 text-2xl font-black text-gray-900">
                ${{ number_format((float)$recibo->monto, 2) }}
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-200 p-5 shadow-sm">
            <div class="text-sm text-gray-500">Fecha recibo</div>
            <div class="mt-2 text-xl font-bold text-gray-900">
                {{ $recibo->fecha_recibo?->format('d/m/Y') ?? '-' }}
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-200 p-5 shadow-sm">
            <div class="text-sm text-gray-500">Saldo anterior</div>
            <div class="mt-2 text-xl font-bold text-gray-900">
                ${{ number_format((float)$recibo->saldo_anterior, 2) }}
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-200 p-5 shadow-sm">
            <div class="text-sm text-gray-500">Saldo posterior</div>
            <div class="mt-2 text-xl font-bold text-green-700">
                ${{ number_format((float)$recibo->saldo_posterior, 2) }}
            </div>
        </div>

    </div>

    {{-- Información principal --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Cliente / Contrato --}}
        <div class="xl:col-span-2 bg-white rounded-3xl border border-gray-200 shadow-sm p-6">

            <h2 class="text-lg font-black text-gray-900 mb-5">
                Cliente / Contrato
            </h2>

            <div class="grid md:grid-cols-2 gap-5">

                <div>
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Cliente
                    </div>
                    <div class="mt-1 text-base font-semibold text-gray-900">
                        {{ $recibo->cliente?->nombre }}
                        {{ $recibo->cliente?->apellido_paterno }}
                        {{ $recibo->cliente?->apellido_materno }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Contrato
                    </div>
                    <div class="mt-1 text-base font-semibold text-gray-900">
                        {{ $recibo->contrato?->folio ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Vendedor
                    </div>
                    <div class="mt-1 text-base font-semibold text-gray-900">
                        {{ $recibo->contrato?->vendedor?->name ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Cuota relacionada
                    </div>
                    <div class="mt-1 text-base font-semibold text-gray-900">
                        #{{ $recibo->cuota?->numero ?? '-' }}
                    </div>
                </div>

            </div>
        </div>

 {{-- Vehículo --}}
<div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">

    <h2 class="text-lg font-black text-gray-900 mb-5">
        Vehículo
    </h2>

    @php
        $auto = $recibo->contrato?->auto;

        $marca = $auto?->marca;

        // Si viene relación objeto
        if (is_object($marca)) {
            $marca = $marca->nombre ?? null;
        }

        // Si viene array/json
        if (is_array($marca)) {
            $marca = $marca['nombre'] ?? null;
        }

        $modelo = $auto?->modelo;

        if (is_object($modelo)) {
            $modelo = $modelo->nombre ?? null;
        }

        if (is_array($modelo)) {
            $modelo = $modelo['nombre'] ?? null;
        }

        $anio = $auto?->anio ?? null;
        $vin = $auto?->vin ?? null;
        $color = $auto?->color ?? null;
        $placas = $auto?->placas ?? null;
    @endphp

    <div class="space-y-5">

        {{-- Marca / Modelo --}}
        <div>
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                Marca / Modelo
            </div>

            <div class="mt-1 text-lg font-bold text-gray-900">
                {{ $marca ?: '-' }}
                {{ $modelo ?: '' }}
            </div>
        </div>

        {{-- Año --}}
        <div>
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                Año
            </div>

            <div class="mt-1 text-base font-semibold text-gray-900">
                {{ $anio ?: '-' }}
            </div>
        </div>

        {{-- Color --}}
        @if($color)
        <div>
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                Color
            </div>

            <div class="mt-1 text-base font-semibold text-gray-900">
                {{ $color }}
            </div>
        </div>
        @endif

        {{-- Placas --}}
        @if($placas)
        <div>
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                Placas
            </div>

            <div class="mt-1 text-base font-semibold text-gray-900">
                {{ $placas }}
            </div>
        </div>
        @endif

        {{-- VIN --}}
        <div>
            <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                VIN
            </div>

            <div class="mt-1 text-sm font-medium text-gray-900 break-all bg-gray-50 border rounded-xl px-3 py-2">
                {{ $vin ?: 'No registrado' }}
            </div>
        </div>

    </div>

</div>

    </div>

    {{-- Detalles financieros --}}
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">

        <h2 class="text-lg font-black text-gray-900 mb-5">
            Información financiera
        </h2>

        <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-5">

            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Método pago
                </div>
                <div class="mt-1 font-semibold text-gray-900">
                    {{ $recibo->pago?->metodo_pago ?? 'N/D' }}
                </div>
            </div>

            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Referencia
                </div>
                <div class="mt-1 font-semibold text-gray-900">
                    {{ $recibo->pago?->referencia ?? '-' }}
                </div>
            </div>

            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Fecha creación
                </div>
                <div class="mt-1 font-semibold text-gray-900">
                    {{ $recibo->created_at?->format('d/m/Y h:i A') ?? '-' }}
                </div>
            </div>

            <div>
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                    Cancelado
                </div>
                <div class="mt-1 font-semibold text-gray-900">
                    {{ $recibo->cancelado_at?->format('d/m/Y h:i A') ?? 'No' }}
                </div>
            </div>

        </div>

    </div>

    {{-- Notas --}}
    @if($recibo->concepto || $recibo->observaciones)

        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">

            <h2 class="text-lg font-black text-gray-900 mb-4">
                Notas / Observaciones
            </h2>

            @if($recibo->concepto)
                <div class="mb-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Concepto
                    </div>

                    <div class="mt-1 text-gray-900">
                        {{ $recibo->concepto }}
                    </div>
                </div>
            @endif

            @if($recibo->observaciones)
                <div>
                    <div class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                        Observaciones
                    </div>

                    <div class="mt-1 text-gray-700 whitespace-pre-line">
                        {{ $recibo->observaciones }}
                    </div>
                </div>
            @endif

        </div>

    @endif

</div>