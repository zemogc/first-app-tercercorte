<?php

namespace App\Services\Asistente;

use RuntimeException;

/**
 * El asistente no puede responder: falta la clave de API o el servicio falló.
 * El mensaje se muestra tal cual al usuario.
 */
class AsistenteNoDisponible extends RuntimeException
{
}
