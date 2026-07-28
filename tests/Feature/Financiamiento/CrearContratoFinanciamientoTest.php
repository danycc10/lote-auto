<?php

namespace Tests\Feature\Financiamiento;

use App\Models\Auto;
use App\Models\Cliente;
use App\Models\User;
use App\Services\Apartados\CrearApartadoAutoService;
use App\Services\Financiamiento\CrearContratoFinanciamientoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CrearContratoFinanciamientoTest extends TestCase
{
    use RefreshDatabase;

    private function crearAuto(string $estatus = 'disponible'): Auto
    {
        $uid = uniqid('', true);
        $marcaId = DB::table('marcas_autos')->insertGetId([
            'nombre' => 'Marca '.$uid,
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $modeloId = DB::table('modelos_autos')->insertGetId([
            'marca_auto_id' => $marcaId,
            'nombre' => 'Modelo '.$uid,
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Auto::create([
            'marca_auto_id' => $marcaId,
            'modelo_auto_id' => $modeloId,
            'anio' => 2024,
            'estatus' => $estatus,
            'activo' => true,
            'precio_contado' => 110000,
            'precio_financiado' => 120000,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function datos(Auto $auto, Cliente $cliente): array
    {
        return [
            'auto_id' => $auto->id,
            'cliente_id' => $cliente->id,
            'fecha_contrato' => now()->toDateString(),
            'fecha_primer_pago' => now()->addMonth()->toDateString(),
            'precio_contado' => 1,
            'precio_venta' => 120000,
            'enganche' => 20000,
            'comision_apertura' => 1000,
            'monto_seguro' => 0,
            'monto_gps' => 0,
            'monto_financiado' => 1,
            'tasa_interes' => 12,
            'plazo' => 12,
            'frecuencia' => 'mensual',
            'monto_cuota' => 1,
            'total_pagar' => 1,
            'total_pagado' => 999999,
            'saldo_actual' => 1,
            'dias_gracia' => 3,
            'tipo_recargo' => null,
            'valor_recargo' => 0,
            'estatus' => 'activo',
            'observaciones' => null,
        ];
    }

    public function test_crea_con_actor_y_totales_autoritativos(): void
    {
        $actor = User::factory()->create();
        $cliente = Cliente::create(['nombre' => 'Cliente seguro']);
        $auto = $this->crearAuto();

        $contrato = app(CrearContratoFinanciamientoService::class)->crear(
            $this->datos($auto, $cliente),
            null,
            $actor->id,
        );

        $this->assertSame($actor->id, $contrato->vendedor_id);
        $this->assertSame('110000.00', $contrato->precio_contado);
        $this->assertSame('101000.00', $contrato->monto_financiado);
        $this->assertSame('0.00', $contrato->total_pagado);
        $this->assertSame($contrato->total_pagar, $contrato->saldo_actual);
        $this->assertCount(12, $contrato->cuotas);
        $this->assertSame('financiado', $auto->fresh()->estatus);
        $this->assertDatabaseHas('historiales_financiamiento', [
            'contrato_financiamiento_id' => $contrato->id,
            'user_id' => $actor->id,
            'evento' => 'contrato_creado',
        ]);
    }

    public function test_no_permite_financiar_un_auto_apartado_sin_convertir_su_apartado(): void
    {
        $actor = User::factory()->create();
        $cliente = Cliente::create(['nombre' => 'Cliente apartado']);
        $auto = $this->crearAuto();
        app(CrearApartadoAutoService::class)->ejecutar([
            'auto_id' => $auto->id,
            'cliente_id' => $cliente->id,
            'usuario_id' => $actor->id,
            'fecha_apartado' => now()->toDateString(),
            'fecha_vencimiento' => now()->addWeek()->toDateString(),
            'monto_anticipo' => 10000,
        ]);

        $this->expectException(ValidationException::class);

        app(CrearContratoFinanciamientoService::class)->crear(
            $this->datos($auto->fresh(), $cliente),
            null,
            $actor->id,
        );
    }

    public function test_convierte_el_apartado_correspondiente_dentro_de_la_operacion(): void
    {
        $actor = User::factory()->create();
        $cliente = Cliente::create(['nombre' => 'Cliente conversión']);
        $auto = $this->crearAuto();
        $apartado = app(CrearApartadoAutoService::class)->ejecutar([
            'auto_id' => $auto->id,
            'cliente_id' => $cliente->id,
            'usuario_id' => $actor->id,
            'fecha_apartado' => now()->toDateString(),
            'fecha_vencimiento' => now()->addWeek()->toDateString(),
            'monto_anticipo' => 10000,
        ]);

        $contrato = app(CrearContratoFinanciamientoService::class)->crear(
            $this->datos($auto->fresh(), $cliente),
            $apartado->id,
            $actor->id,
        );

        $this->assertSame($apartado->id, $contrato->apartado_auto_id);
        $this->assertSame('convertido', $apartado->fresh()->estatus);
        $this->assertSame('financiado', $auto->fresh()->estatus);
    }

    public function test_no_permite_un_segundo_contrato_vigente_para_el_mismo_auto(): void
    {
        $actor = User::factory()->create();
        $cliente = Cliente::create(['nombre' => 'Cliente único']);
        $auto = $this->crearAuto();
        $servicio = app(CrearContratoFinanciamientoService::class);
        $servicio->crear($this->datos($auto, $cliente), null, $actor->id);
        $auto->update(['estatus' => 'disponible']);

        $this->expectException(ValidationException::class);

        $servicio->crear($this->datos($auto->fresh(), $cliente), null, $actor->id);
    }
}
