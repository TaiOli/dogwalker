<?php
use App\Http\Controllers\EvaluationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DogController;
use App\Http\Controllers\TourController;
use Illuminate\Http\Request;

Route::controller(UserController::class)->group(function(){
    Route::post('/users','store');
    Route::post('/login','login');
 });

// Protegidas
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', function (Request $request) {
        $user = $request->user();
        return [
            'id' => $user->id,
            'username' => $user->username,
            'nome' => $user->nome,
            'email' => $user->email,
            'telefone' => $user->telefone,
            'tipo_usuario' => $user->tipo_usuario,
            'foto' => $user->foto
        ];
    });
    
    Route::controller(UserController::class)->group(function(){
        Route::get('/walkers','walkers');
        Route::get('/walkers/{id}','show');
        Route::get('/tutors/{id}','showTutor');
        Route::put('/users/{id}', 'update');
    });
    
    Route::controller(DogController::class)->group(function(){
        Route::post('/dogs','store');
        Route::get('/dogs/my','myDogs');
        Route::put('/dogs/{id}', 'edit');
    });

    Route::controller(TourController::class)->group(function(){
        Route::post('/tours','store');
        Route::get('/tours','index');
        Route::put('/tours/{id}/accept','accept');
        Route::patch('/tours/{id}/reject','reject');
        Route::patch('/tours/{id}/cancel','cancel');
        Route::get('/my-tours','myTours');
        Route::patch('/tours/{id}/complete','complete');
        Route::delete('/tours/{id}','destroy');
    });

    Route::controller(EvaluationController::class)->group(function(){
        Route::post('/evaluation','store');
    });
});