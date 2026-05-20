<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">Seguridad</h1>
            <p class="text-sm text-gray-500">
                Control de usuarios, roles y permisos del sistema.
            </p>
        </div>

        @if (session('success'))
            <div class="rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 space-y-4">
                <div>
                    <h2 class="font-bold text-gray-900">Roles</h2>
                    <p class="text-xs text-gray-500">Crea y selecciona roles.</p>
                </div>

                <div class="flex gap-2">
                    <input
                        type="text"
                        wire:model.defer="nuevoRol"
                        placeholder="Nuevo rol"
                        class="w-full rounded-xl border-gray-300 text-sm"
                    >

                    <button
                        type="button"
                        wire:click="crearRol"
                        class="rounded-xl bg-black text-white px-4 py-2 text-sm font-semibold">
                        Crear
                    </button>
                </div>

                @error('nuevoRol')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror

                <div class="space-y-2">
                    @foreach ($roles as $rol)
                        <div class="flex items-center justify-between rounded-xl border px-3 py-2
                            {{ $rolSeleccionadoId === $rol->id ? 'border-black bg-gray-50' : 'border-gray-200' }}">

                            <button
                                type="button"
                                wire:click="seleccionarRol({{ $rol->id }})"
                                class="text-left">
                                <p class="font-semibold text-sm text-gray-900">{{ $rol->name }}</p>
                                <p class="text-xs text-gray-500">{{ $rol->users_count }} usuario(s)</p>
                            </button>

                            @if ($rol->name !== 'administrador')
                                <button
                                    type="button"
                                    wire:click="eliminarRol({{ $rol->id }})"
                                    wire:confirm="¿Eliminar este rol?"
                                    class="text-xs text-red-600 hover:underline">
                                    Eliminar
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 p-5 space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-gray-900">Permisos del rol</h2>
                        <p class="text-xs text-gray-500">
                            Selecciona qué puede hacer el rol seleccionado.
                        </p>
                    </div>

                    <button
                        type="button"
                        wire:click="guardarPermisosRol"
                        class="rounded-xl bg-black text-white px-4 py-2 text-sm font-semibold">
                        Guardar permisos
                    </button>
                </div>

                @if ($rolSeleccionadoId)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($permisos as $grupo => $items)
                            <div class="rounded-2xl border border-gray-200 p-4">
                                <h3 class="font-bold text-sm text-gray-900 uppercase mb-3">
                                    {{ $grupo }}
                                </h3>

                                <div class="space-y-2">
                                    @foreach ($items as $permiso)
                                        <label class="flex items-center gap-2 text-sm text-gray-700">
                                            <input
                                                type="checkbox"
                                                wire:model="permisosSeleccionados"
                                                value="{{ $permiso->name }}"
                                                class="rounded border-gray-300 text-black focus:ring-black"
                                                @disabled($roles->firstWhere('id', $rolSeleccionadoId)?->name === 'administrador')
                                            >
                                            <span>{{ $permiso->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($roles->firstWhere('id', $rolSeleccionadoId)?->name === 'administrador')
                        <div class="rounded-xl bg-yellow-50 border border-yellow-200 px-4 py-3 text-sm text-yellow-800">
                            El rol administrador siempre tendrá todos los permisos.
                        </div>
                    @endif
                @else
                    <div class="text-sm text-gray-500">
                        Selecciona un rol para editar sus permisos.
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 space-y-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h2 class="font-bold text-gray-900">Usuarios</h2>
                    <p class="text-xs text-gray-500">
                        Asigna roles a cada usuario.
                    </p>
                </div>

                <input
                    type="text"
                    wire:model.live.debounce.400ms="buscarUsuario"
                    placeholder="Buscar usuario..."
                    class="rounded-xl border-gray-300 text-sm w-full md:w-72"
                >
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="overflow-hidden rounded-2xl border border-gray-200">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-4 py-3 text-left">Usuario</th>
                                <th class="px-4 py-3 text-left">Roles</th>
                                <th class="px-4 py-3 text-right">Acción</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($usuarios as $usuario)
                                <tr class="{{ $usuarioSeleccionadoId === $usuario->id ? 'bg-gray-50' : '' }}">
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-gray-900">{{ $usuario->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $usuario->email }}</p>
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse ($usuario->roles as $rol)
                                                <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-700">
                                                    {{ $rol->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400">Sin rol</span>
                                            @endforelse
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-right">
                                        <button
                                            type="button"
                                            wire:click="seleccionarUsuario({{ $usuario->id }})"
                                            class="text-sm font-semibold text-black hover:underline">
                                            Editar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                        No hay usuarios.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="rounded-2xl border border-gray-200 p-5">
                    @if ($usuarioSeleccionadoId)
                        @php
                            $usuarioSeleccionado = $usuarios->firstWhere('id', $usuarioSeleccionadoId)
                                ?? \App\Models\User::find($usuarioSeleccionadoId);
                        @endphp

                        <div class="mb-4">
                            <h3 class="font-bold text-gray-900">
                                Editar roles de {{ $usuarioSeleccionado?->name }}
                            </h3>
                            <p class="text-xs text-gray-500">
                                {{ $usuarioSeleccionado?->email }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            @foreach ($roles as $rol)
                                <label class="flex items-center gap-2 text-sm">
                                    <input
                                        type="checkbox"
                                        wire:model="rolesUsuario"
                                        value="{{ $rol->name }}"
                                        class="rounded border-gray-300 text-black focus:ring-black"
                                    >
                                    <span>{{ $rol->name }}</span>
                                </label>
                            @endforeach
                        </div>

                        <button
                            type="button"
                            wire:click="guardarRolesUsuario"
                            class="mt-5 rounded-xl bg-black text-white px-4 py-2 text-sm font-semibold">
                            Guardar roles del usuario
                        </button>
                    @else
                        <div class="text-sm text-gray-500">
                            Selecciona un usuario para asignarle roles.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div wire:loading.flex
            class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm items-center justify-center">
            <div class="bg-white rounded-2xl shadow-2xl px-6 py-5 flex flex-col items-center gap-3">
                <svg class="animate-spin h-8 w-8 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-80" fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                    </path>
                </svg>

                <div class="text-center">
                    <p class="font-bold text-sm">Guardando cambios...</p>
                    <p class="text-xs text-gray-500">Espera un momento.</p>
                </div>
            </div>
        </div>
    </div>
</div>