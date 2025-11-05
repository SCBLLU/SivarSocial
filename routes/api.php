<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\NotificationController;

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

// Posts públicos (solo lectura)
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);

// Rutas protegidas (requieren autenticación con token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Autenticación
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::get('/auth/user', [AuthController::class, 'me']); // Alias para compatibilidad

    // Posts protegidos (crear y eliminar requieren autenticación)
    Route::post('/posts', [PostController::class, 'store']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    // Usuarios
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/search', [UserController::class, 'search']);
    Route::get('/users/{user}', [UserController::class, 'show']);

    // Notificaciones
    Route::prefix('notifications')->group(function () {
        // Listar notificaciones
        Route::get('/', [NotificationController::class, 'index']);
        
        // Conteo de notificaciones no leídas
        Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
        
        // Marcar notificación como leída
        Route::post('/{notification}/mark-read', [NotificationController::class, 'markAsRead']);
        
        // Marcar todas como leídas
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
        
        // Registrar/desregistrar token de dispositivo
        Route::post('/register-device', [NotificationController::class, 'registerDevice']);
        Route::post('/unregister-device', [NotificationController::class, 'unregisterDevice']);
    });
});

// Ruta de prueba de la API
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API funcionando correctamente',
        'timestamp' => now()
    ]);
});
