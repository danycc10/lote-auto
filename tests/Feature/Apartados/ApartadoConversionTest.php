<?php

namespace Tests\Feature\Apartados;

use App\Models\ApartadoAuto;
use App\Models\Auto;
use App\Models\Cliente;
use App\Models\ContratoFinanciamiento;
use App\Models\User;
use App\Services\Apartados\ConvertirApartadoEnContratoService;
use App\Services\Apartados\CrearApartadoAutoService;
use App\Services\Apartados\VencerApartadosAutoService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ApartadoConversionTest extends TestCase
{
    use RefreshDatabase;

    private function crearAutoDisponible(): Auto
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
            'anio' => 2021,
            'estatus' => 'disponible',
            'activo' => true,
            'precio_contado' => 150000,
            'precio_financiado' => 180000,
        ]);
    }

    private function crearApartado(Auto $auto, Cliente $cliente, User $user): ApartadoAuto
    {
        return app(CrearApartadoAutoService::class)->ejecutar([
            'auto_id' => $auto->id,
            'cliente_id' => $cliente->id,
            'usuario_id' => $user->id,
            'fecha_apartado' => now()->toDateString(),
            'fecha_vencimiento' => now()->addDays(7)->toDateString(),
            'monto_anticipo' => 10000,
        ]);
    }

    private function crearContrato(Auto $auto, Cliente $cliente): ContratoFinanciamiento
    {
        return ContratoFinanciamiento::create([
            'folio' => 'CF-'.uniqid(),
            'auto_id' => $auto->id,
            'cliente_id' => $cliente->id,
            'fecha_contrato' => now()->toDateString(),
            'plazo' => 12,
            'estatus' => 'activo',
        ]);
    }

    public function test_crear_apartado_marca_auto_como_apartado(): void
    {
        $user = User::factory()->create();
        $cliente = Cliente::create(['nombre' => 'Cliente A']);
        $auto = $this->crearAutoDisponible();

        $apartado = $this->crearApartado($auto, $cliente, $user);

        $this->assertSame('activo', $apartado->estatus);
        $this->assertSame('apartado', $auto->fresh()->estatus);
    }

    public function test_no_aparta_auto_que_no_esta_disponible(): void
    {
        $user = User::factory()->create();
        $cliente = Cliente::create(['nombre' => 'Cliente B']);
        $auto = $this->crearAutoDisponible();
        $auto->update(['estatus' => 'financiado']);

        $this->expectException(ValidationException::class);
        $this->crearApartado($auto, $cliente, $user);
    }

    public function test_conversion_actualiza_estatus_de_apartado_auto_y_contrato(): void
    {
        $user = User::factory()->create();
        $cliente = Cliente::create(['nombre' => 'Cliente C']);
        $auto = $this->crearAutoDisponible();
        $apartado = $this->crearApartado($auto, $cliente, $user);
        $contrato = $this->crearContrato($auto, $cliente);

        $service = app(ConvertirApartadoEnContratoService::class);
        $service->validarParaConvertir($apartado);
        $resultado = $service->finalizarConversion($apartado, $contrato);

        $this->assertSame('convertido', $resultado->estatus);
        $this->assertSame('financiado', $auto->fresh()->estatus);
        $this->assertSame($apartado->id, $contrato->fresh()->apartado_auto_id);
    }

    public function test_no_permite_doble_conversion(): void
    {
        $user = User::factory()->create();
        $cliente = Cliente::create(['nombre' => 'Cliente D']);
        $auto = $this->crearAutoDisponible();
        $apartado = $this->crearApartado($auto, $cliente, $user);
        $contrato = $this->crearContrato($auto, $cliente);

        $service = app(ConvertirApartadoEnContratoService::class);
        $service->finalizarConversion($apartado, $contrato);

        // Segundo intento sobre el mismo apartado (ya convertido) debe rechazarse.
        $this->expectException(ValidationException::class);
        $service->finalizarConversion($apartado->fresh(), $this->crearContrato($auto, $cliente));
    }

    public function test_base_de_datos_impide_asociar_dos_contratos_al_mismo_apartado(): void
    {
        $user = User::factory()->create();
        $cliente = Cliente::create(['nombre' => 'Cliente E']);
        $auto = $this->crearAutoDisponible();
        $apartado = $this->crearApartado($auto, $cliente, $user);

        $this->crearContrato($auto, $cliente)->update([
            'apartado_auto_id' => $apartado->id,
        ]);
        $segundoContrato = $this->crearContrato($auto, $cliente);

        $this->expectException(QueryException::class);

        $segundoContrato->update([
            'apartado_auto_id' => $apartado->id,
        ]);
    }

    public function test_vencimiento_no_libera_un_apartado_ya_convertido(): void
    {
        $user = User::factory()->create();
        $cliente = Cliente::create(['nombre' => 'Cliente convertido']);
        $auto = $this->crearAutoDisponible();
        $apartado = $this->crearApartado($auto, $cliente, $user);
        $apartado->update([
            'fecha_vencimiento' => today()->subDay()->toDateString(),
        ]);
        $contrato = $this->crearContrato($auto, $cliente);
        app(ConvertirApartadoEnContratoService::class)->finalizarConversion($apartado, $contrato);

        $vencidos = app(VencerApartadosAutoService::class)->ejecutar();

        $this->assertSame(0, $vencidos);
        $this->assertSame('convertido', $apartado->fresh()->estatus);
        $this->assertSame('financiado', $auto->fresh()->estatus);
    }
}
