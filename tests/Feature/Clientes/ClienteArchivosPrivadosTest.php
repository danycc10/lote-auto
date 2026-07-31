<?php

namespace Tests\Feature\Clientes;

use App\Livewire\Admin\Clientes\Create;
use App\Livewire\Admin\Clientes\Edit;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use RuntimeException;
use Tests\TestCase;

class ClienteArchivosPrivadosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('private');
    }

    public function test_crea_cliente_y_documentos_en_el_disco_privado(): void
    {
        Livewire::test(Create::class)
            ->set('nombre', 'Cliente con documentos')
            ->set('ine', UploadedFile::fake()->image('ine.jpg'))
            ->set('comprobante_domicilio', UploadedFile::fake()->create('comprobante.pdf', 100, 'application/pdf'))
            ->call('guardar')
            ->assertRedirect(route('admin.clientes.index'));

        $cliente = Cliente::query()->firstOrFail();
        $this->assertNotNull($cliente->ruta_ine);
        $this->assertNotNull($cliente->ruta_comprobante_domicilio);
        Storage::disk('private')->assertExists($cliente->ruta_ine);
        Storage::disk('private')->assertExists($cliente->ruta_comprobante_domicilio);
    }

    public function test_reemplazo_confirma_la_base_antes_de_borrar_el_documento_anterior(): void
    {
        $cliente = Cliente::create(['nombre' => 'Cliente', 'ruta_ine' => 'clientes/anterior.jpg']);
        Storage::disk('private')->put($cliente->ruta_ine, 'anterior');

        Livewire::test(Edit::class, ['cliente' => $cliente])
            ->set('ine', UploadedFile::fake()->image('nuevo.jpg'))
            ->call('actualizar')
            ->assertRedirect(route('admin.clientes.edit', $cliente));

        $cliente->refresh();
        $this->assertNotSame('clientes/anterior.jpg', $cliente->ruta_ine);
        Storage::disk('private')->assertMissing('clientes/anterior.jpg');
        Storage::disk('private')->assertExists($cliente->ruta_ine);
    }

    public function test_conserva_el_anterior_y_limpia_el_nuevo_si_falla_la_base(): void
    {
        $cliente = Cliente::create(['nombre' => 'Cliente', 'ruta_ine' => 'clientes/anterior.jpg']);
        Storage::disk('private')->put($cliente->ruta_ine, 'anterior');
        Cliente::updating(function (Cliente $cliente): void {
            if ($cliente->isDirty('ruta_ine')) {
                throw new RuntimeException('Fallo simulado de base de datos');
            }
        });

        try {
            Livewire::test(Edit::class, ['cliente' => $cliente])
                ->set('ine', UploadedFile::fake()->image('nuevo.jpg'))
                ->call('actualizar');

            $this->fail('Se esperaba que la actualización fallara.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Fallo simulado de base de datos', $exception->getMessage());
        }

        $this->assertSame('clientes/anterior.jpg', $cliente->fresh()->ruta_ine);
        Storage::disk('private')->assertExists('clientes/anterior.jpg');
        $this->assertSame(['clientes/anterior.jpg'], Storage::disk('private')->allFiles());
    }

    public function test_elimina_la_referencia_antes_de_borrar_el_archivo(): void
    {
        $cliente = Cliente::create(['nombre' => 'Cliente', 'ruta_ine' => 'clientes/ine.jpg']);
        Storage::disk('private')->put($cliente->ruta_ine, 'ine');

        Livewire::test(Edit::class, ['cliente' => $cliente])
            ->call('eliminarArchivo', 'ine');

        $this->assertNull($cliente->fresh()->ruta_ine);
        Storage::disk('private')->assertMissing('clientes/ine.jpg');
    }
}
