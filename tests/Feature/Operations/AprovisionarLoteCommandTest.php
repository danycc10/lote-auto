<?php

namespace Tests\Feature\Operations;

use App\Models\Configuracion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AprovisionarLoteCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_configura_una_identidad_unica_y_un_administrador(): void
    {
        config([
            'app.version' => '2.3.0',
            'bootstrap.admin.password' => 'SeguraInicial!2026',
        ]);

        $this->artisan('lote:aprovisionar', [
            '--name' => 'Autos Matamoros',
            '--slug' => 'autos-matamoros',
            '--admin-email' => 'admin@autos.test',
        ])->assertSuccessful();

        $admin = User::query()->where('email', 'admin@autos.test')->firstOrFail();

        $this->assertTrue($admin->hasRole('administrador'));
        $this->assertSame('Autos Matamoros', Configuracion::obtener('instalacion.nombre'));
        $this->assertSame('autos-matamoros', Configuracion::obtener('instalacion.slug'));
        $this->assertSame('2.3.0', Configuracion::obtener('instalacion.version'));
        $this->assertNotEmpty(Configuracion::obtener('instalacion.uuid'));
        $this->assertNotEmpty(Configuracion::obtener('instalacion.instalada_at'));
    }

    public function test_no_reemplaza_accidentalmente_una_instalacion_existente(): void
    {
        Configuracion::establecer('instalacion.uuid', 'instalacion-existente');

        $this->artisan('lote:aprovisionar', [
            '--name' => 'Otro lote',
            '--admin-email' => 'admin@otro.test',
        ])->assertFailed();

        $this->assertSame('instalacion-existente', Configuracion::obtener('instalacion.uuid'));
        $this->assertNull(User::query()->where('email', 'admin@otro.test')->first());
    }
}
