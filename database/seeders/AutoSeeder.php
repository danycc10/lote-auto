<?php

namespace Database\Seeders;

use App\Models\Auto;
use App\Models\MarcaAuto;
use App\Models\ModeloAuto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AutoSeeder extends Seeder
{
    public function run(): void
    {
        $autos = [
            [
                'marca' => 'Nissan',
                'modelo' => 'Versa',
                'anio' => 2020,
                'version' => 'Sense',
                'color' => 'Blanco',
                'kilometraje' => 58000,
                'transmision' => 'Manual',
                'tipo_combustible' => 'Gasolina',
                'precio_contado' => 198000,
                'precio_financiado' => 228000,
                'estatus' => 'disponible',
                'descripcion' => 'Sedán económico, ideal para ciudad.',
                'destacado' => true,
            ],
            [
                'marca' => 'Chevrolet',
                'modelo' => 'Aveo',
                'anio' => 2019,
                'version' => 'LT',
                'color' => 'Gris',
                'kilometraje' => 73000,
                'transmision' => 'Manual',
                'tipo_combustible' => 'Gasolina',
                'precio_contado' => 168000,
                'precio_financiado' => 196000,
                'estatus' => 'disponible',
                'descripcion' => 'Auto práctico y rendidor.',
                'destacado' => false,
            ],
            [
                'marca' => 'Ford',
                'modelo' => 'Ranger',
                'anio' => 2021,
                'version' => 'XLT',
                'color' => 'Azul',
                'kilometraje' => 49000,
                'transmision' => 'Automática',
                'tipo_combustible' => 'Diésel',
                'precio_contado' => 485000,
                'precio_financiado' => 545000,
                'estatus' => 'disponible',
                'descripcion' => 'Pickup en excelentes condiciones.',
                'destacado' => true,
            ],
            [
                'marca' => 'Toyota',
                'modelo' => 'Corolla',
                'anio' => 2022,
                'version' => 'LE',
                'color' => 'Plata',
                'kilometraje' => 32000,
                'transmision' => 'Automática',
                'tipo_combustible' => 'Gasolina',
                'precio_contado' => 348000,
                'precio_financiado' => 392000,
                'estatus' => 'disponible',
                'descripcion' => 'Sedán confiable, muy buen historial.',
                'destacado' => true,
            ],
            [
                'marca' => 'Honda',
                'modelo' => 'CR-V',
                'anio' => 2020,
                'version' => 'Turbo Plus',
                'color' => 'Negro',
                'kilometraje' => 61000,
                'transmision' => 'Automática',
                'tipo_combustible' => 'Gasolina',
                'precio_contado' => 429000,
                'precio_financiado' => 479000,
                'estatus' => 'disponible',
                'descripcion' => 'SUV familiar con excelente espacio.',
                'destacado' => false,
            ],
            [
                'marca' => 'Volkswagen',
                'modelo' => 'Jetta',
                'anio' => 2021,
                'version' => 'Trendline',
                'color' => 'Rojo',
                'kilometraje' => 44000,
                'transmision' => 'Automática',
                'tipo_combustible' => 'Gasolina',
                'precio_contado' => 315000,
                'precio_financiado' => 358000,
                'estatus' => 'disponible',
                'descripcion' => 'Jetta con buen equipamiento.',
                'destacado' => false,
            ],
            [
                'marca' => 'Kia',
                'modelo' => 'Rio',
                'anio' => 2023,
                'version' => 'LX',
                'color' => 'Blanco',
                'kilometraje' => 18000,
                'transmision' => 'Manual',
                'tipo_combustible' => 'Gasolina',
                'precio_contado' => 274000,
                'precio_financiado' => 309000,
                'estatus' => 'disponible',
                'descripcion' => 'Compacto seminuevo, muy buena opción.',
                'destacado' => true,
            ],
            [
                'marca' => 'Hyundai',
                'modelo' => 'Accent',
                'anio' => 2018,
                'version' => 'GL Mid',
                'color' => 'Plata',
                'kilometraje' => 86000,
                'transmision' => 'Manual',
                'tipo_combustible' => 'Gasolina',
                'precio_contado' => 154000,
                'precio_financiado' => 181000,
                'estatus' => 'disponible',
                'descripcion' => 'Unidad económica y bien cuidada.',
                'destacado' => false,
            ],
            [
                'marca' => 'Mazda',
                'modelo' => 'Mazda 3',
                'anio' => 2022,
                'version' => 'i Sport',
                'color' => 'Gris',
                'kilometraje' => 27000,
                'transmision' => 'Automática',
                'tipo_combustible' => 'Gasolina',
                'precio_contado' => 365000,
                'precio_financiado' => 412000,
                'estatus' => 'disponible',
                'descripcion' => 'Auto con gran diseño y manejo.',
                'destacado' => true,
            ],
            [
                'marca' => 'Renault',
                'modelo' => 'Duster',
                'anio' => 2020,
                'version' => 'Zen',
                'color' => 'Blanco',
                'kilometraje' => 69000,
                'transmision' => 'Manual',
                'tipo_combustible' => 'Gasolina',
                'precio_contado' => 238000,
                'precio_financiado' => 272000,
                'estatus' => 'disponible',
                'descripcion' => 'SUV compacta con buena altura.',
                'destacado' => false,
            ],
        ];

        foreach ($autos as $item) {
            $marca = MarcaAuto::where('nombre', $item['marca'])->first();
            $modelo = ModeloAuto::where('nombre', $item['modelo'])
                ->where('marca_auto_id', $marca?->id)
                ->first();

            if (!$marca || !$modelo) {
                continue;
            }

            Auto::updateOrCreate(
                [
                    'codigo_inventario' => $this->generarCodigo($marca->nombre, $modelo->nombre, $item['anio']),
                ],
                [
                    'marca_auto_id' => $marca->id,
                    'modelo_auto_id' => $modelo->id,
                    'vin' => $this->generarVin(),
                    'placa' => null,
                    'anio' => $item['anio'],
                    'version' => $item['version'],
                    'color' => $item['color'],
                    'kilometraje' => $item['kilometraje'],
                    'transmision' => $item['transmision'],
                    'tipo_combustible' => $item['tipo_combustible'],
                    'precio_contado' => $item['precio_contado'],
                    'precio_financiado' => $item['precio_financiado'],
                    'estatus' => $item['estatus'],
                    'descripcion' => $item['descripcion'],
                    'destacado' => $item['destacado'],
                    'activo' => true,
                ]
            );
        }
    }

    private function generarCodigo(string $marca, string $modelo, int $anio): string
    {
        $prefijoMarca = Str::upper(Str::substr(preg_replace('/\s+/', '', $marca), 0, 3));
        $prefijoModelo = Str::upper(Str::substr(preg_replace('/\s+/', '', $modelo), 0, 3));
        $random = random_int(100, 999);

        return "{$prefijoMarca}{$prefijoModelo}{$anio}{$random}";
    }

    private function generarVin(): string
    {
        return Str::upper(Str::random(17));
    }
}