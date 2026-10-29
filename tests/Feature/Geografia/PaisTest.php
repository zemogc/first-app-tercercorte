<?php

namespace Tests\Feature\Geografia;

use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Pais;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaisTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_los_invitados_son_redirigidos_al_login(): void
    {
        auth()->logout();

        $this->get('/paises')->assertRedirect('/login');
    }

    public function test_lista_y_busca_paises(): void
    {
        Pais::factory()->create(['pais_codi' => 'COL', 'pais_nomb' => 'Colombia']);
        Pais::factory()->create(['pais_codi' => 'PER', 'pais_nomb' => 'Perú']);

        $this->get('/paises')->assertOk()->assertSee('Colombia')->assertSee('Perú');
        $this->get('/paises?q=colom')->assertOk()->assertSee('Colombia')->assertDontSee('Perú');
    }

    public function test_crea_un_pais_con_codigo_en_mayusculas(): void
    {
        $this->post('/paises', ['pais_codi' => 'ecu', 'pais_nomb' => 'Ecuador'])
            ->assertRedirect('/paises')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('tb_pais', ['pais_codi' => 'ECU', 'pais_nomb' => 'Ecuador']);
    }

    public function test_valida_codigo_y_nombre(): void
    {
        Pais::factory()->create(['pais_codi' => 'COL', 'pais_nomb' => 'Colombia']);

        $this->post('/paises', ['pais_codi' => 'CO', 'pais_nomb' => 'Colombia'])
            ->assertSessionHasErrors(['pais_codi', 'pais_nomb']);
    }

    public function test_actualiza_la_capital(): void
    {
        $municipio = Municipio::factory()->create();
        $pais = $municipio->departamento->pais;

        $this->put("/paises/{$pais->pais_codi}", ['pais_nomb' => 'Nuevo nombre', 'pais_capi' => $municipio->muni_codi])
            ->assertRedirect('/paises');

        $this->assertSame($municipio->muni_codi, $pais->fresh()->pais_capi);
        $this->get("/paises/{$pais->pais_codi}")->assertOk()->assertSee($municipio->muni_nomb);
    }

    public function test_no_elimina_paises_con_departamentos(): void
    {
        $departamento = Departamento::factory()->create();

        $this->delete("/paises/{$departamento->pais_codi}")
            ->assertRedirect('/paises')
            ->assertSessionHas('error');

        $this->assertDatabaseHas('tb_pais', ['pais_codi' => $departamento->pais_codi]);
    }

    public function test_elimina_un_pais_sin_dependencias(): void
    {
        $pais = Pais::factory()->create();

        $this->delete("/paises/{$pais->pais_codi}")->assertSessionHas('success');

        $this->assertDatabaseMissing('tb_pais', ['pais_codi' => $pais->pais_codi]);
    }
}
