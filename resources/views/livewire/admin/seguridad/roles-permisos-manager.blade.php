<div class="p-4 sm:p-6 space-y-6">

    <div>
        <h1 class="text-xl font-semibold text-slate-900">Seguridad</h1>
        <p class="text-sm text-slate-500 mt-0.5">Control de usuarios, roles y permisos del sistema.</p>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Panel: Roles --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-4">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Roles</h2>
                <p class="text-xs text-slate-500 mt-0.5">Crea y selecciona roles para editar permisos.</p>
            </div>

            <div class="flex gap-2">
                <input type="text" wire:model.defer="nuevoRol"
                       placeholder="Nombre del nuevo rol"
                       class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="button" wire:click="crearRol"
                        class="shrink-0 rounded-lg bg-indigo-600 text-white px-3 py-2 text-sm font-medium hover:bg-indigo-700 transition-colors">
                    Crear
                </button>
            </div>
            @error('nuevoRol')
                <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror

            <div class="space-y-2">
                @foreach ($roles as $rol)
                    <div class="flex items-center justify-between rounded-lg border px-3 py-2.5 transition-colors
                        {{ $rolSeleccionadoId === $rol->id ? 'border-indigo-300 bg-indigo-50' : 'border-slate-200 hover:bg-slate-50' }}">
                        <button type="button" wire:click="seleccionarRol({{ $rol->id }})" class="text-left flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900">{{ $rol->name }}</p>
                            <p class="text-xs text-slate-400">{{ $rol->users_count }} usuario(s)</p>
                        </button>
                        @if ($rol->name !== 'administrador')
                            <button type="button"
                                    wire:click="eliminarRol({{ $rol->id }})"
                                    wire:confirm="¿Eliminar este rol?"
                                    class="text-xs text-red-500 hover:text-red-700 hover:underline transition-colors ml-2">
                                Eliminar
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Panel: Permisos del rol --}}
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">Permisos del rol</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Selecciona qué puede hacer el rol seleccionado.</p>
                </div>
                <button type="button" wire:click="guardarPermisosRol"
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 text-white px-4 py-2 text-sm font-medium hover:bg-indigo-700 transition-colors">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd"/>
                    </svg>
                    Guardar permisos
                </button>
            </div>

            @if ($rolSeleccionadoId)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($permisos as $grupo => $items)
                        <div class="rounded-lg border border-slate-200 p-4">
                            <h3 class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-3">{{ $grupo }}</h3>
                            <div class="space-y-2">
                                @foreach ($items as $permiso)
                                    <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                                        <input type="checkbox"
                                               wire:model="permisosSeleccionados"
                                               value="{{ $permiso->name }}"
                                               class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                               @disabled($roles->firstWhere('id', $rolSeleccionadoId)?->name === 'administrador')>
                                        <span class="text-xs">{{ $permiso->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($roles->firstWhere('id', $rolSeleccionadoId)?->name === 'administrador')
                    <div class="rounded-lg bg-amber-50 border border-amber-200 px-4 py-3 text-xs text-amber-800">
                        El rol <strong>administrador</strong> siempre tendrá todos los permisos del sistema.
                    </div>
                @endif
            @else
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                        <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-slate-500">Selecciona un rol para editar sus permisos.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Panel: Usuarios --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">Usuarios</h2>
                <p class="text-xs text-slate-500 mt-0.5">Asigna roles a cada usuario del sistema.</p>
            </div>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.400ms="buscarUsuario"
                       placeholder="Buscar usuario..."
                       class="block rounded-lg border-slate-300 pl-9 text-sm focus:border-indigo-500 focus:ring-indigo-500 w-full sm:w-72">
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            <div class="overflow-hidden rounded-lg border border-slate-200">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Usuario</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Roles</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($usuarios as $usuario)
                            <tr class="hover:bg-slate-50/60 transition-colors {{ $usuarioSeleccionadoId === $usuario->id ? 'bg-indigo-50/50' : '' }}">
                                <td class="px-4 py-3">
                                    <p class="text-sm font-medium text-slate-900">{{ $usuario->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $usuario->email }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse ($usuario->roles as $rol)
                                            <span class="inline-flex items-center rounded-full bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-xs text-indigo-700">{{ $rol->name }}</span>
                                        @empty
                                            <span class="text-xs text-slate-400">Sin rol</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button" wire:click="seleccionarUsuario({{ $usuario->id }})"
                                            class="text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:underline transition-colors">
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-sm text-slate-500">No hay usuarios.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="rounded-lg border border-slate-200 p-5">
                @if ($usuarioSeleccionadoId)
                    @php
                        $usuarioSeleccionado = $usuarios->firstWhere('id', $usuarioSeleccionadoId)
                            ?? \App\Models\User::find($usuarioSeleccionadoId);
                    @endphp
                    <div class="mb-4">
                        <h3 class="text-sm font-semibold text-slate-900">{{ $usuarioSeleccionado?->name }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $usuarioSeleccionado?->email }}</p>
                    </div>
                    <div class="space-y-2">
                        @foreach ($roles as $rol)
                            <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                                <input type="checkbox"
                                       wire:model="rolesUsuario"
                                       value="{{ $rol->name }}"
                                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <span>{{ $rol->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <button type="button" wire:click="guardarRolesUsuario"
                            class="mt-5 inline-flex items-center gap-2 rounded-lg bg-indigo-600 text-white px-4 py-2 text-sm font-medium hover:bg-indigo-700 transition-colors">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd"/>
                        </svg>
                        Guardar roles del usuario
                    </button>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                            <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500">Selecciona un usuario para asignarle roles.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
