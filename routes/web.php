<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Admin\Autos\Index as AutosIndex;
use App\Livewire\Admin\Autos\Create as AutosCreate;
use App\Livewire\Admin\Autos\Edit as AutosEdit;

use App\Livewire\Admin\Clientes\Index as ClientesIndex;
use App\Livewire\Admin\Clientes\Create as ClientesCreate;
use App\Livewire\Admin\Clientes\Edit as ClientesEdit;

use App\Livewire\Admin\ApartadosAutos\Index as ApartadosAutosIndex;
use App\Livewire\Admin\ApartadosAutos\Create as ApartadosAutosCreate;

use App\Livewire\Admin\Recibos\Index as RecibosIndex;
use App\Livewire\Admin\Recibos\Create as RecibosCreate;
use App\Livewire\Admin\Recibos\Show as RecibosShow;
use App\Livewire\Admin\Recibos\Edit as RecibosEdit;

use App\Livewire\Admin\ContratosFinanciamiento\Index as ContratosFinanciamientoIndex;
use App\Livewire\Admin\ContratosFinanciamiento\Create as ContratosFinanciamientoCreate;
use App\Livewire\Admin\ContratosFinanciamiento\Edit as ContratosFinanciamientoEdit;
use App\Livewire\Admin\ContratosFinanciamiento\Show as ContratosFinanciamientoShow;
use App\Livewire\Admin\ContratosFinanciamiento\RegistrarPago as ContratosFinanciamientoRegistrarPago;

use App\Livewire\Admin\CobranzaAutos\Dashboard;

use App\Http\Controllers\Admin\ClienteArchivoController;
use App\Http\Controllers\Admin\ContratoFinanciamientoArchivoController;
use App\Http\Controllers\Admin\ReciboFinanciamientoPdfController;

use App\Livewire\Admin\Seguridad\RolesPermisosManager;
use App\Livewire\Admin\Usuarios\Index as UsuariosIndex;

use App\Livewire\Admin\Finanzas\LogsFinancierosIndex;

use App\Livewire\Admin\Reportes\Index as ReportesIndex;
use App\Http\Controllers\Admin\ReportesExportController;

use App\Livewire\Admin\Administracion\Index as AdministracionIndex;
use App\Livewire\Admin\Administracion\TarjetasCobroIndex;
use App\Livewire\Admin\Sistema\Index as SistemaIndex;
use App\Livewire\Admin\Sistema\AuditoriaIndex;
use App\Livewire\Admin\Sistema\ConfiguracionIndex as SistemaConfiguracionIndex;
use App\Livewire\Admin\Sistema\BrandingIndex as SistemaBrandingIndex;
use App\Livewire\Public\AutosDisponibles;
use App\Livewire\Public\LandingAutos;
use App\Livewire\Public\AutoDetalle;

Route::get('/robots.txt', function () {
    $content  = "User-agent: *\n";
    $content .= "Disallow: /admin/\n";
    $content .= "Disallow: /dashboard\n";
    $content .= "Allow: /\n\n";
    $content .= "Sitemap: " . url('/sitemap.xml') . "\n";
    return response($content, 200, ['Content-Type' => 'text/plain; charset=utf-8']);
});

Route::get('/sitemap.xml', function () {
    $autos = \App\Models\Auto::query()
        ->select(['uuid', 'updated_at'])
        ->where('estatus', 'disponible')
        ->where('activo', true)
        ->latest('updated_at')
        ->get();

    $urls = collect([
        ['loc' => url('/'),       'changefreq' => 'daily',  'priority' => '1.0'],
        ['loc' => url('/autos'),  'changefreq' => 'daily',  'priority' => '0.9'],
    ]);

    foreach ($autos as $auto) {
        $urls->push([
            'loc'        => route('public.autos.show', $auto->uuid),
            'lastmod'    => $auto->updated_at->format('Y-m-d'),
            'changefreq' => 'weekly',
            'priority'   => '0.8',
        ]);
    }

    $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
    foreach ($urls as $url) {
        $xml .= '  <url>' . PHP_EOL;
        $xml .= '    <loc>' . e($url['loc']) . '</loc>' . PHP_EOL;
        if (isset($url['lastmod'])) {
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
        }
        $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
        $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
        $xml .= '  </url>' . PHP_EOL;
    }
    $xml .= '</urlset>';

    return response($xml, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
});

Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/', LandingAutos::class)->name('public.home');
    Route::get('/autos', AutosDisponibles::class)->name('public.autos');
    Route::get('/autos/{auto:uuid}', AutoDetalle::class)->name('public.autos.show');
});

