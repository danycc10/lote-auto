<div class="p-4 sm:p-6 space-y-6">

    @php
    $colores = [
        'nuevo'       => 'bg-slate-100 text-slate-700 border-slate-200',
        'contactado'  => 'bg-blue-50 text-blue-700 border-blue-200',
        'interesado'  => 'bg-amber-50 text-amber-700 border-amber-200',
        'negociacion' => 'bg-purple-50 text-purple-700 border-purple-200',
        'ganado'      => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'perdido'     => 'bg-red-50 text-red-700 border-red-200',
    ];
    $etiquetas = [
        'nuevo'       => 'Nuevo',
        'contactado'  => 'Contactado',
        'interesado'  => 'Interesado',
        'negociacion' => 'Negociación',
        'ganado'      => 'Ganado',
        'perdido'     => 'Perdido',
    ];
    @endphp

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Prospectos</h1>
            <p class="text-sm text-slate-500 mt-0.5">Seguimiento de leads y clientes potenciales.</p>
        </div>
        <button wire:click="abrirModalNuevo" type="button"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-700 transition shadow-sm">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
            </svg>
            Nuevo prospecto
        </button>
    </div>

    {{-- Pipeline de estatus --}}
    <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
        @foreach($etiquetas as $key => $label)
        <button wire:click="$set('estatus', '{{ $estatus === $key ? '' : $key }}')"
                type="button"
                class="flex flex-col items-center gap-1 rounded-xl border p-3 transition cursor-pointer
                    {{ $estatus === $key ? 'ring-2 ring-indigo-500 ' . $colores[$key] : 'border-slate-200 bg-white hover:bg-slate-50' }}">
            <span class="text-lg font-bold {{ $estatus === $key ? '' : 'text-slate-700' }}">
                {{ $this->conteoEstatus[$key] ?? 0 }}
            </span>
            <span class="text-[11px] font-medium {{ $estatus === $key ? '' : 'text-slate-500' }}">{{ $label }}</span>
        </button>
        @endforeach
    </div>

    {{-- Filtros --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.350ms="q"
                       placeholder="Buscar por nombre, teléfono o correo..."
                       class="w-full pl-9 rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            @if($estatus)
            <button wire:click="$set('estatus','')" type="button"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 text-sm text-slate-600 hover:bg-slate-50 transition">
                <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                </svg>
                Quitar filtro
            </button>
            @endif
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto" wire:loading.class="opacity-60">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/70">
                        <th class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Prospecto</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Auto de interés</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estatus</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Asignado</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Último contacto</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($this->prospectos as $p)
                    @php
                        $tel = preg_replace('/[^0-9]/', '', $p->telefono ?? '');
                        if (strlen($tel) === 10) $tel = '52' . $tel;
                        $waUrl = $tel ? 'https://wa.me/' . $tel : null;
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-3.5">
                            <p class="font-semibold text-slate-900">{{ $p->nombre }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                @if($p->telefono)
                                <span class="text-xs text-slate-500">{{ $p->telefono }}</span>
                                @endif
                                @if($p->correo)
                                <span class="text-xs text-slate-400">· {{ $p->correo }}</span>
                                @endif
                            </div>
                            @if($p->origen)
                            <span class="inline-block mt-1 text-[10px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-500">{{ ucfirst($p->origen) }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($p->auto)
                            <p class="text-sm text-slate-700 font-medium">
                                {{ $p->auto->marca?->nombre }} {{ $p->auto->modelo?->nombre }}
                            </p>
                            <p class="text-xs text-slate-400">{{ $p->auto->anio }}</p>
                            @else
                            <span class="text-xs text-slate-300">Sin auto específico</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" type="button"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border transition {{ $colores[$p->estatus] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $etiquetas[$p->estatus] ?? ucfirst($p->estatus) }}
                                    <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <div x-show="open" @click.outside="open = false"
                                     class="absolute z-20 mt-1 left-0 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden w-36">
                                    @foreach($etiquetas as $key => $label)
                                    @if($key !== $p->estatus)
                                    <button wire:click="cambiarEstatus({{ $p->id }}, '{{ $key }}')"
                                            @click="open = false"
                                            type="button"
                                            class="w-full text-left px-3 py-2 text-xs hover:bg-slate-50 transition {{ $colores[$key] ?? '' }} border-0 border-b border-slate-100 last:border-0">
                                        {{ $label }}
                                    </button>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="text-sm text-slate-600">{{ $p->usuarioAsignado?->name ?? '—' }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($p->ultimo_contacto_at)
                            <p class="text-xs text-slate-600">{{ $p->ultimo_contacto_at->format('d/m/Y H:i') }}</p>
                            <p class="text-[10px] text-slate-400">{{ $p->ultimo_contacto_at->diffForHumans() }}</p>
                            @else
                            <span class="text-xs text-red-400 font-medium">Sin contacto</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-1.5">
                                {{-- WA --}}
                                @if($waUrl)
                                <a href="{{ $waUrl }}" target="_blank" rel="noopener"
                                   wire:click="marcarContactado({{ $p->id }})"
                                   title="Contactar por WhatsApp"
                                   class="inline-flex items-center justify-center h-7 w-7 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition">
                                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                    </svg>
                                </a>
                                @endif
                                {{-- Editar --}}
                                <button wire:click="editar({{ $p->id }})" type="button"
                                        title="Editar"
                                        class="inline-flex items-center justify-center h-7 w-7 rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition">
                                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.695 14.763l-1.262 3.154a.5.5 0 0 0 .65.65l3.155-1.262a4 4 0 0 0 1.343-.885L17.5 5.5a2.121 2.121 0 0 0-3-3L3.58 13.42a4 4 0 0 0-.885 1.343Z"/>
                                    </svg>
                                </button>
                                {{-- Eliminar --}}
                                <button wire:click="eliminar({{ $p->id }})"
                                        wire:confirm="¿Eliminar este prospecto?"
                                        type="button"
                                        title="Eliminar"
                                        class="inline-flex items-center justify-center h-7 w-7 rounded-lg border border-red-100 bg-red-50 text-red-400 hover:bg-red-100 transition">
                                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-14 text-center">
                            <svg class="h-10 w-10 text-slate-200 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                            </svg>
                            <p class="text-sm font-medium text-slate-400">Sin prospectos</p>
                            <p class="text-xs text-slate-400 mt-1">Agrega uno manualmente o espera contactos del sitio web.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($this->prospectos->hasPages())
        <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/60">
            {{ $this->prospectos->links() }}
        </div>
        @endif
    </div>

    {{-- Modal crear / editar --}}
    @if($mostrarModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="$set('mostrarModal', false)"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 space-y-4 max-h-[90vh] overflow-y-auto" @click.stop>

            <h3 class="text-base font-semibold text-slate-900">
                {{ $prospectoId ? 'Editar prospecto' : 'Nuevo prospecto' }}
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Nombre *</label>
                    <input type="text" wire:model="nombre" placeholder="Nombre completo"
                           class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('nombre')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Teléfono</label>
                    <input type="text" wire:model="telefono" placeholder="10 dígitos"
                           class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Correo</label>
                    <input type="email" wire:model="correo" placeholder="correo@ejemplo.com"
                           class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('correo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Estatus</label>
                    <select wire:model="estatusForm"
                            class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="nuevo">Nuevo</option>
                        <option value="contactado">Contactado</option>
                        <option value="interesado">Interesado</option>
                        <option value="negociacion">Negociación</option>
                        <option value="ganado">Ganado</option>
                        <option value="perdido">Perdido</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Origen</label>
                    <select wire:model="origen"
                            class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">— Sin especificar —</option>
                        <option value="web">Sitio web</option>
                        <option value="llamada">Llamada</option>
                        <option value="visita">Visita</option>
                        <option value="referido">Referido</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="facebook">Facebook</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Asignar a vendedor</label>
                    <select wire:model="usuarioAsignadoId"
                            class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">— Sin asignar —</option>
                        @foreach($this->usuarios as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Búsqueda de auto --}}
                <div class="sm:col-span-2" x-data="{ open: false }" @click.outside="open = false">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Auto de interés</label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="busquedaAuto"
                               @focus="open = true" @input="open = true"
                               placeholder="Buscar por marca, modelo o año..."
                               class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @if($autoId)
                        <button type="button" @click="$wire.autoId = null; $wire.busquedaAuto = ''"
                                class="absolute inset-y-0 right-2 flex items-center px-2 text-slate-400 hover:text-slate-600">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                            </svg>
                        </button>
                        @endif
                    </div>
                    @if(count($this->autosBusqueda) > 0 && strlen($busquedaAuto) >= 2)
                    <div x-show="open" class="absolute z-30 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden max-w-lg">
                        @foreach($this->autosBusqueda as $a)
                        <button wire:click="seleccionarAuto({{ $a['id'] }}, '{{ addslashes($a['label']) }}')"
                                @click="open = false"
                                type="button"
                                class="w-full text-left px-4 py-2.5 text-sm hover:bg-indigo-50 transition border-b border-slate-100 last:border-0">
                            {{ $a['label'] }}
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Observaciones</label>
                    <textarea wire:model="observaciones" rows="3" placeholder="Notas sobre el prospecto..."
                              class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
                </div>
            </div>

            <div class="flex gap-2 justify-end pt-2">
                <button type="button" wire:click="$set('mostrarModal', false)"
                        class="px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                    Cancelar
                </button>
                <button type="button" wire:click="guardar" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-60 transition">
                    <span wire:loading wire:target="guardar">
                        <svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    Guardar
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
