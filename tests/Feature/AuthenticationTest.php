<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_professional_login_is_rendered_outside_demo_mode(): void
    {
        config(['demo.enabled' => false]);

        $response = $this->get('/login');

        $response
            ->assertStatus(200)
            ->assertSeeText('Iniciar sesión')
            ->assertSeeText('Accede al panel administrativo.')
            ->assertDontSeeText('Todo tu lote')
            ->assertSee('autocomplete="username"', false)
            ->assertSee('type="password"', false)
            ->assertSee('aria-label="Mostrar contraseña"', false);
    }

    public function test_current_login_is_preserved_in_demo_mode(): void
    {
        config(['demo.enabled' => true]);

        $this->get('/login')
            ->assertOk()
            ->assertSeeText('Bienvenido de nuevo')
            ->assertSeeText('Todo tu lote')
            ->assertSeeText('Seguimiento de contratos y pagos en tiempo real')
            ->assertSee('type="password"', false);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
