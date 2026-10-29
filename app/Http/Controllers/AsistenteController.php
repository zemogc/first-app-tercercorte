<?php

namespace App\Http\Controllers;

use App\Services\Asistente\AsistenteNoDisponible;
use App\Services\Asistente\GeoAsistente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AsistenteController extends Controller
{
    public function index(Request $request, GeoAsistente $asistente): View
    {
        return view('asistente', [
            'configurado' => $asistente->configurado(),
            'preguntaInicial' => (string) $request->query('q', ''),
        ]);
    }

    public function preguntar(Request $request, GeoAsistente $asistente): JsonResponse
    {
        $datos = $request->validate([
            'mensajes' => ['required', 'array', 'min:1', 'max:20'],
            'mensajes.*.rol' => ['required', 'in:user,assistant'],
            'mensajes.*.texto' => ['required', 'string', 'max:2000'],
        ]);

        if (end($datos['mensajes'])['rol'] !== 'user') {
            return response()->json(['message' => 'El último mensaje debe ser del usuario.'], 422);
        }

        try {
            $respuesta = $asistente->responder($datos['mensajes']);
        } catch (AsistenteNoDisponible $e) {
            return response()->json(['message' => $e->getMessage()], 503);
        }

        return response()->json($respuesta);
    }
}
