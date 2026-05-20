<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black">Clientes</h1>
            <p class="text-gray-500">Administra la información y documentos de tus clientes.</p>
        </div>

        <a href="{{ route('admin.clientes.create') }}"
           class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-black text-white font-bold shadow-sm hover:opacity-90 transition">
            + Nuevo cliente
        </a>
    </div>

    @if (session()->has('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-700 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-2xl border bg-white p-5 shadow-sm">
            <div class="text-sm text-gray-500 font-medium">Total clientes</div>
            <div class="text-3xl font-black mt-2">{{ number_format($this->totalClientes) }}</div>
        </div>

        <div class="rounded-2xl border bg-white p-5 shadow-sm">
            <div class="text-sm text-gray-500 font-medium">Activos</div>
            <div class="text-3xl font-black mt-2 text-emerald-600">{{ number_format($this->totalActivos) }}</div>
        </div>

        <div class="rounded-2xl border bg-white p-5 shadow-sm">
            <div class="text-sm text-gray-500 font-medium">Inactivos</div>
            <div class="text-3xl font-black mt-2 text-red-600">{{ number_format($this->totalInactivos) }}</div>
        </div>
    </div>

    <div class="bg-white border rounded-2xl p-5 shadow-sm space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Buscar</label>
                <input type="text"
                       wire:model.live.debounce.400ms="busqueda"
                       placeholder="Nombre, teléfono, correo, CURP, RFC..."
                       class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black">
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
                    <option value="nombre_asc">Nombre A-Z</option>
                    <option value="nombre_desc">Nombre Z-A</option>
                </select>
            </div>

            <div class="md:col-span-2 flex flex-wrap items-end gap-2">
                <button wire:click="limpiarFiltros"
                        type="button"
                        class="px-4 py-2 rounded-2xl border font-semibold text-sm hover:bg-gray-50">
                    Limpiar filtros
                </button>

                <div class="text-sm text-gray-500">
                    Mostrando {{ $clientes->total() }} registro(s)
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
        @if ($clientes->count())
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr class="text-left text-gray-500">
                            <th class="px-4 py-3 font-bold">Cliente</th>
                            <th class="px-4 py-3 font-bold">Contacto</th>
                            <th class="px-4 py-3 font-bold">Ubicación</th>
                            <th class="px-4 py-3 font-bold">Perfil</th>
                            <th class="px-4 py-3 font-bold">Documentos</th>
                            <th class="px-4 py-3 font-bold">Estado</th>
                            <th class="px-4 py-3 font-bold text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @foreach ($clientes as $cliente)
                            <tr class="align-top">
                                <td class="px-4 py-4 min-w-[230px]">
                                    <div class="font-black text-gray-900">
                                        {{ $cliente->nombre_completo }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        ID: {{ $cliente->id }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        CURP: {{ $cliente->curp ?: '—' }}
                                    </div>
                                </td>

                                <td class="px-4 py-4 min-w-[220px]">
                                    <div class="space-y-1">
                                        <div><span class="font-semibold text-gray-700">Tel:</span> {{ $cliente->telefono ?: '—' }}</div>
                                        <div><span class="font-semibold text-gray-700">Correo:</span> {{ $cliente->correo ?: '—' }}</div>
                                        <div><span class="font-semibold text-gray-700">RFC:</span> {{ $cliente->rfc ?: '—' }}</div>
                                    </div>
                                </td>

                                <td class="px-4 py-4 min-w-[220px]">
                                    <div class="space-y-1">
                                        <div><span class="font-semibold text-gray-700">Ciudad:</span> {{ $cliente->ciudad ?: '—' }}</div>
                                        <div><span class="font-semibold text-gray-700">Estado:</span> {{ $cliente->estado ?: '—' }}</div>
                                        <div><span class="font-semibold text-gray-700">CP:</span> {{ $cliente->codigo_postal ?: '—' }}</div>
                                    </div>
                                </td>

                                <td class="px-4 py-4 min-w-[220px]">
                                    <div class="space-y-1">
                                        <div><span class="font-semibold text-gray-700">Ocupación:</span> {{ $cliente->ocupacion ?: '—' }}</div>
                                        <div>
                                            <span class="font-semibold text-gray-700">Ingreso:</span>
                                            {{ $cliente->ingreso_mensual !== null ? '$' . number_format((float) $cliente->ingreso_mensual, 2) : '—' }}
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4 min-w-[220px]">
                                    <div class="space-y-2">
                                        <div>
                                            @if($cliente->ruta_ine)
                                                <a href="{{ route('admin.clientes.archivo', [$cliente, 'ine']) }}"
                                                   target="_blank"
                                                   class="inline-flex px-3 py-1.5 rounded-xl border text-xs font-semibold hover:bg-gray-50">
                                                    Ver INE
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400">INE no cargado</span>
                                            @endif
                                        </div>

                                        <div>
                                            @if($cliente->ruta_comprobante_domicilio)
                                                <a href="{{ route('admin.clientes.archivo', [$cliente, 'comprobante']) }}"
                                                   target="_blank"
                                                   class="inline-flex px-3 py-1.5 rounded-xl border text-xs font-semibold hover:bg-gray-50">
                                                    Ver comprobante
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400">Comprobante no cargado</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4 min-w-[150px]">
                                    @if ($cliente->activo)
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-4 min-w-[240px]">
                                    <div class="flex justify-end gap-2 flex-wrap">
                                        <button type="button"
                                                wire:click="toggleActivo({{ $cliente->id }})"
                                                class="px-3 py-2 rounded-xl border text-sm font-semibold hover:bg-gray-50">
                                            {{ $cliente->activo ? 'Desactivar' : 'Activar' }}
                                        </button>

                                        <a href="{{ route('admin.clientes.edit', $cliente) }}"
                                           class="px-3 py-2 rounded-xl bg-black text-white text-sm font-semibold hover:opacity-90">
                                            Editar
                                        </a>

                                        <button type="button"
                                                wire:click="eliminar({{ $cliente->id }})"
                                                wire:confirm="¿Seguro que deseas eliminar este cliente?"
                                                class="px-3 py-2 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t bg-gray-50">
                {{ $clientes->links() }}
            </div>
        @else
            <div class="p-10 text-center">
                <div class="text-lg font-bold text-gray-800">No hay clientes registrados</div>
                <p class="text-sm text-gray-500 mt-1">Empieza creando tu primer cliente.</p>

                <a href="{{ route('admin.clientes.create') }}"
                   class="inline-flex mt-4 px-5 py-3 rounded-2xl bg-black text-white font-bold">
                    + Nuevo cliente
                </a>
            </div>
        @endif
    </div>
</div>