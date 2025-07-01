<?php

use App\Http\Controllers\CartaController;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Support\Facades\Route;

Route::middleware([ForceJsonResponse::class])->group(function () {
    Route::apiResource('/cartas', CartaController::class)->except(['edit', 'create']);

    Route::get('/sortear', [CartaController::class, 'sortear_cartas']);
    Route::get('/analisar/{carta1}/{carta2}/{carta3}', [CartaController::class, 'analisar_cartas']);
});
