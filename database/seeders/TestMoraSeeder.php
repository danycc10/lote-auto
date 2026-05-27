<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestMoraSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = [
            [
                'nombre'           => 'Roberto',
                'apellido_paterno' => 'Martínez',
                'apellido_materno' => 'López',
                'telefono'         => '5551234001',
                'correo'           => 'roberto.martinez@ejemplo.com',
                'curp'             => 'MALR850101HDFRZB01',
                'rfc'              => 'MALR850101AB1',
                'direccion'        => 'Calle Reforma 12',
                'ciudad'           => 'Ciudad de México',
                'estado'           => 'CDMX',
                'codigo_postal'    => '06600',
                'activo'           => true,
            ],
            [
                'nombre'           => 'Sofía',
                'apellido_paterno' => 'Hernández',
                'apellido_materno' => 'Ruiz',
                'telefono'         => '5559872002',
                'correo'           => 'sofia.hernandez@ejemplo.com',
                'curp'             => 'HERS920315MDFRNF02',
                'rfc'              => 'HERS920315CD2',
                'direccion'        => 'Av. Insurgentes 450',
                'ciudad'           => 'Guadalajara',
                'estado'           => 'Jalisco',
                'codigo_postal'    => '44100',
                'activo'           => true,
            ],
            [
                'nombre'           => 'Miguel',
                'apellido_paterno' => 'Torres',
                'apellido_materno' => 'Vega',
                'telefono'         => '8111234003',
                'correo'           => '',  // sin correo — para probar ese caso
                'curp'             => 'TOVM780620HNLRRG03',
                'rfc'              => 'TOVM780620EF3',
                'direccion'        => 'Blvd. Díaz Ordaz 800',
                'ciudad'           => 'Monterrey',
                'estado'           => 'Nuevo León',
                'codigo_postal'    => '64000',
                'activo'           => true,
            ],
        ];

        // Contratos: auto_id, atraso en meses, cuotas vencidas a crear
        $contratos = [
            ['auto_id' => 1, 'monto' => 45000, 'meses_atraso' => 3],
            ['auto_id' => 2, 'monto' => 38000, 'meses_atraso' => 1],
            ['auto_id' => 3, 'monto' => 62000, 'meses_atraso' => 2],
        ];

        foreach ($clientes as $i => $datosCliente) {
            $cliente = Cliente::create($datosCliente);

            $cfg      = $contratos[$i];
            $folio    = 'CF-TEST-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
            $montoCuota = round($cfg['monto'] / 24, 2);

            $contrato = ContratoFinanciamiento::create([
                'folio'                   => $folio,
                'auto_id'                 => $cfg['auto_id'],
                'cliente_id'              => $cliente->id,
                'vendedor_id'             => null,
                'fecha_contrato'          => now()->subMonths($cfg['meses_atraso'] + 2)->toDateString(),
                'fecha_primer_pago'       => now()->subMonths($cfg['meses_atraso'])->startOfMonth()->toDateString(),
                'precio_contado'          => $cfg['monto'],
                'precio_venta'            => $cfg['monto'],
                'enganche'                => round($cfg['monto'] * 0.20, 2),
                'comision_apertura'       => 0,
                'monto_seguro'            => 0,
                'monto_gps'              => 0,
                'monto_financiado'        => round($cfg['monto'] * 0.80, 2),
                'tasa_interes'            => 0,
                'plazo'                   => 24,
                'frecuencia'              => 'mensual',
                'monto_cuota'             => $montoCuota,
                'total_pagar'             => $montoCuota * 24,
                'total_pagado'            => 0,
                'saldo_actual'            => $montoCuota * 24,
                'dias_gracia'             => 3,
                'tipo_recargo'            => null,
                'valor_recargo'           => 0,
                'estatus'                 => 'activo',
                'observaciones'           => 'Contrato de prueba para demo de cobranza.',
            ]);

            // Crear cuotas vencidas (1 por cada mes de atraso)
            for ($n = 1; $n <= $cfg['meses_atraso']; $n++) {
                $fechaVenc = now()->subMonths($cfg['meses_atraso'] - $n + 1)->startOfMonth()->toDateString();

                CuotaFinanciamiento::create([
                    'contrato_financiamiento_id' => $contrato->id,
                    'numero'                     => $n,
                    'fecha_vencimiento'          => $fechaVenc,
                    'monto_capital'              => $montoCuota,
                    'monto_interes'              => 0,
                    'monto_extra'               => 0,
                    'monto'                      => $montoCuota,
                    'monto_pagado'               => 0,
                    'recargo_aplicado'           => 0,
                    'saldo'                      => $montoCuota,
                    'estatus'                    => 'vencida',
                    'fecha_pago'                 => null,
                    'observaciones'              => null,
                ]);
            }

            // Una cuota próxima (pendiente, no vencida) para dar contexto
            CuotaFinanciamiento::create([
                'contrato_financiamiento_id' => $contrato->id,
                'numero'                     => $cfg['meses_atraso'] + 1,
                'fecha_vencimiento'          => now()->addDays(5)->toDateString(),
                'monto_capital'              => $montoCuota,
                'monto_interes'              => 0,
                'monto_extra'               => 0,
                'monto'                      => $montoCuota,
                'monto_pagado'               => 0,
                'recargo_aplicado'           => 0,
                'saldo'                      => $montoCuota,
                'estatus'                    => 'pendiente',
                'fecha_pago'                 => null,
                'observaciones'              => null,
            ]);
        }

        $this->command->info('✓ 3 clientes de prueba creados con contratos y cuotas vencidas.');
        $this->command->info('  Roberto Martínez  → 3 cuotas vencidas | roberto.martinez@ejemplo.com');
        $this->command->info('  Sofía Hernández   → 1 cuota vencida  | sofia.hernandez@ejemplo.com');
        $this->command->info('  Miguel Torres     → 2 cuotas vencidas | sin correo (caso de prueba)');
    }
}
