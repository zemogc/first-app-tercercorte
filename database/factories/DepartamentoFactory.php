<?php

namespace Database\Factories;

use App\Models\Pais;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Departamento>
 */
class DepartamentoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'depa_nomb' => fake()->unique()->state(),
            'pais_codi' => Pais::factory(),
        ];
    }
}
