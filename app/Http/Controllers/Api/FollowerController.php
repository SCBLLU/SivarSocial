<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador de Seguidores para la API de SivarSocial
 * Maneja el sistema de follow/unfollow y listado de seguidores/siguiendo
 * Optimizado para la app móvil con respuestas JSON estructuradas
 */
class FollowerController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Seguir a un usuario
     * El usuario autenticado comienza a seguir al usuario especificado
     * Acepta tanto ID como username
     */
    public function follow($userIdentifier)
    {
        try {
            $authUser = Auth::user();

            // Buscar usuario por ID o username
            $user = is_numeric($userIdentifier)
                ? User::find($userIdentifier)
                : User::where('username', $userIdentifier)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado.'
                ], 404);
            }

            // Verificar que el usuario no se siga a sí mismo
            if ($user->id === $authUser->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes seguirte a ti mismo.'
                ], 400);
            }

            // Verificar que no ya lo esté siguiendo
            if ($user->followers()->where('follower_id', $authUser->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya sigues a este usuario.'
                ], 400);
            }

            // El usuario autenticado sigue al usuario recibido
            $user->followers()->attach($authUser->id);

            // Crear notificación
            $this->notificationService->createFollowNotification($authUser, $user);

            // Obtener contadores actualizados
            $followersCount = $user->followers()->count();
            $followingCount = $authUser->following()->count();

            return response()->json([
                'success' => true,
                'message' => 'Ahora sigues a este usuario.',
                'data' => [
                    'is_following' => true,
                    'followers_count' => $followersCount,
                    'following_count' => $followingCount
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al seguir al usuario.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dejar de seguir a un usuario
     * El usuario autenticado deja de seguir al usuario especificado
     * Acepta tanto ID como username
     */
    public function unfollow($userIdentifier)
    {
        try {
            $authUser = Auth::user();

            // Buscar usuario por ID o username
            $user = is_numeric($userIdentifier)
                ? User::find($userIdentifier)
                : User::where('username', $userIdentifier)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado.'
                ], 404);
            }

            // Verificar que el usuario no se dessiga a sí mismo
            if ($user->id === $authUser->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes dejar de seguirte a ti mismo.'
                ], 400);
            }

            // Verificar que lo esté siguiendo
            if (!$user->followers()->where('follower_id', $authUser->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No sigues a este usuario.'
                ], 400);
            }

            // El usuario autenticado deja de seguir al usuario recibido
            $user->followers()->detach($authUser->id);

            // Obtener contadores actualizados
            $followersCount = $user->followers()->count();
            $followingCount = $authUser->following()->count();

            return response()->json([
                'success' => true,
                'message' => 'Dejaste de seguir a este usuario.',
                'data' => [
                    'is_following' => false,
                    'followers_count' => $followersCount,
                    'following_count' => $followingCount
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al dejar de seguir al usuario.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle follow/unfollow (seguir o dejar de seguir en una sola acción)
     * Si ya sigue al usuario, lo deja de seguir. Si no lo sigue, comienza a seguirlo.
     * Acepta tanto ID como username
     */
    public function toggle($userIdentifier)
    {
        try {
            $authUser = Auth::user();

            // Buscar usuario por ID o username
            $user = is_numeric($userIdentifier)
                ? User::find($userIdentifier)
                : User::where('username', $userIdentifier)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado.'
                ], 404);
            }

            // Verificar que el usuario no se siga a sí mismo
            if ($user->id === $authUser->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes seguirte a ti mismo.'
                ], 400);
            }

            // Verificar si ya lo está siguiendo
            $isFollowing = $user->followers()->where('follower_id', $authUser->id)->exists();

            if ($isFollowing) {
                // Dejar de seguir
                $user->followers()->detach($authUser->id);
                $message = 'Dejaste de seguir a este usuario.';
                $action = 'unfollowed';
                $isFollowingNow = false;
            } else {
                // Seguir
                $user->followers()->attach($authUser->id);

                // Crear notificación
                $this->notificationService->createFollowNotification($authUser, $user);

                $message = 'Ahora sigues a este usuario.';
                $action = 'followed';
                $isFollowingNow = true;
            }

            // Obtener contadores actualizados
            $followersCount = $user->followers()->count();
            $followingCount = $authUser->following()->count();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'action' => $action,
                    'is_following' => $isFollowingNow,
                    'followers_count' => $followersCount,
                    'following_count' => $followingCount
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la acción.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar si el usuario autenticado sigue a un usuario específico
     * Acepta tanto ID como username
     */
    public function check($userIdentifier)
    {
        try {
            $authUser = Auth::user();

            // Buscar usuario por ID o username
            $user = is_numeric($userIdentifier)
                ? User::find($userIdentifier)
                : User::where('username', $userIdentifier)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado.'
                ], 404);
            }

            $isFollowing = $user->followers()->where('follower_id', $authUser->id)->exists();
            return response()->json([
                'success' => true,
                'data' => [
                    'is_following' => $isFollowing,
                    'user_id' => $user->id,
                    'username' => $user->username
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar el estado de seguimiento.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener la lista de seguidores de un usuario
     * Incluye información básica de cada seguidor para mostrar en la app
     * Acepta tanto ID como username
     */
    public function followers($userIdentifier)
    {
        try {
            // Buscar usuario por ID o username
            $user = is_numeric($userIdentifier)
                ? User::find($userIdentifier)
                : User::where('username', $userIdentifier)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado.'
                ], 404);
            }

            // Cargo los seguidores con información básica y paginación
            $followers = $user->followers()
                ->select(['users.id', 'users.name', 'users.username', 'users.imagen', 'users.insignia'])
                ->paginate(20);

            // Transformo cada seguidor para agregar la URL completa de la imagen
            $followers->getCollection()->transform(function ($follower) {
                if ($follower->imagen) {
                    $follower->imagen_url = url('perfiles/' . $follower->imagen);
                }

                // Verificar si el usuario autenticado sigue a este usuario
                if (Auth::check()) {
                    $follower->is_following = $follower->followers()
                        ->where('follower_id', Auth::id())
                        ->exists();
                }

                return $follower;
            });

            return response()->json([
                'success' => true,
                'data' => $followers
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener seguidores.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener la lista de usuarios que sigue un usuario
     * Incluye información básica de cada usuario seguido para mostrar en la app
     * Acepta tanto ID como username
     */
    public function following($userIdentifier)
    {
        try {
            // Buscar usuario por ID o username
            $user = is_numeric($userIdentifier)
                ? User::find($userIdentifier)
                : User::where('username', $userIdentifier)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado.'
                ], 404);
            }

            // Cargo los usuarios que sigue con información básica y paginación
            $following = $user->following()
                ->select(['users.id', 'users.name', 'users.username', 'users.imagen', 'users.insignia'])
                ->paginate(20);

            // Transformo cada usuario para agregar la URL completa de la imagen
            $following->getCollection()->transform(function ($followedUser) {
                if ($followedUser->imagen) {
                    $followedUser->imagen_url = url('perfiles/' . $followedUser->imagen);
                }

                // Verificar si el usuario autenticado sigue a este usuario
                if (Auth::check()) {
                    $followedUser->is_following = $followedUser->followers()
                        ->where('follower_id', Auth::id())
                        ->exists();
                }

                return $followedUser;
            });

            return response()->json([
                'success' => true,
                'data' => $following
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios seguidos.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener las estadísticas de seguimiento de un usuario
     * Devuelve el conteo de seguidores y siguiendo
     * Acepta tanto ID como username
     */
    public function stats($userIdentifier)
    {
        try {
            // Buscar usuario por ID o username
            $user = is_numeric($userIdentifier)
                ? User::find($userIdentifier)
                : User::where('username', $userIdentifier)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado.'
                ], 404);
            }

            $followersCount = $user->followers()->count();
            $followingCount = $user->following()->count();

            // Verificar si el usuario autenticado sigue a este usuario
            $isFollowing = false;
            if (Auth::check()) {
                $isFollowing = $user->followers()
                    ->where('follower_id', Auth::id())
                    ->exists();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'followers_count' => $followersCount,
                    'following_count' => $followingCount,
                    'is_following' => $isFollowing
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
