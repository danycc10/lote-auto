<?php

namespace Tests\Feature\Public;

use App\Livewire\Public\FormularioContacto;
use App\Models\Auto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class PublicFlowTest extends TestCase
{
    use RefreshDatabase;

    private function crearAuto(string $estatus = 'disponible', bool $activo = true): Auto
    {
        $uid = uniqid('', true);

        $marcaId = DB::table('marcas_autos')->insertGetId([
            'nombre' => 'Marca '.$uid, 'activo' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        $modeloId = DB::table('modelos_autos')->insertGetId([
            'marca_auto_id' => $marcaId, 'nombre' => 'Modelo '.$uid, 'activo' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);

        return Auto::create([
            'marca_auto_id' => $marcaId,
            'modelo_auto_id' => $modeloId,
            'anio' => 2022,
            'estatus' => $estatus,
            'activo' => $activo,
            'precio_contado' => 200000,
            'precio_financiado' => 240000,
        ]);
    }

    public function test_landing_responde_200(): void
    {
        $this->get(route('public.home'))->assertStatus(200);
    }

    public function test_catalogo_responde_200(): void
    {
        $this->crearAuto();
        $this->get(route('public.autos'))->assertStatus(200);
    }

    public function test_detalle_auto_disponible_responde_200(): void
    {
        $auto = $this->crearAuto();
        $this->get(route('public.autos.show', $auto->uuid))->assertStatus(200);
    }

    public function test_detalle_auto_vendido_responde_404(): void
    {
        $auto = $this->crearAuto('vendido_contado');
        $this->get(route('public.autos.show', $auto->uuid))->assertNotFound();
    }

    public function test_detalle_auto_inactivo_responde_404(): void
    {
        $auto = $this->crearAuto('disponible', false);
        $this->get(route('public.autos.show', $auto->uuid))->assertNotFound();
    }

    public function test_formulario_contacto_crea_prospecto(): void
    {
        Mail::fake();

        Livewire::test(FormularioContacto::class)
            ->set('nombre', 'Prospecto Web')
            ->set('telefono', '5550001111')
            ->set('correo', 'prospecto@example.com')
            ->set('mensaje', 'Me interesa un auto')
            ->call('enviar')
            ->assertSet('enviado', true)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('prospectos', [
            'nombre' => 'Prospecto Web',
            'origen' => 'web',
            'estatus' => 'nuevo',
        ]);
    }

    public function test_formulario_contacto_requiere_nombre(): void
    {
        Mail::fake();

        Livewire::test(FormularioContacto::class)
            ->set('nombre', '')
            ->call('enviar')
            ->assertHasErrors(['nombre' => 'required']);

        $this->assertDatabaseCount('prospectos', 0);
    }
}
