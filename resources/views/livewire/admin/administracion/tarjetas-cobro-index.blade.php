<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Tarjetas y cuentas de cobro</h1>
            <p class="text-sm text-slate-500 mt-0.5">Registra las cuentas, terminales y CLABES disponibles para recibir pagos.</p>
        </div>
        @if($modo === '')
            <button type="button" wire:click="iniciarCrear"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition-colors shrink-0">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd"/>
                </svg>
                Nueva cuenta
            </button>
        @endif
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Formulario crear / editar --}}
    @if($modo !== '')
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 sm:p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-sm font-semibold text-slate-900">
                    {{ $modo === 'crear' ? 'Nueva cuenta / terminal' : 'Editar cuenta / terminal' }}
                </h2>
                <button type="button" wire:click="cancelar"
                        class="text-slate-400 hover:text-slate-700 transition-colors">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>

            <form wire:submit="guardar" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Nombre --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="nombre"
                               placeholder="Ej: BBVA Terminal, CLABE Santander, Cuenta Banamex..."
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('nombre') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tipo --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">
                            Tipo <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="tipo"
                                class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="tarjeta">Tarjeta (POS / Terminal)</option>
                            <option value="transferencia">Transferencia (SPEI)</option>
                            <option value="deposito">Depósito en cuenta</option>
                        </select>
                        @error('tipo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Banco --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Banco</label>
                        <input type="text" wire:model="banco"
                               placeholder="Ej: BBVA, Santander, HSBC..."
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('banco') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Número --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">
                            Número / CLABE
                            <span class="text-slate-400 font-normal">(opcional)</span>
                        </label>
                        <input type="text" wire:model="numero"
                               placeholder="Últimos 4 dígitos, CLABE, etc."
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('numero') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Titular --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">
                            Titular
                            <span class="text-slate-400 font-normal">(opcional)</span>
                        </label>
                        <input type="text" wire:model="titular"
                               placeholder="Nombre del titular de la cuenta"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('titular') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Activa --}}
                    <div class="sm:col-span-2 flex items-center gap-3">
                        <label class="relative inline-flex cursor-pointer">
                            <input type="checkbox" wire:model="activa" class="sr-only peer">
                            <div class="h-5 w-9 rounded-full bg-slate-200 peer-checked:bg-indigo-600 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:after:translate-x-4"></div>
                        </label>
                        <span class="text-sm text-slate-700">Cuenta activa (disponible para selección en pagos)</span>
                    </div>
                </div>

                <div class="flex gap-3 pt-2 border-t border-slate-100">
                    <button type="submit"
                            wire:loading.attr="disabled" wire:target="guardar"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 transition-colors">
                        <svg wire:loading.remove wire:target="guardar" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd"/>
                        </svg>
                        <svg wire:loading wire:target="guardar" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        {{ $modo === 'crear' ? 'Guardar cuenta' : 'Actualizar cuenta' }}
                    </button>
                    <button type="button" wire:click="cancelar"
                            class="px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Lista de tarjetas agrupadas por tipo --}}
    @php
        $tipoConfig = [
            'tarjeta'        => ['label' => 'Tarjeta / Terminal POS',   'icon_color' => 'text-violet-600', 'bg' => 'bg-violet-50'],
            'transferencia'  => ['label' => 'Transferencia SPEI',        'icon_color' => 'text-indigo-600', 'bg' => 'bg-indigo-50'],
            'deposito'       => ['label' => 'Depósito en cuenta',        'icon_color' => 'text-emerald-600','bg' => 'bg-emerald-50'],
        ];
    @endphp

    @if($tarjetas->isEmpty())
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col items-center justify-center py-16 text-center px-6">
            <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                <svg class="h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-slate-900">No hay cuentas registradas</p>
            <p class="text-xs text-slate-500 mt-1">Agrega terminales, cuentas de transferencia o depósito.</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach(['tarjeta','transferencia','deposito'] as $tipoKey)
                @if($tarjetas->has($tipoKey))
                    @php $cfg = $tipoConfig[$tipoKey]; @endphp
                    <div>
                        <h2 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-3">
                            {{ $cfg['label'] }}
                        </h2>
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-slate-200 bg-slate-50">
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nombre</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider hidden sm:table-cell">Banco</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider hidden md:table-cell">Número / CLABE</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider hidden lg:table-cell">Titular</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($tarjetas[$tipoKey] as $tarjeta)
                                        <tr class="hover:bg-slate-50/60 transition-colors {{ $tarjetaId === $tarjeta->id ? 'bg-indigo-50/40' : '' }}">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="h-7 w-7 rounded-lg {{ $cfg['bg'] }} flex items-center justify-center shrink-0">
                                                        @if($tipoKey === 'tarjeta')
                                                            <svg class="h-3.5 w-3.5 {{ $cfg['icon_color'] }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z"/>
                                                                <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @elseif($tipoKey === 'transferencia')
                                                            <svg class="h-3.5 w-3.5 {{ $cfg['icon_color'] }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z"/>
                                                                <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 14.625v-9.75zM8.25 9.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM18.75 9a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V9.75a.75.75 0 00-.75-.75h-.008zM4.5 9.75A.75.75 0 015.25 9h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75V9.75z" clip-rule="evenodd"/>
                                                                <path d="M2.25 18a.75.75 0 000 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 00-.75-.75H2.25z"/>
                                                            </svg>
                                                        @else
                                                            <svg class="h-3.5 w-3.5 {{ $cfg['icon_color'] }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M11.584 2.376a.75.75 0 01.832 0l9 6a.75.75 0 11-.832 1.248L12 3.901 3.416 9.624a.75.75 0 01-.832-1.248l9-6z"/>
                                                                <path fill-rule="evenodd" d="M20.25 10.332v9.918H21a.75.75 0 010 1.5H3a.75.75 0 010-1.5h.75v-9.918a.75.75 0 01.634-.74A49.109 49.109 0 0112 9c2.59 0 5.134.202 7.616.592a.75.75 0 01.634.74zm-7.5 2.418a.75.75 0 00-1.5 0v6.75a.75.75 0 001.5 0v-6.75zm3-.75a.75.75 0 01.75.75v6.75a.75.75 0 01-1.5 0v-6.75a.75.75 0 01.75-.75zM9 12.75a.75.75 0 00-1.5 0v6.75a.75.75 0 001.5 0v-6.75z" clip-rule="evenodd"/>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <span class="text-sm font-medium text-slate-900">{{ $tarjeta->nombre }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-slate-600 hidden sm:table-cell">
                                                {{ $tarjeta->banco ?: '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-slate-600 font-mono hidden md:table-cell">
                                                {{ $tarjeta->numero ? '****' . ltrim($tarjeta->numero, '*') : '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-slate-600 hidden lg:table-cell">
                                                {{ $tarjeta->titular ?: '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <button type="button"
                                                        wire:click="toggleActiva({{ $tarjeta->id }})"
                                                        title="{{ $tarjeta->activa ? 'Desactivar' : 'Activar' }}"
                                                        class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none {{ $tarjeta->activa ? 'bg-indigo-600' : 'bg-slate-200' }}">
                                                    <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 {{ $tarjeta->activa ? 'translate-x-4' : 'translate-x-0' }}"></span>
                                                </button>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <button type="button"
                                                            wire:click="iniciarEditar({{ $tarjeta->id }})"
                                                            class="text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:underline transition-colors">
                                                        Editar
                                                    </button>
                                                    <button type="button"
                                                            wire:click="eliminar({{ $tarjeta->id }})"
                                                            wire:confirm="¿Eliminar esta cuenta?"
                                                            class="text-xs font-medium text-red-500 hover:text-red-700 hover:underline transition-colors">
                                                        Eliminar
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

</div>
