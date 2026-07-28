<?php

namespace Tests\Feature\Clientes;

use App\Livewire\Admin\Clientes\Create;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ClienteIntegridadTest extends TestCase
{
    use RefreshDatabase;

    public function test_eliminar_cliente_es_soft_delete(): void
    {
        $cliente = Cliente::create(['nombre' => 'Juan Pérez']);

        $cliente->delete();

        $this->assertSoftDeleted('clientes', ['id' => $cliente->id]);
        $this->assertDatabaseCount('clientes', 1);
        $this->assertNull(Cliente::find($cliente->id));
    }

    public function test_curp_y_rfc_se_normalizan_a_mayusculas(): void
    {
        $cliente = Cliente::create([
            'nombre' => 'Ana López',
            'curp' => 'abcd010101hdfxyz09',
            'rfc' => 'xaxx010101000',
        ]);

        $this->assertSame('ABCD010101HDFXYZ09', $cliente->curp);
        $this->assertSame('XAXX010101000', $cliente->rfc);
    }

    public function test_curp_vacia_se_guarda_como_null(): void
    {
        $cliente = Cliente::create(['nombre' => 'Sin Curp', 'curp' => '']);

        $this->assertNull($cliente->curp);
    }

    public function test_rechaza_curp_con_formato_invalido(): void
    {
        Livewire::test(Create::class)
            ->set('nombre', 'Cliente Test')
            ->set('curp', 'FORMATO-INVALIDO')
            ->call('guardar')
            ->assertHasErrors(['curp']);
    }

    public function test_acepta_curp_con_formato_valido(): void
    {
        Livewire::test(Create::class)
            ->set('nombre', 'Cliente Test')
            ->set('curp', 'ABCD010101HDFXYZ09')
            ->call('guardar')
            ->assertHasNoErrors(['curp']);
    }
}
