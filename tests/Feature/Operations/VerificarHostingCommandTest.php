<?php

namespace Tests\Feature\Operations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificarHostingCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_diagnostico_acepta_el_entorno_de_pruebas_compatible(): void
    {
        config(['hosting.required_extensions' => []]);

        $this->artisan('hosting:verificar')
            ->expectsOutputToContain('Resultado: 0 errores')
            ->assertSuccessful();
    }

    public function test_el_diagnostico_falla_si_falta_una_extension_obligatoria(): void
    {
        config(['hosting.required_extensions' => ['extension_que_no_existe']]);

        $this->artisan('hosting:verificar')
            ->expectsOutputToContain('Faltan: extension_que_no_existe')
            ->expectsOutputToContain('Resultado: 1 errores')
            ->assertFailed();
    }
}
