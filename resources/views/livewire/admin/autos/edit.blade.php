<div class="p-4 sm:p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Editar auto</h1>
            <p class="text-sm text-slate-500 mt-0.5">Actualiza la información de la unidad y administra su galería.</p>
        </div>
        <a href="{{ route('admin.autos.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shrink-0">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 010 1.06l-6.22 6.22H21a.75.75 0 010 1.5H4.81l6.22 6.22a.75.75 0 11-1.06 1.06l-7.5-7.5a.75.75 0 010-1.06l7.5-7.5a.75.75 0 011.06 0z" clip-rule="evenodd"/>
            </svg>
            Volver al listado
        </a>
    </div>

    @if(session()->has('success'))
        <div class="flex items-center gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            <svg class="h-4 w-4 text-emerald-500 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Columna principal --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Datos generales --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Datos generales</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Marca, modelo e identificadores de la unidad.</p>
                </div>
                <div class="p-5 space-y-4">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Marca <span class="text-red-500">*</span></label>
                            <select wire:model.live="marca_auto_id"
                                    class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecciona una marca</option>
                                @foreach($this->marcas as $marca)
                                    <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                                @endforeach
                            </select>
                            @error('marca_auto_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Modelo <span class="text-red-500">*</span></label>
                            <select wire:model="modelo_auto_id"
                                    class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecciona un modelo</option>
                                @foreach($this->modelos as $modelo)
                                    <option value="{{ $modelo->id }}">{{ $modelo->nombre }}</option>
                                @endforeach
                            </select>
                            @error('modelo_auto_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Código inventario</label>
                            <input type="text" wire:model="codigo_inventario"
                                   placeholder="Ej. INV-00125"
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('codigo_inventario') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">VIN / Número de serie</label>
                            <input type="text" wire:model="vin"
                                   placeholder="17 caracteres"
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase">
                            @error('vin') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Placa</label>
                            <input type="text" wire:model="placa"
                                   placeholder="ABC-123-X"
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase">
                            @error('placa') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                </div>
            </div>

            {{-- Especificaciones --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Especificaciones</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Datos técnicos y mecánicos.</p>
                </div>
                <div class="p-5 space-y-4">

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Año <span class="text-red-500">*</span></label>
                            <input type="number" wire:model="anio"
                                   placeholder="{{ date('Y') }}"
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('anio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Versión</label>
                            <input type="text" wire:model="version"
                                   placeholder="Ej. Sport, LX"
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('version') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Color</label>
                            <input type="text" wire:model="color"
                                   placeholder="Ej. Blanco"
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('color') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Kilometraje</label>
                            <input type="number" wire:model="kilometraje"
                                   placeholder="0"
                                   class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                            @error('kilometraje') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Transmisión</label>
                            <select wire:model="transmision"
                                    class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecciona</option>
                                <option value="automatica">Automática</option>
                                <option value="manual">Manual</option>
                                <option value="cvt">CVT</option>
                            </select>
                            @error('transmision') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Combustible</label>
                            <select wire:model="tipo_combustible"
                                    class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Selecciona</option>
                                <option value="gasolina">Gasolina</option>
                                <option value="diesel">Diésel</option>
                                <option value="hibrido">Híbrido</option>
                                <option value="electrico">Eléctrico</option>
                            </select>
                            @error('tipo_combustible') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                </div>
            </div>

            {{-- Precios y estado --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Precios y estado</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Valor comercial y disponibilidad en inventario.</p>
                </div>
                <div class="p-5 space-y-4">

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Precio contado <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                                <input type="number" step="0.01" wire:model="precio_contado"
                                       placeholder="0.00"
                                       class="block w-full rounded-lg border-slate-300 pl-6 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                            </div>
                            @error('precio_contado') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Precio financiado</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-400">$</span>
                                <input type="number" step="0.01" wire:model="precio_financiado"
                                       placeholder="0.00"
                                       class="block w-full rounded-lg border-slate-300 pl-6 text-sm focus:border-indigo-500 focus:ring-indigo-500 tabular-nums">
                            </div>
                            @error('precio_financiado') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1.5">Estatus <span class="text-red-500">*</span></label>
                            <select wire:model="estatus"
                                    class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="disponible">Disponible</option>
                                <option value="apartado">Apartado</option>
                                <option value="vendido_contado">Vendido contado</option>
                                <option value="financiado">Financiado</option>
                                <option value="liquidado">Liquidado</option>
                                <option value="recuperado">Recuperado</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                            @error('estatus') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-start gap-3 rounded-lg border border-slate-200 p-4 cursor-pointer hover:bg-slate-50 transition-colors">
                            <div class="relative inline-flex shrink-0 mt-0.5">
                                <input type="checkbox" wire:model="destacado" class="sr-only peer">
                                <div class="h-5 w-9 rounded-full bg-slate-200 peer-checked:bg-indigo-600 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:after:translate-x-4"></div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">Destacado</p>
                                <p class="text-xs text-slate-500 mt-0.5">Resaltado en el catálogo y portada pública.</p>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 rounded-lg border border-slate-200 p-4 cursor-pointer hover:bg-slate-50 transition-colors">
                            <div class="relative inline-flex shrink-0 mt-0.5">
                                <input type="checkbox" wire:model="activo" class="sr-only peer">
                                <div class="h-5 w-9 rounded-full bg-slate-200 peer-checked:bg-indigo-600 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:after:translate-x-4"></div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">Auto activo</p>
                                <p class="text-xs text-slate-500 mt-0.5">Visible en el sistema y en el catálogo.</p>
                            </div>
                        </label>
                    </div>

                </div>
            </div>

            {{-- Descripción --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Descripción</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Equipamiento, detalles adicionales u observaciones.</p>
                </div>
                <div class="p-5">
                    <textarea wire:model="descripcion" rows="4"
                              placeholder="Describe el estado general, equipamiento, extras, detalles interiores, etc."
                              class="block w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 resize-none"></textarea>
                    @error('descripcion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Galería --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Galería de imágenes</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Administra las imágenes guardadas y agrega nuevas.</p>
                </div>
                <div class="p-5 space-y-6">

                    {{-- Imágenes actuales --}}
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Imágenes guardadas
                            </p>
                            <span class="text-xs text-slate-400">{{ $imagenesActuales->count() }} registradas</span>
                        </div>

                        @if($imagenesActuales->count())
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($imagenesActuales as $img)
                                    <div wire:key="actual-{{ $img->id }}"
                                         class="rounded-xl border overflow-hidden bg-white shadow-sm {{ $img->es_portada ? 'ring-2 ring-indigo-500 border-indigo-300' : 'border-slate-200' }}">
                                        <div class="aspect-[4/3] bg-slate-100">
                                            <img src="{{ $img->url }}"
                                                 class="w-full h-full object-cover"
                                                 alt="Imagen del auto">
                                        </div>
                                        <div class="p-2.5 space-y-2">
                                            <div class="flex items-center justify-between gap-1">
                                                <p class="text-xs font-medium text-slate-700 truncate">
                                                    Imagen #{{ $img->orden }}
                                                </p>
                                                @if($img->es_portada)
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 border border-indigo-200 px-1.5 py-0.5 text-[10px] font-semibold text-indigo-700 shrink-0">
                                                        Portada
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex flex-col gap-1.5">
                                                @if(!$img->es_portada)
                                                    <button wire:click="marcarPortada({{ $img->id }})"
                                                            type="button"
                                                            class="w-full py-1.5 rounded-lg text-xs font-medium border border-slate-200 text-slate-600 hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                                        Usar como portada
                                                    </button>
                                                @endif
                                                <button wire:click="eliminarImagen({{ $img->id }})"
                                                        type="button"
                                                        wire:confirm="¿Seguro que deseas eliminar esta imagen?"
                                                        class="w-full py-1.5 rounded-lg text-xs font-medium border border-red-200 text-red-600 hover:bg-red-50 transition-colors">
                                                    Eliminar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-xl border border-slate-200 bg-slate-50 py-8 text-center">
                                <p class="text-sm text-slate-400">Este auto aún no tiene imágenes guardadas.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Agregar nuevas --}}
                    <div class="space-y-4">
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Agregar imágenes nuevas</p>

                        <div class="rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-5 space-y-3">
                            <div class="flex flex-col items-center gap-2 text-center">
                                <div class="h-10 w-10 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center">
                                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-700">Selecciona las imágenes</p>
                                    <p class="text-xs text-slate-400">JPG, PNG, WEBP — máx. 4 MB por imagen</p>
                                </div>
                            </div>
                            <input type="file"
                                   wire:model="imagenesNuevas"
                                   multiple
                                   accept=".jpg,.jpeg,.png,.webp"
                                   class="block w-full text-sm text-slate-500
                                          file:mr-3 file:py-2 file:px-4
                                          file:rounded-lg file:border-0
                                          file:bg-indigo-600 file:text-white file:text-sm file:font-medium
                                          hover:file:bg-indigo-700 file:cursor-pointer file:transition-colors">
                        </div>

                        @error('imagenesNuevas') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                        @error('imagenesNuevas.*') <p class="text-xs text-red-600">{{ $message }}</p> @enderror

                        <div wire:loading wire:target="imagenesNuevas"
                             class="flex items-center gap-2 text-xs text-slate-500">
                            <svg class="animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Procesando imágenes...
                        </div>

                        @if(!empty($imagenesNuevas))
                            <div>
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-3">
                                    Nuevas imágenes — {{ count($imagenesNuevas) }} por guardar
                                </p>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    @foreach($imagenesNuevas as $index => $img)
                                        @php
                                            $ext = strtolower($img->getClientOriginalExtension());
                                            $esPortada = (int) $portadaNuevaIndex === (int) $index;
                                        @endphp
                                        <div wire:key="nueva-{{ $index }}"
                                             class="rounded-xl border overflow-hidden bg-white shadow-sm {{ $esPortada ? 'ring-2 ring-indigo-500 border-indigo-300' : 'border-slate-200' }}">
                                            <div class="aspect-[4/3] bg-slate-100">
                                                @if(in_array($ext, ['jpg','jpeg','png','webp']))
                                                    <img src="{{ $img->temporaryUrl() }}" alt="Nueva imagen {{ $index + 1 }}"
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <p class="text-xs text-slate-400">Sin previa</p>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="p-2.5 space-y-2">
                                                <div class="flex items-center justify-between gap-1">
                                                    <p class="text-xs font-medium text-slate-700 truncate">Nueva {{ $index + 1 }}</p>
                                                    @if($esPortada)
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 border border-indigo-200 px-1.5 py-0.5 text-[10px] font-semibold text-indigo-700 shrink-0">
                                                            Portada
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex gap-1.5">
                                                    <button type="button"
                                                            wire:click="seleccionarPortadaNueva({{ $index }})"
                                                            class="flex-1 py-1.5 rounded-lg text-xs font-medium border transition-colors
                                                                {{ $esPortada ? 'border-indigo-300 bg-indigo-50 text-indigo-700' : 'border-slate-200 text-slate-600 hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700' }}">
                                                        {{ $esPortada ? '✓ Portada' : 'Portada' }}
                                                    </button>
                                                    <button type="button"
                                                            wire:click="quitarImagenNueva({{ $index }})"
                                                            class="py-1.5 px-2.5 rounded-lg text-xs font-medium border border-red-200 text-red-600 hover:bg-red-50 transition-colors">
                                                        Quitar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-5 xl:sticky xl:top-[4.5rem] xl:self-start">

            {{-- Resumen --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50/70">
                    <h2 class="text-sm font-semibold text-slate-900">Resumen</h2>
                </div>
                <div class="p-5 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-xs text-slate-500 shrink-0">Marca / Modelo</span>
                        <span class="text-xs font-semibold text-slate-900 text-right">
                            {{ optional($this->marcas->firstWhere('id', $marca_auto_id))->nombre ?? '—' }}
                            / {{ optional($this->modelos->firstWhere('id', $modelo_auto_id))->nombre ?? '—' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Año</span>
                        <span class="text-xs font-semibold text-slate-900">{{ $anio ?: '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Color</span>
                        <span class="text-xs font-semibold text-slate-900">{{ $color ?: '—' }}</span>
                    </div>
                    <div class="border-t border-slate-100 pt-3 flex items-center justify-between">
                        <span class="text-xs text-slate-500">Precio contado</span>
                        <span class="text-xs font-semibold text-slate-900 tabular-nums">
                            {{ is_numeric($precio_contado) ? '$' . number_format((float)$precio_contado, 2) : '—' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Precio financiado</span>
                        <span class="text-xs font-semibold text-slate-900 tabular-nums">
                            {{ is_numeric($precio_financiado) ? '$' . number_format((float)$precio_financiado, 2) : '—' }}
                        </span>
                    </div>
                    <div class="border-t border-slate-100 pt-3 flex items-center justify-between">
                        <span class="text-xs text-slate-500">Estatus</span>
                        <span class="text-xs font-semibold text-slate-900 capitalize">{{ str_replace('_', ' ', $estatus ?: '—') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Imágenes guardadas</span>
                        <span class="text-xs font-semibold text-slate-900">{{ $imagenesActuales->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Nuevas por guardar</span>
                        <span class="text-xs font-semibold text-slate-900">{{ is_array($imagenesNuevas) ? count($imagenesNuevas) : 0 }}</span>
                    </div>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-3">
                <button wire:click="actualizar"
                        wire:loading.attr="disabled" wire:target="actualizar"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <span wire:loading.remove wire:target="actualizar" class="flex items-center gap-2">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd"/>
                        </svg>
                        Guardar cambios
                    </span>
                    <span wire:loading wire:target="actualizar" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Guardando...
                    </span>
                </button>
                <a href="{{ route('admin.autos.index') }}"
                   class="w-full inline-flex items-center justify-center px-5 py-2.5 rounded-lg border border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                    Cancelar
                </a>
            </div>

        </div>
    </div>
</div>
