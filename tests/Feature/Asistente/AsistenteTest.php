<?php

namespace Tests\Feature\Asistente;

use App\Models\User;
use App\Services\Asistente\GeoAsistente;
use App\Services\Asistente\HerramientasGeograficas;
use Database\Seeders\GeografiaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class AsistenteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_la_pagina_avisa_cuando_falta_la_clave(): void
    {
        config(['services.anthropic.key' => null]);

        $this->get('/asistente')->assertOk()->assertSee('ANTHROPIC_API_KEY');
        $this->postJson('/asistente', ['mensajes' => [['rol' => 'user', 'texto' => 'Hola']]])
            ->assertStatus(503);
    }

    public function test_valida_el_historial(): void
    {
        $this->postJson('/asistente', ['mensajes' => []])->assertUnprocessable();
        $this->postJson('/asistente', ['mensajes' => [['rol' => 'system', 'texto' => 'x']]])->assertUnprocessable();
        $this->postJson('/asistente', ['mensajes' => [['rol' => 'assistant', 'texto' => 'Hola']]])->assertUnprocessable();
    }

    public function test_ejecuta_herramientas_y_devuelve_la_respuesta_final(): void
    {
        $this->seed(GeografiaSeeder::class);

        $usoHerramienta = (object) [
            'stopReason' => 'tool_use',
            'content' => [
                (object) ['type' => 'text', 'text' => 'Voy a consultar Cali.'],
                (object) ['type' => 'tool_use', 'id' => 'toolu_1', 'name' => 'detalle_municipio', 'input' => ['nombre' => 'Cali']],
            ],
        ];
        $respuestaFinal = (object) [
            'stopReason' => 'end_turn',
            'content' => [(object) ['type' => 'text', 'text' => 'Cali tiene **22 comunas**.']],
        ];

        $llamadas = [];
        $asistente = Mockery::mock(GeoAsistente::class, [new HerramientasGeograficas, 'clave-de-prueba', 'claude-opus-5-5'])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $asistente->shouldReceive('enviar')
            ->twice()
            ->andReturnUsing(function (array $mensajes) use (&$llamadas, $usoHerramienta, $respuestaFinal) {
                $llamadas[] = $mensajes;

                return count($llamadas) === 1 ? $usoHerramienta : $respuestaFinal;
            });
        $this->app->instance(GeoAsistente::class, $asistente);

        $this->postJson('/asistente', ['mensajes' => [['rol' => 'user', 'texto' => '¿Cuántas comunas tiene Cali?']]])
            ->assertOk()
            ->assertJson([
                'respuesta' => 'Cali tiene **22 comunas**.',
                'herramientas' => ['detalle_municipio'],
            ]);

        $resultado = $llamadas[1][2]['content'][0];
        $this->assertSame('tool_result', $resultado['type']);
        $this->assertSame('toolu_1', $resultado['toolUseID']);
        $this->assertCount(22, json_decode($resultado['content'], true)['comunas']);
    }

    public function test_responde_con_un_mensaje_amable_ante_un_rechazo(): void
    {
        $asistente = Mockery::mock(GeoAsistente::class, [new HerramientasGeograficas, 'clave-de-prueba', 'claude-opus-5-5'])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $asistente->shouldReceive('enviar')->once()->andReturn((object) ['stopReason' => 'refusal', 'content' => []]);
        $this->app->instance(GeoAsistente::class, $asistente);

        $this->postJson('/asistente', ['mensajes' => [['rol' => 'user', 'texto' => 'Algo fuera de tema']]])
            ->assertOk()
            ->assertJsonPath('respuesta', 'No puedo ayudar con esa solicitud. Prueba con una pregunta sobre los lugares del atlas.');
    }
}
