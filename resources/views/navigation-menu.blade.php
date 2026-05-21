<div>
    {{-- Mobile overlay --}}
    <div x-data
        x-show="$store.sidebar?.open"
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="$store.sidebar.open = false"
        class="fixed inset-0 z-20 bg-slate-900/70 backdrop-blur-sm lg:hidden"
        style="display:none"
        aria-hidden="true">
    </div>

    {{-- Sidebar --}}
    <aside x-data
        :class="$store.sidebar?.open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed inset-y-0 left-0 z-30 flex w-64 flex-col bg-slate-900 transition-transform duration-300 ease-in-out">

        {{-- Brand --}}
        <div class="flex items-center gap-3 h-14 px-4 border-b border-slate-800 shrink-0">
            <div class="h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center shrink-0">
                <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/>
                    <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/>
                    <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                </svg>
            </div>
            <span class="text-white font-semibold text-sm truncate">{{ config('app.name') }}</span>

            {{-- Mobile close --}}
            <button @click="$store.sidebar.open = false" type="button"
                class="ml-auto lg:hidden flex items-center justify-center h-8 w-8 rounded-lg text-slate-500 hover:text-white hover:bg-slate-800 transition"
                aria-label="Cerrar menú">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Nav items --}}
        <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5">


            @if(\App\Models\Configuracion::esActivo('modulo.financiamiento'))
            @can('dashboard.ver')
            <a href="{{ route('dashboard') }}" @click="$store.sidebar.open = false"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z"/>
                </svg>
                Cobranza
            </a>
            @endcan
            @endif

            {{-- INVENTARIO --}}
            <div class="pt-5 pb-1.5 px-3">
                <p class="text-[10px] font-semibold text-slate-600 uppercase tracking-widest">Inventario</p>
            </div>

            @can('autos.ver')
            <a href="{{ route('admin.autos.index') }}" @click="$store.sidebar.open = false"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('admin.autos.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875H3.75a3 3 0 106 0h2.25a.75.75 0 00.75-.75V15z"/>
                    <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z"/>
                    <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z"/>
                </svg>
                Autos
            </a>
            @endcan

            {{-- FINANCIAMIENTO --}}
            @if(\App\Models\Configuracion::esActivo('modulo.financiamiento'))

            <div class="pt-5 pb-1.5 px-3">
                <p class="text-[10px] font-semibold text-slate-600 uppercase tracking-widest">Financiamiento</p>
            </div>

            @can('clientes.ver')
            <a href="{{ route('admin.clientes.index') }}" @click="$store.sidebar.open = false"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('admin.clientes.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM15.75 9.75a3 3 0 116 0 3 3 0 01-6 0zM2.25 9.75a3 3 0 116 0 3 3 0 01-6 0zM6.31 15.117A6.745 6.745 0 0112 12a6.745 6.745 0 016.709 7.498.75.75 0 01-.372.568A12.696 12.696 0 0112 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 01-.372-.568 6.787 6.787 0 011.019-4.38z" clip-rule="evenodd"/>
                    <path d="M5.082 14.254a8.287 8.287 0 00-1.308 5.135 9.687 9.687 0 01-1.764-.44l-.115-.04a.563.563 0 01-.373-.487l-.01-.121a3.75 3.75 0 013.57-4.047zM20.226 19.389a8.287 8.287 0 00-1.308-5.135 3.75 3.75 0 013.57 4.047l-.01.121a.563.563 0 01-.373.487l-.115.04c-.567.2-1.156.349-1.764.44z"/>
                </svg>
                Clientes
            </a>
            @endcan

            @can('apartados.ver')
            <a href="{{ route('admin.apartados-autos.index') }}" @click="$store.sidebar.open = false"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('admin.apartados-autos.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 00-.673-.05A3 3 0 0015 1.5h-1.5a3 3 0 00-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6zM13.5 3A1.5 1.5 0 0012 4.5h4.5A1.5 1.5 0 0015 3h-1.5z" clip-rule="evenodd"/>
                    <path fill-rule="evenodd" d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625V9.375zm9.586 4.594a.75.75 0 00-1.172-.938l-2.476 3.096-.908-.907a.75.75 0 00-1.06 1.06l1.5 1.5a.75.75 0 001.116-.062l3-3.75z" clip-rule="evenodd"/>
                </svg>
                Apartados
            </a>
            @endcan

            @can('contratos.ver')
            <a href="{{ route('admin.contratos-financiamiento.index') }}" @click="$store.sidebar.open = false"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('admin.contratos-financiamiento.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625zM7.5 15a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 017.5 15zm.75-6.75a.75.75 0 000 1.5H12a.75.75 0 000-1.5H8.25z" clip-rule="evenodd"/>
                    <path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z"/>
                </svg>
                Contratos
            </a>
            @endcan

            @can('recibos.ver')
            <a href="{{ route('admin.recibos.index') }}" @click="$store.sidebar.open = false"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('admin.recibos.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z"/>
                    <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 14.625v-9.75zM8.25 9.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM18.75 9a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V9.75a.75.75 0 00-.75-.75h-.008zM4.5 9.75A.75.75 0 015.25 9h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75V9.75z" clip-rule="evenodd"/>
                    <path d="M2.25 18a.75.75 0 000 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 00-.75-.75H2.25z"/>
                </svg>
                Recibos
            </a>
            @endcan

            @can('logs_financieros.ver')
            <a href="{{ route('admin.finanzas.logs-financieros') }}" @click="$store.sidebar.open = false"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('admin.finanzas.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z"/>
                </svg>
                Log financiero
            </a>
            @endcan

            @can('dashboard.ver')
            <a href="{{ route('admin.reportes.index') }}" @click="$store.sidebar.open = false"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('admin.reportes.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M1.5 5.625c0-1.036.84-1.875 1.875-1.875h17.25c1.035 0 1.875.84 1.875 1.875v12.75c0 1.035-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 18.375V5.625zM21 9.375A.375.375 0 0020.625 9h-7.5a.375.375 0 00-.375.375v1.5c0 .207.168.375.375.375h7.5A.375.375 0 0021 10.875v-1.5zm0 3.75a.375.375 0 00-.375-.375h-7.5a.375.375 0 00-.375.375v1.5c0 .207.168.375.375.375h7.5a.375.375 0 00.375-.375v-1.5zm0 3.75a.375.375 0 00-.375-.375h-7.5a.375.375 0 00-.375.375v1.5c0 .207.168.375.375.375h7.5a.375.375 0 00.375-.375v-1.5zM10.875 18.75a.375.375 0 00.375-.375v-1.5a.375.375 0 00-.375-.375h-7.5a.375.375 0 00-.375.375v1.5c0 .207.168.375.375.375h7.5zM3.375 15h7.5a.375.375 0 00.375-.375v-1.5a.375.375 0 00-.375-.375h-7.5a.375.375 0 00-.375.375v1.5c0 .207.168.375.375.375zm0-3.75h7.5a.375.375 0 00.375-.375v-1.5A.375.375 0 0010.875 9h-7.5A.375.375 0 003 9.375v1.5c0 .207.168.375.375.375z" clip-rule="evenodd"/>
                </svg>
                Reportes
            </a>
            @endcan

            @can('dashboard.ver')
            <a href="{{ route('admin.administracion.tarjetas-cobro') }}" @click="$store.sidebar.open = false"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('admin.administracion.tarjetas-cobro') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z"/>
                    <path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd"/>
                </svg>
                Tarjetas y cuentas
            </a>
            @endcan

            @endif

            {{-- SISTEMA --}}
            <div class="pt-5 pb-1.5 px-3">
                <p class="text-[10px] font-semibold text-slate-600 uppercase tracking-widest">Sistema</p>
            </div>


            <a href="{{ route('admin.sistema.configuracion') }}" @click="$store.sidebar.open = false"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('admin.sistema.configuracion') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 00-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 00-2.282.819l-.922 1.597a1.875 1.875 0 00.432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 000 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 00-.432 2.385l.922 1.597a1.875 1.875 0 002.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 002.28-.819l.923-1.597a1.875 1.875 0 00-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 000-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 00-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 00-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 00-1.85-1.567h-1.843zM12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" clip-rule="evenodd"/>
                </svg>
                Configuración
            </a>

            @can('seguridad.roles')
            <a href="{{ route('admin.seguridad.roles-permisos') }}" @click="$store.sidebar.open = false"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('admin.seguridad.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd"/>
                </svg>
                Roles y permisos
            </a>
            @endcan

            @can('seguridad.roles')
            <a href="{{ route('admin.seguridad.usuarios') }}" @click="$store.sidebar.open = false"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('admin.seguridad.usuarios') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18.75 12.75h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zM12 6a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 0112 6zM12 18a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 0112 18zM3.75 6.75h1.5a.75.75 0 100-1.5h-1.5a.75.75 0 000 1.5zM5.25 18.75h-1.5a.75.75 0 010-1.5h1.5a.75.75 0 010 1.5zM3 12a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 013 12zM9 3.75a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5zM12.75 12a2.25 2.25 0 114.5 0 2.25 2.25 0 01-4.5 0zM9 15.75a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z"/>
                </svg>
                Usuarios
            </a>
            @endcan

            @can('auditoria.ver')
            <a href="{{ route('admin.sistema.auditoria') }}" @click="$store.sidebar.open = false"
                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                    {{ request()->routeIs('admin.sistema.auditoria') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 00-1.032 0 11.209 11.209 0 01-7.877 3.08.75.75 0 00-.722.515A12.74 12.74 0 002.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 00.374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 00-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08zm3.094 8.016a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
                </svg>
                Auditoría
            </a>
            @endcan

        </nav>
    </aside>
</div>
