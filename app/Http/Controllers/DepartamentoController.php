<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$departamentos = Departamento::all();
        $departamentos = DB::table('tb_departamento')
            ->join('tb_pais', 'tb_departamento.pais_codi', '=', 'tb_pais.pais_codi')
            ->select('tb_departamento.*', 'tb_pais.pais_nomb')
            ->get();
        return view('departamento.index', ['departamentos'=>$departamentos]);
    }

    public function create()
    {
        $pais = DB::table('tb_pais')
        ->orderBy('pais_nomb')
        ->get();
        return view('departamento.new', ['pais' => $pais]);
    }

    public function store(Request $request)
    {
        $departamento = new Departamento();
        // $departamento->depa_codi = $request->id;
        // El código de departamento es auto incremental
        $departamento->depa_nomb = $request->name;
        $departamento->pais_codi = $request->code;
        $departamento->save();

        $departamentos = DB::table('tb_departamento')
        ->join('tb_pais', 'tb_departamento.pais_codi', '=', 'tb_pais.pais_codi')
        ->select('tb_departamento.*', 'tb_pais.pais_nomb')
        ->get();
        return view('departamento.index', ['departamentos' => $departamentos]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
