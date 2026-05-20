<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900">Inventario de autos</h1>
            <p class="text-sm text-gray-500 mt-1">
                Mantenimiento interno del inventario de unidades.
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.autos.create') }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-black text-white font-semibold hover:bg-gray-800 transition">
                + Nuevo auto
            </a>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="p-4 rounded-2xl bg-green-50 border border-green-200 text-green-700 text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    {{-- KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white border rounded-2xl shadow-sm p-4">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total autos</div>
            <div class="mt-2 text-2xl font-black text-gray-900">{{ number_format($this->totalAutos) }}</div>
        </div>

        <div class="bg-white border rounded-2xl shadow-sm p-4">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Activos</div>
            <div class="mt-2 text-2xl font-black text-emerald-700">{{ number_format($this->totalActivos) }}</div>
        </div>

        <div class="bg-white border rounded-2xl shadow-sm p-4">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Disponibles</div>
            <div class="mt-2 text-2xl font-black text-blue-700">{{ number_format($this->totalDisponibles) }}</div>
        </div>

        <div class="bg-white border rounded-2xl shadow-sm p-4">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Destacados</div>
            <div class="mt-2 text-2xl font-black text-gray-900">{{ number_format($this->totalDestacados) }}</div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white border rounded-2xl shadow-sm p-4 sm:p-5 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4">
            <div class="xl:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Buscar</label>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="busqueda"
                    placeholder="Marca, modelo, año, VIN, placa, color..."
                    class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black"
                >
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Marca</label>
                <select wire:model.live="marcaId"
                        class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    <option value="">Todas</option>
                    @foreach ($marcas as $marca)
                        <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Estatus</label>
                <select wire:model.live="estatus"
                        class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black">
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
                <label class="block text-xs font-semibold text-gray-500 mb-1">Destacado</label>
                <select wire:model.live="destacado"
                        class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    <option value="">Todos</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Activo</label>
                <select wire:model.live="activo"
                        class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    <option value="">Todos</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Ordenar por</label>
                <select wire:model.live="orden"
                        class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    <option value="recientes">Más recientes</option>
                    <option value="antiguos">Más antiguos</option>
                    <option value="precio_menor">Menor precio</option>
                    <option value="precio_mayor">Mayor precio</option>
                    <option value="anio_nuevo">Año más nuevo</option>
                    <option value="anio_viejo">Año más viejo</option>
                </select>
            </div>

            <div class="md:col-span-2 flex flex-wrap items-end gap-2">
                <button wire:click="limpiarFiltros"
                        type="button"
                        class="px-4 py-2 rounded-2xl border font-semibold text-sm hover:bg-gray-50">
                    Limpiar filtros
                </button>

                <div class="text-sm text-gray-500">
                    Mostrando {{ $autos->total() }} registro(s)
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
        @if ($autos->count())
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr class="text-left text-gray-500">
                            <th class="px-4 py-3 font-bold">Unidad</th>
                            <th class="px-4 py-3 font-bold">Datos</th>
                            <th class="px-4 py-3 font-bold">Precios</th>
                            <th class="px-4 py-3 font-bold">Estado</th>
                            <th class="px-4 py-3 font-bold">Imágenes</th>
                            <th class="px-4 py-3 font-bold text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @foreach ($autos as $auto)
                            @php
                                $portada = $auto->imagenes->firstWhere('es_portada', true) ?? $auto->imagenes->first();

                                $colores = [
                                    'disponible' => 'bg-green-100 text-green-700',
                                    'apartado' => 'bg-yellow-100 text-yellow-700',
                                    'vendido_contado' => 'bg-blue-100 text-blue-700',
                                    'financiado' => 'bg-purple-100 text-purple-700',
                                    'liquidado' => 'bg-emerald-100 text-emerald-700',
                                    'recuperado' => 'bg-orange-100 text-orange-700',
                                    'inactivo' => 'bg-gray-200 text-gray-700',
                                ];
                            @endphp

                            <tr class="align-top">
                                {{-- Unidad --}}
                                <td class="px-4 py-4 min-w-[260px]">
                                    <div class="flex gap-3">
                                        <div class="w-24 h-16 rounded-xl overflow-hidden border bg-gray-100 shrink-0 flex items-center justify-center">
                                            @if ($portada && $portada->url)
                                                <img src="{{ $portada->url }}"
                                                     alt="{{ $auto->marca?->nombre }} {{ $auto->modelo?->nombre }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <span class="text-[11px] text-gray-400 font-semibold">Sin imagen</span>
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <div class="font-black text-gray-900">
                                                {{ $auto->marca?->nombre }} {{ $auto->modelo?->nombre }}
                                            </div>
                                            <div class="text-gray-500">
                                                {{ $auto->anio }}{{ $auto->version ? ' · ' . $auto->version : '' }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                Código: {{ $auto->codigo_inventario ?: '—' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Placa: {{ $auto->placa ?: '—' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Datos --}}
                                <td class="px-4 py-4 min-w-[230px]">
                                    <div class="space-y-1">
                                        <div><span class="font-semibold text-gray-700">VIN:</span> {{ $auto->vin ?: '—' }}</div>
                                        <div><span class="font-semibold text-gray-700">Color:</span> {{ $auto->color ?: '—' }}</div>
                                        <div><span class="font-semibold text-gray-700">Km:</span> {{ number_format($auto->kilometraje) }}</div>
                                        <div><span class="font-semibold text-gray-700">Transmisión:</span> {{ $auto->transmision ?: '—' }}</div>
                                        <div><span class="font-semibold text-gray-700">Combustible:</span> {{ $auto->tipo_combustible ?: '—' }}</div>
                                    </div>
                                </td>

                                {{-- Precios --}}
                                <td class="px-4 py-4 min-w-[200px]">
                                    <div class="space-y-2">
                                        <div class="rounded-xl bg-gray-50 p-3">
                                            <div class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Contado</div>
                                            <div class="font-black text-gray-900">${{ number_format((float) $auto->precio_contado, 2) }}</div>
                                        </div>

                                        <div class="rounded-xl bg-gray-50 p-3">
                                            <div class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">Financiado</div>
                                            <div class="font-black text-gray-900">${{ number_format((float) $auto->precio_financiado, 2) }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Estado --}}
                                <td class="px-4 py-4 min-w-[180px]">
                                    <div class="flex flex-col gap-2">
                                        <span class="inline-flex w-fit px-2.5 py-1 rounded-full text-xs font-bold {{ $colores[$auto->estatus] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ str_replace('_', ' ', $auto->estatus) }}
                                        </span>

                                        @if ($auto->destacado)
                                            <span class="inline-flex w-fit px-2.5 py-1 rounded-full text-xs font-bold bg-black text-white">
                                                Destacado
                                            </span>
                                        @endif

                                        @if ($auto->activo)
                                            <span class="inline-flex w-fit px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex w-fit px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                                Inactivo
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Imágenes --}}
                                <td class="px-4 py-4 min-w-[150px]">
                                    <div class="space-y-2">
                                        <div class="font-semibold text-gray-900">
                                            {{ $auto->imagenes->count() }} imagen(es)
                                        </div>

                                        @if (!$auto->imagenes->count())
                                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                                Sin imágenes
                                            </span>
                                        @elseif (!$auto->imagenes->firstWhere('es_portada', true))
                                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                                                Sin portada
                                            </span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                                Con portada
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Acciones --}}
                                <td class="px-4 py-4 min-w-[220px]">
                                    <div class="flex flex-col gap-2 items-stretch md:items-end">
                                        <a href="{{ route('admin.autos.edit', $auto) }}"
                                           class="inline-flex justify-center items-center px-4 py-2 rounded-xl bg-black text-white font-semibold text-sm hover:bg-gray-800">
                                            Editar
                                        </a>

                                        <button wire:click="toggleActivo({{ $auto->id }})"
                                                type="button"
                                                class="inline-flex justify-center items-center px-4 py-2 rounded-xl border text-sm font-semibold hover:bg-gray-50">
                                            {{ $auto->activo ? 'Desactivar' : 'Activar' }}
                                        </button>

                                        <button wire:click="toggleDestacado({{ $auto->id }})"
                                                type="button"
                                                class="inline-flex justify-center items-center px-4 py-2 rounded-xl border text-sm font-semibold hover:bg-gray-50">
                                            {{ $auto->destacado ? 'Quitar destacado' : 'Marcar destacado' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            @if ($auto->descripcion)
                                <tr class="bg-gray-50/60">
                                    <td colspan="6" class="px-4 py-3 text-sm text-gray-600">
                                        <span class="font-semibold text-gray-700">Descripción:</span>
                                        {{ $auto->descripcion }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t bg-white">
                {{ $autos->links() }}
            </div>
        @else
            <div class="p-10 text-center">
                <h3 class="text-lg font-black text-gray-900">No hay autos registrados</h3>
                <p class="text-sm text-gray-500 mt-2">
                    Aún no se encontraron autos con los filtros seleccionados.
                </p>
            </div>
        @endif
    </div>
</div>