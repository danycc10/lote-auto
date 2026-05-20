<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">Administración</h1>
            <p class="text-sm text-gray-500 mt-1">
                Accede a los módulos principales del sistema.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

            @can('autos.ver')
                <a href="{{ route('admin.autos.index') }}"
                   class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md hover:border-black transition">
                    <div class="text-3xl mb-4">🚗</div>
                    <h2 class="text-lg font-bold text-gray-900">Autos</h2>
                    <p class="text-sm text-gray-500 mt-1">Inventario y administración de vehículos.</p>
                </a>
            @endcan

            @can('clientes.ver')
                <a href="{{ route('admin.clientes.index') }}"
                   class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md hover:border-black transition">
                    <div class="text-3xl mb-4">👤</div>
                    <h2 class="text-lg font-bold text-gray-900">Clientes</h2>
                    <p class="text-sm text-gray-500 mt-1">Consulta y administra clientes.</p>
                </a>
            @endcan

            @can('apartados.ver')
                <a href="{{ route('admin.apartados-autos.index') }}"
                   class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md hover:border-black transition">
                    <div class="text-3xl mb-4">📝</div>
                    <h2 class="text-lg font-bold text-gray-900">Apartados</h2>
                    <p class="text-sm text-gray-500 mt-1">Control de apartados de autos.</p>
                </a>
            @endcan

            @can('contratos.ver')
                <a href="{{ route('admin.contratos-financiamiento.index') }}"
                   class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md hover:border-black transition">
                    <div class="text-3xl mb-4">📄</div>
                    <h2 class="text-lg font-bold text-gray-900">Contratos</h2>
                    <p class="text-sm text-gray-500 mt-1">Contratos de financiamiento.</p>
                </a>
            @endcan

            @can('recibos.ver')
                <a href="{{ route('admin.recibos.index') }}"
                   class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md hover:border-black transition">
                    <div class="text-3xl mb-4">🧾</div>
                    <h2 class="text-lg font-bold text-gray-900">Recibos</h2>
                    <p class="text-sm text-gray-500 mt-1">Recibos y comprobantes de pago.</p>
                </a>
            @endcan

            @can('logs_financieros.ver')
                <a href="{{ route('admin.finanzas.logs-financieros') }}"
                   class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-md hover:border-black transition">
                    <div class="text-3xl mb-4">📊</div>
                    <h2 class="text-lg font-bold text-gray-900">Log financiero</h2>
                    <p class="text-sm text-gray-500 mt-1">Bitácora de movimientos financieros.</p>
                </a>
            @endcan


        </div>
    </div>
</div>