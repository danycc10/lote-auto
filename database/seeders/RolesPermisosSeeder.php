<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermisosSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permisos = [
            'dashboard.ver',
            'reportes.ver',

            'autos.ver',
            'autos.crear',
            'autos.editar',
            'autos.eliminar',

            'clientes.ver',
            'clientes.crear',
            'clientes.editar',
            'clientes.eliminar',

            'apartados.ver',
            'apartados.crear',
            'apartados.editar',
            'apartados.cancelar',
            'apartados.convertir',

            'contratos.ver',
            'contratos.crear',
            'contratos.editar',
            'contratos.cancelar',
            'contratos.reestructurar',

            'pagos.ver',
            'pagos.registrar',
            'pagos.cancelar',

            'seguridad.roles',

            'recibos.ver',
            'recibos.imprimir',
            'recibos.cancelar',

            'logs_financieros.ver',
            'auditoria.ver',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate([
                'name' => $permiso,
                'guard_name' => 'web',
            ]);
        }

        $administrador = Role::firstOrCreate(['name' => 'administrador', 'guard_name' => 'web']);
        $gerente = Role::firstOrCreate(['name' => 'gerente', 'guard_name' => 'web']);
        $cobrador = Role::firstOrCreate(['name' => 'cobrador', 'guard_name' => 'web']);
        $vendedor = Role::firstOrCreate(['name' => 'vendedor', 'guard_name' => 'web']);
        $auditor = Role::firstOrCreate(['name' => 'auditor', 'guard_name' => 'web']);

        $administrador->syncPermissions($permisos);

        $gerente->syncPermissions([
            'dashboard.ver',
            'reportes.ver',
            'autos.ver',
            'clientes.ver',
            'clientes.crear',
            'clientes.editar',
            'apartados.ver',
            'apartados.crear',
            'apartados.editar',
            'apartados.cancelar',
            'apartados.convertir',
            'contratos.ver',
            'contratos.crear',
            'contratos.editar',
            'contratos.cancelar',
            'contratos.reestructurar',
            'pagos.ver',
            'pagos.registrar',
            'pagos.cancelar',
            'recibos.ver',
            'recibos.imprimir',
            'recibos.cancelar',
            'seguridad.roles',
            'logs_financieros.ver',
        ]);

        $cobrador->syncPermissions([
            'dashboard.ver',
            'clientes.ver',
            'contratos.ver',
            'pagos.ver',
            'pagos.registrar',
            'recibos.ver',
            'recibos.imprimir',
        ]);

        $vendedor->syncPermissions([
            'dashboard.ver',
            'autos.ver',
            'clientes.ver',
            'clientes.crear',
            'clientes.editar',
            'apartados.ver',
            'apartados.crear',
            'apartados.editar',
            'apartados.convertir',
            'contratos.ver',
            'contratos.crear',
        ]);

        $auditor->syncPermissions([
            'dashboard.ver',
            'reportes.ver',
            'autos.ver',
            'clientes.ver',
            'apartados.ver',
            'contratos.ver',
            'pagos.ver',
            'recibos.ver',
        ]);

        $primerUsuario = User::query()->orderBy('id')->first();

        if ($primerUsuario && ! $primerUsuario->hasRole('administrador')) {
            $primerUsuario->assignRole('administrador');
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}