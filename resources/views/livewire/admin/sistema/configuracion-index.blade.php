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

</div>
