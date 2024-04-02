<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MunicipioController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$municipios = Municipio::all();
        $municipios = DB::table('tb_municipio')
            ->join('tb_departamento', 'tb_municipio.depa_codi', '=', 'tb_departamento.depa_codi')
            ->select('tb_municipio.*', 'tb_departamento.depa_nomb')
            ->get();
        return view('municipio.index', ['municipios'=>$municipios]);
    }

    public function create()
    {
        $departamentos = DB::table('tb_departamento')
        ->orderBy('depa_nomb')
        ->get();
        return view('municipio.new', ['departamentos' => $departamentos]);
    }

    public function store(Request $request)
    {
        $municipio = new Municipio();
        // $municipio->muni_codi = $request->id;
        // El código de municipio es auto incremental
        $municipio->muni_nomb = $request->name;
        $municipio->depa_codi = $request->code;
        $municipio->save();

        $municipios = DB::table('tb_municipio')
        ->join('tb_departamento', 'tb_municipio.depa_codi', '=', 'tb_departamento.depa_codi')
        ->select('tb_municipio.*', 'tb_departamento.depa_nomb')
        ->get();
        return view('municipio.index', ['municipios' => $municipios]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        $municipio= Municipio::find($id);
        $departamentos=DB::table('tb_departamento')
        ->orderBy('depa_nomb')
        ->get();
        return view('municipio.edit', ['municipio' => $municipio, 'departamentos'=>$departamentos]);
    }

    public function update(Request $request, $id)
    {
        $municipio= Municipio::find($id);
        $municipio->muni_nomb = $request->name;
        $municipio->depa_codi = $request->code;
        $municipio->save();
        $municipios=DB::table('tb_municipio')
        ->join('tb_departamento', 'tb_municipio.depa_codi', '=', 'tb_departamento.depa_codi')
        ->select('tb_municipio.*', 'tb_departamento.depa_nomb')
        ->get();
        return view('municipio.index', ['municipios' => $municipios]);
    }

    public function destroy($id)
    {
        $municipio= Municipio::find($id);
        $municipio->delete();

        $municipios=DB::table('tb_municipio')
        ->join('tb_departamento', 'tb_municipio.depa_codi', '=', 'tb_departamento.depa_codi')
        ->select('tb_municipio.*', 'tb_departamento.depa_nomb')
        ->get();
        return view('municipio.index', ['municipios' => $municipios]);
    }
}
