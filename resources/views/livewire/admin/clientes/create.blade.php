<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Nuevo cliente</h1>
            <p class="text-sm text-slate-500 mt-0.5">Captura información general y documentos privados del cliente.</p>
        </div>
        <a href="{{ route('admin.clientes.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shrink-0">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 010 1.06l-6.22 6.22H21a.75.75 0 010 1.5H4.81l6.22 6.22a.75.75 0 11-1.06 1.06l-7.5-7.5a.75.75 0 010-1.06l7.5-7.5a.75.75 0 011.06 0z" clip-rule="evenodd"/>
            </svg>
            Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <p class="font-medium mb-1.5">Hay errores por corregir:</p>
            <ul class="list-disc pl-5 space-y-0.5 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit="guardar" class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <div class="xl:col-span-2 space-y-5">

            {{-- Datos personales --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Datos personales</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Información básica del cliente.</p>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Nombre <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="nombre"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('nombre') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Apellido paterno</label>
                        <input type="text" wire:model="apellido_paterno"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('apellido_paterno') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Apellido materno</label>
                        <input type="text" wire:model="apellido_materno"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('apellido_materno') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Contacto --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Contacto</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Medios para localizar al cliente.</p>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Teléfono</label>
                        <input type="text" wire:model="telefono"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('telefono') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Correo electrónico</label>
                        <input type="email" wire:model="correo"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('correo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Identificación --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Identificación</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Datos fiscales y de identificación.</p>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">CURP</label>
                        <input type="text" wire:model="curp" maxlength="18"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase">
                        @error('curp') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">RFC</label>
                        <input type="text" wire:model="rfc" maxlength="13"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase">
                        @error('rfc') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Domicilio --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Domicilio</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Ubicación del cliente.</p>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Dirección</label>
                        <textarea wire:model="direccion" rows="3"
                                  class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 resize-none"></textarea>
                        @error('direccion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Ciudad</label>
                            <input type="text" wire:model="ciudad"
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('ciudad') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Estado</label>
                            <input type="text" wire:model="estado"
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('estado') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Código postal</label>
                            <input type="text" wire:model="codigo_postal" maxlength="5"
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('codigo_postal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Perfil económico --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Perfil económico</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Información complementaria para evaluación.</p>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Ocupación</label>
                        <input type="text" wire:model="ocupacion"
                               class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('ocupacion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Ingreso mensual</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                            <input type="number" step="0.01" min="0" wire:model="ingreso_mensual"
                                   placeholder="0.00"
                                   class="block w-full rounded-lg border-slate-300 pl-6 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                        </div>
                        @error('ingreso_mensual') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Documentos privados --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Documentos privados</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Los archivos se almacenan en zona privada. JPG, PNG, WEBP, PDF · Máx. 5 MB.</p>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-lg border border-slate-200 p-4 space-y-3">
                        <label class="block text-xs font-medium text-slate-700">INE</label>
                        <input type="file" wire:model="ine" accept=".jpg,.jpeg,.png,.webp,.pdf"
                               class="block w-full text-sm text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                        <div wire:loading wire:target="ine" class="text-xs text-slate-500">Cargando INE...</div>
                        @error('ine') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="rounded-lg border border-slate-200 p-4 space-y-3">
                        <label class="block text-xs font-medium text-slate-700">Comprobante de domicilio</label>
                        <input type="file" wire:model="comprobante_domicilio" accept=".jpg,.jpeg,.png,.webp,.pdf"
                               class="block w-full text-sm text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                        <div wire:loading wire:target="comprobante_domicilio" class="text-xs text-slate-500">Cargando comprobante...</div>
                        @error('comprobante_domicilio') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-5 xl:sticky xl:top-[4.5rem] xl:self-start">

            {{-- Estado --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Estado del cliente</h2>
                </div>
                <div class="p-5">
                    <label class="flex items-center justify-between cursor-pointer gap-3">
                        <div>
                            <p class="text-sm font-medium text-slate-700">Cliente activo</p>
                            <p class="text-xs text-slate-500 mt-0.5">Puede ser asignado a contratos y apartados.</p>
                        </div>
                        <div class="relative shrink-0">
                            <input type="checkbox" wire:model="activo" class="sr-only peer">
                            <div class="w-10 h-6 rounded-full bg-slate-200 peer-checked:bg-indigo-600 transition-colors"></div>
                            <div class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white shadow transition-transform peer-checked:translate-x-4"></div>
                        </div>
                    </label>
                    @error('activo') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Acciones --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-3">
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="guardar"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <span wire:loading.remove wire:target="guardar" class="flex items-center gap-2">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd"/>
                        </svg>
                        Guardar cliente
                    </span>
                    <span wire:loading wire:target="guardar" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Guardando...
                    </span>
                </button>
                <a href="{{ route('admin.clientes.index') }}"
                   class="w-full inline-flex items-center justify-center px-5 py-2.5 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                    Cancelar
                </a>
            </div>

        </div>

    </form>
</div>
