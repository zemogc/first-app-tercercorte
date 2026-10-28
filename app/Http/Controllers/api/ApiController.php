<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class ApiController extends Controller
{
    /**
     * Tamaño de página solicitado con ?per_page=, limitado entre 1 y 100.
     */
    protected function porPagina(Request $request): int
    {
        return max(1, min(100, $request->integer('per_page', 15)));
    }

    /**
     * Elimina el modelo o responde 409 si otros registros dependen de él.
     */
    protected function eliminar(Model $modelo): JsonResponse
    {
        try {
            $modelo->delete();
        } catch (QueryException) {
            return response()->json([
                'message' => 'No se puede eliminar el registro porque tiene registros asociados.',
            ], 409);
        }

        return response()->json(null, 204);
    }
}
