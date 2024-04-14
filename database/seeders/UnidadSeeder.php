<?php

namespace Database\Seeders;

use App\Models\Unidad;
use Illuminate\Database\Seeder;

class UnidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unidades = [
            'pieza',
            'metro',
            'kilogramo',
            'metro cúbico',
            'centímetro',
            'gramo',
            'pulgada',
        ];

        Unidad::insert(array_map(fn ($unidad) => ['nombre' => $unidad], $unidades));
    }
}
