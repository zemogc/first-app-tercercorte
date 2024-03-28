<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/estudiantes', function () {
    return view('estudiantes');
});

Route::get('/profesores/{cofigo?}', function ($codigo = 0) {
    //return $codigo;
    $nombre = 'Pepito';
    
    return view('profesores',
    ['nombre'=>$nombre,
     'codigo'=>$codigo,
     'plan'=> 12345
    ]);
})->name('profesor');