<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black tracking-tight">Nuevo auto</h1>
            <p class="text-sm text-gray-500 mt-1">
                Captura una nueva unidad para el inventario con información general, precios y galería.
            </p>
        </div>

        <a href="{{ route('admin.autos.index') }}"
            class="inline-flex items-center justify-center px-4 py-2.5 rounded-2xl border border-gray-300 bg-white text-sm font-semibold hover:bg-gray-50 transition">
            Volver al listado
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Columna principal --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Datos generales --}}
            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50/80">
                    <h2 class="text-lg font-black">Datos generales</h2>
                    <p class="text-sm text-gray-500">Información básica de la unidad.</p>
                </div>

                <div class="p-5 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Marca *</label>
                            <select wire:model.live="marca_auto_id"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                                <option value="">Seleccione una marca</option>
                                @foreach($this->marcas as $marca)
                                <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                                @endforeach
                            </select>
                            @error('marca_auto_id')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Modelo *</label>
                            <select wire:model="modelo_auto_id"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                                <option value="">Seleccione un modelo</option>
                                @foreach($this->modelos as $modelo)
                                <option value="{{ $modelo->id }}">{{ $modelo->nombre }}</option>
                                @endforeach
                            </select>
                            @error('modelo_auto_id')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Código inventario</label>
                            <input type="text" wire:model="codigo_inventario"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black"
                                placeholder="Ej. INV-00125">
                            @error('codigo_inventario')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">VIN</label>
                            <input type="text" wire:model="vin"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black"
                                placeholder="Número de serie">
                            @error('vin')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Placa</label>
                            <input type="text" wire:model="placa"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black"
                                placeholder="Ej. ABC-123-X">
                            @error('placa')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Especificaciones --}}
            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50/80">
                    <h2 class="text-lg font-black">Especificaciones</h2>
                    <p class="text-sm text-gray-500">Datos técnicos y de presentación.</p>
                </div>

                <div class="p-5 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Año *</label>
                            <input type="number" wire:model="anio"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black"
                                placeholder="2024">
                            @error('anio')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Versión</label>
                            <input type="text" wire:model="version"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black"
                                placeholder="Ej. Sport">
                            @error('version')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Color</label>
                            <input type="text" wire:model="color"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black"
                                placeholder="Blanco">
                            @error('color')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Kilometraje</label>
                            <input type="number" wire:model="kilometraje"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black"
                                placeholder="0">
                            @error('kilometraje')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">
                                Transmisión
                            </label>

                            <select wire:model="transmision"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                                <option value="">Selecciona una opción</option>
                                <option value="automatica">Automática</option>
                                <option value="manual">Manual</option>
                                <option value="cvt">CVT</option>
                            </select>

                            @error('transmision')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">
                                Tipo combustible
                            </label>

                            <select wire:model="tipo_combustible"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                                <option value="">Selecciona una opción</option>
                                <option value="gasolina">Gasolina</option>
                                <option value="diesel">Diésel</option>
                                <option value="hibrido">Híbrido</option>
                                <option value="electrico">Eléctrico</option>
                            </select>

                            @error('tipo_combustible')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Precios y estado --}}
            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50/80">
                    <h2 class="text-lg font-black">Precios y estado</h2>
                    <p class="text-sm text-gray-500">Define el valor comercial y disponibilidad.</p>
                </div>

                <div class="p-5 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Precio contado *</label>
                            <input type="number" step="0.01" wire:model="precio_contado"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black"
                                placeholder="0.00">
                            @error('precio_contado')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Precio financiado</label>
                            <input type="number" step="0.01" wire:model="precio_financiado"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black"
                                placeholder="0.00">
                            @error('precio_financiado')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Estatus *</label>
                            <select wire:model="estatus"
                                class="w-full mt-1.5 rounded-2xl border-gray-300 focus:border-black focus:ring-black">
                                <option value="disponible">Disponible</option>
                                <option value="apartado">Apartado</option>
                                <option value="vendido_contado">Vendido contado</option>
                                <option value="financiado">Financiado</option>
                                <option value="liquidado">Liquidado</option>
                                <option value="recuperado">Recuperado</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                            @error('estatus')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 rounded-2xl border p-4 cursor-pointer hover:bg-gray-50">
                            <input type="checkbox" wire:model="destacado" class="rounded border-gray-300 text-black focus:ring-black">
                            <div>
                                <div class="font-semibold">Marcar como destacado</div>
                                <div class="text-sm text-gray-500">Se puede usar para resaltar en listados o portada.</div>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 rounded-2xl border p-4 cursor-pointer hover:bg-gray-50">
                            <input type="checkbox" wire:model="activo" class="rounded border-gray-300 text-black focus:ring-black">
                            <div>
                                <div class="font-semibold">Auto activo</div>
                                <div class="text-sm text-gray-500">Disponible para mostrarse en el sistema.</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Descripción --}}
            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50/80">
                    <h2 class="text-lg font-black">Descripción</h2>
                    <p class="text-sm text-gray-500">Agrega observaciones, detalles o equipo adicional.</p>
                </div>

                <div class="p-5">
                    <textarea wire:model="descripcion" rows="5"
                        class="w-full rounded-2xl border-gray-300 focus:border-black focus:ring-black"
                        placeholder="Describe el estado del auto, equipamiento, detalles interiores, extras, etc."></textarea>
                    @error('descripcion')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Galería --}}
            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50/80">
                    <h2 class="text-lg font-black">Galería</h2>
                    <p class="text-sm text-gray-500">Puedes subir varias imágenes y elegir portada.</p>
                </div>

                <div class="p-5 space-y-4">
                    <div class="rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 p-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Seleccionar imágenes
                        </label>

                        <input type="file"
                            wire:model="imagenes"
                            multiple
                            accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full text-sm text-gray-700 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-black file:text-white file:font-semibold hover:file:bg-gray-800">

                        <p class="text-xs text-gray-500 mt-2">
                            Formatos: JPG, JPEG, PNG, WEBP. Máximo 4 MB por imagen.
                        </p>
                    </div>

                    @error('imagenes')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror

                    @error('imagenes.*')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror

                    <div wire:loading wire:target="imagenes" class="text-sm text-gray-500">
                        Cargando imágenes...
                    </div>

                    @if(!empty($imagenes))
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-gray-800">
                                Vista previa ({{ count($imagenes) }})
                            </h3>

                            @if($portadaIndex !== null && isset($imagenes[$portadaIndex]))
                            <span class="text-xs px-2.5 py-1 rounded-full bg-black text-white font-semibold">
                                Portada seleccionada
                            </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($imagenes as $index => $img)
                            @php
                            $extension = strtolower($img->getClientOriginalExtension());
                            $previewables = ['jpg', 'jpeg', 'png', 'webp'];
                            @endphp

                            <div wire:key="preview-{{ $index }}"
                                class="rounded-2xl border overflow-hidden bg-white shadow-sm">
                                <div class="aspect-[4/3] bg-gray-100">
                                    @if(in_array($extension, $previewables))
                                    <img src="{{ $img->temporaryUrl() }}"
                                        alt="Preview {{ $index + 1 }}"
                                        class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 text-center p-4">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-700">Sin vista previa</div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                El archivo .{{ $extension }} no puede previsualizarse en Livewire.
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <div class="p-3 space-y-3">
                                    <div class="flex items-center justify-between gap-2">
                                        <div>
                                            <div class="font-semibold text-sm">Imagen {{ $index + 1 }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ method_exists($img, 'getClientOriginalName') ? $img->getClientOriginalName() : 'Imagen subida' }}
                                            </div>
                                        </div>

                                        @if((int) $portadaIndex === (int) $index)
                                        <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 font-bold">
                                            Portada
                                        </span>
                                        @endif
                                    </div>

                                    <div class="flex gap-2">
                                        <button type="button"
                                            wire:click="seleccionarPortada({{ $index }})"
                                            class="flex-1 px-3 py-2 rounded-xl border text-sm font-semibold hover:bg-gray-50">
                                            {{ (int) $portadaIndex === (int) $index ? 'Portada actual' : 'Usar como portada' }}
                                        </button>

                                        <button type="button"
                                            wire:click="quitarImagen({{ $index }})"
                                            class="px-3 py-2 rounded-xl bg-red-50 text-red-700 text-sm font-semibold hover:bg-red-100">
                                            Quitar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="rounded-2xl border bg-gray-50 p-6 text-center text-sm text-gray-500">
                        Aún no has cargado imágenes.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Resumen --}}
            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b bg-gray-50/80">
                    <h2 class="text-lg font-black">Resumen rápido</h2>
                </div>

                <div class="p-5 space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Marca / Modelo</span>
                        <span class="font-semibold text-right">
                            {{ optional($this->marcas->firstWhere('id', $marca_auto_id))->nombre ?? '—' }}
                            /
                            {{ optional($this->modelos->firstWhere('id', $modelo_auto_id))->nombre ?? '—' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Año</span>
                        <span class="font-semibold">{{ $anio ?: '—' }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Precio contado</span>
                        <span class="font-semibold">
                            {{ is_numeric($precio_contado) ? '$' . number_format((float)$precio_contado, 2) : '—' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Precio financiado</span>
                        <span class="font-semibold">
                            {{ is_numeric($precio_financiado) ? '$' . number_format((float)$precio_financiado, 2) : '—' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Estatus</span>
                        <span class="font-semibold capitalize">{{ str_replace('_', ' ', $estatus ?: '—') }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Imágenes</span>
                        <span class="font-semibold">{{ is_array($imagenes) ? count($imagenes) : 0 }}</span>
                    </div>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="bg-white border border-gray-200 rounded-3xl shadow-sm p-5">
                <div class="flex flex-col gap-3">
                    <button wire:click="guardar"
                        wire:loading.attr="disabled"
                        class="w-full inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-black text-white font-bold hover:bg-gray-800 transition disabled:opacity-60">
                        <span wire:loading.remove wire:target="guardar">Guardar auto</span>
                        <span wire:loading wire:target="guardar">Guardando...</span>
                    </button>

                    <a href="{{ route('admin.autos.index') }}"
                        class="w-full inline-flex items-center justify-center px-5 py-3 rounded-2xl border font-semibold hover:bg-gray-50 transition">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>