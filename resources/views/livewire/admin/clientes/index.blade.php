<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Clientes</h1>
            <p class="text-sm text-slate-500 mt-0.5">Información y documentos de clientes.</p>
        </div>
        <a href="{{ route('admin.clientes.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition shrink-0">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
            </svg>
            Nuevo cliente
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
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total clientes</p>
            <p class="mt-2 text-2xl font-semibold text-slate-900 tabular-nums">{{ number_format($this->totalClientes) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Activos</p>
            <p class="mt-2 text-2xl font-semibold text-emerald-600 tabular-nums">{{ number_format($this->totalActivos) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Inactivos</p>
            <p class="mt-2 text-2xl font-semibold text-red-500 tabular-nums">{{ number_format($this->totalInactivos) }}</p>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Buscar</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.400ms="busqueda"
                        placeholder="Nombre, teléfono, correo, CURP, RFC..."
                        class="w-full pl-9 rounded-lg border-slate-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Estado</label>
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
                    <option value="nombre_asc">Nombre A-Z</option>
                    <option value="nombre_desc">Nombre Z-A</option>
                </select>
            </div>
            <button wire:click="limpiarFiltros" type="button"
                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
                Limpiar
            </button>
            <span class="text-xs text-slate-400 ml-auto">{{ $clientes->total() }} registro(s)</span>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        @if ($clientes->count())
            <div wire:loading.class="opacity-60 pointer-events-none">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/70">
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Contacto</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Docs</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($clientes as $cliente)
                            <tr class="hover:bg-slate-50/60 transition-colors align-middle">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
                                            <span class="text-xs font-semibold text-indigo-700">{{ strtoupper(substr($cliente->nombres ?? '?', 0, 1)) }}</span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-slate-900 truncate">{{ $cliente->nombre_completo }}</p>
                                            <p class="text-xs text-slate-400">ID {{ $cliente->id }}@if($cliente->curp) · ****{{ substr($cliente->curp, -4) }}@endif</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="space-y-0.5 text-xs text-slate-600">
                                        <p>{{ $cliente->telefono ?: '—' }}</p>
                                        <p class="text-slate-400 truncate max-w-[180px]">{{ $cliente->correo ?: '—' }}</p>
                                        @if($cliente->ciudad)
                                            <p class="text-slate-400">{{ $cliente->ciudad }}, {{ $cliente->estado }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        @if($cliente->ruta_ine)
                                            <a href="{{ route('admin.clientes.archivo', [$cliente, 'ine']) }}" target="_blank"
                                               title="Ver INE"
                                               class="inline-flex items-center gap-1 px-2 py-1 rounded-md border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                                                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z"/></svg>
                                                INE
                                            </a>
                                        @else
                                            <span class="inline-flex px-2 py-1 rounded-md border border-dashed border-slate-200 text-xs text-slate-300">INE</span>
                                        @endif
                                        @if($cliente->ruta_comprobante_domicilio)
                                            <a href="{{ route('admin.clientes.archivo', [$cliente, 'comprobante']) }}" target="_blank"
                                               title="Ver comprobante"
                                               class="inline-flex items-center gap-1 px-2 py-1 rounded-md border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                                                <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                                                Dom
                                            </a>
                                        @else
                                            <span class="inline-flex px-2 py-1 rounded-md border border-dashed border-slate-200 text-xs text-slate-300">Dom</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($cliente->activo)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-600 border border-red-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" wire:click="toggleActivo({{ $cliente->id }})"
                                            wire:loading.attr="disabled" wire:target="toggleActivo({{ $cliente->id }})"
                                            class="px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                                            {{ $cliente->activo ? 'Desactivar' : 'Activar' }}
                                        </button>
                                        <a href="{{ route('admin.clientes.edit', $cliente) }}"
                                            class="px-2.5 py-1.5 rounded-lg bg-indigo-600 text-xs font-medium text-white hover:bg-indigo-700 transition">
                                            Editar
                                        </a>
                                        <button type="button" wire:click="eliminar({{ $cliente->id }})"
                                            wire:confirm="¿Seguro que deseas eliminar este cliente? Esta acción no se puede deshacer."
                                            wire:loading.attr="disabled" wire:target="eliminar({{ $cliente->id }})"
                                            class="px-2.5 py-1.5 rounded-lg bg-red-600 text-xs font-medium text-white hover:bg-red-700 transition">
                                            ×
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/60">
                {{ $clientes->links() }}
            </div>
        @else
            <div class="py-16 text-center">
                <div class="mx-auto h-12 w-12 rounded-xl bg-slate-100 flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-900">No hay clientes registrados</p>
                <p class="text-xs text-slate-500 mt-1">Agrega el primer cliente para comenzar.</p>
                <a href="{{ route('admin.clientes.create') }}"
                    class="inline-flex items-center gap-2 mt-4 px-4 py-2 rounded-lg bg-indigo-600 text-sm font-medium text-white hover:bg-indigo-700 transition">
                    + Nuevo cliente
                </a>
            </div>
        @endif
    </div>
</div>
