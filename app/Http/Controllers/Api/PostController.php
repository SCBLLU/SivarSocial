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
                // Solo agrego la URL si el post tiene un archivo
                if ($post->archivo) {
                    $post->archivo_url = url('files/' . $post->archivo);
                }
                // Agrego también la imagen de perfil del usuario que creó el post
                if ($post->user && $post->user->imagen) {
                    $post->user->setAttribute('imagen_url', url('perfiles/' . $post->user->imagen));
                }
                // Agrego las imágenes de perfil de los usuarios en comentarios
                if ($post->comentarios) {
                    $post->comentarios->transform(function ($comentario) {
                        if ($comentario->user && $comentario->user->imagen) {
                            $comentario->user->setAttribute('imagen_url', url('perfiles/' . $comentario->user->imagen));
                        }
                        return $comentario;
                    });
                }
                // Agrego las imágenes de perfil de los usuarios que dieron like
                if ($post->likes) {
                    $post->likes->transform(function ($like) {
                        if ($like->user && $like->user->imagen) {
                            $like->user->setAttribute('imagen_url', url('perfiles/' . $like->user->imagen));
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
                'descripcion' => 'nullable|string|max:500',
                'tipo' => 'required|in:imagen,musica,texto,archivo',
                'imagen' => 'required_if:tipo,imagen|image|max:20480', // 20MB
                'archivo' => 'nullable|mimes:pdf,doc,docx,zip|max:5120', // 5MB

                // Campos legacy (compatibilidad con versiones anteriores del frontend móvil)
                'artista' => 'nullable|string|max:255',
                'titulo' => 'nullable|string|max:255',
                'album' => 'nullable|string|max:255',
                'texto' => 'nullable|string|max:5000',

                // Campos de música de iTunes (NUEVOS - los que realmente usa el frontend web)
                'music_source' => 'nullable|string|max:50',
                'itunes_track_id' => 'nullable|string|max:100',
                'itunes_track_name' => 'nullable|string|max:255',
                'itunes_artist_name' => 'nullable|string|max:255',
                'itunes_collection_name' => 'nullable|string|max:255',
                'itunes_artwork_url' => 'nullable|string|max:500',
                'itunes_preview_url' => 'nullable|string|max:500',
                'itunes_track_view_url' => 'nullable|string|max:500',
                'itunes_track_time_millis' => 'nullable|integer',
                'itunes_country' => 'nullable|string|max:10',
                'itunes_primary_genre_name' => 'nullable|string|max:100',
                'apple_music_url' => 'nullable|string|max:500',
                'spotify_web_url' => 'nullable|string|max:500',
                'artist_search_term' => 'nullable|string|max:255',
                'track_search_term' => 'nullable|string|max:255',

                // Campos específicos de música del frontend móvil (legacy)
                'musica_url' => 'nullable|string|max:500',
                'musica_imagen' => 'nullable|string|max:500',
                'musica_artista' => 'nullable|string|max:255',
                'musica_titulo' => 'nullable|string|max:255',
                'musica_album' => 'nullable|string|max:255',
                'musica_duracion' => 'nullable|string|max:20',
                'musica_genero' => 'nullable|string|max:100',
                'itunes_url' => 'nullable|string|max:500',
            ]);

            // Creo el nuevo post y asigno los datos básicos
            $post = new Post();
            $post->user_id = Auth::id();
            $post->tipo = $request->tipo;

            // Descripción opcional
            if ($request->filled('descripcion')) {
                $post->descripcion = $request->descripcion;
            }

            // Si el post incluye una imagen, la proceso y guardo
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                $imagen->move(public_path('uploads'), $nombreImagen);
                $post->imagen = $nombreImagen;
            }

            // Si el post incluye un archivo, lo proceso y guardo
            if ($request->hasFile('archivo')) {
                $file = $request->file('archivo');
                $nombreArchivo = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('files'), $nombreArchivo);
                $post->archivo = $nombreArchivo;
            }

            // Campos específicos por tipo de post
            if ($request->tipo === 'musica') {
                // Campos de iTunes (los que realmente existen en la BD y envía el frontend)
                if ($request->filled('music_source')) $post->music_source = $request->music_source;
                if ($request->filled('itunes_track_id')) $post->itunes_track_id = $request->itunes_track_id;
                if ($request->filled('itunes_track_name')) $post->itunes_track_name = $request->itunes_track_name;
                if ($request->filled('itunes_artist_name')) $post->itunes_artist_name = $request->itunes_artist_name;
                if ($request->filled('itunes_collection_name')) $post->itunes_collection_name = $request->itunes_collection_name;
                if ($request->filled('itunes_artwork_url')) $post->itunes_artwork_url = $request->itunes_artwork_url;
                if ($request->filled('itunes_preview_url')) $post->itunes_preview_url = $request->itunes_preview_url;
                if ($request->filled('itunes_track_view_url')) $post->itunes_track_view_url = $request->itunes_track_view_url;
                if ($request->filled('itunes_track_time_millis')) $post->itunes_track_time_millis = $request->itunes_track_time_millis;
                if ($request->filled('itunes_country')) $post->itunes_country = $request->itunes_country;
                if ($request->filled('itunes_primary_genre_name')) $post->itunes_primary_genre_name = $request->itunes_primary_genre_name;

                // Campos de enlaces cruzados
                if ($request->filled('apple_music_url')) $post->apple_music_url = $request->apple_music_url;
                if ($request->filled('spotify_web_url')) $post->spotify_web_url = $request->spotify_web_url;
                if ($request->filled('artist_search_term')) $post->artist_search_term = $request->artist_search_term;
                if ($request->filled('track_search_term')) $post->track_search_term = $request->track_search_term;

                // Campos legacy (compatibilidad con versiones anteriores - campos musica_* del frontend móvil)
                // Solo se usan si no hay campos de iTunes específicos
                if ($request->filled('musica_titulo') && !$request->filled('itunes_track_name')) {
                    $post->itunes_track_name = $request->musica_titulo;
                }
                if ($request->filled('musica_artista') && !$request->filled('itunes_artist_name')) {
                    $post->itunes_artist_name = $request->musica_artista;
                }
                if ($request->filled('musica_album') && !$request->filled('itunes_collection_name')) {
                    $post->itunes_collection_name = $request->musica_album;
                }
                if ($request->filled('musica_imagen') && !$request->filled('itunes_artwork_url')) {
                    $post->itunes_artwork_url = $request->musica_imagen;
                }
                if ($request->filled('musica_url') && !$request->filled('itunes_preview_url')) {
                    $post->itunes_preview_url = $request->musica_url;
                }
                if ($request->filled('itunes_url') && !$request->filled('itunes_track_view_url')) {
                    $post->itunes_track_view_url = $request->itunes_url;
                }
                if ($request->filled('musica_duracion') && !$request->filled('itunes_track_time_millis')) {
                    // Convertir duración de string (3:45) a milisegundos si es necesario
                    $duracion = $request->musica_duracion;
                    if (is_numeric($duracion)) {
                        $post->itunes_track_time_millis = $duracion;
                    }
                }
                if ($request->filled('musica_genero') && !$request->filled('itunes_primary_genre_name')) {
                    $post->itunes_primary_genre_name = $request->musica_genero;
                }

                // Título personalizado del usuario (opcional para posts de música)
                if ($request->filled('titulo')) {
                    $post->titulo = $request->titulo;
                }
            } elseif ($request->tipo === 'imagen') {
                // Para posts de imagen, solo el título es relevante
                if ($request->filled('titulo')) $post->titulo = $request->titulo;
            } elseif ($request->tipo === 'texto') {
                // Para posts de texto, guardo el contenido
                if ($request->filled('texto')) $post->texto = $request->texto;
                if ($request->filled('titulo')) $post->titulo = $request->titulo;
            } elseif ($request->tipo === 'archivo') {
                // Para posts de archivo, guardo el título
                if ($request->filled('titulo')) $post->titulo = $request->titulo;
            }

            // Guardo el post en la base de datos
            $post->save();

            // Cargo las relaciones para devolver un objeto completo
            $post->load(['user', 'comentarios', 'likes']);
            $post->loadCount(['comentarios', 'likes']);

            // Agrego URLs completas para la app móvil
            if ($post->imagen) {
                $post->imagen_url = url('uploads/' . $post->imagen);
            }

            if ($post->archivo) {
                $post->archivo_url = url('files/' . $post->archivo);
            }

            if ($post->user && $post->user->imagen) {
                $post->user->setAttribute('imagen_url', url('perfiles/' . $post->user->imagen));
            }

            // Devuelvo el post creado con código 201 (Created)
            return response()->json([
                'success' => true,
                'data' => $post,
                'message' => 'Post creado exitosamente'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Errores de validación
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Cualquier otro error
            return response()->json([
                'success' => false,
                'message' => 'Error al crear post',
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
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

            // Solo agrego la URL si el post tiene un archivo
            if ($post->archivo) {
                $post->archivo_url = url('files/' . $post->archivo);
            }

            // Agrego la imagen de perfil del usuario del post
            if ($post->user && $post->user->imagen) {
                $post->user->setAttribute('imagen_url', url('perfiles/' . $post->user->imagen));
            }

            // Agrego las imágenes de perfil de los usuarios en comentarios
            if ($post->comentarios) {
                $post->comentarios->transform(function ($comentario) {
                    if ($comentario->user && $comentario->user->imagen) {
                        $comentario->user->setAttribute('imagen_url', url('perfiles/' . $comentario->user->imagen));
                    }
                    return $comentario;
                });
            }

            // Agrego las imágenes de perfil de los usuarios que dieron like
            if ($post->likes) {
                $post->likes->transform(function ($like) {
                    if ($like->user && $like->user->imagen) {
                        $like->user->setAttribute('imagen_url', url('perfiles/' . $like->user->imagen));
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
