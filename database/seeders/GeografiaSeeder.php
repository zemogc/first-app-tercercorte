<?php

namespace Database\Seeders;

use App\Models\Comuna;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Pais;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos geográficos de referencia. Las poblaciones son aproximadas
 * (proyecciones recientes redondeadas) y sirven solo para demostración.
 */
class GeografiaSeeder extends Seeder
{
    /**
     * país => [nombre, capital, [departamento => [municipio => población]]]
     */
    private const DATOS = [
        'COL' => ['Colombia', 'Bogotá', [
            'Amazonas' => ['Leticia' => 50000],
            'Antioquia' => ['Medellín' => 2600000, 'Bello' => 560000, 'Itagüí' => 290000, 'Envigado' => 240000, 'Rionegro' => 140000],
            'Arauca' => ['Arauca' => 100000],
            'Atlántico' => ['Barranquilla' => 1300000, 'Soledad' => 690000, 'Malambo' => 130000],
            'Bogotá D.C.' => ['Bogotá' => 7900000],
            'Bolívar' => ['Cartagena' => 1060000, 'Magangué' => 140000],
            'Boyacá' => ['Tunja' => 180000, 'Duitama' => 120000, 'Sogamoso' => 120000],
            'Caldas' => ['Manizales' => 450000, 'La Dorada' => 80000],
            'Caquetá' => ['Florencia' => 180000],
            'Casanare' => ['Yopal' => 180000],
            'Cauca' => ['Popayán' => 330000, 'Santander de Quilichao' => 100000],
            'Cesar' => ['Valledupar' => 540000],
            'Chocó' => ['Quibdó' => 130000],
            'Córdoba' => ['Montería' => 510000, 'Lorica' => 120000],
            'Cundinamarca' => ['Soacha' => 700000, 'Zipaquirá' => 150000, 'Fusagasugá' => 150000, 'Facatativá' => 140000],
            'Guainía' => ['Inírida' => 20000],
            'Guaviare' => ['San José del Guaviare' => 55000],
            'Huila' => ['Neiva' => 370000, 'Pitalito' => 140000],
            'La Guajira' => ['Riohacha' => 210000, 'Maicao' => 170000],
            'Magdalena' => ['Santa Marta' => 540000, 'Ciénaga' => 105000],
            'Meta' => ['Villavicencio' => 560000],
            'Nariño' => ['Pasto' => 390000, 'Tumaco' => 260000, 'Ipiales' => 150000],
            'Norte de Santander' => ['Cúcuta' => 790000, 'Ocaña' => 130000],
            'Putumayo' => ['Mocoa' => 60000],
            'Quindío' => ['Armenia' => 300000, 'Calarcá' => 80000],
            'Risaralda' => ['Pereira' => 480000, 'Dosquebradas' => 230000],
            'San Andrés y Providencia' => ['San Andrés' => 65000],
            'Santander' => ['Bucaramanga' => 620000, 'Floridablanca' => 270000, 'Barrancabermeja' => 220000, 'Girón' => 200000],
            'Sucre' => ['Sincelejo' => 300000],
            'Tolima' => ['Ibagué' => 550000, 'Espinal' => 80000],
            'Valle del Cauca' => ['Cali' => 2280000, 'Palmira' => 360000, 'Buenaventura' => 320000, 'Tuluá' => 230000, 'Jamundí' => 190000, 'Yumbo' => 140000, 'Cartago' => 140000, 'Buga' => 130000],
            'Vaupés' => ['Mitú' => 35000],
            'Vichada' => ['Puerto Carreño' => 20000],
        ]],
        'ECU' => ['Ecuador', 'Quito', [
            'Pichincha' => ['Quito' => 2800000],
            'Guayas' => ['Guayaquil' => 2700000],
            'Azuay' => ['Cuenca' => 600000],
        ]],
        'PER' => ['Perú', 'Lima', [
            'Lima' => ['Lima' => 10000000],
            'Arequipa' => ['Arequipa' => 1100000],
            'Cusco' => ['Cusco' => 430000],
        ]],
        'VEN' => ['Venezuela', 'Caracas', [
            'Distrito Capital' => ['Caracas' => 2000000],
            'Zulia' => ['Maracaibo' => 1500000],
        ]],
        'BRA' => ['Brasil', 'Brasília', [
            'Distrito Federal' => ['Brasília' => 2800000],
            'São Paulo' => ['São Paulo' => 12300000],
            'Río de Janeiro' => ['Río de Janeiro' => 6200000],
        ]],
        'ARG' => ['Argentina', 'Buenos Aires', [
            'Ciudad Autónoma de Buenos Aires' => ['Buenos Aires' => 3100000],
            'Córdoba (AR)' => ['Córdoba' => 1400000],
        ]],
        'MEX' => ['México', 'Ciudad de México', [
            'Ciudad de México' => ['Ciudad de México' => 9200000],
            'Jalisco' => ['Guadalajara' => 1400000],
            'Nuevo León' => ['Monterrey' => 1150000],
        ]],
        'CHL' => ['Chile', 'Santiago', [
            'Región Metropolitana' => ['Santiago' => 5600000],
            'Valparaíso' => ['Valparaíso' => 300000],
        ]],
    ];

