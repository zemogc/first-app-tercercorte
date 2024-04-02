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
        $pais = DB::table('tb_pais')
        ->select('tb_pais.*', 'tb_pais.pais_codi')
        ->get();
        return view('pais.index', ['pais' => $pais]);
    }


    public function create()
    {
        $pais = DB::table('tb_pais')
        ->orderBy('pais_codi')
        ->get();
        return view('pais.new', ['pais' => $pais]);
    }

    public function store(Request $request)
    {
        $pais = new Pais();
        $pais->pais_capi = $request->id;
        // El código de pais es auto incremental
        $pais->pais_nomb = $request->name;
        $pais->pais_codi = $request->code;
        $pais->save();

        $pais = DB::table('tb_pais')
        ->select('tb_pais.*', 'tb_pais.pais_codi')
        ->get();
        return view('pais.index', ['pais' => $pais]);
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

    public function destroy( $id)
    {
        //
    }
}
