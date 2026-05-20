<?php

namespace App\Livewire\Admin\Autos;

use App\Models\Auto;
use App\Models\ImagenAuto;
use App\Models\MarcaAuto;
use App\Models\ModeloAuto;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $marca_auto_id;
    public $modelo_auto_id;

    public $codigo_inventario;
    public $vin;
    public $placa;

    public $anio;
    public $version;
    public $color;
    public $kilometraje = 0;

    public $transmision;
    public $tipo_combustible;

    public $precio_contado = 0;
    public $precio_financiado = 0;

    public $estatus = 'disponible';
    public $descripcion;
    public $destacado = false;
    public $activo = true;

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public $imagenes = [];

    public ?int $portadaIndex = 0;

    protected function rules(): array
    {
        return [
            'marca_auto_id' => 'required|exists:marcas_autos,id',
            'modelo_auto_id' => 'required|exists:modelos_autos,id',

            'codigo_inventario' => 'nullable|string|max:255|unique:autos,codigo_inventario',
            'vin' => 'nullable|string|max:255|unique:autos,vin',
            'placa' => 'nullable|string|max:255|unique:autos,placa',

            'anio' => 'required|integer|min:1900|max:' . date('Y'),
            'version' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'kilometraje' => 'nullable|integer|min:0',

            'transmision' => 'nullable|string|max:255',
            'tipo_combustible' => 'nullable|string|max:255',

            'precio_contado' => 'required|numeric|min:0',
            'precio_financiado' => 'nullable|numeric|min:0',

            'estatus' => 'required|in:disponible,apartado,vendido_contado,financiado,liquidado,recuperado,inactivo',
            'descripcion' => 'nullable|string',

            'destacado' => 'boolean',
            'activo' => 'boolean',

            'imagenes' => 'nullable|array|max:15',
            'imagenes.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            'portadaIndex' => 'nullable|integer|min:0',
        ];
    }

    protected $messages = [
        'imagenes.max' => 'Solo puedes cargar hasta 15 imágenes.',
        'imagenes.*.image' => 'Todos los archivos deben ser imágenes.',
        'imagenes.*.mimes' => 'Las imágenes deben ser jpg, jpeg, png o webp.',
        'imagenes.*.max' => 'Cada imagen debe pesar máximo 4 MB.',
    ];

    public function getMarcasProperty()
    {
        return MarcaAuto::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();
    }

    public function getModelosProperty()
    {
        return ModeloAuto::query()
            ->when($this->marca_auto_id, fn($q) => $q->where('marca_auto_id', $this->marca_auto_id))
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();
    }

    public function updatedMarcaAutoId(): void
    {
        $this->modelo_auto_id = null;
    }

    public function updatedImagenes(): void
    {
        $this->validateOnly('imagenes');
        $this->validateOnly('imagenes.*');

        if (!empty($this->imagenes) && ($this->portadaIndex === null || !isset($this->imagenes[$this->portadaIndex]))) {
            $this->portadaIndex = 0;
        }
    }

    public function quitarImagen(int $index): void
    {
        if (!isset($this->imagenes[$index])) {
            return;
        }

        unset($this->imagenes[$index]);
        $this->imagenes = array_values($this->imagenes);

        if (empty($this->imagenes)) {
            $this->portadaIndex = null;
            return;
        }

        if ($this->portadaIndex === $index) {
            $this->portadaIndex = 0;
            return;
        }

        if ($this->portadaIndex !== null && $this->portadaIndex > $index) {
            $this->portadaIndex--;
        }
    }

    public function seleccionarPortada(int $index): void
    {
        if (!isset($this->imagenes[$index])) {
            return;
        }

        $this->portadaIndex = $index;
    }

    public function guardar()
    {
        $data = $this->validate();

        DB::transaction(function () use ($data) {
            $auto = Auto::create([
                'marca_auto_id' => $data['marca_auto_id'],
                'modelo_auto_id' => $data['modelo_auto_id'],
                'codigo_inventario' => $data['codigo_inventario'] ?? null,
                'vin' => $data['vin'] ?? null,
                'placa' => $data['placa'] ?? null,
                'anio' => $data['anio'],
                'version' => $data['version'] ?? null,
                'color' => $data['color'] ?? null,
                'kilometraje' => $data['kilometraje'] ?? 0,
                'transmision' => $data['transmision'] ?? null,
                'tipo_combustible' => $data['tipo_combustible'] ?? null,
                'precio_contado' => $data['precio_contado'],
                'precio_financiado' => $data['precio_financiado'] ?? 0,
                'estatus' => $data['estatus'],
                'descripcion' => $data['descripcion'] ?? null,
                'destacado' => (bool) ($data['destacado'] ?? false),
                'activo' => (bool) ($data['activo'] ?? true),
            ]);

            if (!empty($this->imagenes)) {
                foreach ($this->imagenes as $index => $imagen) {
                    $ruta = $imagen->store("autos/{$auto->id}", 'public');

                    ImagenAuto::create([
                        'auto_id' => $auto->id,
                        'ruta' => $ruta,
                        'disco' => 'public',
                        'mime_type' => $imagen->getMimeType(),
                        'tamano' => $imagen->getSize(),
                        'es_portada' => (int) $index === (int) ($this->portadaIndex ?? 0),
                        'orden' => $index + 1,
                    ]);
                }
            }
        });

        session()->flash('success', 'Auto creado correctamente.');

        return redirect()->route('admin.autos.index');
    }

    public function render()
    {
        return view('livewire.admin.autos.create')
            ->layout('layouts.app');
    }
}