<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('principal');
});

Route::get('/register', [UserController::class, 'index'])->name('register');
Route::post('/register', [UserController::class, 'store']);

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']); 

Route::get('/logout', [LogoutController::class, 'store'])->name('logout');;

Route::get('/muro', [PostController::class, 'index'])->name('posts.index')->middleware('auth');
