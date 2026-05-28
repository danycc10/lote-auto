@php use Illuminate\Support\Str; @endphp
<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-xl font-semibold text-slate-900">Marcas y modelos</h1>
        <p class="text-sm text-slate-500 mt-0.5">Administra el catálogo de marcas y sus modelos de vehículos.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ═══════════════════════════════════════════════════
             Panel izquierdo — MARCAS
        ════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">

            {{-- Cabecera panel --}}
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 bg-slate-50">
                <h2 class="text-sm font-semibold text-slate-800">Marcas</h2>
                @can('autos.editar')
                @if(!$mostrarFormMarca)
                    <button type="button" wire:click="nuevaMarca"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition-colors">
                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd"/>
                        </svg>
                        Nueva marca
                    </button>
                @endif
                @endcan
            </div>

            {{-- Formulario nueva/editar marca --}}
            @can('autos.editar')
            @if($mostrarFormMarca)
                <div class="px-4 py-3 border-b border-indigo-100 bg-indigo-50/40">
                    <p class="text-xs font-medium text-indigo-700 mb-2">
                        {{ $marcaEditandoId ? 'Editar marca' : 'Nueva marca' }}
                    </p>
                    <form wire:submit="guardarMarca" class="flex gap-2">
                        <div class="flex-1">
                            <input type="text" wire:model="marcaNombre"
                                   placeholder="Nombre de la marca"
                                   autofocus
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('marcaNombre')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                                wire:loading.attr="disabled" wire:target="guardarMarca"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 disabled:opacity-50 transition-colors shrink-0">
                            <svg wire:loading.remove wire:target="guardarMarca" class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd"/>
                            </svg>
                            <svg wire:loading wire:target="guardarMarca" class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Guardar
                        </button>
                        <button type="button" wire:click="cancelarMarca"
                                class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors shrink-0">
                            Cancelar
                        </button>
                    </form>
                </div>
            @endif
            @endcan

            {{-- Lista de marcas --}}
            @if($marcas->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center px-6">
                    <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                        <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-slate-500">No hay marcas registradas.</p>
                </div>
            @else
                <ul class="divide-y divide-slate-100 flex-1 overflow-y-auto">
                    @foreach($marcas as $marca)
                        <li wire:key="marca-{{ $marca->id }}"
                            class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50/60 transition-colors cursor-pointer
                                   {{ $marcaSeleccionada === $marca->id ? 'bg-indigo-50 border-l-4 border-indigo-500' : 'border-l-4 border-transparent' }}"
                            wire:click="seleccionarMarca({{ $marca->id }})">

                            {{-- Icono --}}
                            <div class="h-8 w-8 rounded-lg {{ $marca->activo ? 'bg-slate-100' : 'bg-red-50' }} flex items-center justify-center shrink-0">
                                <svg class="h-4 w-4 {{ $marca->activo ? 'text-slate-500' : 'text-red-400' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/>
                                    <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/>
                                    <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                                </svg>
                            </div>

                            {{-- Nombre y contadores --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate">{{ $marca->nombre }}</p>
                                <p class="text-xs text-slate-400">
                                    {{ $marca->modelos_count }} {{ Str::plural('modelo', $marca->modelos_count) }}
                                    · {{ $marca->autos_count }} {{ Str::plural('auto', $marca->autos_count) }}
                                </p>
                            </div>

                            {{-- Estado badge --}}
                            @if(!$marca->activo)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700 shrink-0">
                                    Inactiva
                                </span>
                            @endif

                            {{-- Acciones (detener propagación del click en la fila) --}}
                            <div class="flex items-center gap-1 shrink-0" @click.stop>
                                @can('autos.editar')
                                    <button type="button"
                                            wire:click="toggleActivaMarca({{ $marca->id }})"
                                            title="{{ $marca->activo ? 'Desactivar' : 'Activar' }}"
                                            class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none {{ $marca->activo ? 'bg-indigo-600' : 'bg-slate-200' }}">
                                        <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 {{ $marca->activo ? 'translate-x-4' : 'translate-x-0' }}"></span>
                                    </button>
                                    <button type="button"
                                            wire:click="editarMarca({{ $marca->id }})"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                            title="Editar">
                                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z"/>
                                            <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z"/>
                                        </svg>
                                    </button>
                                @endcan
                                @can('autos.eliminar')
                                    <button type="button"
                                            wire:click="eliminarMarca({{ $marca->id }})"
                                            wire:confirm="¿Eliminar la marca {{ $marca->nombre }}? También se eliminarán sus modelos (si no tienen autos)."
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                            title="Eliminar">
                                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                @endcan
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════════════
             Panel derecho — MODELOS
        ════════════════════════════════════════════════════ --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">

            {{-- Cabecera panel --}}
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 bg-slate-50">
                <div>
                    <h2 class="text-sm font-semibold text-slate-800">
                        Modelos
                        @if($marcaActual)
                            <span class="text-indigo-600">— {{ $marcaActual->nombre }}</span>
                        @endif
                    </h2>
                    @if(!$marcaActual)
                        <p class="text-xs text-slate-400 mt-0.5">Selecciona una marca para ver sus modelos.</p>
                    @endif
                </div>
                @if($marcaActual)
                    @can('autos.editar')
                    @if(!$mostrarFormModelo)
                        <button type="button" wire:click="nuevoModelo"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition-colors">
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd"/>
                            </svg>
                            Nuevo modelo
                        </button>
                    @endif
                    @endcan
                @endif
            </div>

            {{-- Formulario nuevo/editar modelo --}}
            @can('autos.editar')
            @if($marcaActual && $mostrarFormModelo)
                <div class="px-4 py-3 border-b border-indigo-100 bg-indigo-50/40">
                    <p class="text-xs font-medium text-indigo-700 mb-2">
                        {{ $modeloEditandoId ? 'Editar modelo' : 'Nuevo modelo para ' . $marcaActual->nombre }}
                    </p>
                    <form wire:submit="guardarModelo" class="flex gap-2">
                        <div class="flex-1">
                            <input type="text" wire:model="modeloNombre"
                                   placeholder="Nombre del modelo"
                                   autofocus
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('modeloNombre')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                                wire:loading.attr="disabled" wire:target="guardarModelo"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 disabled:opacity-50 transition-colors shrink-0">
                            <svg wire:loading.remove wire:target="guardarModelo" class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd"/>
                            </svg>
                            <svg wire:loading wire:target="guardarModelo" class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Guardar
                        </button>
                        <button type="button" wire:click="cancelarModelo"
                                class="px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors shrink-0">
                            Cancelar
                        </button>
                    </form>
                </div>
            @endif
            @endcan

            {{-- Estado vacío: sin marca seleccionada --}}
            @if(!$marcaActual)
                <div class="flex flex-col items-center justify-center py-16 text-center px-6 flex-1">
                    <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                        <svg class="h-6 w-6 text-slate-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-400">Selecciona una marca</p>
                    <p class="text-xs text-slate-400 mt-1">Haz clic en una marca del panel izquierdo para ver y gestionar sus modelos.</p>
                </div>

            {{-- Lista de modelos --}}
            @elseif($modelos->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center px-6 flex-1">
                    <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                        <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-slate-500">No hay modelos para esta marca.</p>
                    @can('autos.editar')
                    <p class="text-xs text-slate-400 mt-1">Usa el botón <strong>Nuevo modelo</strong> para agregar el primero.</p>
                    @endcan
                </div>
            @else
                <ul class="divide-y divide-slate-100 flex-1 overflow-y-auto">
                    @foreach($modelos as $modelo)
                        <li wire:key="modelo-{{ $modelo->id }}"
                            class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50/60 transition-colors">

                            {{-- Icono --}}
                            <div class="h-8 w-8 rounded-lg {{ $modelo->activo ? 'bg-slate-100' : 'bg-red-50' }} flex items-center justify-center shrink-0">
                                <svg class="h-4 w-4 {{ $modelo->activo ? 'text-slate-500' : 'text-red-400' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 00-.673-.05A3 3 0 0015 1.5h-1.5a3 3 0 00-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6zM13.5 3A1.5 1.5 0 0012 4.5h4.5A1.5 1.5 0 0015 3h-1.5z" clip-rule="evenodd"/>
                                    <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625V9.375zm9.586 4.594a.75.75 0 00-1.172-.938l-2.476 3.096-.908-.907a.75.75 0 00-1.06 1.06l1.5 1.5a.75.75 0 001.116-.062l3-3.75z" clip-rule="evenodd"/>
                                </svg>
                            </div>

                            {{-- Nombre y contadores --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate">{{ $modelo->nombre }}</p>
                                <p class="text-xs text-slate-400">
                                    {{ $modelo->autos_count }} {{ Str::plural('auto', $modelo->autos_count) }}
                                </p>
                            </div>

                            {{-- Estado badge --}}
                            @if(!$modelo->activo)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700 shrink-0">
                                    Inactivo
                                </span>
                            @endif

                            {{-- Acciones --}}
                            <div class="flex items-center gap-1 shrink-0">
                                @can('autos.editar')
                                    <button type="button"
                                            wire:click="toggleActivoModelo({{ $modelo->id }})"
                                            title="{{ $modelo->activo ? 'Desactivar' : 'Activar' }}"
                                            class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none {{ $modelo->activo ? 'bg-indigo-600' : 'bg-slate-200' }}">
                                        <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 {{ $modelo->activo ? 'translate-x-4' : 'translate-x-0' }}"></span>
                                    </button>
                                    <button type="button"
                                            wire:click="editarModelo({{ $modelo->id }})"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                            title="Editar">
                                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z"/>
                                            <path d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z"/>
                                        </svg>
                                    </button>
                                @endcan
                                @can('autos.eliminar')
                                    <button type="button"
                                            wire:click="eliminarModelo({{ $modelo->id }})"
                                            wire:confirm="¿Eliminar el modelo {{ $modelo->nombre }}?"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                            title="Eliminar">
                                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                @endcan
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>

</div>
