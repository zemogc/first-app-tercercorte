<?php

namespace Tests\Feature\Asistente;

use App\Services\Asistente\HerramientasGeograficas;
use Database\Seeders\GeografiaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HerramientasGeograficasTest extends TestCase
{
    use RefreshDatabase;

    private HerramientasGeograficas $herramientas;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(GeografiaSeeder::class);
        $this->herramientas = new HerramientasGeograficas;
    }

    public function test_las_definiciones_tienen_nombre_unico_y_esquema(): void
    {
        $nombres = array_column($this->herramientas->definiciones(), 'name');

        $this->assertSame($nombres, array_unique($nombres));
        foreach ($this->herramientas->definiciones() as $definicion) {
            $this->assertSame('object', $definicion['inputSchema']['type']);
        }
    }

    public function test_buscar_lugares_en_todos_los_niveles(): void
    {
        $resultado = $this->herramientas->ejecutar('buscar_lugares', ['texto' => 'valle']);

        $this->assertContains('Valle del Cauca', array_column($resultado['departamentos'], 'nombre'));
        $this->assertContains('Valledupar', array_column($resultado['municipios'], 'nombre'));
    }

    public function test_detalle_de_municipio_con_comunas(): void
    {
        $cali = $this->herramientas->ejecutar('detalle_municipio', ['nombre' => 'cali']);

        $this->assertSame('Valle del Cauca', $cali['departamento']);
        $this->assertCount(22, $cali['comunas']);
        $this->assertFalse($cali['es_capital_del_pais']);
        $this->assertTrue($this->herramientas->ejecutar('detalle_municipio', ['nombre' => 'Bogotá'])['es_capital_del_pais']);
    }

    public function test_detalle_de_pais_por_codigo_o_nombre(): void
    {
        $this->assertSame('Bogotá', $this->herramientas->ejecutar('detalle_pais', ['pais' => 'col'])['capital']);
        $this->assertSame('ECU', $this->herramientas->ejecutar('detalle_pais', ['pais' => 'Ecuador'])['codigo']);
        $this->assertArrayHasKey('error', $this->herramientas->ejecutar('detalle_pais', ['pais' => 'Atlántida']));
    }

    public function test_detalle_de_departamento(): void
    {
        $antioquia = $this->herramientas->ejecutar('detalle_departamento', ['nombre' => 'Antioquia']);

        $this->assertSame('Medellín', $antioquia['municipios'][0]['nombre']);
        $this->assertSame(16, $antioquia['municipios'][0]['comunas']);
    }

    public function test_ranking_de_poblacion_filtrado_por_pais(): void
    {
        $municipios = $this->herramientas->ejecutar('ranking_poblacion', ['nivel' => 'municipios', 'pais' => 'COL', 'limite' => 3]);
        $this->assertSame(['Bogotá', 'Medellín', 'Cali'], array_column($municipios['municipios'], 'nombre'));

        $departamentos = $this->herramientas->ejecutar('ranking_poblacion', ['nivel' => 'departamentos', 'limite' => 50]);
        $this->assertCount(20, $departamentos['departamentos']);
    }

    public function test_resumen_general(): void
    {
        $this->assertSame(8, $this->herramientas->ejecutar('resumen_general', [])['paises']);
    }

    public function test_rechaza_herramientas_o_parametros_invalidos(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->herramientas->ejecutar('detalle_municipio', ['nombre' => '  ']);
    }
}
