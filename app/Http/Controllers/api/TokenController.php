<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TokenController extends ApiController
{
    /**
     * Intercambia correo y contraseña por un token personal de Sanctum.
     */
    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'dispositivo' => ['nullable', 'string', 'max:100'],
        ]);

        $usuario = User::where('email', $datos['email'])->first();

        if (! $usuario || ! Hash::check($datos['password'], $usuario->password)) {
            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }

        $token = $usuario->createToken($datos['dispositivo'] ?? 'api')->plainTextToken;

        return response()->json(['token' => $token, 'tipo' => 'Bearer'], 201);
    }

    /**
     * Revoca el token con el que se hizo la petición.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(null, 204);
    }
}
