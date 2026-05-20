<div class="max-w-6xl mx-auto p-4 sm:p-6 space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black">Editar cliente</h1>
            <p class="text-gray-500">Actualiza información general y documentos privados.</p>
        </div>

        <a href="{{ route('admin.clientes.index') }}"
           class="px-4 py-2 rounded-2xl border font-semibold hover:bg-gray-50">
            Volver
        </a>
    </div>

    @if (session()->has('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-700 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="actualizar" class="space-y-6">
        <div class="bg-white border rounded-3xl shadow-sm p-5 sm:p-6 space-y-6">

            <div>
                <h2 class="text-lg font-black">Datos personales</h2>
                <p class="text-sm text-gray-500">Información básica del cliente.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Nombre *</label>
                    <input type="text" wire:model="nombre"
                           class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    @error('nombre') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Apellido paterno</label>
                    <input type="text" wire:model="apellido_paterno"
                           class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    @error('apellido_paterno') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Apellido materno</label>
                    <input type="text" wire:model="apellido_materno"
                           class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    @error('apellido_materno') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <h2 class="text-lg font-black">Contacto</h2>
                <p class="text-sm text-gray-500">Medios para localizar al cliente.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Teléfono</label>
                    <input type="text" wire:model="telefono"
                           class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    @error('telefono') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Correo</label>
                    <input type="email" wire:model="correo"
                           class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    @error('correo') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <h2 class="text-lg font-black">Identificación</h2>
                <p class="text-sm text-gray-500">Datos fiscales y de identificación.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">CURP</label>
                    <input type="text" wire:model="curp"
                           class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    @error('curp') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">RFC</label>
                    <input type="text" wire:model="rfc"
                           class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    @error('rfc') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <h2 class="text-lg font-black">Domicilio</h2>
                <p class="text-sm text-gray-500">Ubicación del cliente.</p>
            </div>

            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Dirección</label>
                    <textarea wire:model="direccion" rows="3"
                              class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black"></textarea>
                    @error('direccion') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Ciudad</label>
                    <input type="text" wire:model="ciudad"
                           class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    @error('ciudad') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Estado</label>
                    <input type="text" wire:model="estado"
                           class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    @error('estado') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Código postal</label>
                    <input type="text" wire:model="codigo_postal"
                           class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    @error('codigo_postal') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <h2 class="text-lg font-black">Perfil económico</h2>
                <p class="text-sm text-gray-500">Información complementaria para evaluación.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Ocupación</label>
                    <input type="text" wire:model="ocupacion"
                           class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    @error('ocupacion') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Ingreso mensual</label>
                    <input type="number" step="0.01" min="0" wire:model="ingreso_mensual"
                           class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                    @error('ingreso_mensual') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>
            </div>

            <div>
                <h2 class="text-lg font-black">Documentos privados</h2>
                <p class="text-sm text-gray-500">Los archivos se almacenan de forma privada.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="rounded-2xl border p-4 space-y-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">INE</label>

                        @if($cliente->ruta_ine)
                            <div class="mt-2 flex flex-wrap gap-2">
                                <a href="{{ route('admin.clientes.archivo', [$cliente, 'ine']) }}"
                                   target="_blank"
                                   class="px-3 py-2 rounded-xl border text-sm font-semibold hover:bg-gray-50">
                                    Ver archivo actual
                                </a>

                                <button type="button"
                                        wire:click="eliminarArchivo('ine')"
                                        wire:confirm="¿Seguro que deseas eliminar el INE actual?"
                                        class="px-3 py-2 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700">
                                    Eliminar archivo
                                </button>
                            </div>
                        @else
                            <div class="text-sm text-gray-400 mt-2">No hay INE cargado.</div>
                        @endif
                    </div>

                    <div>
                        <input type="file"
                               wire:model="ine"
                               accept=".jpg,.jpeg,.png,.webp,.pdf"
                               class="block w-full text-sm text-gray-700 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-black file:text-white file:font-semibold hover:file:bg-gray-800">

                        <p class="text-xs text-gray-500 mt-2">Sube un nuevo archivo para reemplazar el actual.</p>

                        <div wire:loading wire:target="ine" class="text-sm text-gray-500 mt-2">
                            Cargando INE...
                        </div>

                        @error('ine') <div class="text-red-600 text-sm mt-2">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="rounded-2xl border p-4 space-y-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Comprobante de domicilio</label>

                        @if($cliente->ruta_comprobante_domicilio)
                            <div class="mt-2 flex flex-wrap gap-2">
                                <a href="{{ route('admin.clientes.archivo', [$cliente, 'comprobante']) }}"
                                   target="_blank"
                                   class="px-3 py-2 rounded-xl border text-sm font-semibold hover:bg-gray-50">
                                    Ver archivo actual
                                </a>

                                <button type="button"
                                        wire:click="eliminarArchivo('comprobante')"
                                        wire:confirm="¿Seguro que deseas eliminar el comprobante actual?"
                                        class="px-3 py-2 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700">
                                    Eliminar archivo
                                </button>
                            </div>
                        @else
                            <div class="text-sm text-gray-400 mt-2">No hay comprobante cargado.</div>
                        @endif
                    </div>

                    <div>
                        <input type="file"
                               wire:model="comprobante_domicilio"
                               accept=".jpg,.jpeg,.png,.webp,.pdf"
                               class="block w-full text-sm text-gray-700 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-black file:text-white file:font-semibold hover:file:bg-gray-800">

                        <p class="text-xs text-gray-500 mt-2">Sube un nuevo archivo para reemplazar el actual.</p>

                        <div wire:loading wire:target="comprobante_domicilio" class="text-sm text-gray-500 mt-2">
                            Cargando comprobante...
                        </div>

                        @error('comprobante_domicilio') <div class="text-red-600 text-sm mt-2">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <label class="inline-flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" wire:model="activo"
                           class="rounded border-gray-300 text-black focus:ring-black">
                    <span class="font-semibold text-gray-800">Cliente activo</span>
                </label>
                @error('activo') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-end gap-3">
            <a href="{{ route('admin.clientes.index') }}"
               class="px-5 py-3 rounded-2xl border font-bold text-center hover:bg-gray-50">
                Cancelar
            </a>

            <button type="submit"
                    wire:loading.attr="disabled"
                    wire:target="actualizar"
                    class="px-5 py-3 rounded-2xl bg-black text-white font-bold shadow-sm hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="actualizar">Guardar cambios</span>
                <span wire:loading wire:target="actualizar">Guardando...</span>
            </button>
        </div>
    </form>
</div>