<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador de Usuarios para la API de SivarSocial
 * Maneja la obtención de usuarios, perfiles individuales y búsqueda de usuarios
 * Optimizado para la app móvil con paginación y URLs de imágenes completas
 */
class UserController extends Controller
{
    /**
     * Obtiene la lista paginada de todos los usuarios
     * Implementé select específico para optimizar la consulta y reducir datos transferidos
     * Perfecto para mostrar un directorio de usuarios en la app móvil
     */
    public function index()
    {
        try {
            // Selecciono solo los campos necesarios para mejorar el rendimiento
            // Incluyo universidad_id y carrera_id para cargar las relaciones
            $users = User::with(['universidad', 'carrera'])
                ->select(['id', 'name', 'username', 'imagen', 'insignia', 'universidad_id', 'carrera_id'])
                ->paginate(20); // Pagino de 20 en 20 para no sobrecargar la app

            // Transformo cada usuario para agregar la URL completa de la imagen de perfil  
            // Esto es esencial para que la app móvil pueda cargar las fotos correctamente
            $users->getCollection()->transform(function ($user) {
                // Solo agrego la URL si el usuario tiene imagen de perfil
                // Uso url() para generar URLs absolutas con el dominio completo
                if ($user->imagen) {
                    $user->imagen_url = url('perfiles/' . $user->imagen);
                }
                return $user;
            });

            // Devuelvo la lista paginada con formato JSON estándar para la API
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            // Capturo cualquier error y devuelvo respuesta estructurada para debugging
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene el perfil completo de un usuario específico
     * Incluye todos sus posts con interacciones y estadísticas del perfil
     * Perfecto para mostrar la pantalla de perfil de usuario en la app
     */
    public function show(User $user)
    {
        try {
            // Cargo las relaciones de universidad y carrera del usuario
            $user->load(['universidad', 'carrera']);

            // Cargo los posts del usuario con sus relaciones de comentarios y likes
            // Uso una función dentro del load para optimizar y ordenar los posts
            $user->load(['posts' => function ($query) {
                $query->with(['comentarios', 'likes'])->latest(); // Los posts más recientes primero
            }]);

            // Cargo las estadísticas del perfil: cantidad de posts, seguidores y siguiendo
            // Estas estadísticas son importantes para mostrar en el perfil del usuario
            $user->loadCount(['posts', 'followers', 'following']);

            // Agrego la URL completa de la imagen de perfil para la app móvil
            // Solo si el usuario tiene imagen configurada
            if ($user->imagen) {
                $user->imagen_url = url('perfiles/' . $user->imagen);
            }

            // Devuelvo el perfil completo del usuario
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            // Manejo cualquier error al obtener el perfil del usuario
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Busca usuarios por nombre o username
     * Implementé búsqueda flexible que funciona con coincidencias parciales
     * Limitado a 10 resultados para mantener la respuesta rápida en la app
     */
    public function search(Request $request)
    {
        try {
            // Obtengo el término de búsqueda desde el parámetro 'q' de la URL
            $query = $request->input('q');

            // Busco usuarios que coincidan en nombre O username (búsqueda flexible)
            // Uso LIKE con % para encontrar coincidencias parciales
            $users = User::with(['universidad', 'carrera'])
                ->where('name', 'like', "%{$query}%")
                ->orWhere('username', 'like', "%{$query}%")
                ->select(['id', 'name', 'username', 'imagen', 'insignia', 'universidad_id', 'carrera_id']) // Solo campos necesarios
                ->take(10) // Limito a 10 resultados para que la búsqueda sea rápida
                ->get();

            // Transformo cada resultado para agregar la URL completa de la imagen
            // Necesario para que la app móvil pueda mostrar las fotos de perfil en los resultados
            $users->transform(function ($user) {
                // Solo agrego la URL si el usuario tiene imagen de perfil
                if ($user->imagen) {
                    $user->imagen_url = url('perfiles/' . $user->imagen);
                }
                return $user;
            });

            // Devuelvo los resultados de búsqueda en formato JSON
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            // Capturo errores durante la búsqueda y devuelvo respuesta estructurada
            return response()->json([
                'success' => false,
                'message' => 'Error en búsqueda',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene todos los posts publicados por un usuario específico
     * Endpoint dedicado para el feed de posts de un usuario
     * Incluye paginación y todas las interacciones (likes, comentarios)
     */
    public function posts($userIdentifier)
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

            // Obtengo todos los posts del usuario con sus relaciones
            $posts = Post::where('user_id', $user->id)
                ->with(['user', 'comentarios.user', 'likes'])
                ->withCount(['comentarios', 'likes'])
                ->latest()
                ->paginate(20);

            // Transformo cada post para agregar URLs completas
            $posts->getCollection()->transform(function ($post) {
                // URL de imagen del post
                if ($post->imagen) {
                    $post->imagen_url = url('uploads/' . $post->imagen);
                }
                // URL de archivo del post
                if ($post->archivo) {
                    $post->archivo_url = url('files/' . $post->archivo);
                }
                // Imagen de perfil del usuario
                if ($post->user && $post->user->imagen) {
                    $post->user->setAttribute('imagen_url', url('perfiles/' . $post->user->imagen));
                }
                // Imágenes de usuarios en comentarios
                if ($post->comentarios) {
                    $post->comentarios->transform(function ($comentario) {
                        if ($comentario->user && $comentario->user->imagen) {
                            $comentario->user->setAttribute('imagen_url', url('perfiles/' . $comentario->user->imagen));
                        }
                        return $comentario;
                    });
                }
                return $post;
            });

            return response()->json([
                'success' => true,
                'data' => $posts,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'name' => $user->name
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener posts del usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene las estadísticas completas de un usuario
     * Incluye: posts totales, seguidores, siguiendo, likes recibidos
     * Endpoint optimizado para mostrar el perfil completo del usuario
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

            // Cargar contadores
            $user->loadCount(['posts', 'followers', 'following']);

            // Calcular total de likes recibidos en todos sus posts
            $totalLikes = $user->posts()->withCount('likes')->get()->sum('likes_count');

            // Calcular total de comentarios recibidos en todos sus posts
            $totalComments = $user->posts()->withCount('comentarios')->get()->sum('comentarios_count');

            // Verificar si el usuario autenticado sigue a este usuario
            $isFollowing = false;
            if (Auth::check() && Auth::id() !== $user->id) {
                $isFollowing = $user->followers()
                    ->where('follower_id', Auth::id())
                    ->exists();
            }

            // Agregar URL de imagen de perfil
            $profileImageUrl = null;
            if ($user->imagen) {
                $profileImageUrl = url('perfiles/' . $user->imagen);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'name' => $user->name,
                    'imagen' => $user->imagen,
                    'imagen_url' => $profileImageUrl,
                    'insignia' => $user->insignia,
                    'posts_count' => $user->posts_count,
                    'followers_count' => $user->followers_count,
                    'following_count' => $user->following_count,
                    'total_likes_received' => $totalLikes,
                    'total_comments_received' => $totalComments,
                    'is_following' => $isFollowing,
                    'universidad' => $user->universidad,
                    'carrera' => $user->carrera
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas del usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
