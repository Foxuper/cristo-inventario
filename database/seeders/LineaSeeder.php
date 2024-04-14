<?php

namespace Database\Seeders;

use App\Models\Linea;
use Illuminate\Database\Seeder;

class LineaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lineas = [
            'ferreteria',
            'material electrico',
            'material de construccion',
            'herramientas',
            'bombas perifericas',
            'refrigercion',
            'chapas y herrajes',
            'sanitarios',
            'herramientas y accesorios',
            'pinturas',
            'gas',
            'limpieza',
            'plomeria',
            'jardineria',
            'hogar',
            'plmeria',
            'servicios',
            'tornilleria',
        ];

        Linea::insert(array_map(fn ($linea) => ['nombre' => $linea], $lineas));
    }
}
