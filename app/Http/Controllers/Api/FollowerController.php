<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function follow($id)
    {
        try {
            $authUser = Auth::user();
            $user = User::find($id);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
            }

            if ($user->id === $authUser->id) {
                return response()->json(['success' => false, 'message' => 'No puedes seguirte a ti mismo.'], 400);
            }

            if ($user->followers()->where('follower_id', $authUser->id)->exists()) {
                return response()->json(['success' => false, 'message' => 'Ya sigues a este usuario.'], 400);
            }

            $user->followers()->attach($authUser->id);
            $this->notificationService->createFollowNotification($authUser, $user);

            return response()->json([
                'success' => true,
                'message' => 'Ahora sigues a este usuario.',
                'action' => 'followed',
                'data' => [
                    'is_following' => true,
                    'followers_count' => $user->followers()->count(),
                    'following_count' => $authUser->following()->count()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al seguir al usuario.', 'error' => $e->getMessage()], 500);
        }
    }

    public function unfollow($id)
    {
        try {
            $authUser = Auth::user();
            $user = User::find($id);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
            }

            if ($user->id === $authUser->id) {
                return response()->json(['success' => false, 'message' => 'No puedes dejar de seguirte a ti mismo.'], 400);
            }

            if (!$user->followers()->where('follower_id', $authUser->id)->exists()) {
                return response()->json(['success' => false, 'message' => 'No sigues a este usuario.'], 400);
            }

            $user->followers()->detach($authUser->id);

            return response()->json([
                'success' => true,
                'message' => 'Dejaste de seguir a este usuario.',
                'action' => 'unfollowed',
                'data' => [
                    'is_following' => false,
                    'followers_count' => $user->followers()->count(),
                    'following_count' => $authUser->following()->count()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al dejar de seguir al usuario.', 'error' => $e->getMessage()], 500);
        }
    }

    public function toggle($id)
    {
        try {
            $authUser = Auth::user();
            $user = User::find($id);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
            }

            if ($user->id === $authUser->id) {
                return response()->json(['success' => false, 'message' => 'No puedes seguirte a ti mismo.'], 400);
            }

            $isFollowing = $user->followers()->where('follower_id', $authUser->id)->exists();

            if ($isFollowing) {
                $user->followers()->detach($authUser->id);
                $message = 'Dejaste de seguir a este usuario.';
                $action = 'unfollowed';
                $isFollowingNow = false;
            } else {
                $user->followers()->attach($authUser->id);
                $this->notificationService->createFollowNotification($authUser, $user);
                $message = 'Ahora sigues a este usuario.';
                $action = 'followed';
                $isFollowingNow = true;
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'action' => $action,
                    'is_following' => $isFollowingNow,
                    'followers_count' => $user->followers()->count(),
                    'following_count' => $authUser->following()->count()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al procesar la acción.', 'error' => $e->getMessage()], 500);
        }
    }

    public function check($id)
    {
        try {
            $authUser = Auth::user();
            $user = User::find($id);

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
            }

            $isFollowing = $user->followers()->where('follower_id', $authUser->id)->exists();

            return response()->json(['success' => true, 'is_following' => $isFollowing], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al verificar el estado de seguimiento.', 'error' => $e->getMessage()], 500);
        }
    }

    public function followers($id)
    {
        try {
            $user = User::findOrFail($id);

            $followers = $user->followers()
                ->select('users.id', 'users.name', 'users.username', 'users.imagen')
                ->get()
                ->map(function ($follower) {
                    $follower->imagen_url = $follower->imagen ? url('perfiles/' . $follower->imagen) : null;
                    return $follower;
                });

            return response()->json(['success' => true, 'count' => $followers->count(), 'followers' => $followers]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al obtener seguidores.', 'error' => $e->getMessage()], 500);
        }
    }

    public function following($id)
    {
        try {
            $user = User::findOrFail($id);

            $following = $user->following()
                ->select('users.id', 'users.name', 'users.username', 'users.imagen')
                ->get()
                ->map(function ($followed) {
                    $followed->imagen_url = $followed->imagen ? url('perfiles/' . $followed->imagen) : null;
                    return $followed;
                });

            return response()->json(['success' => true, 'count' => $following->count(), 'following' => $following]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al obtener usuarios seguidos.', 'error' => $e->getMessage()], 500);
        }
    }

    public function stats($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'followers_count' => $user->followers()->count(),
                    'following_count' => $user->following()->count()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al obtener estadísticas.', 'error' => $e->getMessage()], 500);
        }
    }
}
