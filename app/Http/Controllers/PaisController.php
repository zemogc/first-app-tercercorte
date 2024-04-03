<?php

namespace App\Http\Controllers;

use App\Models\Pais;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaisController extends Controller
{

    public function index()
    {
        //$pais = Pais::all();
        $paises = DB::table('tb_pais')
            ->join('tb_municipio', 'tb_pais.pais_capi', '=', 'tb_municipio.muni_codi')
            ->select('tb_pais.*', 'tb_municipio.muni_nomb')
            ->get();
        return view('pais.index', ['paises'=>$paises]);
    }


    public function create()
    {
        $municipios = DB::table('tb_municipio')
        ->orderBy('muni_nomb')
        ->get();
        return view('pais.new', ['municipios' => $municipios]);
    }

    public function store(Request $request)
    {
        $pais = new Pais();
        $pais->pais_codi = strtoupper($request->id);
        $pais->pais_nomb = $request->name;
        $pais->pais_capi = $request->code;
        $pais->save();

        $paises = DB::table('tb_pais')
        ->join('tb_municipio', 'tb_pais.pais_capi', '=', 'tb_municipio.muni_codi')
        ->select('tb_pais.*', 'tb_municipio.muni_nomb')
        ->get();
        return view('pais.index', ['paises' => $paises]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        $pais= Pais::find($id);
        $municipios=DB::table('tb_municipio')
        ->orderBy('muni_nomb')
        ->get();
        return view('pais.edit', ['pais' => $pais, 'municipios'=>$municipios]);
    }

    public function update(Request $request, $id)
    {
        $pais= Pais::find($id);
        $pais->pais_codi = strtoupper($request->id);
        $pais->pais_nomb = $request->name;
        $pais->pais_capi = $request->code;
        $pais->save();
        $paises=DB::table('tb_pais')
        ->join('tb_municipio', 'tb_pais.pais_codi', '=', 'tb_municipio.muni_codi')
        ->select('tb_pais.*', 'tb_municipio.muni_nomb')
        ->get();
        return view('pais.index', ['paises' => $paises]);
    }

    public function destroy( $id)
    {
        $pais= Pais::find($id);
        $pais->delete();

        $paises=DB::table('tb_pais')
        ->join('tb_municipio', 'tb_pais.pais_capi', '=', 'tb_municipio.muni_codi')
        ->select('tb_pais.*', 'tb_municipio.muni_nomb')
        ->get();
        return view('pais.index', ['paises' => $paises]);
    }
}
