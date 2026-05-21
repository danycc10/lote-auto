<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Inventario de autos</h1>
            <p class="text-sm text-slate-500 mt-0.5">Gestión interna del inventario de unidades.</p>
        </div>
        <a href="{{ route('admin.autos.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition shrink-0">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
            </svg>
            Nuevo auto
        </a>
    </div>

    @if (session()->has('success'))
        <div class="flex items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            <svg class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- KPIs --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total</p>
            <p class="mt-2 text-2xl font-semibold text-slate-900 tabular-nums">{{ number_format($this->totalAutos) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Activos</p>
            <p class="mt-2 text-2xl font-semibold text-emerald-600 tabular-nums">{{ number_format($this->totalActivos) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Disponibles</p>
            <p class="mt-2 text-2xl font-semibold text-indigo-600 tabular-nums">{{ number_format($this->totalDisponibles) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Destacados</p>
            <p class="mt-2 text-2xl font-semibold text-amber-600 tabular-nums">{{ number_format($this->totalDestacados) }}</p>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4">
            <div class="xl:col-span-2">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Buscar</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.400ms="busqueda"
                        placeholder="Marca, modelo, año, VIN, placa..."
                        class="w-full pl-9 rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Marca</label>
                <select wire:model.live="marcaId"
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todas</option>
                    @foreach ($marcas as $marca)
                        <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Estatus</label>
                <select wire:model.live="estatus"
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todos</option>
                    <option value="disponible">Disponible</option>
                    <option value="apartado">Apartado</option>
                    <option value="vendido_contado">Vendido contado</option>
                    <option value="financiado">Financiado</option>
                    <option value="liquidado">Liquidado</option>
                    <option value="recuperado">Recuperado</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Destacado</label>
                <select wire:model.live="destacado"
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todos</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Activo</label>
                <select wire:model.live="activo"
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Todos</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="w-48">
                <select wire:model.live="orden"
                    class="w-full rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="recientes">Más recientes</option>
                    <option value="antiguos">Más antiguos</option>
                    <option value="precio_menor">Menor precio</option>
                    <option value="precio_mayor">Mayor precio</option>
                    <option value="anio_nuevo">Año más nuevo</option>
                    <option value="anio_viejo">Año más viejo</option>
                </select>
            </div>
            <button wire:click="limpiarFiltros" type="button"
                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
                Limpiar
            </button>
            <span class="text-xs text-slate-400 ml-auto">{{ $autos->total() }} registro(s)</span>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        @if ($autos->count())
            <div class="overflow-x-auto" wire:loading.class="opacity-60 pointer-events-none">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/70">
                            <th class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Unidad</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Datos técnicos</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Precios</th>
                            <th class="px-5 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                            <th class="px-5 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Imágenes</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($autos as $auto)
                            @php
                                $portada = $auto->imagenes->firstWhere('es_portada', true) ?? $auto->imagenes->first();
                                $statusColors = [
                                    'disponible'     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'apartado'       => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'vendido_contado'=> 'bg-blue-50 text-blue-700 border-blue-200',
                                    'financiado'     => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'liquidado'      => 'bg-sky-50 text-sky-700 border-sky-200',
                                    'recuperado'     => 'bg-orange-50 text-orange-700 border-orange-200',
                                    'inactivo'       => 'bg-slate-100 text-slate-500 border-slate-200',
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors align-top">
                                <td class="px-5 py-4 min-w-[260px]">
                                    <div class="flex gap-3">
                                        <div class="w-20 h-14 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 shrink-0 flex items-center justify-center">
                                            @if ($portada && $portada->url)
                                                <img src="{{ $portada->url }}"
                                                    alt="{{ $auto->marca?->nombre }} {{ $auto->modelo?->nombre }}"
                                                    class="w-full h-full object-cover"
                                                    loading="lazy" width="80" height="56">
                                            @else
                                                <svg class="h-5 w-5 text-slate-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-900">
                                                {{ $auto->marca?->nombre }} {{ $auto->modelo?->nombre }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                {{ $auto->anio }}{{ $auto->version ? ' · ' . $auto->version : '' }}
                                            </p>
                                            <p class="text-xs text-slate-400 mt-0.5">{{ $auto->codigo_inventario ?: 'Sin código' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 min-w-[200px]">
                                    <div class="space-y-0.5 text-xs text-slate-600">
                                        <p><span class="text-slate-400">VIN:</span> {{ $auto->vin ?: '—' }}</p>
                                        <p><span class="text-slate-400">Placa:</span> {{ $auto->placa ?: '—' }}</p>
                                        <p><span class="text-slate-400">Color:</span> {{ $auto->color ?: '—' }}</p>
                                        <p><span class="text-slate-400">Km:</span> {{ number_format($auto->kilometraje) }}</p>
                                        <p><span class="text-slate-400">Trans.:</span> {{ $auto->transmision ?: '—' }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-right min-w-[160px]">
                                    <div class="space-y-1">
                                        <div>
                                            <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Contado</p>
                                            <p class="font-semibold text-slate-900 tabular-nums">${{ number_format((float) $auto->precio_contado, 2) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Financiado</p>
                                            <p class="font-semibold text-slate-900 tabular-nums">${{ number_format((float) $auto->precio_financiado, 2) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center min-w-[140px]">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusColors[$auto->estatus] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                            {{ ucfirst(str_replace('_', ' ', $auto->estatus)) }}
                                        </span>
                                        @if ($auto->destacado)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-900 text-white">
                                                Destacado
                                            </span>
                                        @endif
                                        @if (!$auto->activo)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-600 border border-red-200">
                                                Inactivo
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-sm font-semibold text-slate-900">{{ $auto->imagenes->count() }}</span>
                                        @if (!$auto->imagenes->count())
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-50 text-red-600 border border-red-200">Sin imágenes</span>
                                        @elseif (!$auto->imagenes->firstWhere('es_portada', true))
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-50 text-amber-600 border border-amber-200">Sin portada</span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-600 border border-emerald-200">Con portada</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-right min-w-[200px]">
                                    <div class="flex flex-col gap-1.5 items-end">
                                        <a href="{{ route('admin.autos.edit', $auto) }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 text-xs font-medium text-white hover:bg-indigo-700 transition">
                                            Editar
                                        </a>
                                        <button wire:click="toggleActivo({{ $auto->id }})" type="button"
                                            wire:loading.attr="disabled" wire:target="toggleActivo({{ $auto->id }})"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                                            {{ $auto->activo ? 'Desactivar' : 'Activar' }}
                                        </button>
                                        <button wire:click="toggleDestacado({{ $auto->id }})" type="button"
                                            wire:loading.attr="disabled" wire:target="toggleDestacado({{ $auto->id }})"
                                            class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                                            {{ $auto->destacado ? 'Quitar destacado' : 'Destacar' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            @if ($auto->descripcion)
                                <tr class="bg-slate-50/40 border-b border-slate-100">
                                    <td colspan="6" class="px-5 py-2.5 text-xs text-slate-500">
                                        <span class="font-medium text-slate-600">Descripción:</span> {{ $auto->descripcion }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/60">
                {{ $autos->links() }}
            </div>
        @else
            <div class="py-16 text-center">
                <div class="mx-auto h-12 w-12 rounded-xl bg-slate-100 flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-900">No hay autos con estos filtros</p>
                <p class="text-xs text-slate-500 mt-1">Ajusta los filtros o agrega nuevas unidades.</p>
            </div>
        @endif
    </div>
</div>
