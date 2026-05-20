<?php

namespace Database\Seeders;

use App\Models\MarcaAuto;
use App\Models\ModeloAuto;
use Illuminate\Database\Seeder;

class ModeloAutoSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Nissan' => ['Versa', 'March', 'Sentra', 'Altima', 'NP300', 'Kicks'],
            'Chevrolet' => ['Aveo', 'Onix', 'Spark', 'Cruze', 'Tracker', 'S10'],
            'Ford' => ['Fiesta', 'Focus', 'Fusion', 'Escape', 'Ranger', 'Lobo'],
            'Toyota' => ['Yaris', 'Corolla', 'Camry', 'Hilux', 'RAV4', 'Avanza'],
            'Honda' => ['Fit', 'City', 'Civic', 'Accord', 'CR-V', 'HR-V'],
            'Volkswagen' => ['Gol', 'Vento', 'Jetta', 'Polo', 'Tiguan', 'Saveiro'],
            'Kia' => ['Rio', 'Forte', 'Soul', 'Sportage', 'Seltos', 'Sorento'],
            'Hyundai' => ['i10', 'Accent', 'Elantra', 'Creta', 'Tucson', 'Santa Fe'],
            'Mazda' => ['Mazda 2', 'Mazda 3', 'Mazda 6', 'CX-3', 'CX-5', 'BT-50'],
            'Renault' => ['Kwid', 'Logan', 'Sandero', 'Duster', 'Oroch', 'Stepway'],
        ];

        foreach ($data as $nombreMarca => $modelos) {
            $marca = MarcaAuto::where('nombre', $nombreMarca)->first();

            if (!$marca) {
                continue;
            }

            foreach ($modelos as $nombreModelo) {
                ModeloAuto::updateOrCreate(
                    [
                        'marca_auto_id' => $marca->id,
                        'nombre' => $nombreModelo,
                    ],
                    [
                        'activo' => true,
                    ]
                );
            }
        }
    }
}