<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Usuarios</h1>
            <p class="mt-0.5 text-sm text-slate-500">Gestión de accesos al sistema</p>
        </div>
        @if($modo === '')
        <button wire:click="iniciarCrear" type="button"
            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
            </svg>
            Nuevo usuario
        </button>
        @endif
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 flex items-center gap-3">
        <svg class="h-5 w-5 text-emerald-500 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 flex items-center gap-3">
        <svg class="h-5 w-5 text-red-500 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Formulario inline --}}
    @if($modo !== '')
    <div class="rounded-xl border border-slate-200 shadow-sm bg-white">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 bg-slate-50/70 rounded-t-xl">
            <h2 class="text-sm font-semibold text-slate-800">
                {{ $modo === 'crear' ? 'Nuevo usuario' : 'Editar usuario' }}
            </h2>
            <button wire:click="cancelar" type="button"
                class="text-slate-400 hover:text-slate-600 transition">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                </svg>
            </button>
        </div>

        <form wire:submit="guardar" class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Nombre --}}
                <div>
                    <label for="name" class="block text-xs font-medium text-slate-700 mb-1">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="name" id="name" type="text" autocomplete="name"
                        class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-300 bg-red-50 @enderror"
                        placeholder="Nombre completo">
                    @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Correo --}}
                <div>
                    <label for="email" class="block text-xs font-medium text-slate-700 mb-1">
                        Correo electrónico <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="email" id="email" type="email" autocomplete="email"
                        class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-300 bg-red-50 @enderror"
                        placeholder="correo@ejemplo.com">
                    @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Contraseña --}}
                <div>
                    <label for="password" class="block text-xs font-medium text-slate-700 mb-1">
                        Contraseña
                        @if($modo === 'crear')
                        <span class="text-red-500">*</span>
                        @else
                        <span class="text-slate-400 font-normal">(dejar en blanco para no cambiar)</span>
                        @endif
                    </label>
                    <input wire:model="password" id="password" type="password" autocomplete="new-password"
                        class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-red-300 bg-red-50 @enderror"
                        placeholder="{{ $modo === 'crear' ? 'Mínimo 8 caracteres' : '••••••••' }}">
                    @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Rol --}}
                <div>
                    <label for="rol" class="block text-xs font-medium text-slate-700 mb-1">
                        Rol <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="rol" id="rol"
                        class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('rol') border-red-300 bg-red-50 @enderror">
                        <option value="">Seleccionar rol...</option>
                        @foreach($roles as $r)
                        <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                    @error('rol')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="mt-5 flex items-center gap-3 pt-4 border-t border-slate-100">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    <svg wire:loading wire:target="guardar" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="guardar">
                        {{ $modo === 'crear' ? 'Crear usuario' : 'Guardar cambios' }}
                    </span>
                    <span wire:loading wire:target="guardar">Guardando...</span>
                </button>
                <button wire:click="cancelar" type="button"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2 transition">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Filtros --}}
    <div class="rounded-xl border border-slate-200 shadow-sm bg-white">
        <div class="p-4 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/>
                </svg>
                <input wire:model.live.debounce.300ms="busqueda" type="search"
                    placeholder="Buscar por nombre o correo..."
                    class="block w-full rounded-lg border-slate-300 pl-9 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <select wire:model.live="filtroRol"
                class="rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:w-44">
                <option value="">Todos los roles</option>
                @foreach($roles as $r)
                <option value="{{ $r }}">{{ ucfirst($r) }}</option>
                @endforeach
            </select>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/70">
                    <tr>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Rol</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold text-slate-500 uppercase tracking-wider hidden sm:table-cell">Registro</th>
                        <th class="px-5 py-3 text-right text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($usuarios as $usuario)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
                                    <span class="text-xs font-semibold text-indigo-700">
                                        {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900 leading-tight">{{ $usuario->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $usuario->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            @php $rolNombre = $usuario->roles->first()?->name @endphp
                            @if($rolNombre)
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ match($rolNombre) {
                                    'administrador' => 'bg-indigo-100 text-indigo-800',
                                    'gerente'       => 'bg-violet-100 text-violet-800',
                                    'cobrador'      => 'bg-emerald-100 text-emerald-800',
                                    'vendedor'      => 'bg-amber-100 text-amber-800',
                                    'auditor'       => 'bg-slate-100 text-slate-700',
                                    default         => 'bg-slate-100 text-slate-600',
                                } }}">
                                {{ ucfirst($rolNombre) }}
                            </span>
                            @else
                            <span class="text-xs text-slate-400">Sin rol</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-sm text-slate-500 hidden sm:table-cell">
                            {{ $usuario->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="iniciarEditar({{ $usuario->id }})" type="button"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition">
                                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z"/>
                                        <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z"/>
                                    </svg>
                                    Editar
                                </button>
                                @if($usuario->id !== auth()->id())
                                <button wire:click="eliminar({{ $usuario->id }})"
                                    wire:confirm="¿Eliminar al usuario {{ $usuario->name }}? Esta acción no se puede deshacer."
                                    type="button"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 hover:border-red-300 transition">
                                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd"/>
                                    </svg>
                                    Eliminar
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center">
                            <svg class="mx-auto h-10 w-10 text-slate-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                            <p class="mt-3 text-sm font-medium text-slate-500">No se encontraron usuarios</p>
                            @if($busqueda || $filtroRol)
                            <p class="mt-1 text-xs text-slate-400">Prueba con otros filtros de búsqueda</p>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($usuarios->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">
            {{ $usuarios->links() }}
        </div>
        @endif
    </div>

</div>
