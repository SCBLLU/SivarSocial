<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
            // No traigo campos sensibles como email, password, etc.
            $users = User::select(['id', 'name', 'username', 'imagen', 'profession', 'insignia'])
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
            $users = User::where('name', 'like', "%{$query}%")
                ->orWhere('username', 'like', "%{$query}%")
                ->select(['id', 'name', 'username', 'imagen', 'profession', 'insignia']) // Solo campos necesarios
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
}
