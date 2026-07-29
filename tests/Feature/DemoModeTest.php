<?php

namespace Tests\Feature;

use App\Exceptions\DemoModeException;
use App\Livewire\Public\FormularioContacto;
use App\Models\User;
use App\Services\Security\RoleAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class DemoModeTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_banner_is_only_visible_when_demo_mode_is_enabled(): void
    {
        config(['demo.enabled' => false]);

        $this->assertStringNotContainsString(
            'Modo demo',
            Blade::render('<x-demo-mode-banner />'),
        );

        config(['demo.enabled' => true]);

        $this->assertStringContainsString(
            'Modo demo',
            Blade::render('<x-demo-mode-banner />'),
        );
    }

    public function test_demo_mode_blocks_livewire_mutations_without_sending_mail(): void
    {
        Notification::fake();
        config(['demo.enabled' => true]);

        Livewire::test(FormularioContacto::class)
            ->set('nombre', 'Visitante Demo')
            ->set('correo', 'visitante@example.com')
            ->call('enviar')
            ->assertDispatched('toast', type: 'warning', title: 'Modo demo');

        $this->assertDatabaseCount('prospectos', 0);
        Notification::assertNothingSent();
    }

    public function test_model_guard_blocks_unprotected_writes_in_demo_mode(): void
    {
        $user = User::factory()->create();
        config([
            'demo.enabled' => true,
            'demo.allow_console_writes' => false,
        ]);

        $this->expectException(DemoModeException::class);

        $user->forceFill(['name' => 'Nombre modificado'])->save();
    }

    public function test_domain_services_are_protected_even_outside_livewire(): void
    {
        $actor = User::factory()->create();
        $target = User::factory()->create();
        config(['demo.enabled' => true]);

        $this->expectException(DemoModeException::class);

        app(RoleAssignmentService::class)->syncRoles($actor, $target, []);
    }

    public function test_fortify_mutations_are_blocked_without_sending_notifications(): void
    {
        User::factory()->create(['email' => 'demo@example.com']);
        Notification::fake();
        config(['demo.enabled' => true]);

        $this->post('/forgot-password', [
            'email' => 'demo@example.com',
        ])
            ->assertStatus(423)
            ->assertSeeText('Acción no disponible');

        Notification::assertNothingSent();
    }

    public function test_login_and_read_only_navigation_remain_available(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);
        config(['demo.enabled' => true]);

        $this->get('/')->assertOk();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
    }

    public function test_uploads_and_shared_account_secrets_are_blocked(): void
    {
        $user = User::factory()->create();
        config(['demo.enabled' => true]);

        $this->post('/livewire/upload-file')->assertStatus(423);

        $this->actingAs($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get('/user/two-factor-qr-code')
            ->assertStatus(423);
    }

    public function test_scheduled_mutations_exit_successfully_in_demo_mode(): void
    {
        config(['demo.enabled' => true]);

        $this->artisan('cuotas:marcar-vencidas')
            ->expectsOutputToContain('Modo demo activo')
            ->assertSuccessful();

        $this->artisan('apartados:vencer')
            ->expectsOutputToContain('Modo demo activo')
            ->assertSuccessful();

        $this->artisan('cuotas:notificar-vencimientos')
            ->expectsOutputToContain('Modo demo activo')
            ->assertSuccessful();
    }
}
