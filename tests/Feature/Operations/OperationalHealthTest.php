<?php

namespace Tests\Feature\Operations;

use App\Livewire\Admin\Sistema\SaludIndex;
use App\Models\Configuracion;
use App\Models\EstadoOperacion;
use App\Models\User;
use Database\Seeders\RolesPermisosSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OperationalHealthTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_users_with_health_permission_can_open_the_screen(): void
    {
        $this->seed(RolesPermisosSeeder::class);

        $withoutPermission = User::factory()->create();
        $withPermission = User::factory()->create();
        $withPermission->assignRole('gerente');

        $this->actingAs($withoutPermission)
            ->get(route('admin.sistema.salud'))
            ->assertForbidden();

        $this->actingAs($withPermission)
            ->get(route('admin.sistema.salud'))
            ->assertOk()
            ->assertSeeText('Salud de la instalación');
    }

    public function test_health_screen_reports_operational_heartbeats_and_installation_identity(): void
    {
        $this->seed(RolesPermisosSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('administrador');

        Configuracion::establecer('instalacion.nombre', 'Lote Norte');
        Configuracion::establecer('instalacion.slug', 'lote-norte');
        Configuracion::establecer('instalacion.uuid', '018f-test-installation');

        EstadoOperacion::query()->create([
            'clave' => 'scheduler',
            'estado' => 'ok',
            'mensaje' => 'Scheduler activo.',
            'ejecutado_at' => now(),
        ]);
        EstadoOperacion::query()->create([
            'clave' => 'queue',
            'estado' => 'ok',
            'mensaje' => 'Cola activa.',
            'ejecutado_at' => now(),
        ]);

        config()->set('hosting.queue_worker.mode', 'cron');
        config()->set('queue.default', 'database');
        config()->set('backup.enabled', false);

        $this->actingAs($user);

        Livewire::test(SaludIndex::class)
            ->assertSee('Lote Norte')
            ->assertSee('lote-norte')
            ->assertSee('Scheduler activo.')
            ->assertSee('Cola activa.')
            ->call('refreshHealth')
            ->assertOk();
    }

    public function test_health_screen_does_not_render_secrets(): void
    {
        $this->seed(RolesPermisosSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('administrador');

        config()->set('app.key', 'base64:'.base64_encode(str_repeat('s', 32)));
        config()->set('database.connections.sqlite.password', 'never-show-database-password');

        $this->actingAs($user)
            ->get(route('admin.sistema.salud'))
            ->assertOk()
            ->assertDontSee('never-show-this-secret')
            ->assertDontSee('never-show-database-password');
    }
}
