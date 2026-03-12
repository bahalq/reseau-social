<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login',[AuthController::class, 'getLogin'])->name('login.show');
Route::post('/login',[AuthController::class, 'postLogin'])->name('login.store');
Route::get('/register',[AuthController::class, 'getRegister'])->name('register.show');
Route::post('/register',[AuthController::class, 'postRegister'])->name('register.store');
Route::get('/logout',[AuthController::class, 'logout'])->name('logout');