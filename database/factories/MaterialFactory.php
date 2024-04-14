<?php

namespace Database\Factories;

use App\Models\Linea;
use App\Models\Unidad;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $unidad = Unidad::get()->random();

        return [
            'descripcion' => $this->faker->sentence,
            'cantidad' => $this->faker->numberBetween(1, 100),
            'precio' => $this->faker->randomFloat(2, 10, 1000),
            'codigo' => $this->faker->unique()->regexify('[a-zA-Z0-9]{8}'),
            'clave_sat' => $this->faker->unique()->numberBetween(10000000, 99999999),
            'activo' => $this->faker->boolean,
            'linea_id' => Linea::get()->random()->id,
            'unidad_medida_id' => $unidad->id,
            'unidad_compra_id' => $unidad->id,
        ];
    }
}
