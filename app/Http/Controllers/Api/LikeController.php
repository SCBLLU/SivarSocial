<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Like;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Controlador de Likes para la API de SivarSocial
 * Maneja las operaciones de dar like y quitar like a posts
 */
class LikeController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Da like o quita like a un post (toggle)
     * Si el usuario ya dio like, lo elimina. Si no, lo crea.
     */
    public function toggle(Post $post)
    {
        try {
            $userId = Auth::id();

            // Verifico si el usuario ya dio like
            $existingLike = Like::where('post_id', $post->id)
                ->where('user_id', $userId)
                ->first();

            if ($existingLike) {
                // Si ya existe, lo elimino (quitar like)
                $existingLike->delete();

                return response()->json([
                    'success' => true,
                    'liked' => false,
                    'likes_count' => $post->likes()->count(),
                    'message' => 'Like eliminado'
                ]);
            } else {
                // Si no existe, lo creo (dar like)
                $like = Like::create([
                    'user_id' => $userId,
                    'post_id' => $post->id
                ]);

                // Creo notificación solo si no es el dueño del post
                if ($post->user_id !== $userId) {
                    $this->notificationService->createLikeNotification(
                        Auth::user(),
                        $post
                    );
                }

                return response()->json([
                    'success' => true,
                    'liked' => true,
                    'likes_count' => $post->likes()->count(),
                    'message' => 'Like agregado'
                ], 201);
            }
        } catch (\Exception $e) {
            Log::error('Error al dar/quitar like via API: ' . $e->getMessage(), [
                'post_id' => $post->id,
                'user_id' => Auth::id()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar like',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene la lista de usuarios que dieron like a un post
     */
    public function index(Post $post)
    {
        try {
            // Obtengo los likes con información de usuarios
            $likes = $post->likes()
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            // Transformo para agregar URLs de imágenes de perfil
            $likes->transform(function ($like) {
                if ($like->user && $like->user->imagen) {
                    $like->user->imagen_url = url('perfiles/' . $like->user->imagen);
                }
                return $like;
            });

            return response()->json([
                'success' => true,
                'data' => $likes,
                'count' => $likes->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener likes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verifica si el usuario autenticado dio like a un post
     */
    public function check(Post $post)
    {
        try {
            $liked = Like::where('post_id', $post->id)
                ->where('user_id', Auth::id())
                ->exists();

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $post->likes()->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar like',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
