<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Database\Seeders\RolesPermisosSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReportesExportAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesPermisosSeeder::class);
        Queue::fake();
    }

    public function test_usuario_con_permiso_de_reportes_puede_exportar_sin_permiso_de_dashboard(): void
    {
        $usuario = $this->usuarioConRol('reportero', ['reportes.ver']);

        $this->actingAs($usuario)
            ->post(route('admin.reportes.export'), ['tipo' => 'inventario'])
            ->assertRedirect(route('admin.reportes.index'));
    }

    public function test_usuario_con_dashboard_pero_sin_reportes_no_puede_exportar(): void
    {
        $usuario = $this->usuarioConRol('operador-dashboard', ['dashboard.ver']);

        $this->actingAs($usuario)
            ->post(route('admin.reportes.export'), ['tipo' => 'inventario'])
            ->assertForbidden();
    }

    public function test_administrador_puede_exportar_reportes(): void
    {
        $administrador = User::factory()->create();
        $administrador->assignRole('administrador');

        $this->actingAs($administrador)
            ->post(route('admin.reportes.export'), ['tipo' => 'inventario'])
            ->assertRedirect(route('admin.reportes.index'));
    }

    /**
     * @param  list<string>  $permisos
     */
    private function usuarioConRol(string $nombreRol, array $permisos): User
    {
        $rol = Role::findOrCreate($nombreRol, 'web');
        $rol->syncPermissions($permisos);

        $usuario = User::factory()->create();
        $usuario->assignRole($rol);

        return $usuario;
    }
}
