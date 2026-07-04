<?php
use App\Http\Controllers\AvaliacaoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DogController;
use App\Http\Controllers\TourController;
use Illuminate\Http\Request;

Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);

// Protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/walkers', [UserController::class, 'walkers']);
    Route::get('/walkers/{id}', [UserController::class, 'show']);
    Route::get('/tutors/{id}', [UserController::class, 'showTutor']);
    Route::post('/dogs', [DogController::class, 'store']);
    Route::get('/dogs/my', [DogController::class, 'myDogs']);
    Route::post('/tours', [TourController::class, 'store']);
    Route::get('/tours', [TourController::class, 'index']);
    Route::put('/tours/{id}/accept', [TourController::class, 'accept']);
    Route::patch('/tours/{id}/reject', [TourController::class, 'reject']);
    Route::patch('/tours/{id}/cancel', [TourController::class, 'cancel']);
    Route::get('/my-tours',[TourController::class, 'myTours']);
    Route::post('/tours/{id}/complete', [TourController::class, 'complete']); 
    Route::delete('/tours/{id}', [TourController::class, 'destroy']);
    Route::post('/avaliacoes', [AvaliacaoController::class, 'store']);
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