    private const COMUNAS = [
        'Cali' => [
            'Comuna 1', 'Comuna 2', 'Comuna 3', 'Comuna 4', 'Comuna 5', 'Comuna 6', 'Comuna 7', 'Comuna 8',
            'Comuna 9', 'Comuna 10', 'Comuna 11', 'Comuna 12', 'Comuna 13', 'Comuna 14', 'Comuna 15', 'Comuna 16',
            'Comuna 17', 'Comuna 18', 'Comuna 19', 'Comuna 20', 'Comuna 21', 'Comuna 22',
        ],
        'Medellín' => [
            'Popular', 'Santa Cruz', 'Manrique', 'Aranjuez', 'Castilla', 'Doce de Octubre', 'Robledo', 'Villa Hermosa',
            'Buenos Aires', 'La Candelaria', 'Laureles-Estadio', 'La América', 'San Javier', 'El Poblado', 'Guayabal', 'Belén',
        ],
        'Bogotá' => [
            'Usaquén', 'Chapinero', 'Santa Fe', 'San Cristóbal', 'Usme', 'Tunjuelito', 'Bosa', 'Kennedy', 'Fontibón', 'Engativá',
            'Suba', 'Barrios Unidos', 'Teusaquillo', 'Los Mártires', 'Antonio Nariño', 'Puente Aranda', 'La Candelaria',
            'Rafael Uribe Uribe', 'Ciudad Bolívar', 'Sumapaz',
        ],
    ];

    public function run(): void
    {
        DB::transaction(function () {
            foreach (self::DATOS as $codigo => [$nombre, $capital, $departamentos]) {
                $pais = Pais::create(['pais_codi' => $codigo, 'pais_nomb' => $nombre]);

                foreach ($departamentos as $depaNombre => $municipios) {
                    $departamento = Departamento::create(['depa_nomb' => $depaNombre, 'pais_codi' => $codigo]);

                    foreach ($municipios as $muniNombre => $poblacion) {
                        $municipio = Municipio::create([
                            'muni_nomb' => $muniNombre,
                            'depa_codi' => $departamento->depa_codi,
                            'muni_pobl' => $poblacion,
                        ]);

                        if ($muniNombre === $capital && $pais->pais_capi === null) {
                            $pais->update(['pais_capi' => $municipio->muni_codi]);
                        }

                        foreach (self::COMUNAS[$muniNombre] ?? [] as $comuna) {
                            Comuna::create(['comu_nomb' => $comuna, 'muni_codi' => $municipio->muni_codi]);
                        }
                    }
                }
            }
        });
    }
}
