<?php

namespace Database\Seeders;

use App\Models\Auto;
use App\Models\Cliente;
use App\Models\ContratoFinanciamiento;
use App\Models\CuotaFinanciamiento;
use Illuminate\Database\Seeder;

class TestMoraSeeder extends Seeder
{
    public function run(): void
    {
        // Necesita autos disponibles
        $autos = Auto::where('estatus', 'disponible')->where('activo', true)->take(5)->get();

        if ($autos->isEmpty()) {
            $this->command->warn('No hay autos disponibles. Corre AutoSeeder primero.');
            return;
        }

        // Escenarios: [nombre, apellido_p, meses_atraso, plazo, estatus_contrato]
        $casos = [
            ['nombre' => 'Roberto',  'ap' => 'Martínez', 'am' => 'López',    'tel' => '5551234001', 'correo' => 'roberto.martinez@test.com',  'meses_atraso' => 3,  'plazo' => 24, 'desc' => '3 cuotas vencidas'],
            ['nombre' => 'Sofía',    'ap' => 'Hernández','am' => 'Ruiz',     'tel' => '5559872002', 'correo' => 'sofia.hernandez@test.com',   'meses_atraso' => 1,  'plazo' => 18, 'desc' => '1 cuota vencida'],
            ['nombre' => 'Miguel',   'ap' => 'Torres',   'am' => 'Vega',     'tel' => '8111234003', 'correo' => '',                           'meses_atraso' => 2,  'plazo' => 36, 'desc' => '2 cuotas vencidas, sin correo'],
            ['nombre' => 'Laura',    'ap' => 'Ramírez',  'am' => 'Fuentes',  'tel' => '6641234004', 'correo' => 'laura.ramirez@test.com',     'meses_atraso' => 0,  'plazo' => 24, 'desc' => 'Al corriente'],
            ['nombre' => 'Carlos',   'ap' => 'Jiménez',  'am' => 'Cruz',     'tel' => '5556781005', 'correo' => 'carlos.jimenez@test.com',    'meses_atraso' => -1, 'plazo' => 12, 'desc' => 'Liquidado'],
        ];

        foreach ($casos as $i => $caso) {
            $auto = $autos->get($i % $autos->count());

            // Idempotente: saltar si ya existe por correo / teléfono
            $existente = Cliente::where('telefono', $caso['tel'])->first();
            if ($existente) {
                $this->command->line("  Saltando {$caso['nombre']} {$caso['ap']} (ya existe)");
                continue;
            }

            $cliente = Cliente::create([
                'nombre'           => $caso['nombre'],
                'apellido_paterno' => $caso['ap'],
                'apellido_materno' => $caso['am'],
                'telefono'         => $caso['tel'],
                'correo'           => $caso['correo'],
                'curp'             => $this->curp($caso['ap'], $i),
                'rfc'              => strtoupper(substr($caso['ap'], 0, 4)) . '8501' . str_pad($i + 1, 2, '0', STR_PAD_LEFT) . 'AB' . ($i + 1),
                'direccion'        => 'Calle de Prueba ' . (($i + 1) * 10),
                'ciudad'           => ['Ciudad de México', 'Guadalajara', 'Monterrey', 'Puebla', 'León'][$i % 5],
                'estado'           => ['CDMX', 'Jalisco', 'Nuevo León', 'Puebla', 'Guanajuato'][$i % 5],
                'codigo_postal'    => str_pad(6600 + $i * 100, 5, '0', STR_PAD_LEFT),
                'activo'           => true,
            ]);

            $folio      = 'CF-TEST-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
            $precio     = (float) $auto->precio_financiado ?: 200000;
            $enganche   = round($precio * 0.20, 2);
            $financiado = round($precio * 0.80, 2);
            $cuota      = round($financiado / $caso['plazo'], 2);
            $mesesAtras = max($caso['meses_atraso'], 0);

            // Fecha de contrato: lo suficientemente atrás para que existan cuotas vencidas
            $inicioMeses = $mesesAtras + 2;
            $fechaContrato   = now()->subMonths($inicioMeses)->toDateString();
            $fechaPrimerPago = now()->subMonths($mesesAtras > 0 ? $mesesAtras : 1)->startOfMonth()->toDateString();

            $esLiquidado = $caso['meses_atraso'] === -1;

            $contrato = ContratoFinanciamiento::create([
                'folio'                   => $folio,
                'auto_id'                 => $auto->id,
                'cliente_id'              => $cliente->id,
                'vendedor_id'             => null,
                'fecha_contrato'          => $fechaContrato,
                'fecha_primer_pago'       => $fechaPrimerPago,
                'precio_contado'          => (float) $auto->precio_contado ?: $precio * 0.9,
                'precio_venta'            => $precio,
                'enganche'                => $enganche,
                'comision_apertura'       => 0,
                'monto_seguro'            => 0,
                'monto_gps'               => 0,
                'monto_financiado'        => $financiado,
                'tasa_interes'            => 0,
                'plazo'                   => $caso['plazo'],
                'frecuencia'              => 'mensual',
                'monto_cuota'             => $cuota,
                'total_pagar'             => $cuota * $caso['plazo'],
                'total_pagado'            => $esLiquidado ? $cuota * $caso['plazo'] : 0,
                'saldo_actual'            => $esLiquidado ? 0 : $cuota * $caso['plazo'],
                'dias_gracia'             => 3,
                'tipo_recargo'            => null,
                'valor_recargo'           => 0,
                'estatus'                 => $esLiquidado ? 'liquidado' : ($mesesAtras > 0 ? 'atrasado' : 'activo'),
                'observaciones'           => 'Dato de prueba — ' . $caso['desc'],
            ]);

            // ── Cuotas ────────────────────────────────────────────
            if ($esLiquidado) {
                // Todas pagadas
                for ($n = 1; $n <= $caso['plazo']; $n++) {
                    CuotaFinanciamiento::create([
                        'contrato_financiamiento_id' => $contrato->id,
                        'numero'          => $n,
                        'fecha_vencimiento' => now()->subMonths($caso['plazo'] - $n + 1)->startOfMonth()->toDateString(),
                        'monto_capital'   => $cuota,
                        'monto_interes'   => 0,
                        'monto_extra'     => 0,
                        'monto'           => $cuota,
                        'monto_pagado'    => $cuota,
                        'recargo_aplicado'=> 0,
                        'saldo'           => 0,
                        'estatus'         => 'pagada',
                        'fecha_pago'      => now()->subMonths($caso['plazo'] - $n + 1)->startOfMonth()->addDays(2),
                        'observaciones'   => null,
                    ]);
                }
            } else {
                // Cuotas vencidas
                for ($n = 1; $n <= $mesesAtras; $n++) {
                    CuotaFinanciamiento::create([
                        'contrato_financiamiento_id' => $contrato->id,
                        'numero'          => $n,
                        'fecha_vencimiento' => now()->subMonths($mesesAtras - $n + 1)->startOfMonth()->toDateString(),
                        'monto_capital'   => $cuota,
                        'monto_interes'   => 0,
                        'monto_extra'     => 0,
                        'monto'           => $cuota,
                        'monto_pagado'    => 0,
                        'recargo_aplicado'=> 0,
                        'saldo'           => $cuota,
                        'estatus'         => 'vencida',
                        'fecha_pago'      => null,
                        'observaciones'   => null,
                    ]);
                }

                // Cuota próxima pendiente
                CuotaFinanciamiento::create([
                    'contrato_financiamiento_id' => $contrato->id,
                    'numero'          => $mesesAtras + 1,
                    'fecha_vencimiento' => now()->addDays(7)->toDateString(),
                    'monto_capital'   => $cuota,
                    'monto_interes'   => 0,
                    'monto_extra'     => 0,
                    'monto'           => $cuota,
                    'monto_pagado'    => 0,
                    'recargo_aplicado'=> 0,
                    'saldo'           => $cuota,
                    'estatus'         => 'pendiente',
                    'fecha_pago'      => null,
                    'observaciones'   => null,
                ]);
            }

            // Marcar auto como vendido
            $auto->update(['estatus' => 'vendido']);

            $this->command->line("  ✓ {$caso['nombre']} {$caso['ap']} — {$caso['desc']}");
        }

        $this->command->info('Seeder de prueba completado.');
    }

    private function curp(string $apellido, int $idx): string
    {
        $base = strtoupper(substr($apellido, 0, 4));
        return str_pad($base, 4, 'X') . '8501' . str_pad($idx + 1, 2, '0', STR_PAD_LEFT) . 'H' . 'NLE' . 'RZB' . str_pad($idx, 2, '0', STR_PAD_LEFT);
    }
}
