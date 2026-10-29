<?php

namespace Tests\Feature;

use App\Models\Pais;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_landing_muestra_los_totales(): void
    {
        Pais::factory()->count(3)->create();

        $this->get('/')
            ->assertOk()
            ->assertSee('GeoAtlas')
            ->assertSeeInOrder(['Países', '3']);
    }
}
