

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
use App\Http\Controllers\RecoverController;
use App\Http\Controllers\ColaboradoresController;

// ============================================================================
// RUTA PRINCIPAL - PÁGINA DE INICIO
// ============================================================================

/**
 * Página principal de la aplicación
 * Muestra el feed principal con posts de usuarios
 */
Route::get('/', HomeController::class)->name('home');

// ============================================================================
// SISTEMA DE AUTENTICACIÓN
// ============================================================================

/**
 * REGISTRO DE USUARIOS
 * Formulario de registro y procesamiento de nuevos usuarios
 */
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/register/validate-step1', [RegisterController::class, 'validateStep1']);

/**
 * INICIO DE SESIÓN
 * Formulario de login y autenticación de usuarios
 */
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

/**
 * CIERRE DE SESIÓN
 * Termina la sesión del usuario autenticado
 */
Route::post('/logout', [LogoutController::class, 'store'])->name('logout');

// ============================================================================
// SISTEMA DE RECUPERACIÓN DE CONTRASEÑA
// ============================================================================

/**
 * SOLICITUD DE RECUPERACIÓN
 * Formulario para solicitar código de recuperación por email
 */
Route::get('/recuperar', [RecoverController::class, 'index'])->name('recuperar');
Route::post('/recuperar', [RecoverController::class, 'enviarCodigo'])->name('recuperar.enviar');

/**
 * VERIFICACIÓN DEL CÓDIGO
 * Formulario para ingresar el código de verificación enviado por email
 */
Route::get('/code-verific', [RecoverController::class, 'index2'])->name('code.verific');
Route::post('/code-verific', [RecoverController::class, 'validarCodigo'])->name('code.verification');

/**
 * RESTABLECIMIENTO DE CONTRASEÑA
 * Formulario para establecer nueva contraseña tras verificación exitosa
 */
Route::get('/restablecer', [RecoverController::class, 'index3'])->name('restablecer');
Route::post('/restablecer', [RecoverController::class, 'restablecer'])->name('restablecer.verification');

// ============================================================================
// GESTIÓN DE PERFILES Y USUARIOS
// ============================================================================

/**
 * EDICIÓN DE PERFIL
 * Permite al usuario modificar su información personal y configuración
 */
Route::get('/editar-perfil', [PerfilController::class, 'index'])->name('perfil.index')->middleware('auth');
Route::post('/editar-perfil', [PerfilController::class, 'store'])->name('perfil.store')->middleware('auth');

/**
 * ENLACES SOCIALES
 * Gestión de enlaces sociales del usuario
 */
Route::post('/social-links', [App\Http\Controllers\SocialLinksController::class, 'store'])->name('social-links.store')->middleware('auth');
Route::delete('/social-links/{id}', [App\Http\Controllers\SocialLinksController::class, 'destroy'])->name('social-links.destroy')->middleware('auth');
Route::patch('/social-links/{id}/move-up', [App\Http\Controllers\SocialLinksController::class, 'moveUp'])->name('social-links.move-up')->middleware('auth');
Route::patch('/social-links/{id}/move-down', [App\Http\Controllers\SocialLinksController::class, 'moveDown'])->name('social-links.move-down')->middleware('auth');

/**
 * BÚSQUEDA DE USUARIOS
 * Sistema de búsqueda para encontrar otros usuarios de la plataforma
 */
Route::get('/buscar-usuarios', [UserController::class, 'buscar'])->name('usuarios.buscar');

/**
 * ELIMINACIÓN DE CUENTA
 * Permite al usuario eliminar permanentemente su cuenta
 */
Route::delete('/user', [UserController::class, 'destroy'])->name('user.destroy');

// ============================================================================
// SECCIÓN DE COLABORADORES
// ============================================================================

/**
 * PÁGINA DE COLABORADORES
 * Muestra la lista de usuarios con badge de colaborador
 */
Route::get('/colaboradores', [ColaboradoresController::class, 'index'])->name('colaboradores.index');

// ============================================================================
// SISTEMA DE POSTS (PUBLICACIONES)
// ============================================================================

/**
 * CREACIÓN DE POSTS
 * Formulario para crear nuevas publicaciones
 */
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create')->middleware('auth');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store')->middleware('auth');

/**
 * EDICIÓN DE POSTS
 * Formulario para editar publicaciones existentes
 */
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit')->middleware('auth');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update')->middleware('auth');

/**
 * VISUALIZACIÓN Y GESTIÓN DE POSTS
 * Ver post individual y eliminar posts propios
 */
Route::get('/{user:username}/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy')->middleware('auth');

/**
 * PERFIL DE USUARIO
 * Muestra todos los posts de un usuario específico
 */
Route::get('/{user:username}', [PostController::class, 'index'])->name('posts.index');

// ============================================================================
// SISTEMA DE LIKES
// ============================================================================

/**
 * GESTIÓN DE LIKES
 * Dar like y quitar like a publicaciones
 */
