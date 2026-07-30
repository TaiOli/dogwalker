<?php

use App\Http\Controllers\DogController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rotas Públicas

Route::controller(UserController::class)->group(function () {
    Route::post('/users', 'store');
    Route::post('/login', 'login');
    Route::post('/forgot-password', 'forgotPassword');
    Route::post('/reset-password', 'resetPassword');
});

// Rotas Protegidas

Route::middleware('auth:sanctum')->group(function () {

    // Usuário autenticado
    Route::get('/me', function (Request $request) {
        $user = $request->user();

        return [
            'id'            => $user->id,
            'username'      => $user->username,
            'nome'          => $user->nome,
            'email'         => $user->email,
            'telefone'      => $user->telefone,
            'tipo_usuario'  => $user->tipo_usuario->value,
            'foto'          => $user->foto,
        ];
    });

    // Usuários

    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::put('/{id}', 'update');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/walkers', 'walkers');
        Route::get('/walkers/{id}', 'show');
        Route::get('/tutors/{id}', 'showTutor');
    });

    // Cães

    Route::prefix('dogs')->controller(DogController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/my', 'myDogs');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });

    // Passeios

    Route::prefix('tours')->controller(TourController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/', 'index');

        Route::put('/{id}/accept', 'accept');
        Route::patch('/{id}/reject', 'reject');
        Route::patch('/{id}/cancel', 'cancel');
        Route::patch('/{id}/complete', 'complete');

        Route::delete('/{id}', 'destroy');
    });

    Route::get('/my-tours', [TourController::class, 'myTours']);

    // Avaliações

    Route::prefix('evaluations')->controller(EvaluationController::class)->group(function () {
        Route::post('/', 'store');
    });

    // Notificações

    Route::get('/notifications', function (Request $request) {
        return response()->json(
            $request->user()->notifications()->latest()->get()
        );
    });
});