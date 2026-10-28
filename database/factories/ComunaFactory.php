<?php

namespace Database\Factories;

use App\Models\Municipio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comuna>
 */
class ComunaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'comu_nomb' => 'Comuna '.fake()->unique()->numberBetween(1, 999),
            'muni_codi' => Municipio::factory(),
        ];
    }
}
