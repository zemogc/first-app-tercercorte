<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pais>
 */
class PaisFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pais_codi' => strtoupper(fake()->unique()->lexify('???')),
            'pais_nomb' => fake()->unique()->country(),
            'pais_capi' => null,
        ];
    }
}
