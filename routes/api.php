<?php

use App\Http\Controllers\Rentman\WebhookController;
use App\Http\Middleware\Rentman\VerifyRentmanDigest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/webhook/rentman', [WebhookController::class, 'handle'])
    ->middleware(VerifyRentmanDigest::class);

