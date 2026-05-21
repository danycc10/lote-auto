<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Registrar pago</h1>
            <p class="text-sm text-slate-500 mt-0.5">Selecciona la cuota y registra el cobro del contrato.</p>
        </div>
        <a href="{{ route('admin.contratos-financiamiento.show', $contrato) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shrink-0">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 010 1.06l-6.22 6.22H21a.75.75 0 010 1.5H4.81l6.22 6.22a.75.75 0 11-1.06 1.06l-7.5-7.5a.75.75 0 010-1.06l7.5-7.5a.75.75 0 011.06 0z" clip-rule="evenodd"/>
            </svg>
            Volver al contrato
        </a>
    </div>

    {{-- Contexto del contrato --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Contrato</p>
            <p class="mt-1.5 text-sm font-semibold text-slate-900 tabular-nums">{{ $contrato->folio }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Cliente</p>
            <p class="mt-1.5 text-sm font-semibold text-slate-900">{{ $contrato->cliente?->nombre_completo ?: '—' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Saldo actual</p>
            <p class="mt-1.5 text-sm font-semibold text-slate-900 tabular-nums">${{ number_format((float) $contrato->saldo_actual, 2) }}</p>
        </div>
    </div>

    {{-- Sin cuotas disponibles --}}
    @if($cuotasDisponibles->isEmpty())
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col items-center justify-center py-16 text-center px-6">
            <div class="h-12 w-12 rounded-full bg-emerald-50 flex items-center justify-center mb-3">
                <svg class="h-6 w-6 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-slate-900">No hay cuotas pendientes</p>
            <p class="text-xs text-slate-500 mt-1">Todas las cuotas de este contrato han sido liquidadas.</p>
        </div>

    @else
        {{-- Layout dos columnas --}}
        <div class="grid grid-cols-1 lg:grid-cols-[300px_1fr] gap-5 items-start">

            {{-- Panel izquierdo: lista de cuotas --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden sticky top-4">
                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/80">
                    <h2 class="text-xs font-medium text-slate-500 uppercase tracking-wider">Cuotas pendientes</h2>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $cuotasDisponibles->count() }} cuota(s) por saldar</p>
                </div>

                <div class="divide-y divide-slate-100 max-h-[520px] overflow-y-auto">
                    @foreach($cuotasDisponibles as $cuota)
                        @php
                            $esSeleccionada = (int) $cuota_id === $cuota->id;
                            $hoy = \Carbon\Carbon::today();
                            $diasAtraso = ($cuota->estatus === 'vencida' && $cuota->fecha_vencimiento)
                                ? (int) abs($hoy->diffInDays(\Carbon\Carbon::parse($cuota->fecha_vencimiento)))
                                : 0;
                            [$statusBg, $statusText] = match($cuota->estatus) {
                                'pendiente' => ['bg-blue-50 border-blue-200 text-blue-700', 'Pendiente'],
                                'parcial'   => ['bg-amber-50 border-amber-200 text-amber-700', 'Parcial'],
                                'vencida'   => ['bg-red-50 border-red-200 text-red-700', 'Vencida'],
                                default     => ['bg-slate-50 border-slate-200 text-slate-700', ucfirst($cuota->estatus)],
                            };
                        @endphp
                        <button type="button"
                                wire:click="seleccionarCuota({{ $cuota->id }})"
                                class="w-full px-4 py-3.5 text-left transition-colors relative {{ $esSeleccionada ? 'bg-indigo-50' : 'hover:bg-slate-50/70' }}">
                            @if($esSeleccionada)
                                <span class="absolute inset-y-0 left-0 w-[3px] bg-indigo-500 rounded-r-sm"></span>
                            @endif
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold {{ $esSeleccionada ? 'text-indigo-900' : 'text-slate-900' }}">
                                        Cuota #{{ $cuota->numero }}
                                    </p>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        Vence {{ optional($cuota->fecha_vencimiento)->format('d/m/Y') }}
                                        @if($diasAtraso > 0)
                                            <span class="text-red-600 font-medium">&nbsp;· {{ $diasAtraso }}d de atraso</span>
                                        @endif
                                    </p>
                                </div>
                                <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium shrink-0 {{ $statusBg }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-xs text-slate-400">Saldo</span>
                                <span class="text-sm font-bold tabular-nums {{ $esSeleccionada ? 'text-indigo-700' : 'text-slate-900' }}">
                                    ${{ number_format((float) $cuota->saldo, 2) }}
                                </span>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Panel derecho: formulario y resumen --}}
            <div class="space-y-5">

                @if(!$cuota_id)
                    {{-- Estado vacío: ninguna cuota seleccionada --}}
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col items-center justify-center py-20 text-center px-6">
                        <div class="h-12 w-12 rounded-full bg-indigo-50 flex items-center justify-center mb-3">
                            <svg class="h-6 w-6 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zM12 2.25V4.5m5.834.166l-1.591 1.591M20.25 10.5H18M7.757 14.743l-1.59 1.59M6 10.5H3.75m4.007-4.243l-1.59-1.59"/>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-900">Selecciona una cuota</p>
                        <p class="text-xs text-slate-500 mt-1">Elige la cuota a cobrar de la lista de la izquierda.</p>
                    </div>

                @else
                    {{-- Formulario de pago --}}
                    <form wire:submit="guardar" class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 sm:p-6 space-y-5">

                        {{-- Cuota seleccionada (resumen visual) --}}
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                            <div class="h-9 w-9 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                <svg class="h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z"/>
                                    <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 14.625v-9.75zM8.25 9.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM18.75 9a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V9.75a.75.75 0 00-.75-.75h-.008zM4.5 9.75A.75.75 0 015.25 9h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75V9.75z" clip-rule="evenodd"/>
                                    <path d="M2.25 18a.75.75 0 000 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 00-.75-.75H2.25z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">
                                    Cuota #{{ $cuotaSeleccionada->numero }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    Vence {{ optional($cuotaSeleccionada->fecha_vencimiento)->format('d/m/Y') }}
                                    &nbsp;·&nbsp; Saldo <span class="tabular-nums font-semibold text-slate-700">${{ number_format((float) $cuotaSeleccionada->saldo, 2) }}</span>
                                </p>
                            </div>
                        </div>

                        {{-- Fecha, monto, forma de pago --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1.5">
                                    Fecha de pago <span class="text-red-500">*</span>
                                </label>
                                <input type="date" wire:model="fecha_pago"
                                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('fecha_pago')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1.5">
                                    Monto <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                                    <input type="number" step="0.01" wire:model.live="monto"
                                           class="block w-full rounded-lg border-slate-300 pl-6 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                                </div>
                                @error('monto')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1.5">
                                    Forma de pago <span class="text-red-500">*</span>
                                </label>
                                <select wire:model.live="forma_pago"
                                        class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="efectivo">Efectivo</option>
                                    <option value="transferencia">Transferencia</option>
                                    <option value="tarjeta">Tarjeta</option>
                                    <option value="deposito">Depósito</option>
                                    <option value="otro">Otro</option>
                                </select>
                                @error('forma_pago')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Cuenta / Terminal (cuando forma_pago no es efectivo) --}}
                        @if(in_array($forma_pago, ['tarjeta', 'transferencia', 'deposito']))
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1.5">
                                    @if($forma_pago === 'tarjeta') Terminal / Cuenta
                                    @elseif($forma_pago === 'transferencia') Cuenta SPEI destino
                                    @else Cuenta para depósito
                                    @endif
                                </label>

                                @if($tarjetasDisponibles->isEmpty())
                                    <div class="flex items-center gap-2 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2.5">
                                        <svg class="h-4 w-4 text-amber-500 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
                                        </svg>
                                        <p class="text-xs text-amber-700">
                                            No hay cuentas activas para este tipo.
                                            <a href="{{ route('admin.administracion.tarjetas-cobro') }}" target="_blank"
                                               class="font-semibold underline hover:text-amber-900">Registrar cuenta</a>
                                        </p>
                                    </div>
                                @else
                                    <div class="grid grid-cols-1 gap-2">
                                        @foreach($tarjetasDisponibles as $tarjeta)
                                            <label class="flex items-center gap-3 rounded-lg border px-3 py-2.5 cursor-pointer transition-colors
                                                {{ (int) $tarjeta_cobro_id === $tarjeta->id
                                                    ? 'border-indigo-400 bg-indigo-50'
                                                    : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50' }}">
                                                <input type="radio"
                                                       wire:model.live="tarjeta_cobro_id"
                                                       value="{{ $tarjeta->id }}"
                                                       class="text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-medium text-slate-900">{{ $tarjeta->nombre }}</p>
                                                    <p class="text-xs text-slate-500">
                                                        {{ $tarjeta->banco ?: '' }}
                                                        @if($tarjeta->numero)
                                                            {{ $tarjeta->banco ? '·' : '' }} ****{{ ltrim($tarjeta->numero, '*') }}
                                                        @endif
                                                        @if($tarjeta->titular)
                                                            · {{ $tarjeta->titular }}
                                                        @endif
                                                    </p>
                                                </div>
                                                @if((int) $tarjeta_cobro_id === $tarjeta->id)
                                                    <svg class="h-4 w-4 text-indigo-600 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
                                                    </svg>
                                                @endif
                                            </label>
                                        @endforeach
                                        <label class="flex items-center gap-3 rounded-lg border px-3 py-2.5 cursor-pointer transition-colors
                                            {{ $tarjeta_cobro_id === null || $tarjeta_cobro_id === ''
                                                ? 'border-slate-300 bg-slate-50'
                                                : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50' }}">
                                            <input type="radio"
                                                   wire:model.live="tarjeta_cobro_id"
                                                   value=""
                                                   class="text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                            <span class="text-sm text-slate-500">No especificar cuenta</span>
                                        </label>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Recargo de mora (checkbox interactivo) --}}
                        @if($recargoSugerido > 0)
                            <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox"
                                           wire:model.live="incluirRecargo"
                                           class="mt-0.5 h-4 w-4 rounded border-amber-300 text-indigo-600 focus:ring-indigo-500">
                                    <div>
                                        <p class="text-xs font-semibold text-amber-800">Incluir recargo por mora</p>
                                        <p class="text-xs text-amber-700 mt-0.5">
                                            Se calculó un recargo de
                                            <span class="font-bold tabular-nums">${{ number_format($recargoSugerido, 2) }}</span>
                                            por días de atraso. Al marcar esta casilla se suma al monto automáticamente.
                                        </p>
                                    </div>
                                </label>
                            </div>
                        @endif

                        {{-- Referencia (solo si no es efectivo) --}}
                        @if($forma_pago !== 'efectivo')
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1.5">Referencia</label>
                                <input type="text" wire:model="referencia"
                                       placeholder="Número de transferencia, folio, etc."
                                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('referencia')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        {{-- Concepto y observaciones --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1.5">Concepto</label>
                                <input type="text" wire:model="concepto"
                                       placeholder="Descripción del pago"
                                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('concepto')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1.5">Observaciones</label>
                                <input type="text" wire:model="observaciones"
                                       placeholder="Notas adicionales opcionales"
                                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('observaciones')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Acciones --}}
                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2 border-t border-slate-100">
                            <a href="{{ route('admin.contratos-financiamiento.show', $contrato) }}"
                               class="inline-flex items-center justify-center px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="guardar"
                                    class="inline-flex items-center justify-center gap-2 px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                <span wire:loading.remove wire:target="guardar" class="flex items-center gap-2">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM9 7.5A.75.75 0 009 9h1.5c.98 0 1.813.626 2.122 1.5H9A.75.75 0 009 12h3.622a2.251 2.251 0 01-2.122 1.5H9a.75.75 0 00-.53 1.28l3 3a.75.75 0 101.06-1.06l-1.7-1.7A3.75 3.75 0 0013.5 12H15a.75.75 0 000-1.5h-1.5a3.75 3.75 0 00-1.033-2.554A3.75 3.75 0 0015 7.5H9z" clip-rule="evenodd"/>
                                    </svg>
                                    Guardar pago y generar recibo
                                </span>
                                <span wire:loading wire:target="guardar" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    Procesando...
                                </span>
                            </button>
                        </div>

                    </form>

                    {{-- Resumen del cobro --}}
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                        <h3 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-4">Resumen del cobro</h3>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">Saldo de la cuota #{{ $cuotaSeleccionada->numero }}</span>
                                <span class="text-sm font-medium tabular-nums text-slate-900">${{ number_format((float) $cuotaSeleccionada->saldo, 2) }}</span>
                            </div>
                            @if($recargoSugerido > 0)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-amber-700 flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
                                        </svg>
                                        Recargo por mora {{ $incluirRecargo ? '(incluido)' : '(no incluido)' }}
                                    </span>
                                    <span class="text-sm font-medium tabular-nums {{ $incluirRecargo ? 'text-amber-700' : 'text-slate-400 line-through' }}">${{ number_format($recargoSugerido, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                                    <span class="text-sm font-semibold text-slate-700">Total recomendado</span>
                                    <span class="text-sm font-bold tabular-nums text-slate-900">${{ number_format((float) $cuotaSeleccionada->saldo + $recargoSugerido, 2) }}</span>
                                </div>
                            @endif
                        </div>

                        @if($monto)
                            @php
                                $montoNum = (float) str_replace(',', '', (string) $monto);
                                $nuevoSaldo = max(0, (float) $contrato->saldo_actual - $montoNum);
                            @endphp
                            <div class="mt-4 pt-4 border-t border-slate-100 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-600">Monto a cobrar</span>
                                    <span class="text-sm font-semibold tabular-nums text-indigo-700">${{ number_format($montoNum, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-600">Saldo actual del contrato</span>
                                    <span class="text-sm tabular-nums text-slate-700">${{ number_format((float) $contrato->saldo_actual, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                                    <span class="text-sm font-semibold text-slate-700">Nuevo saldo proyectado</span>
                                    <span class="text-sm font-bold tabular-nums {{ $nuevoSaldo <= 0 ? 'text-emerald-600' : 'text-slate-900' }}">
                                        ${{ number_format($nuevoSaldo, 2) }}
                                    </span>
                                </div>
                                @if($nuevoSaldo <= 0)
                                    <div class="flex items-center gap-2 rounded-lg bg-emerald-50 border border-emerald-200 px-3 py-2 mt-1">
                                        <svg class="h-4 w-4 text-emerald-600 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
                                        </svg>
                                        <p class="text-xs font-semibold text-emerald-800">Este pago liquidaría el contrato por completo.</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    @endif

</div>
