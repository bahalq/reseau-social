<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [AuthController::class, 'getLogin'])->name('login.show')->middleware('check.guest');
Route::post('/login', [AuthController::class, 'postLogin'])->name('login.store');
Route::get('/register', [AuthController::class, 'getRegister'])->name('register.show');
Route::post('/register', [AuthController::class, 'postRegister'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::resource('posts', PostsController::class)->except(['create'])
->middleware('check.auth');
Route::match(['GET', 'POST'], '/posts/{post_id}/like', [LikesController::class, 'toggle'])->name('likes.toggle');