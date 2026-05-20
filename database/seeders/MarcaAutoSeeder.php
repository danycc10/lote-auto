<?php

namespace Database\Seeders;

use App\Models\MarcaAuto;
use Illuminate\Database\Seeder;

class MarcaAutoSeeder extends Seeder
{
    public function run(): void
    {
        $marcas = [
            'Nissan',
            'Chevrolet',
            'Ford',
            'Toyota',
            'Honda',
            'Volkswagen',
            'Kia',
            'Hyundai',
            'Mazda',
            'Renault',
        ];

        foreach ($marcas as $marca) {
            MarcaAuto::updateOrCreate(
                ['nombre' => $marca],
                ['activo' => true]
            );
        }
    }
}