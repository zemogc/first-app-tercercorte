<?php

namespace Tests\Feature\Geografia;

use App\Models\Comuna;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Pais;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DivisionesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_crud_de_departamentos(): void
    {
        $pais = Pais::factory()->create();

        $this->post('/departamentos', ['depa_nomb' => 'Valle del Cauca', 'pais_codi' => $pais->pais_codi])
            ->assertRedirect('/departamentos');
        $departamento = Departamento::firstWhere('depa_nomb', 'Valle del Cauca');
        $this->assertNotNull($departamento);

        $this->get('/departamentos')->assertOk()->assertSee('Valle del Cauca');
        $this->get("/departamentos/{$departamento->depa_codi}")->assertOk()->assertSee($pais->pais_nomb);

        $this->put("/departamentos/{$departamento->depa_codi}", ['depa_nomb' => 'Valle', 'pais_codi' => $pais->pais_codi])
            ->assertRedirect('/departamentos');
        $this->assertSame('Valle', $departamento->fresh()->depa_nomb);

        $this->delete("/departamentos/{$departamento->depa_codi}")->assertSessionHas('success');
        $this->assertModelMissing($departamento);
    }

    public function test_departamento_requiere_pais_existente(): void
    {
        $this->post('/departamentos', ['depa_nomb' => 'Fantasma', 'pais_codi' => 'XXX'])
            ->assertSessionHasErrors('pais_codi');
    }

    public function test_crud_de_municipios(): void
    {
        $departamento = Departamento::factory()->create();

        $this->post('/municipios', ['muni_nomb' => 'Cali', 'depa_codi' => $departamento->depa_codi, 'muni_pobl' => 2280000])
            ->assertRedirect('/municipios');
        $municipio = Municipio::firstWhere('muni_nomb', 'Cali');

        $this->get('/municipios?q=cali')->assertOk()->assertSee('2.280.000');
        $this->get("/municipios/{$municipio->muni_codi}")->assertOk()->assertSee($departamento->depa_nomb);

        $this->put("/municipios/{$municipio->muni_codi}", ['muni_nomb' => 'Santiago de Cali', 'depa_codi' => $departamento->depa_codi, 'muni_pobl' => ''])
            ->assertRedirect('/municipios');
        $this->assertSame('Santiago de Cali', $municipio->fresh()->muni_nomb);
        $this->assertNull($municipio->fresh()->muni_pobl);
    }

    public function test_no_elimina_municipios_con_comunas(): void
    {
        $comuna = Comuna::factory()->create();

        $this->delete("/municipios/{$comuna->muni_codi}")->assertSessionHas('error');
        $this->assertDatabaseHas('tb_municipio', ['muni_codi' => $comuna->muni_codi]);
    }

    public function test_crud_de_comunas(): void
    {
        $municipio = Municipio::factory()->create();

        $this->post('/comunas', ['comu_nomb' => 'Comuna 2', 'muni_codi' => $municipio->muni_codi])
            ->assertRedirect('/comunas');
        $comuna = Comuna::firstWhere('comu_nomb', 'Comuna 2');

        $this->get('/comunas')->assertOk()->assertSee('Comuna 2')->assertSee($municipio->muni_nomb);

        $this->put("/comunas/{$comuna->comu_codi}", ['comu_nomb' => 'Comuna 22', 'muni_codi' => $municipio->muni_codi])
            ->assertRedirect('/comunas');
        $this->assertSame('Comuna 22', $comuna->fresh()->comu_nomb);

        $this->delete("/comunas/{$comuna->comu_codi}")->assertSessionHas('success');
        $this->assertModelMissing($comuna);
    }

    public function test_formularios_de_creacion_y_edicion_cargan(): void
    {
        $comuna = Comuna::factory()->create();
        $municipio = $comuna->municipio;
        $departamento = $municipio->departamento;

        foreach ([
            '/paises/create', "/paises/{$departamento->pais_codi}/edit",
            '/departamentos/create', "/departamentos/{$departamento->depa_codi}/edit",
            '/municipios/create', "/municipios/{$municipio->muni_codi}/edit",
            '/comunas/create', "/comunas/{$comuna->comu_codi}/edit",
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_dashboard_muestra_totales_con_datos_semilla(): void
    {
        $this->seed(\Database\Seeders\GeografiaSeeder::class);

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Bogotá')
            ->assertSee('Colombia');

        $this->assertSame('Bogotá', Pais::find('COL')->capital->muni_nomb);
        $this->assertSame(22, Municipio::firstWhere('muni_nomb', 'Cali')->comunas()->count());
    }
}
