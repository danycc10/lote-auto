<div class="p-4 sm:p-6 max-w-3xl space-y-6">

        {{-- Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.sistema.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-900 transition-colors">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/>
                </svg>
                Sistema
            </a>
        </div>

        <div>
            <h1 class="text-xl font-semibold text-slate-900">Configuración del sistema</h1>
            <p class="text-sm text-slate-500 mt-0.5">Activa o desactiva los módulos disponibles en el panel de administración.</p>
        </div>

        {{-- Módulos --}}
        <div class="space-y-4">
            <h2 class="text-xs font-medium text-slate-400 uppercase tracking-wider">Módulos</h2>

            {{-- Módulo: Catálogo de Autos --}}
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center">
                            <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/>
                                <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/>
                                <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-gray-900">Catálogo de autos</h3>
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Siempre activo
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Página pública de autos disponibles, catálogo y detalle de cada unidad. Incluye gestión de autos e imágenes en el panel.</p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                @foreach(['Landing pública', 'Catálogo', 'Detalle de auto', 'Gestión de inventario'] as $feat)
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">{{ $feat }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="h-6 w-11 rounded-full bg-emerald-500 flex items-center justify-end px-0.5 cursor-not-allowed opacity-60"
                             title="Este módulo no puede desactivarse">
                            <div class="h-5 w-5 rounded-full bg-white shadow"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Módulo: Financiamiento --}}
            <div class="bg-white rounded-xl border shadow-sm p-6 transition {{ $financiamientoActivo ? 'border-slate-200' : 'border-orange-200 bg-orange-50/30' }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 h-10 w-10 rounded-xl {{ $financiamientoActivo ? 'bg-indigo-50' : 'bg-gray-100' }} flex items-center justify-center">
                            <svg class="h-5 w-5 {{ $financiamientoActivo ? 'text-indigo-600' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z"/>
                                <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 14.625v-9.75zM8.25 9.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM18.75 9a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V9.75a.75.75 0 00-.75-.75h-.008zM4.5 9.75A.75.75 0 015.25 9h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75V9.75z" clip-rule="evenodd"/>
                                <path d="M2.25 18a.75.75 0 000 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 00-.75-.75H2.25z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold {{ $financiamientoActivo ? 'text-gray-900' : 'text-gray-500' }}">
                                    Financiamiento y cobranza
                                </h3>
                                @if($financiamientoActivo)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Activo
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-orange-50 border border-orange-200 px-2.5 py-0.5 text-xs font-semibold text-orange-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-orange-400"></span>
                                    Desactivado
                                </span>
                                @endif
                            </div>
                            <p class="text-sm mt-1 {{ $financiamientoActivo ? 'text-gray-500' : 'text-gray-400' }}">
                                Sistema completo de contratos a plazos, control de pagos, recibos y cobranza. Para clientes que adquieren el plan completo.
                            </p>
                            <div class="flex flex-wrap gap-2 mt-3">
                                @foreach(['Clientes', 'Apartados', 'Contratos', 'Pagos y recibos', 'Cobranza', 'Logs financieros'] as $feat)
                                <span class="inline-flex items-center rounded-full {{ $financiamientoActivo ? 'bg-indigo-50 text-indigo-700' : 'bg-gray-100 text-gray-400' }} px-2.5 py-0.5 text-xs font-medium">
                                    {{ $feat }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Toggle --}}
                    <div class="flex-shrink-0">
                        <button
                            wire:click="toggleFinanciamiento"
                            wire:loading.attr="disabled"
                            wire:target="toggleFinanciamiento"
                            type="button"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 {{ $financiamientoActivo ? 'bg-indigo-600' : 'bg-gray-200' }}"
                            role="switch"
                            :aria-checked="$wire.financiamientoActivo"
                            aria-label="Toggle módulo de financiamiento">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $financiamientoActivo ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>
                </div>

                @if(!$financiamientoActivo)
                <div class="mt-4 flex items-start gap-2 rounded-xl bg-orange-50 border border-orange-200 px-4 py-3">
                    <svg class="h-4 w-4 text-orange-500 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-xs text-orange-700">
                        Las secciones de clientes, contratos, apartados, pagos y cobranza no serán accesibles desde el panel de administración mientras este módulo esté desactivado.
                    </p>
                </div>
                @endif
            </div>
        </div>

        {{-- Contacto --}}
        <div class="space-y-4">
            <h2 class="text-xs font-medium text-slate-400 uppercase tracking-wider">Contacto público</h2>

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-5">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <svg class="h-5 w-5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">WhatsApp y mapa</h3>
                        <p class="text-sm text-gray-500 mt-1">Estos datos se muestran en todas las páginas públicas (landing, catálogo y detalle de autos).</p>
                    </div>
                </div>

                <div class="space-y-4">
                    {{-- WhatsApp --}}
                    <div>
                        <label for="whatsapp" class="block text-sm font-medium text-slate-700 mb-1">
                            Número de WhatsApp
                            <span class="text-slate-400 font-normal">(solo dígitos con código de país)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400 text-sm select-none">+</span>
                            <input id="whatsapp"
                                   type="text"
                                   wire:model="whatsapp"
                                   placeholder="5218001234567"
                                   inputmode="numeric"
                                   maxlength="20"
                                   class="block w-full rounded-lg border border-slate-300 py-2 pl-7 pr-3 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('whatsapp') border-red-400 bg-red-50 @enderror">
                        </div>
                        @error('whatsapp')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-slate-400">Ejemplo: 5218001234567 (52 = México, 800 = código de área, 1234567 = número)</p>
                    </div>

                    {{-- Instagram --}}
                    <div>
                        <label for="instagram" class="block text-sm font-medium text-slate-700 mb-1">
                            Instagram
                            <span class="text-slate-400 font-normal">(opcional)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </span>
                            <input id="instagram"
                                   type="url"
                                   wire:model="instagram"
                                   placeholder="https://instagram.com/tunegocio"
                                   class="block w-full rounded-lg border border-slate-300 py-2 pl-9 pr-3 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('instagram') border-red-400 bg-red-50 @enderror">
                        </div>
                        @error('instagram')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Facebook --}}
                    <div>
                        <label for="facebook" class="block text-sm font-medium text-slate-700 mb-1">
                            Facebook
                            <span class="text-slate-400 font-normal">(opcional)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </span>
                            <input id="facebook"
                                   type="url"
                                   wire:model="facebook"
                                   placeholder="https://facebook.com/tunegocio"
                                   class="block w-full rounded-lg border border-slate-300 py-2 pl-9 pr-3 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('facebook') border-red-400 bg-red-50 @enderror">
                        </div>
                        @error('facebook')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Maps embed --}}
                    <div>
                        <label for="mapsEmbed" class="block text-sm font-medium text-slate-700 mb-1">
                            URL del mapa de Google
                            <span class="text-slate-400 font-normal">(opcional)</span>
                        </label>
                        <input id="mapsEmbed"
                               type="url"
                               wire:model="mapsEmbed"
                               placeholder="https://www.google.com/maps/embed?pb=..."
                               class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('mapsEmbed') border-red-400 bg-red-50 @enderror">
                        @error('mapsEmbed')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-slate-400">
                            En Google Maps: busca tu negocio → Compartir → Insertar mapa → copia solo el valor del atributo <code class="bg-slate-100 px-1 rounded">src</code>.
                        </p>
                    </div>
                </div>

                <div class="flex justify-end pt-2 border-t border-slate-100">
                    <button wire:click="guardarContacto"
                            wire:loading.attr="disabled"
                            wire:target="guardarContacto"
                            type="button"
                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-60 transition">
                        <svg wire:loading wire:target="guardarContacto" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Guardar contacto
                    </button>
                </div>
            </div>
        </div>

        {{-- Plantillas de notificación --}}
        <div class="space-y-4">
            <h2 class="text-xs font-medium text-slate-400 uppercase tracking-wider">Plantillas de notificación de mora</h2>
            <p class="text-xs text-slate-500 -mt-2">
                Variables disponibles:
                <code class="bg-slate-100 px-1 rounded text-slate-600">{nombre}</code>
                <code class="bg-slate-100 px-1 rounded text-slate-600">{folio}</code>
                <code class="bg-slate-100 px-1 rounded text-slate-600">{numero_cuota}</code>
                <code class="bg-slate-100 px-1 rounded text-slate-600">{fecha_vencimiento}</code>
                <code class="bg-slate-100 px-1 rounded text-slate-600">{dias_atraso}</code>
                <code class="bg-slate-100 px-1 rounded text-slate-600">{monto_pendiente}</code>
                <code class="bg-slate-100 px-1 rounded text-slate-600">{monto_cuota}</code>
            </p>

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-5">

                {{-- Asunto correo --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Asunto del correo
                    </label>
                    <input type="text" wire:model="notifCorreoAsunto" maxlength="200"
                           placeholder="Recordatorio de pago - Contrato {folio}"
                           class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('notifCorreoAsunto') border-red-400 bg-red-50 @enderror">
                    @error('notifCorreoAsunto') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Cuerpo correo --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Cuerpo del correo
                    </label>
                    <textarea wire:model="notifCorreoCuerpo" rows="5" maxlength="2000"
                              placeholder="Estimado/a {nombre}, le recordamos que tiene {cuotas_vencidas} cuota(s) vencida(s)..."
                              class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-y @error('notifCorreoCuerpo') border-red-400 bg-red-50 @enderror"></textarea>
                    @error('notifCorreoCuerpo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    <p class="mt-1 text-xs text-slate-400">Puedes usar saltos de línea. Se envía como correo HTML.</p>
                </div>

                {{-- Mensaje WA --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Mensaje de WhatsApp
                        <span class="text-slate-400 font-normal">(máx. 500 caracteres)</span>
                    </label>
                    <div class="relative">
                        <textarea wire:model="notifWaMensaje" rows="3" maxlength="500"
                                  placeholder="Hola {nombre}, tiene pagos vencidos por ${monto_atrasado}..."
                                  class="block w-full rounded-lg border border-slate-300 py-2 px-3 text-sm text-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 resize-none pb-6 @error('notifWaMensaje') border-red-400 bg-red-50 @enderror"></textarea>
                        <span class="absolute right-3 bottom-2 text-xs text-slate-400 tabular-nums">{{ strlen($notifWaMensaje) }}/500</span>
                    </div>
                    @error('notifWaMensaje') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end pt-2 border-t border-slate-100">
                    <button wire:click="guardarPlantillasNotif"
                            wire:loading.attr="disabled"
                            wire:target="guardarPlantillasNotif"
                            type="button"
                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-60 transition">
                        <svg wire:loading wire:target="guardarPlantillasNotif" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Guardar plantillas
                    </button>
                </div>
            </div>
        </div>

</div>
