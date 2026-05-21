<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-xl font-semibold text-slate-900">{{ $recibo->folio }}</h1>
                @php
                    $badgeRecibo = match($recibo->estatus) {
                        'emitido'   => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
                        'cancelado' => 'bg-red-50 text-red-700 ring-1 ring-red-200',
                        default     => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200',
                    };
                @endphp
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badgeRecibo }}">
                    {{ strtoupper($recibo->estatus) }}
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-0.5">Detalle completo del recibo emitido.</p>
        </div>
        <div class="flex flex-wrap gap-2 shrink-0">
            <a href="{{ route('admin.recibos.pdf', $recibo->id) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.875 1.5C6.839 1.5 6 2.34 6 3.375v2.99c-.426.053-.851.11-1.274.174-1.454.218-2.476 1.483-2.476 2.917v6.294a3 3 0 003 3h.27l-.155 1.705A1.875 1.875 0 007.232 22.5h9.536a1.875 1.875 0 001.867-2.045l-.155-1.705h.27a3 3 0 003-3V9.456c0-1.434-1.022-2.7-2.476-2.917A48.716 48.716 0 0018 6.366V3.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM16.5 6.205v-2.83A.375.375 0 0016.125 3h-8.25a.375.375 0 00-.375.375v2.83a49.353 49.353 0 018.99 0zm-9.38 6.157a.75.75 0 00-1.06 1.06L7.72 14.78a.75.75 0 001.414-.478l-.034-4.25a.75.75 0 00-.75-.745zm6.5 0a.75.75 0 01.75.745l-.034 4.25a.75.75 0 001.414.478l1.66-1.358a.75.75 0 10-.96-1.152l-.12.098.028-3.556a.75.75 0 00-.738-.757z" clip-rule="evenodd"/>
                </svg>
                Imprimir PDF
            </a>
            <a href="{{ route('admin.recibos.edit', $recibo->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-amber-300 bg-amber-50 text-amber-700 text-sm font-medium hover:bg-amber-100 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z"/>
                    <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z"/>
                </svg>
                Editar
            </a>
            <a href="{{ route('admin.recibos.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 010 1.06l-6.22 6.22H21a.75.75 0 010 1.5H4.81l6.22 6.22a.75.75 0 11-1.06 1.06l-7.5-7.5a.75.75 0 010-1.06l7.5-7.5a.75.75 0 011.06 0z" clip-rule="evenodd"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500">Monto</p>
            <p class="mt-2 text-xl font-semibold text-slate-900 tabular-nums">${{ number_format((float)$recibo->monto, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500">Fecha recibo</p>
            <p class="mt-2 text-xl font-semibold text-slate-900">{{ $recibo->fecha_recibo?->format('d/m/Y') ?? '—' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500">Saldo anterior</p>
            <p class="mt-2 text-xl font-semibold text-slate-700 tabular-nums">${{ number_format((float)$recibo->saldo_anterior, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-medium text-slate-500">Saldo posterior</p>
            <p class="mt-2 text-xl font-semibold text-emerald-600 tabular-nums">${{ number_format((float)$recibo->saldo_posterior, 2) }}</p>
        </div>
    </div>

    {{-- Info principal --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Cliente / Contrato --}}
        <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                <h2 class="text-sm font-semibold text-slate-900">Cliente / Contrato</h2>
            </div>
            <div class="p-5 grid md:grid-cols-2 gap-5">
                <div>
                    <p class="text-xs font-medium text-slate-500">Cliente</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $recibo->cliente?->nombre }}
                        {{ $recibo->cliente?->apellido_paterno }}
                        {{ $recibo->cliente?->apellido_materno }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500">Contrato</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">{{ $recibo->contrato?->folio ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500">Vendedor</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">{{ $recibo->contrato?->vendedor?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500">Cuota relacionada</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">#{{ $recibo->cuota?->numero ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Vehículo --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                <h2 class="text-sm font-semibold text-slate-900">Vehículo</h2>
            </div>
            <div class="p-5 space-y-4">
                @php
                    $auto = $recibo->contrato?->auto;
                    $marca = $auto?->marca;
                    if (is_object($marca)) $marca = $marca->nombre ?? null;
                    if (is_array($marca)) $marca = $marca['nombre'] ?? null;
                    $modelo = $auto?->modelo;
                    if (is_object($modelo)) $modelo = $modelo->nombre ?? null;
                    if (is_array($modelo)) $modelo = $modelo['nombre'] ?? null;
                    $anio = $auto?->anio ?? null;
                    $vin = $auto?->vin ?? null;
                    $color = $auto?->color ?? null;
                    $placas = $auto?->placas ?? null;
                @endphp
                <div>
                    <p class="text-xs font-medium text-slate-500">Marca / Modelo</p>
                    <p class="mt-1 text-sm font-semibold text-slate-900">{{ $marca ?: '—' }} {{ $modelo ?: '' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-slate-500">Año</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">{{ $anio ?: '—' }}</p>
                    </div>
                    @if($color)
                    <div>
                        <p class="text-xs font-medium text-slate-500">Color</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">{{ $color }}</p>
                    </div>
                    @endif
                    @if($placas)
                    <div>
                        <p class="text-xs font-medium text-slate-500">Placas</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">{{ $placas }}</p>
                    </div>
                    @endif
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500">VIN</p>
                    <p class="mt-1 text-xs font-mono text-slate-700 bg-slate-50 rounded-lg border border-slate-200 px-3 py-2 break-all">{{ $vin ?: 'No registrado' }}</p>
                </div>
            </div>
        </div>

    </div>

    {{-- Información financiera --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
            <h2 class="text-sm font-semibold text-slate-900">Información financiera</h2>
        </div>
        <div class="p-5 grid md:grid-cols-2 xl:grid-cols-4 gap-5">
            <div>
                <p class="text-xs font-medium text-slate-500">Método de pago</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">{{ ucfirst($recibo->pago?->metodo_pago ?? 'N/D') }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">Referencia</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">{{ $recibo->pago?->referencia ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">Fecha de creación</p>
                <p class="mt-1 text-sm font-semibold text-slate-900">{{ $recibo->created_at?->format('d/m/Y h:i A') ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500">Cancelado</p>
                <p class="mt-1 text-sm font-semibold {{ $recibo->cancelado_at ? 'text-red-600' : 'text-slate-400' }}">
                    {{ $recibo->cancelado_at?->format('d/m/Y h:i A') ?? 'No' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Notas / Observaciones --}}
    @if($recibo->concepto || $recibo->observaciones)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                <h2 class="text-sm font-semibold text-slate-900">Notas / Observaciones</h2>
            </div>
            <div class="p-5 space-y-4">
                @if($recibo->concepto)
                    <div>
                        <p class="text-xs font-medium text-slate-500">Concepto</p>
                        <p class="mt-1 text-sm text-slate-900">{{ $recibo->concepto }}</p>
                    </div>
                @endif
                @if($recibo->observaciones)
                    <div>
                        <p class="text-xs font-medium text-slate-500">Observaciones</p>
                        <p class="mt-1 text-sm text-slate-700 whitespace-pre-line">{{ $recibo->observaciones }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>
