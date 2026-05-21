<div class="p-4 sm:p-6 space-y-6">

    <div>
        <h1 class="text-xl font-semibold text-slate-900">Administración</h1>
        <p class="text-sm text-slate-500 mt-0.5">Accede a los módulos principales del sistema.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        @can('autos.ver')
            <a href="{{ route('admin.autos.index') }}"
               class="group bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all">
                <div class="h-10 w-10 rounded-lg bg-indigo-50 flex items-center justify-center mb-4">
                    <svg class="h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/>
                        <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/>
                        <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-slate-900 group-hover:text-indigo-700 transition-colors">Autos</h2>
                <p class="text-xs text-slate-500 mt-1">Inventario y administración de vehículos.</p>
            </a>
        @endcan

        @if(\App\Models\Configuracion::esActivo('modulo.financiamiento'))

            @can('clientes.ver')
                <a href="{{ route('admin.clientes.index') }}"
                   class="group bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all">
                    <div class="h-10 w-10 rounded-lg bg-violet-50 flex items-center justify-center mb-4">
                        <svg class="h-5 w-5 text-violet-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-900 group-hover:text-violet-700 transition-colors">Clientes</h2>
                    <p class="text-xs text-slate-500 mt-1">Consulta y administra clientes.</p>
                </a>
            @endcan

            @can('apartados.ver')
                <a href="{{ route('admin.apartados-autos.index') }}"
                   class="group bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all">
                    <div class="h-10 w-10 rounded-lg bg-amber-50 flex items-center justify-center mb-4">
                        <svg class="h-5 w-5 text-amber-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 00-.673-.05A3 3 0 0015 1.5h-1.5a3 3 0 00-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6zM13.5 3A1.5 1.5 0 0012 4.5h4.5A1.5 1.5 0 0015 3h-1.5z" clip-rule="evenodd"/>
                            <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625V9.375zm9.586 4.594a.75.75 0 00-1.172-.938l-2.476 3.096-.908-.907a.75.75 0 00-1.06 1.06l1.5 1.5a.75.75 0 001.116-.062l3-3.75z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-900 group-hover:text-amber-700 transition-colors">Apartados</h2>
                    <p class="text-xs text-slate-500 mt-1">Control de apartados de autos.</p>
                </a>
            @endcan

            @can('contratos.ver')
                <a href="{{ route('admin.contratos-financiamiento.index') }}"
                   class="group bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all">
                    <div class="h-10 w-10 rounded-lg bg-emerald-50 flex items-center justify-center mb-4">
                        <svg class="h-5 w-5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625zM7.5 15a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 017.5 15zm.75-6.75a.75.75 0 000 1.5H12a.75.75 0 000-1.5H8.25z" clip-rule="evenodd"/>
                            <path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-900 group-hover:text-emerald-700 transition-colors">Contratos</h2>
                    <p class="text-xs text-slate-500 mt-1">Contratos de financiamiento.</p>
                </a>
            @endcan

            @can('recibos.ver')
                <a href="{{ route('admin.recibos.index') }}"
                   class="group bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all">
                    <div class="h-10 w-10 rounded-lg bg-sky-50 flex items-center justify-center mb-4">
                        <svg class="h-5 w-5 text-sky-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM9 7.5A.75.75 0 009 9h1.5c.98 0 1.813.626 2.122 1.5H9A.75.75 0 009 12h3.622a2.251 2.251 0 01-2.122 1.5H9a.75.75 0 00-.53 1.28l3 3a.75.75 0 101.06-1.06l-1.7-1.7A3.75 3.75 0 0013.5 12H15a.75.75 0 000-1.5h-1.5a3.75 3.75 0 00-1.033-2.554A3.75 3.75 0 0015 7.5H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-900 group-hover:text-sky-700 transition-colors">Recibos</h2>
                    <p class="text-xs text-slate-500 mt-1">Recibos y comprobantes de pago.</p>
                </a>
            @endcan

            @can('logs_financieros.ver')
                <a href="{{ route('admin.finanzas.logs-financieros') }}"
                   class="group bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all">
                    <div class="h-10 w-10 rounded-lg bg-rose-50 flex items-center justify-center mb-4">
                        <svg class="h-5 w-5 text-rose-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-900 group-hover:text-rose-700 transition-colors">Log financiero</h2>
                    <p class="text-xs text-slate-500 mt-1">Bitácora de movimientos financieros.</p>
                </a>
            @endcan

            @can('dashboard.ver')
                <a href="{{ route('admin.administracion.tarjetas-cobro') }}"
                   class="group bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all">
                    <div class="h-10 w-10 rounded-lg bg-teal-50 flex items-center justify-center mb-4">
                        <svg class="h-5 w-5 text-teal-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z"/>
                            <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-900 group-hover:text-teal-700 transition-colors">Tarjetas y cuentas</h2>
                    <p class="text-xs text-slate-500 mt-1">Terminales, CLABES y cuentas de cobro.</p>
                </a>
            @endcan

        @endif

    </div>
</div>
