<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador de Posts para la API de SivarSocial
 * Maneja todas las operaciones CRUD de posts (crear, leer, actualizar, eliminar)
 * Soporta posts de imagen y música con sus respectivos metadatos
 */
class PostController extends Controller
{
    /**
     * Obtiene todos los posts para el feed de la API
     * Implementé paginación de 20 posts por página para mejorar el rendimiento
     * Incluye relaciones con usuario, comentarios y likes para evitar consultas adicionales
     */
    public function index()
    {
        try {
            // Traigo los posts con todas sus relaciones necesarias para mostrar en el feed
            // with() carga las relaciones, withCount() cuenta comentarios y likes
            $posts = Post::with(['user', 'comentarios.user', 'likes'])
                ->withCount(['comentarios', 'likes'])
                ->latest() // Los ordeno del más reciente al más antiguo
                ->paginate(20); // Pagino de 20 en 20 para no sobrecargar la app móvil

            // Transformo cada post para agregar la URL completa de la imagen
            // Esto es necesario para que la app móvil pueda cargar las imágenes correctamente
            $posts->getCollection()->transform(function ($post) {
                // Solo agrego la URL si el post tiene imagen
                if ($post->imagen) {
                    $post->imagen_url = url('uploads/' . $post->imagen);
                }
                // Agrego también la imagen de perfil del usuario que creó el post
                if ($post->user && $post->user->imagen) {
                    $post->user->imagen_url = url('perfiles/' . $post->user->imagen);
                }
                // Agrego las imágenes de perfil de los usuarios en comentarios
                if ($post->comentarios) {
                    $post->comentarios->transform(function ($comentario) {
                        if ($comentario->user && $comentario->user->imagen) {
                            $comentario->user->imagen_url = url('perfiles/' . $comentario->user->imagen);
                        }
                        return $comentario;
                    });
                }
                // Agrego las imágenes de perfil de los usuarios que dieron like
                if ($post->likes) {
                    $post->likes->transform(function ($like) {
                        if ($like->user && $like->user->imagen) {
                            $like->user->imagen_url = url('perfiles/' . $like->user->imagen);
                        }
                        return $like;
                    });
                }
                return $post;
            });

            // Devuelvo respuesta JSON exitosa con los posts paginados
            return response()->json([
                'success' => true,
                'data' => $posts
            ]);
        } catch (\Exception $e) {
            // Si algo sale mal, devuelvo un error 500 con detalles para debugging
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crea un nuevo post desde la API móvil
     * Manejo tanto posts de imagen como de música con validaciones específicas
     * El usuario se obtiene automáticamente del token de autenticación
     */
    public function store(Request $request)
    {
        try {
            // Valido todos los campos según el tipo de post que quiere crear
            $request->validate([
                'descripcion' => 'nullable|string|max:500', // La descripción es opcional
                'tipo' => 'required|in:imagen,musica', // Solo acepto estos dos tipos
                'imagen' => 'required_if:tipo,imagen|image|max:20480', // 20MB max para imágenes
                // Campos específicos para posts de música
                'artista' => 'nullable|string|max:255',
                'titulo' => 'nullable|string|max:255',
                'album' => 'nullable|string|max:255',
            ]);

            // Creo el nuevo post y asigno los datos básicos
            $post = new Post();
            $post->user_id = Auth::id(); // Obtengo el ID del usuario autenticado por token
            $post->descripcion = $request->descripcion;
            $post->tipo = $request->tipo;

            // Si el post incluye una imagen, la proceso y guardo
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                // Genero un nombre único usando timestamp para evitar conflictos
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                // Muevo la imagen a la carpeta public/uploads
                $imagen->move(public_path('uploads'), $nombreImagen);
                $post->imagen = $nombreImagen;
                $post->imagen_url = url('uploads/' . $nombreImagen); // Genero URL absoluta
            }

            // Si es un post de música, guardo los metadatos adicionales
            if ($request->tipo === 'musica') {
                $post->artista = $request->artista;
                $post->titulo = $request->titulo;
                $post->album = $request->album;
            }

            // Guardo el post en la base de datos
            $post->save();

            // Cargo las relaciones para devolver un objeto completo en la respuesta
            $post->load(['user', 'comentarios', 'likes']);
            $post->loadCount(['comentarios', 'likes']);

            // Agrego la URL completa de la imagen del post para la app móvil
            if ($post->imagen) {
                $post->imagen_url = url('uploads/' . $post->imagen);
            }

            // Agrego también la imagen de perfil del usuario
            if ($post->user && $post->user->imagen) {
                $post->user->imagen_url = url('perfiles/' . $post->user->imagen);
            }

            // Devuelvo el post creado con código 201 (Created)
            return response()->json([
                'success' => true,
                'data' => $post,
                'message' => 'Post creado exitosamente'
            ], 201);
        } catch (\Exception $e) {
            // Si hay algún error, lo capturo y devuelvo detalles para debugging
            return response()->json([
                'success' => false,
                'message' => 'Error al crear post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene un post específico con todos sus detalles
     * Útil para mostrar la vista detallada de un post individual
     * Incluye todos los comentarios y likes con sus respectivos usuarios
     */
    public function show(Post $post)
    {
        try {
            // Cargo todas las relaciones necesarias para mostrar el post completo
            // Incluyo los usuarios de comentarios y likes para mostrar quién interactuó
            $post->load(['user', 'comentarios.user', 'likes.user']);
            $post->loadCount(['comentarios', 'likes']); // Cuento las interacciones

            // Si el post tiene imagen, agrego la URL completa con dominio
            if ($post->imagen) {
                $post->imagen_url = url('uploads/' . $post->imagen);
            }

            // Agrego la imagen de perfil del usuario del post
            if ($post->user && $post->user->imagen) {
                $post->user->imagen_url = url('perfiles/' . $post->user->imagen);
            }

            // Agrego las imágenes de perfil de los usuarios en comentarios
            if ($post->comentarios) {
                $post->comentarios->transform(function ($comentario) {
                    if ($comentario->user && $comentario->user->imagen) {
                        $comentario->user->imagen_url = url('perfiles/' . $comentario->user->imagen);
                    }
                    return $comentario;
                });
            }

            // Agrego las imágenes de perfil de los usuarios que dieron like
            if ($post->likes) {
                $post->likes->transform(function ($like) {
                    if ($like->user && $like->user->imagen) {
                        $like->user->imagen_url = url('perfiles/' . $like->user->imagen);
                    }
                    return $like;
                });
            }

            // Devuelvo el post con todos sus detalles
            return response()->json([
                'success' => true,
                'data' => $post
            ]);
        } catch (\Exception $e) {
            // Manejo cualquier error que pueda ocurrir al obtener el post
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un post específico
     * Solo el usuario que creó el post puede eliminarlo (verificación de autorización)
     * Implementé esta validación por seguridad para evitar que eliminen posts ajenos
     */
    public function destroy(Post $post)
    {
        try {
            // Verifico que el usuario autenticado sea el dueño del post
            // Esta es una validación crítica de seguridad
            if ($post->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 403); // Código 403: Forbidden
            }

            // Si es el dueño, procedo a eliminar el post
            $post->delete();

            // Devuelvo confirmación de eliminación exitosa
            return response()->json([
                'success' => true,
                'message' => 'Post eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            // Capturo cualquier error durante la eliminación
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar post',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
