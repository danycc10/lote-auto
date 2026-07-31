<?php

namespace Tests\Feature\Autos;

use App\Livewire\Admin\Autos\Create;
use App\Models\ImagenAuto;
use App\Services\Autos\ImagenAutoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use RuntimeException;
use Tests\TestCase;

class ImagenAutoServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_convierte_y_redimensiona_imagenes_a_webp(): void
    {
        $archivo = UploadedFile::fake()->image('vehiculo.jpg', 2400, 1200);

        $resultado = app(ImagenAutoService::class)->guardar($archivo, 15);

        Storage::disk('public')->assertExists($resultado['ruta']);
        $this->assertSame('public', $resultado['disco']);
        $this->assertSame('image/webp', $resultado['mime_type']);
        $this->assertStringEndsWith('.webp', $resultado['ruta']);

        $dimensiones = getimagesize(Storage::disk('public')->path($resultado['ruta']));
        $this->assertIsArray($dimensiones);
        $this->assertSame(1600, $dimensiones[0]);
        $this->assertSame(800, $dimensiones[1]);
        $this->assertSame(IMAGETYPE_WEBP, $dimensiones[2]);
        $this->assertSame($resultado['tamano'], Storage::disk('public')->size($resultado['ruta']));
    }

    public function test_rechaza_archivos_que_no_se_pueden_decodificar_como_imagen(): void
    {
        $archivo = UploadedFile::fake()->createWithContent('falsa.jpg', 'contenido que no es una imagen');

        $this->expectException(RuntimeException::class);

        app(ImagenAutoService::class)->guardar($archivo, 1);
    }

    public function test_creacion_persiste_solo_la_version_optimizada(): void
    {
        [$marcaId, $modeloId] = $this->crearMarcaYModelo();

        Livewire::test(Create::class)
            ->set('marca_auto_id', $marcaId)
            ->set('modelo_auto_id', $modeloId)
            ->set('anio', 2025)
            ->set('precio_contado', 250000)
            ->set('imagenes', [UploadedFile::fake()->image('auto.png', 1800, 900)])
            ->call('guardar')
            ->assertRedirect(route('admin.autos.index'));

        $imagen = ImagenAuto::query()->firstOrFail();
        $this->assertSame('image/webp', $imagen->mime_type);
        $this->assertStringEndsWith('.webp', $imagen->ruta);
        Storage::disk('public')->assertExists($imagen->ruta);
    }

    public function test_limpia_archivos_si_la_transaccion_de_creacion_falla(): void
    {
        [$marcaId, $modeloId] = $this->crearMarcaYModelo();
        ImagenAuto::creating(fn () => throw new RuntimeException('Fallo simulado de base de datos'));

        try {
            Livewire::test(Create::class)
                ->set('marca_auto_id', $marcaId)
                ->set('modelo_auto_id', $modeloId)
                ->set('anio', 2025)
                ->set('precio_contado', 250000)
                ->set('imagenes', [UploadedFile::fake()->image('auto.jpg', 1200, 800)])
                ->call('guardar');

            $this->fail('Se esperaba que la transacción fallara.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Fallo simulado de base de datos', $exception->getMessage());
        }

        $this->assertDatabaseCount('autos', 0);
        $this->assertSame([], Storage::disk('public')->allFiles('autos'));
    }

    public function test_rechaza_dimensiones_excesivas_antes_de_procesar(): void
    {
        [$marcaId, $modeloId] = $this->crearMarcaYModelo();

        Livewire::test(Create::class)
            ->set('marca_auto_id', $marcaId)
            ->set('modelo_auto_id', $modeloId)
            ->set('imagenes', [UploadedFile::fake()->image('demasiado-ancha.jpg', 4501, 100)])
            ->assertHasErrors(['imagenes.0']);
    }

    /** @return array{0: int, 1: int} */
    private function crearMarcaYModelo(): array
    {
        $marcaId = DB::table('marcas_autos')->insertGetId([
            'nombre' => 'Marca de prueba',
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $modeloId = DB::table('modelos_autos')->insertGetId([
            'marca_auto_id' => $marcaId,
            'nombre' => 'Modelo de prueba',
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [$marcaId, $modeloId];
    }
}
