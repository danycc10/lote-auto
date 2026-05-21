<div class="p-4 sm:p-6 space-y-6">

    <div>
        <h1 class="text-xl font-semibold text-slate-900">Sistema</h1>
        <p class="text-sm text-slate-500 mt-0.5">Configuración, seguridad y monitoreo del sistema.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        @if(\App\Models\Configuracion::esActivo('modulo.financiamiento'))
            @can('logs_financieros.ver')
                <a href="{{ route('admin.finanzas.logs-financieros') }}"
                   class="group bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all">
                    <div class="h-10 w-10 rounded-lg bg-rose-50 flex items-center justify-center mb-4">
                        <svg class="h-5 w-5 text-rose-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-slate-900 group-hover:text-rose-700 transition-colors">Log financiero</h2>
                    <p class="text-xs text-slate-500 mt-1">Bitácora de movimientos financieros y eventos críticos.</p>
                </a>
            @endcan
        @endif

        @can('seguridad.roles')
            <a href="{{ route('admin.seguridad.roles-permisos') }}"
               class="group bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all">
                <div class="h-10 w-10 rounded-lg bg-violet-50 flex items-center justify-center mb-4">
                    <svg class="h-5 w-5 text-violet-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-slate-900 group-hover:text-violet-700 transition-colors">Roles y permisos</h2>
                <p class="text-xs text-slate-500 mt-1">Control de acceso y administración de usuarios.</p>
            </a>
        @endcan

        <a href="{{ route('admin.sistema.configuracion') }}"
           class="group bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all">
            <div class="h-10 w-10 rounded-lg bg-slate-100 flex items-center justify-center mb-4">
                <svg class="h-5 w-5 text-slate-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 00-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 00-2.282.819l-.922 1.597a1.875 1.875 0 00.432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 000 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 00-.432 2.385l.922 1.597a1.875 1.875 0 002.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 002.28-.819l.923-1.597a1.875 1.875 0 00-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 000-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 00-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 00-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 00-1.85-1.567h-1.843zM12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h2 class="text-sm font-semibold text-slate-900 group-hover:text-slate-700 transition-colors">Configuración</h2>
            <p class="text-xs text-slate-500 mt-1">Activa o desactiva módulos del sistema según el plan contratado.</p>
        </a>

        @can('auditoria.ver')
            <a href="{{ route('admin.sistema.auditoria') }}"
               class="group bg-white rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md hover:border-indigo-300 transition-all">
                <div class="h-10 w-10 rounded-lg bg-emerald-50 flex items-center justify-center mb-4">
                    <svg class="h-5 w-5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 00-1.032 0 11.209 11.209 0 01-7.877 3.08.75.75 0 00-.722.515A12.74 12.74 0 002.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 00.374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 00-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08zm3.094 8.016a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2 class="text-sm font-semibold text-slate-900 group-hover:text-emerald-700 transition-colors">Auditoría</h2>
                <p class="text-xs text-slate-500 mt-1">Revisa cambios antes/después, usuario, IP y acciones críticas.</p>
            </a>
        @endcan

    </div>
</div>