// Dashboard de cobranza (requiere módulo financiamiento)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'permission:dashboard.ver',
    'modulo.financiamiento',
])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
});

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ── Siempre disponibles ──────────────────────────────────────
        Route::get('/administracion', AdministracionIndex::class)
            ->middleware('permission:dashboard.ver')
            ->name('administracion.index');

        Route::get('/sistema', SistemaIndex::class)
            ->middleware('permission:dashboard.ver')
            ->name('sistema.index');

        Route::get('/sistema/configuracion', SistemaConfiguracionIndex::class)
            ->middleware('permission:seguridad.roles')
            ->name('sistema.configuracion');

        Route::get('/sistema/apariencia', SistemaBrandingIndex::class)
            ->middleware('permission:dashboard.ver')
            ->name('sistema.apariencia');

        Route::get('/sistema/auditoria', AuditoriaIndex::class)
            ->middleware('permission:auditoria.ver')
            ->name('sistema.auditoria');

        Route::get('/seguridad/roles-permisos', RolesPermisosManager::class)
            ->middleware('permission:seguridad.roles')
            ->name('seguridad.roles-permisos');

        Route::get('/seguridad/usuarios', UsuariosIndex::class)
            ->middleware('permission:seguridad.roles')
            ->name('seguridad.usuarios');

        Route::get('/autos', AutosIndex::class)
            ->middleware('permission:autos.ver')
            ->name('autos.index');

        Route::get('/autos/create', AutosCreate::class)
            ->middleware('permission:autos.crear')
            ->name('autos.create');

        Route::get('/autos/{auto}/edit', AutosEdit::class)
            ->middleware('permission:autos.editar')
            ->name('autos.edit');

        // ── Módulo de financiamiento (condicional) ───────────────────
        Route::middleware('modulo.financiamiento')->group(function () {

            Route::get('/clientes', ClientesIndex::class)
                ->middleware('permission:clientes.ver')
                ->name('clientes.index');

            Route::get('/clientes/create', ClientesCreate::class)
                ->middleware('permission:clientes.crear')
                ->name('clientes.create');

            Route::get('/clientes/{cliente}/edit', ClientesEdit::class)
                ->middleware('permission:clientes.editar')
                ->name('clientes.edit');

            Route::get('/clientes/{cliente}/archivo/{tipo}', [ClienteArchivoController::class, 'show'])
                ->middleware('permission:clientes.ver')
                ->whereIn('tipo', ['ine', 'comprobante'])
                ->name('clientes.archivo');

            Route::get('/apartados-autos', ApartadosAutosIndex::class)
                ->middleware('permission:apartados.ver')
                ->name('apartados-autos.index');

            Route::get('/apartados-autos/create', ApartadosAutosCreate::class)
                ->middleware('permission:apartados.crear')
                ->name('apartados-autos.create');

            Route::get('/contratos-financiamiento', ContratosFinanciamientoIndex::class)
                ->middleware('permission:contratos.ver')
                ->name('contratos-financiamiento.index');

            Route::get('/contratos-financiamiento/create', ContratosFinanciamientoCreate::class)
                ->middleware('permission:contratos.crear')
                ->name('contratos-financiamiento.create');

            Route::get('/contratos-financiamiento/{contrato}', ContratosFinanciamientoShow::class)
                ->middleware('permission:contratos.ver')
                ->name('contratos-financiamiento.show');

            Route::get('/contratos-financiamiento/{contrato}/edit', ContratosFinanciamientoEdit::class)
                ->middleware('permission:contratos.editar')
                ->name('contratos-financiamiento.edit');

            Route::get('/contratos-financiamiento/{contrato}/registrar-pago', ContratosFinanciamientoRegistrarPago::class)
                ->middleware('permission:pagos.registrar')
                ->name('contratos-financiamiento.registrar-pago');

            Route::get('/contratos-financiamiento/{contrato}/archivo', [ContratoFinanciamientoArchivoController::class, 'show'])
                ->middleware('permission:contratos.ver')
                ->name('contratos-financiamiento.archivo');

            Route::get('/recibos', RecibosIndex::class)
                ->middleware('permission:recibos.ver')
                ->name('recibos.index');

            Route::get('/recibos-financiamiento/{recibo}/pdf', [ReciboFinanciamientoPdfController::class, 'show'])
                ->middleware('permission:recibos.imprimir')
                ->name('recibos-financiamiento.pdf');

            Route::get('/recibos/create', RecibosCreate::class)
                ->middleware('permission:recibos.ver')
                ->name('recibos.create');

            Route::get('/recibos/{recibo}', RecibosShow::class)
                ->middleware('permission:recibos.ver')
                ->name('recibos.show');

            Route::get('/recibos/{recibo}/edit', RecibosEdit::class)
                ->middleware('permission:recibos.cancelar')
                ->name('recibos.edit');

            Route::get('/recibos/{recibo}/pdf', [ReciboFinanciamientoPdfController::class, 'show'])
                ->middleware('permission:recibos.imprimir')
                ->name('recibos.pdf');

            Route::get('/finanzas/logs-financieros', LogsFinancierosIndex::class)
                ->middleware('permission:logs_financieros.ver')
                ->name('finanzas.logs-financieros');

            Route::get('/reportes', ReportesIndex::class)
                ->middleware('permission:dashboard.ver')
                ->name('reportes.index');

            Route::get('/reportes/export', [ReportesExportController::class, 'export'])
                ->middleware('permission:dashboard.ver')
                ->name('reportes.export');

            Route::get('/administracion/tarjetas-cobro', TarjetasCobroIndex::class)
                ->middleware('permission:seguridad.roles')
                ->name('administracion.tarjetas-cobro');
        });
    });
