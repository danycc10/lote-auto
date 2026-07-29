<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
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

    public function test_los_textos_de_jetstream_y_fortify_se_muestran_en_espanol(): void
    {
        $this->assertSame('Perfil', __('Profile'));
        $this->assertSame('Autenticación de dos factores', __('Two Factor Authentication'));
        $this->assertSame(
            'Esta contraseña no coincide con nuestros registros.',
            __('This password does not match our records.')
        );
    }

    public function test_el_correo_para_restablecer_la_contrasena_se_muestra_en_espanol(): void
    {
        $user = User::factory()->make();
        $message = (new ResetPassword('token-de-prueba'))->toMail($user);

        $this->assertSame('Restablezca su contraseña', $message->subject);
        $this->assertSame('Restablecer contraseña', $message->actionText);
        $this->assertContains(
            'Recibió este mensaje porque se solicitó restablecer la contraseña de su cuenta.',
            $message->introLines
        );
        $this->assertContains(
            'Si no solicitó restablecer su contraseña, no necesita realizar ninguna acción.',
            $message->outroLines
        );
    }

    public function test_la_pagina_no_encontrada_se_muestra_en_espanol(): void
    {
        $this->get('/ruta-inexistente-para-probar-localizacion')
            ->assertNotFound()
            ->assertSeeText('Página no encontrada')
            ->assertSeeText('Volver al inicio');
    }
}
