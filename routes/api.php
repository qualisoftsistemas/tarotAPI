<?php

use App\Http\Controllers\CartaController;
use App\Http\Controllers\MensagemController;
use App\Http\Middleware\Autenticar;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware([ForceJsonResponse::class])->group(function () {

    Route::post('/login', function (Request $request) {
        $user     = config('auth.credentials.user');
        $password = config('auth.credentials.password');
        $token    = config('auth.credentials.token');

        if (
            $request->input('nome') === $user &&
            $request->input('senha') === $password
        ) {
            return response()->json([
                'status' => [
                    'code'      => 200,
                    'message'   => 'Logado com sucesso'
                ],
                'access_token' => $token,
                'token_type'   => 'Bearer',
            ], 200);
        }

        return response()->json([
            'status' => [
                'code'      => 401,
                'message'   => 'Credenciais Inválidas'
            ],
        ], 401);
    });

    Route::middleware([Autenticar::class])->group(function () {
        Route::apiResource('/cartas', CartaController::class)->except(['edit', 'create']);
        Route::get('/mensagens', [MensagemController::class, 'index']);
    });

    Route::get('/sortear', [CartaController::class, 'sortear_cartas']);
    Route::get('/analisar/{carta1}/{carta2}/{carta3}', [CartaController::class, 'analisar_cartas']);

    Route::post('/mensagens', [MensagemController::class, 'store']);
});
