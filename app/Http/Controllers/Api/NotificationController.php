<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador de Notificaciones para la API de SivarSocial
 * Maneja todas las operaciones relacionadas con notificaciones
 * (likes, comentarios, nuevos seguidores, etc.)
 */
class NotificationController extends Controller
{
    /**
     * Obtener todas las notificaciones del usuario autenticado
     * Devuelve notificaciones ordenadas por fecha (más recientes primero)
     * Incluye información del usuario que generó la notificación y del post relacionado
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // Obtengo las notificaciones del usuario autenticado
            // Cargo las relaciones completas sin filtrar campos
            $notifications = Notification::where('user_id', Auth::id())
                ->with(['fromUser', 'post'])
                ->orderBy('created_at', 'desc')
                ->paginate(20); // Pagino de 20 en 20 para no sobrecargar la app

            // Transformo las notificaciones para agregar URLs completas de imágenes
            $notifications->getCollection()->transform(function ($notification) {
                // Preparo datos del usuario que generó la notificación
                if ($notification->fromUser) {
                    $fromUserData = [
                        'id' => $notification->fromUser->id,
                        'name' => $notification->fromUser->name,
                        'username' => $notification->fromUser->username,
                        'imagen' => $notification->fromUser->imagen,
                        'insignia' => $notification->fromUser->insignia ?? null,
                    ];
                    
                    // Agrego URL de imagen del usuario si tiene
                    if ($notification->fromUser->imagen) {
                        $fromUserData['imagen_url'] = url('perfiles/' . $notification->fromUser->imagen);
                    }
                    
                    $notification->from_user_data = $fromUserData;
                }

                // Preparo datos del post relacionado (si existe)
                if ($notification->post) {
                    $postData = [
                        'id' => $notification->post->id,
                        'titulo' => $notification->post->titulo,
                        'tipo' => $notification->post->tipo,
                        'imagen' => $notification->post->imagen,
                    ];
                    
                    // Agrego URL de imagen del post si tiene
                    if ($notification->post->imagen) {
                        $postData['imagen_url'] = url('uploads/' . $notification->post->imagen);
                    }
                    
                    $notification->post_data = $postData;
                }

                // Agrego el mensaje descriptivo de la notificación
                $notification->message = $notification->getMessage();
                
                // Agrego tiempo relativo (hace 5 minutos, hace 2 horas, etc.)
                $notification->time_ago = $notification->getTimeAgoAttribute();

                // Oculto las relaciones originales para usar los datos preparados
                unset($notification->fromUser);
                unset($notification->post);
                unset($notification->user);

                return $notification;
            });

            return response()->json([
                'success' => true,
                'data' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener notificaciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener solo las notificaciones no leídas
     * Útil para mostrar el badge con el número de notificaciones pendientes
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function unread()
    {
        try {
            // Obtengo solo las notificaciones no leídas
            $notifications = Notification::where('user_id', Auth::id())
                ->unread() // Uso el scope definido en el modelo
                ->with(['fromUser', 'post'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Transformo igual que en index()
            $notifications->transform(function ($notification) {
                // Preparo datos del usuario que generó la notificación
                if ($notification->fromUser) {
                    $fromUserData = [
                        'id' => $notification->fromUser->id,
                        'name' => $notification->fromUser->name,
                        'username' => $notification->fromUser->username,
                        'imagen' => $notification->fromUser->imagen,
                        'insignia' => $notification->fromUser->insignia ?? null,
                    ];
                    
                    if ($notification->fromUser->imagen) {
                        $fromUserData['imagen_url'] = url('perfiles/' . $notification->fromUser->imagen);
                    }
                    
                    $notification->from_user_data = $fromUserData;
                }

                // Preparo datos del post relacionado (si existe)
                if ($notification->post) {
                    $postData = [
                        'id' => $notification->post->id,
                        'titulo' => $notification->post->titulo,
                        'tipo' => $notification->post->tipo,
                        'imagen' => $notification->post->imagen,
                    ];
                    
                    if ($notification->post->imagen) {
                        $postData['imagen_url'] = url('uploads/' . $notification->post->imagen);
                    }
                    
                    $notification->post_data = $postData;
                }

                $notification->message = $notification->getMessage();
                $notification->time_ago = $notification->getTimeAgoAttribute();

                // Oculto las relaciones originales
                unset($notification->fromUser);
                unset($notification->post);
                unset($notification->user);

                return $notification;
            });

            return response()->json([
                'success' => true,
                'data' => $notifications,
                'count' => $notifications->count() // Total de notificaciones no leídas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener notificaciones no leídas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener el contador de notificaciones no leídas
     * Endpoint ligero para actualizar solo el badge sin cargar todas las notificaciones
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function count()
    {
        try {
            // Solo cuento las notificaciones no leídas sin cargar relaciones
            $count = Notification::where('user_id', Auth::id())
                ->unread()
                ->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al contar notificaciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marcar una notificación específica como leída
     * 
     * @param Notification $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Notification $notification)
    {
        try {
            // Verifico que la notificación pertenezca al usuario autenticado
            if ($notification->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 403);
            }

            // Marco como leída
            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notificación marcada como leída',
                'data' => $notification
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar notificación como leída',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marcar todas las notificaciones como leídas
     * Útil cuando el usuario abre el panel de notificaciones
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
        try {
            // Marco todas las notificaciones no leídas del usuario como leídas
            $updated = Notification::where('user_id', Auth::id())
                ->unread()
                ->update(['read_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Todas las notificaciones marcadas como leídas',
                'updated_count' => $updated
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar todas las notificaciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar una notificación específica
     * Permite al usuario limpiar notificaciones antiguas o no deseadas
     * 
     * @param Notification $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Notification $notification)
    {
        try {
            // Verifico que la notificación pertenezca al usuario autenticado
            if ($notification->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 403);
            }

            // Elimino la notificación
            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notificación eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar notificación',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar todas las notificaciones leídas
     * Útil para limpiar el historial de notificaciones antiguas
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearRead()
    {
        try {
            // Elimino solo las notificaciones leídas del usuario
            $deleted = Notification::where('user_id', Auth::id())
                ->read() // Uso el scope definido en el modelo
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notificaciones leídas eliminadas',
                'deleted_count' => $deleted
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al limpiar notificaciones',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener notificaciones filtradas por tipo
     * Permite filtrar por likes, comentarios o follows
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function byType(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:like,comment,follow'
            ]);

            // Obtengo notificaciones del tipo especificado
            $notifications = Notification::where('user_id', Auth::id())
                ->where('type', $request->type)
                ->with(['fromUser', 'post'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            // Transformo las notificaciones
            $notifications->getCollection()->transform(function ($notification) {
                // Preparo datos del usuario que generó la notificación
                if ($notification->fromUser) {
                    $fromUserData = [
                        'id' => $notification->fromUser->id,
                        'name' => $notification->fromUser->name,
                        'username' => $notification->fromUser->username,
                        'imagen' => $notification->fromUser->imagen,
                        'insignia' => $notification->fromUser->insignia ?? null,
                    ];
                    
                    if ($notification->fromUser->imagen) {
                        $fromUserData['imagen_url'] = url('perfiles/' . $notification->fromUser->imagen);
                    }
                    
                    $notification->from_user_data = $fromUserData;
                }

                // Preparo datos del post relacionado (si existe)
                if ($notification->post) {
                    $postData = [
                        'id' => $notification->post->id,
                        'titulo' => $notification->post->titulo,
                        'tipo' => $notification->post->tipo,
                        'imagen' => $notification->post->imagen,
                    ];
                    
                    if ($notification->post->imagen) {
                        $postData['imagen_url'] = url('uploads/' . $notification->post->imagen);
                    }
                    
                    $notification->post_data = $postData;
                }

                $notification->message = $notification->getMessage();
                $notification->time_ago = $notification->getTimeAgoAttribute();

                // Oculto las relaciones originales
                unset($notification->fromUser);
                unset($notification->post);
                unset($notification->user);

                return $notification;
            });

            return response()->json([
                'success' => true,
                'data' => $notifications,
                'type' => $request->type
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener notificaciones por tipo',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
