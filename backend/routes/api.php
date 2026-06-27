<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DogController;
use App\Http\Controllers\PasseioController;
use Illuminate\Http\Request;

Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);

// Protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/walkers', [UserController::class, 'walkers']);
    Route::get('/walkers/{id}', [UserController::class, 'show']);
    Route::post('/dogs', [DogController::class, 'store']);
    Route::get('/dogs/my', [DogController::class, 'myDogs']);
    Route::post('/passeios', [PasseioController::class, 'store']);
    Route::get('/passeios', [PasseioController::class, 'index']);
    Route::put('/passeios/{id}/aceitar', [PasseioController::class, 'aceitar']);
    Route::patch('/passeios/{id}/recusar', [PasseioController::class, 'recusar']);
    Route::get('/meus-passeios',[PasseioController::class, 'meusPasseios']);
    Route::delete('/passeios/{id}', [PasseioController::class, 'destroy']);
    Route::get('/me', function (Request $request) {
        $user = $request->user();

        return [
            'id' => $user->id,
            'nome' => $user->nome,
            'email' => $user->email,
            'telefone' => $user->telefone,
            'tipo_usuario' => $user->tipo_usuario,
            'foto' => $user->foto
        ];
    });
});