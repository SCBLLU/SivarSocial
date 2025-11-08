<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    /**
     * ========================================================================
     * MÉTODOS PARA PUSH NOTIFICATIONS (Firebase Cloud Messaging)
     * ========================================================================
     */

    /**
     * Registrar token de dispositivo para recibir push notifications
     * Se llama cuando el usuario inicia sesión en la app móvil
     * 
     * POST /api/notifications/register-device
     * Body: { "device_token": "xxx", "platform": "android" | "ios" }
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerDevice(Request $request)
    {
        try {
            $request->validate([
                'device_token' => 'required|string',
                'platform' => 'required|in:android,ios'
            ]);

            $user = Auth::user();

            // Verificar si el token ya existe para este usuario
            $existingToken = DeviceToken::where('user_id', $user->id)
                ->where('device_token', $request->device_token)
                ->first();

            if ($existingToken) {
                // Actualizar la fecha de última actividad
                $existingToken->touch();
                $existingToken->update(['last_used_at' => now()]);

                return response()->json([
                    'success' => true,
                    'message' => 'Token de dispositivo ya registrado',
                    'updated' => true
                ]);
            }

            // Crear nuevo registro de token
            DeviceToken::create([
                'user_id' => $user->id,
                'device_token' => $request->device_token,
                'platform' => $request->platform,
                'last_used_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Token de dispositivo registrado exitosamente',
                'created' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar token de dispositivo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desregistrar token de dispositivo
     * Se llama cuando el usuario cierra sesión en la app móvil
     * 
     * POST /api/notifications/unregister-device
     * Body: { "device_token": "xxx" }
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unregisterDevice(Request $request)
    {
        try {
            $request->validate([
                'device_token' => 'required|string'
            ]);

            $user = Auth::user();

            $deleted = DeviceToken::where('user_id', $user->id)
                ->where('device_token', $request->device_token)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Token de dispositivo desregistrado exitosamente',
                'deleted' => $deleted > 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al desregistrar token de dispositivo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enviar notificación push a un usuario específico
     * Método estático para ser llamado desde otros controladores/servicios
     * 
     * Uso:
     * NotificationController::sendPushNotification(
     *     $userId,
     *     'Título',
     *     'Mensaje',
     *     ['type' => 'like', 'post_id' => '123']
     * );
     * 
     * @param int $userId ID del usuario que recibirá la notificación
     * @param string $title Título de la notificación
     * @param string $body Mensaje de la notificación
     * @param array $data Datos adicionales (type, post_id, user_id, etc.)
     * @return bool
     */
    public static function sendPushNotification($userId, $title, $body, $data = [])
    {
        try {
            // Obtener todos los tokens de dispositivo del usuario
            $devices = DeviceToken::where('user_id', $userId)
                ->where('last_used_at', '>=', now()->subDays(30)) // Solo dispositivos activos
                ->get();

            if ($devices->isEmpty()) {
                Log::info('No active devices found for user', ['user_id' => $userId]);
                return false;
            }

            $successCount = 0;
            foreach ($devices as $device) {
                if (self::sendFCMNotification($device->device_token, $title, $body, $data)) {
                    $successCount++;
                }
            }

            return $successCount > 0;
        } catch (\Exception $e) {
            Log::error('Error in sendPushNotification: ' . $e->getMessage(), [
                'user_id' => $userId,
                'title' => $title
            ]);
            return false;
        }
    }

    /**
     * Enviar notificación mediante Firebase Cloud Messaging (HTTP v1 API)
     * Método privado que maneja el envío real a FCM
     * 
     * @param string $deviceToken Token FCM del dispositivo
     * @param string $title Título de la notificación
     * @param string $body Mensaje de la notificación
     * @param array $data Datos adicionales
     * @return bool
     */
    private static function sendFCMNotification($deviceToken, $title, $body, $data = [])
    {
        try {
            // Verificar que el archivo de credenciales existe
            $credentialsPath = storage_path('app/firebase/service-account.json');

            if (!file_exists($credentialsPath)) {
                Log::warning('Firebase service account file not found. Push notifications disabled.');
                return false;
            }

            // Leer las credenciales
            $credentials = json_decode(file_get_contents($credentialsPath), true);
            $projectId = $credentials['project_id'] ?? env('FIREBASE_PROJECT_ID');

            if (!$projectId) {
                Log::error('Firebase project ID not found');
                return false;
            }

            // Obtener el access token de OAuth2
            $accessToken = self::getFirebaseAccessToken($credentials);

            if (!$accessToken) {
                Log::error('Failed to get Firebase access token');
                return false;
            }

            // Convertir todos los datos a string (requerimiento de FCM)
            $stringData = [];
            foreach ($data as $key => $value) {
                $stringData[$key] = (string) $value;
            }

            // Preparar el payload del mensaje
            $payload = [
                'message' => [
                    'token' => $deviceToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $stringData,
                    'android' => [
                        'priority' => 'high',
                        'notification' => [
                            'sound' => 'default',
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'channel_id' => 'default',
                            'icon' => 'push_icon', // Nombre del archivo sin extensión
                            'color' => '#6200EA'
                        ]
                    ],
                    'apns' => [
                        'payload' => [
                            'aps' => [
                                'sound' => 'default',
                                'badge' => 1
                            ]
                        ]
                    ]
                ]
            ];

            // Enviar la notificación usando HTTP Client de Laravel
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post(
                "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
                $payload
            );

            if ($response->successful()) {
                Log::info('Push notification sent successfully', [
                    'device_token' => substr($deviceToken, 0, 20) . '...',
                    'title' => $title,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('Failed to send push notification', [
                    'device_token' => substr($deviceToken, 0, 20) . '...',
                    'title' => $title,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error sending FCM notification: ' . $e->getMessage(), [
                'device_token' => substr($deviceToken, 0, 20) . '...',
                'title' => $title,
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    /**
     * Obtener access token de Firebase usando las credenciales de servicio
     * Implementa OAuth2 con JWT para autenticarse con Firebase
     * 
     * @param array $credentials Credenciales del archivo service-account.json
     * @return string|null
     */
    private static function getFirebaseAccessToken($credentials)
    {
        try {
            // Crear el JWT
            $now = time();
            $expiration = $now + 3600; // 1 hora

            $header = [
                'alg' => 'RS256',
                'typ' => 'JWT'
            ];

            $claimSet = [
                'iss' => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $expiration,
                'iat' => $now
            ];

            // Codificar header y claim set
            $headerEncoded = self::base64UrlEncode(json_encode($header));
            $claimSetEncoded = self::base64UrlEncode(json_encode($claimSet));

            // Crear la firma
            $signatureInput = $headerEncoded . '.' . $claimSetEncoded;
            $signature = '';

            openssl_sign(
                $signatureInput,
                $signature,
                $credentials['private_key'],
                'SHA256'
            );

            $signatureEncoded = self::base64UrlEncode($signature);

            // Crear el JWT completo
            $jwt = $signatureInput . '.' . $signatureEncoded;

            // Intercambiar el JWT por un access token
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['access_token'] ?? null;
            }

            Log::error('Failed to exchange JWT for access token', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Error getting Firebase access token: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Base64 URL encode (sin padding)
     * Formato especial requerido por JWT
     * 
     * @param string $data
     * @return string
     */
    private static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
