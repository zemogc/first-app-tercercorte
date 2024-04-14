<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>COMUNAS</title>
  </head>
  <body>
  <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Comunas') }}
        </h2>
    </x-slot>
    <div class="container">
    <h1>Listado de Comunas</h1>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('comunas.create') }}"
                        class="bg-green-700 hover:bg-green-900 text-white font-bold py-2 px-4 rounded ml-2">Add</a>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Code</th>
                                <th scope="col">Commune</th>
                                <th scope="col">Municipality</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comunas as $comuna)
                                <tr>
                                    <th scope="row">{{ $comuna->comu_codi }}</th>
                                    <td>{{ $comuna->comu_nomb }}</td>
                                    <td>{{ $comuna->muni_nomb }}</td>
                                    <td>
                                        <a href="{{ route('comunas.edit', ['comuna' => $comuna->comu_codi]) }}"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Edit </a></li>


                                        <form action="{{ route('comunas.destroy', ['comuna' => $comuna->comu_codi]) }}"
                                            method='POST' style="display: inline-block">
                                            @method('delete')
                                            @csrf
                                            <input
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2"
                                                type="submit" value="Delete">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
