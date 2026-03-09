<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/webhooks/asaas', [\App\Http\Controllers\Api\AsaasWebhookController::class , 'handleAsaas']);

Route::get('/ofertas-disponiveis', [\App\Http\Controllers\InscricaoController::class , 'ofertasDisponiveis']);