<?php

namespace Database\Factories;

use App\Models\Departamento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Municipio>
 */
class MunicipioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'muni_nomb' => fake()->unique()->city(),
            'depa_codi' => Departamento::factory(),
            'muni_pobl' => fake()->numberBetween(5000, 3000000),
        ];
    }
}
