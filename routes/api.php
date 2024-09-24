<?php

use App\Http\Controllers\LoginAPIController;
use App\Http\Controllers\RegisterAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
    //Verificación sancutm

})->middleware('auth:sanctum');


Route::post('/login', [LoginAPIController::class, 'login'])->name('login');
Route::post('/register', [RegisterAPIController::class, 'register'])->name('register');
