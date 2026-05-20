<?php

namespace Tests\Feature\Financiamiento;

use App\Models\Cliente;
use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use App\Models\PagoFinanciamiento;
use App\Models\ReciboFinanciamiento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

abstract class FinanciamientoTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    protected function usuarioConPermiso(string ...$permisos): User
    {
        $user = User::factory()->create();
        foreach ($permisos as $permiso) {
            $perm = Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'web']);
            $user->givePermissionTo($perm);
        }
        return $user;
    }

    protected function crearContrato(array $attrs = []): ContratoFinanciamiento
    {
        $uid = uniqid('', true);

        $cliente = Cliente::create(['nombre' => 'Cliente ' . $uid]);

        $marcaId = DB::table('marcas_autos')->insertGetId([
            'nombre' => 'Marca ' . $uid,
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $modeloId = DB::table('modelos_autos')->insertGetId([
            'marca_auto_id' => $marcaId,
            'nombre' => 'Modelo ' . $uid,
            'activo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $autoId = DB::table('autos')->insertGetId([
            'marca_auto_id' => $marcaId,
            'modelo_auto_id' => $modeloId,
            'anio' => 2020,
            'estatus' => 'financiado',
            'activo' => 1,
            'precio_contado' => 100000,
            'precio_financiado' => 120000,
            'kilometraje' => 0,
            'destacado' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return ContratoFinanciamiento::create(array_merge([
            'folio' => 'CF-TEST-' . substr($uid, -8),
            'auto_id' => $autoId,
            'cliente_id' => $cliente->id,
            'fecha_contrato' => now()->toDateString(),
            'fecha_primer_pago' => now()->addMonth()->toDateString(),
            'precio_contado' => 100000,
            'precio_venta' => 120000,
            'enganche' => 20000,
            'monto_financiado' => 100000,
            'tasa_interes' => 0,
            'plazo' => 4,
            'frecuencia' => 'mensual',
            'monto_cuota' => 25000,
            'total_pagar' => 100000,
            'total_pagado' => 0,
            'saldo_actual' => 100000,
            'estatus' => 'activo',
        ], $attrs));
    }

    protected function crearCuota(ContratoFinanciamiento $contrato, int $numero = 1, array $attrs = []): CuotaFinanciamiento
    {
        return CuotaFinanciamiento::create(array_merge([
            'contrato_financiamiento_id' => $contrato->id,
            'numero' => $numero,
            'fecha_vencimiento' => now()->addMonths($numero)->toDateString(),
            'monto_capital' => 25000,
            'monto_interes' => 0,
            'monto_extra' => 0,
            'monto' => 25000,
            'monto_pagado' => 0,
            'saldo' => 25000,
            'estatus' => 'pendiente',
        ], $attrs));
    }

    protected function crearPagoYRecibo(
        ContratoFinanciamiento $contrato,
        CuotaFinanciamiento $cuota,
        User $user
    ): array {
        $pago = PagoFinanciamiento::create([
            'contrato_financiamiento_id' => $contrato->id,
            'cuota_id' => $cuota->id,
            'cliente_id' => $contrato->cliente_id,
            'capturado_por' => $user->id,
            'fecha_pago' => now()->toDateString(),
            'monto' => $cuota->monto,
            'monto_aplicado' => $cuota->monto,
            'monto_restante' => 0,
            'forma_pago' => 'efectivo',
            'estatus' => 'aplicado',
        ]);

        $saldoAnterior = (float) $contrato->saldo_actual;
        $saldoPosterior = max(0, $saldoAnterior - (float) $cuota->monto);

        $uid = uniqid('', true);
        $recibo = ReciboFinanciamiento::create([
            'folio' => 'RF-' . substr($uid, -10),
            'contrato_financiamiento_id' => $contrato->id,
            'cuota_id' => $cuota->id,
            'pago_financiamiento_id' => $pago->id,
            'cliente_id' => $contrato->cliente_id,
            'fecha_recibo' => now()->toDateString(),
            'monto' => $cuota->monto,
            'saldo_anterior' => $saldoAnterior,
            'saldo_posterior' => $saldoPosterior,
            'concepto' => 'Pago de cuota #' . $cuota->numero,
            'estatus' => 'vigente',
        ]);

        return ['pago' => $pago, 'recibo' => $recibo];
    }
}
