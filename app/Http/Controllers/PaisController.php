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
        //
    }

    public function store(Request $request)
    {
        //
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