Route::post('/posts/{post}/likes', [LikeController::class, 'store'])->name('posts.likes.store')->middleware('auth');
Route::delete('/posts/{post}/likes', [LikeController::class, 'destroy'])->name('posts.likes.destroy')->middleware('auth');

/**
 * OBTENER LIKES VIA AJAX
 * Para cargar dinámicamente la lista de usuarios que dieron like
 */
Route::get('/posts/{post}/likes', [PostController::class, 'getLikes'])->name('posts.likes.get');

// ============================================================================
// SISTEMA DE COMENTARIOS
// ============================================================================

/**
 * AGREGAR COMENTARIOS
 * Permite comentar en las publicaciones de otros usuarios
 */
Route::post('/{user:username}/posts/{post}', [ComentarioController::class, 'store'])->name('comentarios.store')->middleware('auth');

// ============================================================================
// GESTIÓN DE IMÁGENES
// ============================================================================

/**
 * SUBIDA DE IMÁGENES
 * Para posts y contenido multimedia
 */
Route::post('/imagenes', [ImagenController::class, 'store'])->name('imagenes.store');

/**
 * IMÁGENES DE PERFIL
 * Subida y actualización de foto de perfil de usuario
 */
Route::post('/imagenes-perfil', [ImagenController::class, 'storePerfil'])->name('imagenes.perfil.store');

/**
 * ELIMINACIÓN DE IMÁGENES
 * Remover imágenes del servidor y base de datos
 */
Route::delete('/imagenes', [ImagenController::class, 'destroy'])->name('imagenes.destroy');

// ============================================================================
// SISTEMA DE SEGUIMIENTO (FOLLOW/UNFOLLOW)
// ============================================================================

/**
 * SEGUIR/DEJAR DE SEGUIR POR USERNAME
 * Sistema básico de seguimiento usando el nombre de usuario
 */
Route::post('/{user:username}/follow', [FollowerController::class, 'store'])->name('users.follow')->middleware('auth');
Route::delete('/{user:username}/unfollow', [FollowerController::class, 'destroy'])->name('users.unfollow')->middleware('auth');

/**
 * LISTAS DE SEGUIDORES Y SEGUIDOS
 * Muestra la lista de usuarios que siguen o que son seguidos por un usuario específico
 */
Route::get('/{user:username}/followers', [FollowerController::class, 'followers'])->name('users.followers');
Route::get('/{user:username}/following', [FollowerController::class, 'following'])->name('users.following');

/**
 * SEGUIR/DEJAR DE SEGUIR POR ID
 * Para funcionalidades AJAX y llamadas asíncronas
 */
Route::post('/users/{user}/follow', [FollowerController::class, 'storeById'])->name('users.follow.id')->middleware('auth');
Route::post('/users/{user}/unfollow', [FollowerController::class, 'destroyById'])->name('users.unfollow.id')->middleware('auth');

// ============================================================================
// INTEGRACIÓN CON APIs MUSICALES
// ============================================================================

/**
 * API DE SPOTIFY
 * Búsqueda de música y obtención de información de tracks
 */
Route::get('/spotify/search', [SpotifyApiController::class, 'search'])->name('spotify.search');
Route::get('/spotify/track', [SpotifyApiController::class, 'getTrack'])->name('spotify.track');

/**
 * API DE ITUNES
 * Búsqueda de música, tracks, géneros y contenido popular
 */
Route::get('/itunes/search', [iTunesApiController::class, 'search'])->name('itunes.search');
Route::get('/itunes/track', [iTunesApiController::class, 'getTrack'])->name('itunes.track');
Route::get('/itunes/genre', [iTunesApiController::class, 'searchByGenre'])->name('itunes.genre');
Route::get('/itunes/popular', [iTunesApiController::class, 'getPopular'])->name('itunes.popular');
Route::get('/itunes/more', [iTunesApiController::class, 'getMoreResults'])->name('itunes.more');

// ============================================================================
// CHATIFY - CUSTOM ROUTES OVERRIDE
// ============================================================================

use App\Http\Controllers\ChatifyOverrideController;

/**
 * Custom Chatify endpoints to preserve unread counts and chat order
 * These routes override the default Chatify behavior
 */
Route::middleware(['web', 'auth'])->prefix('chatify')->group(function () {
    Route::post('/getContactsCustom', [ChatifyOverrideController::class, 'getContacts'])->name('chatify.contacts.get.custom');
    Route::get('/getContactsCustom', [ChatifyOverrideController::class, 'getContacts'])->name('chatify.contacts.get.custom.fallback');
    Route::post('/updateContacts', [ChatifyOverrideController::class, 'updateContactItem'])->name('chatify.contacts.update');
    Route::post('/mutualContactsList', [ChatifyOverrideController::class, 'mutualContactsList'])->name('chatify.mutual.contacts');
    Route::post('/idInfo', [ChatifyOverrideController::class, 'idInfo'])->name('chatify.idinfo');
});
