<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comentario;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Controlador de Comentarios para la API de SivarSocial
 * Maneja todas las operaciones CRUD de comentarios (crear, leer, eliminar)
 * Soporta comentarios anidados y respuestas
 */
class CommentController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Obtiene todos los comentarios de un post específico
     * Incluye relaciones con usuario y respuestas anidadas
     */
    public function index(Post $post)
    {
        try {
            // Cargo los comentarios con sus usuarios y respuestas
            // Solo traigo comentarios de nivel superior (sin parent_id)
            $comentarios = $post->comentarios()
                ->whereNull('parent_id')
                ->with(['user', 'children.user'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Transformo para agregar URLs de imágenes de perfil
            $comentarios->transform(function ($comentario) {
                return $this->transformComment($comentario);
            });

            return response()->json([
                'success' => true,
                'data' => $comentarios
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener comentarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crea un nuevo comentario en un post
     * Soporta comentarios de nivel superior y respuestas anidadas
     */
    public function store(Request $request, Post $post)
    {
        try {
            // Valido los datos del comentario
            $request->validate([
                'comentario' => 'required|string|max:500',
                'parent_id' => 'nullable|integer|exists:comentarios,id',
                'gif_url' => 'nullable|string|url|max:500'
            ]);

            $parentId = $request->input('parent_id', null);

            DB::beginTransaction();

            $depth = 0;

            // Si es una respuesta a otro comentario, valido y calculo la profundidad
            if ($parentId !== null) {
                // Bloqueo la fila padre para evitar race conditions
                $parent = Comentario::where('id', $parentId)->lockForUpdate()->first();

                if (!$parent) {
                    throw ValidationException::withMessages([
                        'parent_id' => 'El comentario padre no existe.'
                    ]);
                }

                // Verifico que el comentario padre pertenezca al mismo post
                if ($parent->post_id !== $post->id) {
                    throw ValidationException::withMessages([
                        'parent_id' => 'El comentario padre no pertenece a esta publicación.'
                    ]);
                }

                $depth = $parent->depth + 1;

                // Limito la profundidad máxima de anidamiento a 5 niveles
                if ($depth > 5) {
                    throw ValidationException::withMessages([
                        'parent_id' => 'Se ha alcanzado el límite máximo de respuestas anidadas.'
                    ]);
                }
            }

            // Creo el nuevo comentario
            $comentario = Comentario::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id,
                'comentario' => $request->comentario,
                'parent_id' => $parentId,
                'depth' => $depth,
                'reply_count' => 0,
                'gif_url' => $request->gif_url
            ]);

            // Si es una respuesta, incremento el contador del padre
            if ($parentId !== null) {
                Comentario::where('id', $parentId)->increment('reply_count');
            }

            DB::commit();

            // Creo notificación solo si no es el dueño del post quien comenta
            if ($post->user_id !== Auth::id()) {
                $this->notificationService->createCommentNotification(
                    Auth::user(),
                    $post,
                    $request->comentario
                );
            }

            // Cargo las relaciones para la respuesta
            $comentario->load(['user', 'children']);

            // Transformo el comentario para incluir URLs completas
            $comentario = $this->transformComment($comentario);

            return response()->json([
                'success' => true,
                'data' => $comentario,
                'message' => 'Comentario creado exitosamente'
            ], 201);
        } catch (ValidationException $ve) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $ve->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creando comentario via API: ' . $e->getMessage(), [
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'parent_id' => $parentId ?? null
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al crear comentario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene un comentario específico con todas sus respuestas
     */
    public function show(Comentario $comentario)
    {
        try {
            // Cargo el usuario y todas las respuestas con sus usuarios
            $comentario->load(['user', 'children.user', 'children.children.user']);

            // Transformo el comentario
            $comentario = $this->transformComment($comentario);

            return response()->json([
                'success' => true,
                'data' => $comentario
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener comentario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un comentario
     * Solo el propietario del comentario puede eliminarlo
     */
    public function destroy(Comentario $comentario)
    {
        try {
            // Verifico que el usuario autenticado sea el propietario
            if ($comentario->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para eliminar este comentario'
                ], 403);
            }

            DB::beginTransaction();

            // Si tiene un padre, decremento su contador de respuestas
            if ($comentario->parent_id) {
                Comentario::where('id', $comentario->parent_id)
                    ->decrement('reply_count');
            }

            // Elimino el comentario (las respuestas se manejan con cascade)
            $comentario->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Comentario eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error eliminando comentario via API: ' . $e->getMessage(), [
                'comentario_id' => $comentario->id,
                'user_id' => Auth::id()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar comentario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza un comentario existente
     * Solo el propietario puede editarlo
     */
    public function update(Request $request, Comentario $comentario)
    {
        try {
            // Verifico que el usuario autenticado sea el propietario
            if ($comentario->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para editar este comentario'
                ], 403);
            }

            // Valido los datos
            $request->validate([
                'comentario' => 'required|string|max:500',
                'gif_url' => 'nullable|string|url|max:500'
            ]);

            // Actualizo el comentario
            $comentario->update([
                'comentario' => $request->comentario,
                'gif_url' => $request->gif_url
            ]);

            // Cargo las relaciones
            $comentario->load(['user', 'children']);

            // Transformo el comentario
            $comentario = $this->transformComment($comentario);

            return response()->json([
                'success' => true,
                'data' => $comentario,
                'message' => 'Comentario actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error actualizando comentario via API: ' . $e->getMessage(), [
                'comentario_id' => $comentario->id,
                'user_id' => Auth::id()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar comentario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene las respuestas de un comentario específico
     */
    public function replies(Comentario $comentario)
    {
        try {
            // Cargo todas las respuestas del comentario
            $replies = $comentario->children()
                ->with(['user', 'children.user'])
                ->orderBy('created_at', 'asc')
                ->get();

            // Transformo cada respuesta
            $replies->transform(function ($reply) {
                return $this->transformComment($reply);
            });

            return response()->json([
                'success' => true,
                'data' => $replies
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener respuestas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Transforma un comentario agregando URLs completas de imágenes
     * Método privado helper para mantener consistencia
     */
    private function transformComment($comentario)
    {
        // Agrego la imagen de perfil del usuario del comentario
        if ($comentario->user && $comentario->user->imagen) {
            $comentario->user->imagen_url = url('perfiles/' . $comentario->user->imagen);
        }

        // Si tiene respuestas, las transformo recursivamente
        if ($comentario->children && $comentario->children->count() > 0) {
            $comentario->children->transform(function ($child) {
                return $this->transformComment($child);
            });
        }

        // Agrego el formato de tiempo compacto
        $comentario->time_ago = $comentario->compact_time ?? $this->getCompactTime($comentario->created_at);

        return $comentario;
    }

    /**
     * Calcula el tiempo transcurrido en formato compacto
     */
    private function getCompactTime($date)
    {
        $diff = $date->diffInSeconds(now());

        if ($diff < 60) {
            return 'ahora';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes == 1 ? '1 min' : $minutes . ' mins';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours == 1 ? '1 hora' : $hours . ' horas';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days == 1 ? '1 día' : $days . ' días';
        } else {
            return $date->format('d M Y');
        }
    }
}
