<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('principal');
});


Route::get('/register', [UserController::class, 'index']) ->name('register');
Route::post('/register', [UserController::class, 'store']);

Route::get('/muro', [PostController::class, 'index']) ->name('posts.index');
