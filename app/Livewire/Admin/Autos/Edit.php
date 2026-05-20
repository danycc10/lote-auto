<?php

namespace App\Livewire\Admin\Autos;

use App\Models\Auto;
use App\Models\ImagenAuto;
use App\Models\MarcaAuto;
use App\Models\ModeloAuto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Auto $auto;

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
    public $imagenesNuevas = [];

    public ?int $portadaNuevaIndex = 0;

    public function mount(Auto $auto): void
    {
        $this->auto = $auto->load('imagenes');

        $this->marca_auto_id = $auto->marca_auto_id;
        $this->modelo_auto_id = $auto->modelo_auto_id;
        $this->codigo_inventario = $auto->codigo_inventario;
        $this->vin = $auto->vin;
        $this->placa = $auto->placa;
        $this->anio = $auto->anio;
        $this->version = $auto->version;
        $this->color = $auto->color;
        $this->kilometraje = $auto->kilometraje;
        $this->transmision = $auto->transmision;
        $this->tipo_combustible = $auto->tipo_combustible;
        $this->precio_contado = $auto->precio_contado;
        $this->precio_financiado = $auto->precio_financiado;
        $this->estatus = $auto->estatus;
        $this->descripcion = $auto->descripcion;
        $this->destacado = (bool) $auto->destacado;
        $this->activo = (bool) $auto->activo;
    }

    protected function rules(): array
    {
        return [
            'marca_auto_id' => 'required|exists:marcas_autos,id',
            'modelo_auto_id' => 'required|exists:modelos_autos,id',
            'codigo_inventario' => 'nullable|string|max:255|unique:autos,codigo_inventario,' . $this->auto->id,
            'vin' => 'nullable|string|max:255|unique:autos,vin,' . $this->auto->id,
            'placa' => 'nullable|string|max:255|unique:autos,placa,' . $this->auto->id,
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

            'imagenesNuevas' => 'nullable|array|max:15',
            'imagenesNuevas.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            'portadaNuevaIndex' => 'nullable|integer|min:0',
        ];
    }

    protected $messages = [
        'marca_auto_id.required' => 'La marca es obligatoria.',
        'modelo_auto_id.required' => 'El modelo es obligatorio.',
        'codigo_inventario.unique' => 'Ese código de inventario ya existe.',
        'vin.unique' => 'Ese VIN ya existe.',
        'placa.unique' => 'Esa placa ya existe.',
        'anio.required' => 'El año es obligatorio.',
        'precio_contado.required' => 'El precio contado es obligatorio.',
        'imagenesNuevas.max' => 'Solo puedes cargar hasta 15 imágenes.',
        'imagenesNuevas.*.image' => 'Cada archivo debe ser una imagen válida.',
        'imagenesNuevas.*.mimes' => 'Las imágenes deben ser JPG, JPEG, PNG o WEBP.',
        'imagenesNuevas.*.max' => 'Cada imagen debe pesar máximo 4 MB.',
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

    public function getImagenesActualesProperty()
    {
        return $this->auto->imagenes()
            ->orderByDesc('es_portada')
            ->orderBy('orden')
            ->get();
    }

    public function updatedMarcaAutoId(): void
    {
        $modeloValido = ModeloAuto::query()
            ->where('id', $this->modelo_auto_id)
            ->where('marca_auto_id', $this->marca_auto_id)
            ->exists();

        if (! $modeloValido) {
            $this->modelo_auto_id = null;
        }
    }

    public function updatedImagenesNuevas(): void
    {
        $this->validateOnly('imagenesNuevas');
        $this->validateOnly('imagenesNuevas.*');

        if (!empty($this->imagenesNuevas) && ($this->portadaNuevaIndex === null || !isset($this->imagenesNuevas[$this->portadaNuevaIndex]))) {
            $this->portadaNuevaIndex = 0;
        }
    }

    public function quitarImagenNueva(int $index): void
    {
        if (!isset($this->imagenesNuevas[$index])) {
            return;
        }

        unset($this->imagenesNuevas[$index]);
        $this->imagenesNuevas = array_values($this->imagenesNuevas);

        if (empty($this->imagenesNuevas)) {
            $this->portadaNuevaIndex = null;
            return;
        }

        if ($this->portadaNuevaIndex === $index) {
            $this->portadaNuevaIndex = 0;
            return;
        }

        if ($this->portadaNuevaIndex !== null && $this->portadaNuevaIndex > $index) {
            $this->portadaNuevaIndex--;
        }
    }

    public function seleccionarPortadaNueva(int $index): void
    {
        if (!isset($this->imagenesNuevas[$index])) {
            return;
        }

        $this->portadaNuevaIndex = $index;
    }

    public function actualizar()
    {
        $data = $this->validate();

        DB::transaction(function () use ($data) {
            $this->auto->update([
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

            if (!empty($this->imagenesNuevas)) {
                $siguienteOrden = ((int) $this->auto->imagenes()->max('orden')) + 1;

                foreach ($this->imagenesNuevas as $index => $imagen) {
                    $ruta = $imagen->store("autos/{$this->auto->id}", 'public');

                    ImagenAuto::create([
                        'auto_id' => $this->auto->id,
                        'ruta' => $ruta,
                        'disco' => 'public',
                        'mime_type' => $imagen->getMimeType(),
                        'tamano' => $imagen->getSize(),
                        'es_portada' => false,
                        'orden' => $siguienteOrden + $index,
                    ]);
                }

                if ($this->portadaNuevaIndex !== null && isset($this->imagenesNuevas[$this->portadaNuevaIndex])) {
                    $this->auto->imagenes()->update(['es_portada' => false]);

                    $ultimaInsertadaComoPortada = $this->auto->imagenes()
                        ->orderByDesc('id')
                        ->skip(count($this->imagenesNuevas) - 1 - $this->portadaNuevaIndex)
                        ->first();

                    if ($ultimaInsertadaComoPortada) {
                        $ultimaInsertadaComoPortada->update(['es_portada' => true]);
                    }
                } elseif (!$this->auto->imagenes()->where('es_portada', true)->exists()) {
                    $primera = $this->auto->imagenes()->orderBy('orden')->first();
                    if ($primera) {
                        $primera->update(['es_portada' => true]);
                    }
                }
            } else {
                if (!$this->auto->imagenes()->where('es_portada', true)->exists()) {
                    $primera = $this->auto->imagenes()->orderBy('orden')->first();
                    if ($primera) {
                        $primera->update(['es_portada' => true]);
                    }
                }
            }
        });

        $this->imagenesNuevas = [];
        $this->portadaNuevaIndex = 0;
        $this->auto->refresh();

        session()->flash('success', 'Auto actualizado correctamente.');

        return redirect()->route('admin.autos.edit', $this->auto);
    }

    public function eliminarImagen(int $imagenId): void
    {
        $imagen = ImagenAuto::query()
            ->where('auto_id', $this->auto->id)
            ->findOrFail($imagenId);

        $eraPortada = (bool) $imagen->es_portada;

        if ($imagen->ruta && Storage::disk($imagen->disco)->exists($imagen->ruta)) {
            Storage::disk($imagen->disco)->delete($imagen->ruta);
        }

        $imagen->delete();

        if ($eraPortada) {
            $nuevaPortada = $this->auto->imagenes()->orderBy('orden')->first();
            if ($nuevaPortada) {
                $nuevaPortada->update(['es_portada' => true]);
            }
        }

        session()->flash('success', 'Imagen eliminada correctamente.');
    }

    public function marcarPortada(int $imagenId): void
    {
        DB::transaction(function () use ($imagenId) {
            $this->auto->imagenes()->update(['es_portada' => false]);

            $imagen = $this->auto->imagenes()->findOrFail($imagenId);
            $imagen->update(['es_portada' => true]);
        });

        session()->flash('success', 'Imagen portada actualizada.');
    }

    public function render()
    {
        return view('livewire.admin.autos.edit', [
            'imagenesActuales' => $this->imagenesActuales,
        ])->layout('layouts.app');
    }
}