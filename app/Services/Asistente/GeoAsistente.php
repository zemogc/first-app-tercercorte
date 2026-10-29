<?php

namespace App\Services\Asistente;

use Anthropic\Client;
use Anthropic\Core\Exceptions\APIConnectionException;
use Anthropic\Core\Exceptions\APIStatusException;
use Anthropic\Core\Exceptions\AuthenticationException;
use Anthropic\Core\Exceptions\RateLimitException;
use Illuminate\Support\Facades\Log;

/**
 * Asistente conversacional que responde preguntas sobre los datos del atlas.
 * Claude decide qué herramientas de consulta usar y este servicio las ejecuta
 * contra la base de datos hasta obtener la respuesta final.
 */
class GeoAsistente
{
    /** Máximo de idas y vueltas con herramientas por pregunta. */
    private const MAX_ITERACIONES = 6;

    private const INSTRUCCIONES = <<<'TXT'
        Eres el asistente de GeoAtlas, una aplicación que registra países de Latinoamérica con sus
        departamentos, municipios y comunas (o localidades). Respondes en español, de forma clara y breve.

        - Usa las herramientas para consultar los datos registrados antes de responder sobre lugares,
          poblaciones o cantidades. No inventes cifras.
        - Las poblaciones del atlas son aproximadas; menciónalo cuando des cifras.
        - Si algo no está registrado, dilo. Puedes complementar con conocimiento general, pero indica
          claramente qué parte no proviene del atlas.
        - Da formato con listas y **negritas** cuando ayude a leer. No uses tablas ni encabezados.
        TXT;

    private ?Client $cliente = null;

    public function __construct(
        private readonly HerramientasGeograficas $herramientas,
        private readonly ?string $apiKey,
        private readonly string $modelo,
    ) {
    }

    public function configurado(): bool
    {
        return filled($this->apiKey);
    }

    /**
     * Responde al último mensaje del historial.
     *
     * @param  list<array{rol: string, texto: string}>  $historial
     * @return array{respuesta: string, herramientas: list<string>}
     */
    public function responder(array $historial): array
    {
        if (! $this->configurado()) {
            throw new AsistenteNoDisponible('El asistente no está configurado. Define ANTHROPIC_API_KEY en el archivo .env.');
        }

        $mensajes = array_map(
            fn (array $m) => ['role' => $m['rol'], 'content' => $m['texto']],
            $historial,
        );
        $usadas = [];

        for ($i = 0; $i < self::MAX_ITERACIONES; $i++) {
            $respuesta = $this->enviar($mensajes);

            if ($respuesta->stopReason === 'refusal') {
                return [
                    'respuesta' => 'No puedo ayudar con esa solicitud. Prueba con una pregunta sobre los lugares del atlas.',
                    'herramientas' => $usadas,
                ];
            }

            if ($respuesta->stopReason !== 'tool_use') {
                return ['respuesta' => $this->texto($respuesta->content), 'herramientas' => $usadas];
            }

            $resultados = [];
            foreach ($respuesta->content as $bloque) {
                if ($bloque->type !== 'tool_use') {
                    continue;
                }

                $usadas[] = $bloque->name;
                $resultados[] = $this->ejecutar($bloque->id, $bloque->name, (array) $bloque->input);
            }

            $mensajes[] = ['role' => 'assistant', 'content' => $respuesta->content];
            $mensajes[] = ['role' => 'user', 'content' => $resultados];
        }

        return [
            'respuesta' => 'La consulta requirió demasiados pasos. Intenta con una pregunta más específica.',
            'herramientas' => $usadas,
        ];
    }

    /**
     * Envía la conversación a la API de mensajes.
     *
     * @param  list<array<string, mixed>>  $mensajes
     */
    protected function enviar(array $mensajes): object
    {
        try {
            return $this->cliente()->beta->messages->create(
                model: $this->modelo,
                maxTokens: 16000,
                system: [
                    ['type' => 'text', 'text' => self::INSTRUCCIONES, 'cacheControl' => ['type' => 'ephemeral']],
                ],
                tools: $this->herramientas->definiciones(),
                messages: $mensajes,
                // Conversación: esfuerzo bajo para responder rápido.
                outputConfig: ['effort' => 'low'],
                // Si el modelo rechaza la solicitud, el servidor reintenta con el respaldo por defecto.
                fallbacks: 'default',
                betas: ['server-side-fallback-2026-07-01'],
            );
        } catch (AuthenticationException $e) {
            throw new AsistenteNoDisponible('La clave ANTHROPIC_API_KEY no es válida.', previous: $e);
        } catch (RateLimitException $e) {
            throw new AsistenteNoDisponible('El asistente está recibiendo muchas consultas. Espera un momento e inténtalo de nuevo.', previous: $e);
        } catch (APIStatusException|APIConnectionException $e) {
            Log::warning('Error al consultar la API de Claude', ['error' => $e->getMessage()]);

            throw new AsistenteNoDisponible('No fue posible contactar al asistente. Inténtalo de nuevo en unos segundos.', previous: $e);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function ejecutar(string $id, string $nombre, array $entrada): array
    {
        try {
            $resultado = $this->herramientas->ejecutar($nombre, $entrada);

            return [
                'type' => 'tool_result',
                'toolUseID' => $id,
                'content' => json_encode($resultado, JSON_UNESCAPED_UNICODE),
            ];
        } catch (\InvalidArgumentException $e) {
            return ['type' => 'tool_result', 'toolUseID' => $id, 'content' => $e->getMessage(), 'isError' => true];
        }
    }

    /**
     * @param  iterable<object>  $bloques
     */
    private function texto(iterable $bloques): string
    {
        $partes = [];
        foreach ($bloques as $bloque) {
            if ($bloque->type === 'text') {
                $partes[] = $bloque->text;
            }
        }

        return trim(implode("\n\n", $partes)) ?: 'No encontré una respuesta para esa pregunta.';
    }

    private function cliente(): Client
    {
        return $this->cliente ??= new Client(apiKey: $this->apiKey);
    }
}
