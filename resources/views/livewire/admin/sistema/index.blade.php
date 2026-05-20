<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">Sistema</h1>

            <p class="text-sm text-gray-500 mt-1">
                Configuración, seguridad y monitoreo del sistema.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

            @can('logs_financieros.ver')
            <a href="{{ route('admin.finanzas.logs-financieros') }}"
                class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md hover:border-black transition">

                <div class="text-3xl mb-4">📊</div>

                <h2 class="text-lg font-bold text-gray-900">
                    Log financiero
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Bitácora de movimientos financieros y eventos críticos.
                </p>
            </a>
            @endcan

            @can('seguridad.roles')
            <a href="{{ route('admin.seguridad.roles-permisos') }}"
                class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md hover:border-black transition">

                <div class="text-3xl mb-4">🔐</div>

                <h2 class="text-lg font-bold text-gray-900">
                    Roles y permisos
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Control de acceso y administración de usuarios.
                </p>
            </a>
            @endcan

            <div class="group bg-gray-50 rounded-2xl border border-dashed border-gray-300 p-6">

                <div class="text-3xl mb-4">⚙️</div>

                <h2 class="text-lg font-bold text-gray-500">
                    Configuración
                </h2>

                <p class="text-sm text-gray-400 mt-1">
                    Próximamente.
                </p>
            </div>

            @can('auditoria.ver')
            <a href="{{ route('admin.sistema.auditoria') }}"
                class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md hover:border-black transition">

                <div class="text-3xl mb-4">🛡️</div>

                <h2 class="text-lg font-bold text-gray-900">
                    Auditoría
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Revisa cambios antes/después, usuario, IP y acciones críticas.
                </p>
            </a>
            @endcan

        </div>
    </div>
</div>