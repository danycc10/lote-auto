<?php

namespace Tests\Feature\Cotizador;

use App\Livewire\Admin\Cotizador\Index;
use App\Mail\CotizacionMail;
use App\Models\Auto;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class CotizadorCalculoTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_correo_de_cotizacion_se_procesa_despues_del_commit(): void
    {
        $this->assertInstanceOf(
            ShouldQueueAfterCommit::class,
            new CotizacionMail([], '%PDF-1.4'),
        );
    }

    private function crearAuto(float $precioFinanciado): Auto
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
            'estatus' => 'disponible',
            'activo' => true,
            'precio_contado' => $precioFinanciado,
            'precio_financiado' => $precioFinanciado,
        ]);
    }

    public function test_cuota_sin_interes_es_capital_entre_plazo(): void
    {
        $auto = $this->crearAuto(120000);

        $i = Livewire::test(Index::class)
            ->set('autoId', $auto->id)
            ->set('enganche', 20000)
            ->set('plazo', 10)
            ->set('tasaAnual', 0)
            ->instance();

        $this->assertEquals(100000.0, $i->montoFinanciado());
        $this->assertEquals(10000.0, $i->cuotaMensual());
        $this->assertEquals(100000.0, $i->totalPagar());
        $this->assertEquals(0.0, $i->totalIntereses());
    }

    public function test_cuota_con_interes_genera_intereses_positivos(): void
    {
        $auto = $this->crearAuto(120000);

        $i = Livewire::test(Index::class)
            ->set('autoId', $auto->id)
            ->set('enganche', 20000)
            ->set('plazo', 12)
            ->set('tasaAnual', 12)
            ->instance();

        $this->assertGreaterThan(0, $i->cuotaMensual());
        $this->assertGreaterThan($i->montoFinanciado(), $i->totalPagar());
        $this->assertGreaterThan(0, $i->totalIntereses());
    }

    public function test_tabla_amortizacion_cierra_en_cero(): void
    {
        $auto = $this->crearAuto(120000);

        $tabla = Livewire::test(Index::class)
            ->set('autoId', $auto->id)
            ->set('enganche', 20000)
            ->set('plazo', 12)
            ->set('tasaAnual', 15)
            ->instance()
            ->tablaAmortizacion();

        $this->assertCount(12, $tabla);
        $this->assertSame(0.0, (float) end($tabla)['saldo']);
    }
}
