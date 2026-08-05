<?php

use App\Http\Controllers\DogController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\UserController;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rotas Públicas
Route::post('/users', [UserController::class, 'store'])
    ->middleware('throttle:register');

Route::post('/login', [UserController::class, 'login'])
    ->middleware('throttle:login');

Route::post('/forgot-password', [UserController::class, 'forgotPassword'])
    ->middleware('throttle:password-reset');

Route::post('/reset-password', [UserController::class, 'resetPassword'])
    ->middleware('throttle:password-reset');

// Rotas Protegidas
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {

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

    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::put('/{id}', 'update');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/walkers', 'walkers');
        Route::get('/walkers/{id}', 'show');
        Route::get('/tutors/{id}', 'showTutor');
    });

    Route::prefix('dogs')->controller(DogController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/my', 'myDogs');
        Route::put('/{id}', 'edit');
        Route::delete('/{id}', 'destroy');
    });

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

    Route::prefix('evaluations')->controller(EvaluationController::class)->group(function () {
        Route::post('/', 'store');
    });

    Route::get('/notifications', function (Request $request) {
        return response()->json(
            $request->user()->notifications()->latest()->get()
        );
    });

    Route::get('/public-logs', function () {

        return response()->json(
            Log::with('user:id,name,email')->latest()->paginate(10)
        );
    });
});