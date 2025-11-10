<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\MusicSearchController;
use App\Http\Controllers\Api\UniversidadController;
use App\Http\Controllers\Api\FollowerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Rutas públicas (no requieren autenticación)
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// Universidades y Carreras (públicas para el formulario de registro)
Route::get('/universidades', [UniversidadController::class, 'index']);
Route::get('/universidades/{universidadId}/carreras', [UniversidadController::class, 'getCarreras']);
Route::get('/carreras', [UniversidadController::class, 'getAllCarreras']);

// Posts públicos (solo lectura)
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);
Route::get('/posts/user/{userId}', [PostController::class, 'userPosts']);

// Búsqueda de música iTunes (público)
Route::get('/music/search', [MusicSearchController::class, 'search']);
Route::get('/music/track', [MusicSearchController::class, 'getTrack']);
Route::get('/music/genre', [MusicSearchController::class, 'searchByGenre']);
Route::get('/music/popular', [MusicSearchController::class, 'getPopular']);

// Rutas protegidas (requieren autenticación con token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Autenticación
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::get('/auth/user', [AuthController::class, 'me']); // Alias para compatibilidad

    //followers count
    Route::get('/auth/followers/{id}', [FollowerController::class, 'followers']); // Alias para compatibilidad
    Route::get('/auth/following/{id}', [FollowerController::class, 'following']);

    // Posts protegidos (crear y eliminar requieren autenticación)
    Route::post('/posts', [PostController::class, 'store']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    // Comentarios
    Route::get('/posts/{post}/comments', [CommentController::class, 'index']); // Listar comentarios de un post
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']); // Crear comentario
    Route::get('/comments/{comentario}', [CommentController::class, 'show']); // Ver comentario específico
    Route::put('/comments/{comentario}', [CommentController::class, 'update']); // Editar comentario
    Route::delete('/comments/{comentario}', [CommentController::class, 'destroy']); // Eliminar comentario
    Route::get('/comments/{comentario}/replies', [CommentController::class, 'replies']); // Ver respuestas

    // Likes
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle']); // Dar/quitar like (toggle)
    Route::get('/posts/{post}/likes', [LikeController::class, 'index']); // Listar usuarios que dieron like
    Route::get('/posts/{post}/like/check', [LikeController::class, 'check']); // Verificar si el usuario dio like

    // Usuarios
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/search', [UserController::class, 'search']);
    Route::get('/users/{user}', [UserController::class, 'show']);

    // Notificaciones (likes, comentarios, seguidores)
    Route::get('/notifications', [NotificationController::class, 'index']); // Todas las notificaciones
    Route::get('/notifications/unread', [NotificationController::class, 'unread']); // Solo no leídas
    Route::get('/notifications/count', [NotificationController::class, 'count']); // Contador de no leídas
    Route::get('/notifications/type', [NotificationController::class, 'byType']); // Filtrar por tipo
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']); // Marcar una como leída
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']); // Marcar todas como leídas
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']); // Eliminar una notificación
    Route::delete('/notifications/clear-read', [NotificationController::class, 'clearRead']); // Limpiar leídas

    // Push Notifications - Registro de tokens de dispositivo
    Route::post('/notifications/register-device', [NotificationController::class, 'registerDevice']); // Registrar token FCM
    Route::post('/notifications/unregister-device', [NotificationController::class, 'unregisterDevice']); // Desregistrar token FCM
});

// Ruta de prueba de la API
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API funcionando correctamente',
        'timestamp' => now()
    ]);
});
