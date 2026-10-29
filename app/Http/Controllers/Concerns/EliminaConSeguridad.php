<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;

trait EliminaConSeguridad
{
    /**
     * Elimina el modelo y redirige al listado. Si existen registros que
     * dependen de él (llave foránea), informa al usuario en lugar de fallar.
     */
    protected function eliminar(Model $modelo, string $ruta, string $etiqueta): RedirectResponse
    {
        try {
            $modelo->delete();
        } catch (QueryException) {
            return redirect()->route($ruta)
                ->with('error', "No se puede eliminar {$etiqueta} porque tiene registros asociados.");
        }

        return redirect()->route($ruta)->with('success', "Se eliminó {$etiqueta} correctamente.");
    }
}
