<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_configuracion_regional_predeterminada_es_espanol_de_mexico(): void
    {
        $this->assertSame('es', config('app.locale'));
        $this->assertSame('es', config('app.fallback_locale'));
        $this->assertSame('es_MX', config('app.faker_locale'));
    }

    public function test_las_validaciones_de_laravel_se_muestran_en_espanol(): void
    {
        $response = $this->from('/login')->post('/login', []);

        $response
            ->assertRedirect('/login')
            ->assertSessionHasErrors([
                'email' => 'El campo correo electrónico es obligatorio.',
                'password' => 'El campo contraseña es obligatorio.',
            ]);
    }

    public function test_el_error_de_credenciales_de_fortify_se_muestra_en_espanol(): void
    {
        $user = User::factory()->create();

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'incorrecta',
        ]);

        $response
            ->assertRedirect('/login')
            ->assertSessionHasErrors([
                'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
            ]);
    }
}
