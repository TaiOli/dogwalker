<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DogController;
use App\Http\Controllers\PasseioController;

Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);

// Protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/dogs', [DogController::class, 'store']);
    Route::get('/dogs/my', [DogController::class, 'myDogs']);
    Route::post('/passeios', [PasseioController::class, 'store']);
    Route::get('/passeios', [PasseioController::class, 'index']);
    Route::put('/passeios/{id}/aceitar', [PasseioController::class, 'aceitar']);
    Route::put('/passeios/{id}/recusar', [PasseioController::class, 'recusar']);
});