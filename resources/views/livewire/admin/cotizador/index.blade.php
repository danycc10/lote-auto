<div class="p-4 sm:p-6 space-y-6" x-data>

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Cotizador</h1>
            <p class="text-sm text-slate-500 mt-0.5">Genera cotizaciones de financiamiento al instante.</p>
        </div>
        @if($autoId)
        <button wire:click="limpiar" type="button"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-600 hover:bg-slate-50 transition shadow-sm">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            Nueva cotización
        </button>
        @endif
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ── Columna izquierda: búsqueda + datos ── --}}
        <div class="xl:col-span-1 space-y-4">

            {{-- Búsqueda de auto --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
                <h2 class="text-sm font-semibold text-slate-900 mb-3">Vehículo</h2>

                @if(! $autoId)
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="busqueda"
                               @focus="open = true" @input="open = true"
                               placeholder="Marca, modelo, año, placas..."
                               class="w-full pl-9 rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    @if(count($this->resultadosBusqueda) > 0 && strlen($busqueda) >= 2)
                    <div x-show="open" class="absolute z-20 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden">
                        @foreach($this->resultadosBusqueda as $resultado)
                        <button wire:click="seleccionarAuto({{ $resultado['id'] }})"
                                @click="open = false"
                                type="button"
                                class="w-full flex items-start justify-between gap-3 px-4 py-3 hover:bg-indigo-50 transition text-left border-b border-slate-100 last:border-0">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $resultado['label'] }}</p>
                                <p class="text-xs text-slate-500">{{ $resultado['placas'] }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-bold text-indigo-600">${{ number_format($resultado['precio'], 2) }}</p>
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full
                                    {{ $resultado['estatus'] === 'disponible' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ ucfirst($resultado['estatus']) }}
                                </span>
                            </div>
                        </button>
                        @endforeach
                    </div>
                    @endif

                    @if(strlen($busqueda) >= 2 && count($this->resultadosBusqueda) === 0)
                    <p class="mt-2 text-xs text-slate-400 text-center py-2">Sin resultados para "{{ $busqueda }}".</p>
                    @endif
                </div>
                @else
                {{-- Auto seleccionado --}}
                @php $auto = $this->auto; @endphp
                <div class="rounded-lg border border-indigo-200 bg-indigo-50/60 p-3">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="text-sm font-bold text-slate-900">
                                {{ $auto?->marca?->nombre }} {{ $auto?->modelo?->nombre }} {{ $auto?->anio }}
                            </p>
                            @if($auto?->placas)
                            <p class="text-xs text-slate-500 mt-0.5">Placas: {{ $auto->placas }}</p>
                            @endif
                        </div>
                        <p class="text-sm font-bold text-indigo-700 shrink-0">
                            ${{ number_format((float) $auto?->precio_financiado, 2) }}
                        </p>
                    </div>
                    @if($auto?->color || $auto?->kilometraje)
                    <p class="text-xs text-slate-500 mt-1">
                        {{ $auto?->color }}{{ ($auto?->color && $auto?->kilometraje) ? ' · ' : '' }}{{ $auto?->kilometraje ? number_format($auto->kilometraje) . ' km' : '' }}
                    </p>
                    @endif
                </div>
                @endif
            </div>

            {{-- Parámetros de financiamiento --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 space-y-4">
                <h2 class="text-sm font-semibold text-slate-900">Financiamiento</h2>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Enganche ($)</label>
                    <input type="number" wire:model.live="enganche" min="0" step="100"
                           class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Plazo (meses)</label>
                    <select wire:model.live="plazo"
                            class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach([6, 12, 18, 24, 36, 48, 60] as $p)
                        <option value="{{ $p }}">{{ $p }} meses</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">
                        Tasa de interés anual (%) <span class="text-slate-400 font-normal">— 0 = sin interés</span>
                    </label>
                    <input type="number" wire:model.live="tasaAnual" min="0" max="100" step="0.5"
                           class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            {{-- Datos del cliente (opcional) --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 space-y-4">
                <h2 class="text-sm font-semibold text-slate-900">Cliente <span class="text-xs font-normal text-slate-400">(opcional)</span></h2>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Nombre</label>
                    <input type="text" wire:model.live="nombreCliente" placeholder="Nombre completo"
                           class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Teléfono</label>
                    <input type="text" wire:model.live="telefonoCliente" placeholder="10 dígitos"
                           class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Correo</label>
                    <input type="email" wire:model.live="correoCliente" placeholder="correo@ejemplo.com"
                           class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

        </div>

        {{-- ── Columna derecha: resultados ── --}}
        <div class="xl:col-span-2 space-y-4">

            @if(! $autoId)
            {{-- Estado vacío --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm flex flex-col items-center justify-center py-24 text-center">
                <svg class="h-12 w-12 text-slate-200 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zM8.25 6h7.5v2.25h-7.5V6zM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.638 4.5 4.757V19.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.757c0-1.12-.806-2.057-1.907-2.185A48.507 48.507 0 0 0 12 2.25z" />
                </svg>
                <p class="text-sm font-medium text-slate-400">Busca un auto para generar la cotización</p>
            </div>
            @else

            {{-- KPIs resultado --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 text-center">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">Enganche</p>
                    <p class="text-lg font-bold text-slate-700">${{ number_format($enganche, 2) }}</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 text-center">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">Financiado</p>
                    <p class="text-lg font-bold text-slate-700">${{ number_format($this->montoFinanciado, 2) }}</p>
                </div>
                <div class="bg-indigo-600 border border-indigo-600 rounded-xl shadow-sm p-4 text-center">
                    <p class="text-xs font-medium text-indigo-200 uppercase tracking-wider mb-1">Mensualidad</p>
                    <p class="text-2xl font-bold text-white">${{ number_format($this->cuotaMensual, 2) }}</p>
                    <p class="text-xs text-indigo-200 mt-0.5">× {{ $plazo }} meses</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 text-center">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-1">Total a pagar</p>
                    <p class="text-lg font-bold text-emerald-600">${{ number_format($this->totalPagar, 2) }}</p>
                    @if($this->totalIntereses > 0)
                    <p class="text-[10px] text-slate-400 mt-0.5">Intereses: ${{ number_format($this->totalIntereses, 2) }}</p>
                    @endif
                </div>
            </div>

            {{-- Acciones --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
                <p class="text-xs font-medium text-slate-500 mb-3">Compartir cotización</p>
                <div class="flex flex-wrap gap-2">
                    {{-- PDF --}}
                    <a href="{{ $this->urlPdf() }}" target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-700 transition shadow-sm">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        Ver / Imprimir PDF
                    </a>

                    {{-- Correo --}}
                    <button wire:click="abrirModalCorreo" type="button"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:bg-slate-50 transition shadow-sm">
                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                        Enviar por correo
                    </button>

                    {{-- WhatsApp --}}
                    @if($this->urlWhatsapp())
                    <a href="{{ $this->urlWhatsapp() }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-emerald-200 bg-emerald-50 text-sm font-semibold text-emerald-700 hover:bg-emerald-100 transition shadow-sm">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                        </svg>
                        Enviar por WhatsApp
                    </a>
                    @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-100 bg-slate-50 text-sm font-medium text-slate-300 cursor-not-allowed"
                          title="Ingresa el teléfono del cliente para habilitar WhatsApp">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                        </svg>
                        WhatsApp (sin teléfono)
                    </span>
                    @endif
                </div>
            </div>

            {{-- Tabla de amortización --}}
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200">
                    <h2 class="text-sm font-semibold text-slate-900">Tabla de pagos</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Primer pago estimado: {{ now()->addMonth()->startOfMonth()->format('d/m/Y') }}</p>
                </div>
                <div class="overflow-x-auto max-h-96 overflow-y-auto">
                    <table class="min-w-full text-sm">
                        <thead class="sticky top-0">
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider w-12">#</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Capital</th>
                                @if($tasaAnual > 0)
                                <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Interés</th>
                                @endif
                                <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Cuota</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($this->tablaAmortizacion as $fila)
                            <tr class="hover:bg-slate-50/60 transition-colors {{ $loop->last ? 'font-semibold bg-slate-50/80' : '' }}">
                                <td class="px-4 py-2.5 text-center text-slate-500 tabular-nums">{{ $fila['numero'] }}</td>
                                <td class="px-4 py-2.5 text-center text-slate-600 tabular-nums">{{ $fila['fecha'] }}</td>
                                <td class="px-4 py-2.5 text-right text-slate-700 tabular-nums">${{ number_format($fila['capital'], 2) }}</td>
                                @if($tasaAnual > 0)
                                <td class="px-4 py-2.5 text-right text-amber-600 tabular-nums">${{ number_format($fila['interes'], 2) }}</td>
                                @endif
                                <td class="px-4 py-2.5 text-right font-semibold text-indigo-700 tabular-nums">${{ number_format($fila['cuota'], 2) }}</td>
                                <td class="px-4 py-2.5 text-right text-slate-600 tabular-nums">${{ number_format($fila['saldo'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @endif
        </div>
    </div>

    {{-- Modal envío por correo --}}
    @if($mostrarModalCorreo)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="$set('mostrarModalCorreo', false)"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6" @click.stop>
            <h3 class="text-base font-semibold text-slate-900 mb-1">Enviar cotización</h3>
            <p class="text-sm text-slate-500 mb-4">Se enviará el PDF adjunto al correo indicado.</p>

            <div class="mb-4">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Correo electrónico</label>
                <input type="email" wire:model="correoEnvio" placeholder="correo@ejemplo.com"
                       class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('correoEnvio')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2 justify-end">
                <button type="button" wire:click="$set('mostrarModalCorreo', false)"
                        class="px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                    Cancelar
                </button>
                <button type="button" wire:click="enviarPorCorreo" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-60 transition">
                    <span wire:loading wire:target="enviarPorCorreo">
                        <svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    Enviar
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
