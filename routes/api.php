<?php

use App\Http\Controllers\Rentman\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/webhook/rentman', [WebhookController::class, 'handle']);
Route::get('/webhook/rentman', [WebhookController::class, 'handle']);