<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DogController;

Route::post('/users', [UserController::class, 'store']);
Route::post('/dogs', [DogController::class, 'store']);