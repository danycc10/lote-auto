<?php

namespace Tests\Feature\Financiamiento;

use App\Models\ApartadoAuto;
use App\Models\Cliente;
use App\Services\Financiamiento\ActualizarContratoFinanciamientoService;
use Illuminate\Validation\ValidationException;

class ActualizarContratoFinanciamientoTest extends FinanciamientoTestCase
{
    /**
     * @return array<string, mixed>
     */
    private function datos(array $cambios = []): array
    {
        return array_merge([
            'auto_id' => 0,
            'cliente_id' => 0,
            'fecha_contrato' => now()->toDateString(),
            'fecha_primer_pago' => now()->addMonth()->toDateString(),
            'precio_contado' => 1,
            'precio_venta' => 120000,
            'enganche' => 10000,
            'comision_apertura' => 1000,
            'monto_seguro' => 0,
            'monto_gps' => 0,
            'monto_financiado' => 1,
            'tasa_interes' => 10,
            'plazo' => 10,
            'frecuencia' => 'mensual',
            'monto_cuota' => 1,
            'total_pagar' => 1,
            'total_pagado' => 999999,
            'saldo_actual' => 1,
            'dias_gracia' => 0,
            'tipo_recargo' => null,
            'valor_recargo' => 0,
            'estatus' => 'activo',
            'observaciones' => null,
        ], $cambios);
    }

    public function test_recalcula_importes_y_conserva_campos_inmutables(): void
    {
        $actor = $this->usuarioConPermiso('contratos.editar');
        $contrato = $this->crearContrato([
            'folio' => 'CF-INMUTABLE',
            'vendedor_id' => $actor->id,
            'ruta_contrato_firmado' => 'contratos/anterior.pdf',
        ]);
        $datos = $this->datos([
            'auto_id' => $contrato->auto_id,
            'cliente_id' => $contrato->cliente_id,
        ]);

        $actualizado = app(ActualizarContratoFinanciamientoService::class)->actualizar(
            $contrato,
            $datos,
            $actor->id,
        );

        $this->assertSame('CF-INMUTABLE', $actualizado->folio);
        $this->assertSame($actor->id, $actualizado->vendedor_id);
        $this->assertSame('100000.00', $actualizado->precio_contado);
        $this->assertSame('111000.00', $actualizado->monto_financiado);
        $this->assertSame('122100.00', $actualizado->total_pagar);
        $this->assertSame('0.00', $actualizado->total_pagado);
        $this->assertSame($actualizado->total_pagar, $actualizado->saldo_actual);
        $this->assertSame('contratos/anterior.pdf', $actualizado->ruta_contrato_firmado);
        $this->assertCount(10, $actualizado->cuotas);
        $this->assertDatabaseHas('historiales_financiamiento', [
            'contrato_financiamiento_id' => $contrato->id,
            'user_id' => $actor->id,
            'evento' => 'contrato_actualizado',
        ]);
    }

    public function test_rechaza_mover_el_contrato_a_un_auto_ya_financiado(): void
    {
        $actor = $this->usuarioConPermiso('contratos.editar');
        $contrato = $this->crearContrato();
        $otroContrato = $this->crearContrato();
        $datos = $this->datos([
            'auto_id' => $otroContrato->auto_id,
            'cliente_id' => $contrato->cliente_id,
        ]);

        $this->expectException(ValidationException::class);

        app(ActualizarContratoFinanciamientoService::class)->actualizar(
            $contrato,
            $datos,
            $actor->id,
        );
    }

    public function test_no_permite_cambiar_auto_o_cliente_si_proviene_de_apartado(): void
    {
        $actor = $this->usuarioConPermiso('contratos.editar');
        $contrato = $this->crearContrato();
        $contrato->auto->update(['estatus' => 'apartado']);
        $apartado = ApartadoAuto::create([
            'folio' => 'AP-'.uniqid(),
            'auto_id' => $contrato->auto_id,
            'cliente_id' => $contrato->cliente_id,
            'usuario_id' => $actor->id,
            'fecha_apartado' => now()->toDateString(),
            'fecha_vencimiento' => now()->addWeek()->toDateString(),
            'monto_anticipo' => 10000,
            'estatus' => 'convertido',
        ]);
        $contrato->update(['apartado_auto_id' => $apartado->id]);
        $otroCliente = Cliente::create(['nombre' => 'Cliente distinto']);
        $datos = $this->datos([
            'auto_id' => $contrato->auto_id,
            'cliente_id' => $otroCliente->id,
        ]);

        $this->expectException(ValidationException::class);

        app(ActualizarContratoFinanciamientoService::class)->actualizar(
            $contrato,
            $datos,
            $actor->id,
        );
    }

    public function test_no_regenera_un_contrato_con_pagos_aplicados(): void
    {
        $actor = $this->usuarioConPermiso('contratos.editar');
        $contrato = $this->crearContrato();
        $cuota = $this->crearCuota($contrato);
        $this->crearPagoYRecibo($contrato, $cuota, $actor);
        $datos = $this->datos([
            'auto_id' => $contrato->auto_id,
            'cliente_id' => $contrato->cliente_id,
        ]);

        $this->expectException(ValidationException::class);

        app(ActualizarContratoFinanciamientoService::class)->actualizar(
            $contrato,
            $datos,
            $actor->id,
        );
    }
}
