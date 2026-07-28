<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\RolesPermisosSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBootstrapSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_crea_una_cuenta_administrativa_sin_configuracion_explicita(): void
    {
        config()->set('bootstrap.admin.email');
        config()->set('bootstrap.admin.password');

        $this->seed(AdminUserSeeder::class);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_crea_el_administrador_configurado_con_su_rol(): void
    {
        config()->set('bootstrap.admin.email', 'bootstrap@example.test');
        config()->set('bootstrap.admin.password', 'Clave-Segura-2026!');

        $this->seed(RolesPermisosSeeder::class);
        $this->seed(AdminUserSeeder::class);

        $administrator = User::where('email', 'bootstrap@example.test')->firstOrFail();

        $this->assertTrue($administrator->hasRole('administrador'));
    }

    public function test_el_seeder_de_permisos_no_promueve_al_primer_usuario(): void
    {
        $user = User::factory()->create();

        $this->seed(RolesPermisosSeeder::class);

        $this->assertFalse($user->fresh()->hasRole('administrador'));
    }
}
