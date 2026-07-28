<?php

namespace Tests\Feature\Security;

use App\Models\User;
use App\Services\Security\RoleAssignmentService;
use Database\Seeders\RolesPermisosSeeder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleAssignmentServiceTest extends TestCase
{
    use RefreshDatabase;

    private RoleAssignmentService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesPermisosSeeder::class);
        $this->service = app(RoleAssignmentService::class);
    }

    public function test_gerente_no_recibe_permisos_de_seguridad(): void
    {
        $manager = User::factory()->create();
        $manager->assignRole('gerente');

        $this->assertFalse($manager->can('seguridad.roles'));
        $this->assertFalse($manager->can('seguridad.usuarios'));
    }

    public function test_gestor_de_usuarios_no_puede_asignar_administrador(): void
    {
        $role = Role::create(['name' => 'gestor-usuarios', 'guard_name' => 'web']);
        $role->givePermissionTo('seguridad.usuarios');

        $actor = User::factory()->create();
        $actor->assignRole($role);
        $target = User::factory()->create();

        $this->expectException(AuthorizationException::class);

        $this->service->syncRoles($actor, $target, ['administrador']);
    }

    public function test_no_permite_remover_al_ultimo_administrador(): void
    {
        $administrator = User::factory()->create();
        $administrator->assignRole('administrador');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Debe existir al menos otro usuario administrador.');

        $this->service->syncRoles($administrator, $administrator, ['gerente']);
    }

    public function test_no_permite_eliminar_al_ultimo_administrador(): void
    {
        $securityRole = Role::create(['name' => 'seguridad-total', 'guard_name' => 'web']);
        $securityRole->givePermissionTo([
            'seguridad.usuarios',
            'seguridad.roles.asignar_administrador',
        ]);

        $actor = User::factory()->create();
        $actor->assignRole($securityRole);
        $target = User::factory()->create();
        $target->assignRole('administrador');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Debe existir al menos otro usuario administrador.');

        $this->service->delete($actor, $target);
    }

    public function test_administrador_puede_promover_a_otro_usuario(): void
    {
        $administrator = User::factory()->create();
        $administrator->assignRole('administrador');
        $target = User::factory()->create();

        $this->service->syncRoles($administrator, $target, ['administrador']);

        $this->assertTrue($target->fresh()->hasRole('administrador'));
    }
}
