<?php

use App\Models\Comentario;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SpotifyApiController;
use App\Http\Controllers\iTunesApiController;
use App\Http\Controllers\RegisterController;

Route::get('/', function () {
    return view('home');
});

Route::get('/', HomeController::class)->name('home');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);


Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']); 


Route::post('/logout', [LogoutController::class, 'store'])->name('logout');


Route::get('/editar-perfil', [PerfilController::class, 'index'])->name('perfil.index');
Route::post('/editar-perfil', [PerfilController::class, 'store'])->name('perfil.store');



Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/{user:username}/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');


Route::post('/{user:username}/posts/{post}', [ComentarioController::class, 'store'])->name('comentarios.store');


Route::post('/imagenes', [ImagenController::class, 'store'])->name('imagenes.store');
Route::post('/imagenes-perfil', [ImagenController::class, 'storePerfil'])->name('imagenes.perfil.store');
Route::delete('/imagenes', [ImagenController::class, 'destroy'])->name('imagenes.destroy');

// Rutas de Spotify
Route::get('/spotify/search', [SpotifyApiController::class, 'search'])->name('spotify.search');
Route::get('/spotify/track', [SpotifyApiController::class, 'getTrack'])->name('spotify.track');

// Rutas de iTunes
Route::get('/itunes/search', [iTunesApiController::class, 'search'])->name('itunes.search');
Route::get('/itunes/track', [iTunesApiController::class, 'getTrack'])->name('itunes.track');
Route::get('/itunes/genre', [iTunesApiController::class, 'searchByGenre'])->name('itunes.genre');
Route::get('/itunes/popular', [iTunesApiController::class, 'getPopular'])->name('itunes.popular');
Route::get('/itunes/more', [iTunesApiController::class, 'getMoreResults'])->name('itunes.more');


Route::post('/posts/{post}/likes', [LikeController::class, 'store'])->name('posts.likes.store');
Route::delete('/posts/{post}/likes', [LikeController::class, 'destroy'])->name('posts.likes.destroy');



Route::get('/{user:username}', [PostController::class, 'index'])->name('posts.index');


Route::post('/{user:username}/follow', [FollowerController::class, 'store'])->name('users.follow');
Route::delete('/{user:username}/unfollow', [FollowerController::class, 'destroy'])->name('users.unfollow');
Route::delete('/user', [UserController::class, 'destroy'])->name('user.destroy');