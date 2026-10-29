<?php

namespace Tests\Feature\Api;

use App\Models\Comuna;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Pais;
use App\Models\User;
use Database\Seeders\GeografiaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GeografiaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_paises_paginados_con_su_capital(): void
    {
        $this->seed(GeografiaSeeder::class);

        $this->getJson('/api/paises?per_page=2')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.total', 8)
            ->assertJsonStructure(['data' => [['codigo', 'nombre', 'capital', 'total_departamentos']], 'links', 'meta']);

        $this->getJson('/api/paises?q=colombia')
            ->assertJsonPath('data.0.codigo', 'COL')
            ->assertJsonPath('data.0.capital.nombre', 'Bogotá');
    }

    public function test_detalle_de_pais_incluye_departamentos(): void
    {
        $this->seed(GeografiaSeeder::class);

        $this->getJson('/api/paises/COL')
            ->assertOk()
            ->assertJsonPath('data.total_departamentos', 33)
            ->assertJsonCount(33, 'data.departamentos');
    }

    public function test_filtra_municipios_por_pais_y_ordena_por_poblacion(): void
    {
        $this->seed(GeografiaSeeder::class);

        $respuesta = $this->getJson('/api/municipios?pais=col&orden=poblacion&per_page=3')->assertOk();

        $this->assertSame(['Bogotá', 'Medellín', 'Cali'], array_column($respuesta->json('data'), 'nombre'));
    }

    public function test_filtra_comunas_por_municipio(): void
    {
        $this->seed(GeografiaSeeder::class);
        $cali = Municipio::firstWhere('muni_nomb', 'Cali');

        $this->getJson("/api/comunas?municipio={$cali->muni_codi}&per_page=100")
            ->assertOk()
            ->assertJsonCount(22, 'data');
    }

    public function test_estadisticas(): void
    {
        $this->seed(GeografiaSeeder::class);

        $this->getJson('/api/estadisticas')
            ->assertOk()
            ->assertJsonPath('totales.paises', 8)
            ->assertJsonPath('municipios_mas_poblados.0.nombre', 'São Paulo');
    }

    public function test_recurso_inexistente_responde_404_en_json(): void
    {
        $this->get('/api/municipios/999999')->assertNotFound()->assertJsonStructure(['message']);
    }

    public function test_escritura_requiere_token(): void
    {
        $this->postJson('/api/paises', ['pais_codi' => 'URY', 'pais_nomb' => 'Uruguay'])->assertUnauthorized();
    }

    public function test_emite_token_y_permite_crear_con_el(): void
    {
        User::factory()->create(['email' => 'api@geoatlas.test']);

        $this->postJson('/api/tokens', ['email' => 'api@geoatlas.test', 'password' => 'incorrecta'])
            ->assertUnprocessable();

        $token = $this->postJson('/api/tokens', ['email' => 'api@geoatlas.test', 'password' => 'password'])
            ->assertCreated()
            ->json('token');

        $this->withToken($token)
            ->postJson('/api/paises', ['pais_codi' => 'ury', 'pais_nomb' => 'Uruguay'])
            ->assertCreated()
            ->assertJsonPath('data.codigo', 'URY');
    }

    public function test_crud_autenticado_de_municipios(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $departamento = Departamento::factory()->create();

        $codigo = $this->postJson('/api/municipios', [
            'muni_nomb' => 'Jamundí', 'depa_codi' => $departamento->depa_codi, 'muni_pobl' => 190000,
        ])->assertCreated()->json('data.codigo');

        $this->putJson("/api/municipios/{$codigo}", ['muni_nomb' => 'Jamundí', 'depa_codi' => $departamento->depa_codi, 'muni_pobl' => 200000])
            ->assertOk()
            ->assertJsonPath('data.poblacion', 200000);

        $this->postJson('/api/municipios', ['muni_nomb' => ''])->assertUnprocessable()
            ->assertJsonValidationErrors(['muni_nomb', 'depa_codi']);

        $this->deleteJson("/api/municipios/{$codigo}")->assertNoContent();
        $this->assertDatabaseMissing('tb_municipio', ['muni_codi' => $codigo]);
    }

    public function test_no_elimina_con_dependencias(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $comuna = Comuna::factory()->create();

        $this->deleteJson("/api/municipios/{$comuna->muni_codi}")->assertStatus(409);
        $this->deleteJson("/api/paises/{$comuna->municipio->departamento->pais_codi}")->assertStatus(409);
        $this->assertSame(1, Pais::count());
    }
}
