<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\DeviceToken;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Obtener notificaciones del usuario autenticado
     * 
     * GET /api/notifications
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->input('per_page', 15);

        $notifications = $user->notifications()
            ->with(['fromUser:id,username,name,imagen'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $notifications->items(),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
                'last_page' => $notifications->lastPage(),
                'has_more' => $notifications->hasMorePages()
            ]
        ]);
    }

    /**
     * Contar notificaciones no leídas
     * 
     * GET /api/notifications/unread-count
     */
    public function unreadCount()
    {
        $user = Auth::user();
        $count = $user->unreadNotifications()->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Marcar una notificación como leída
     * 
     * POST /api/notifications/{notification}/mark-read
     */
    public function markAsRead(Notification $notification)
    {
        // Verificar que la notificación pertenece al usuario autenticado
        if ($notification->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado'
            ], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como leída'
        ]);
    }

    /**
     * Marcar todas las notificaciones como leídas
     * 
     * POST /api/notifications/mark-all-read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications()->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Todas las notificaciones marcadas como leídas'
        ]);
    }

    /**
     * Registrar token de dispositivo
     * 
     * POST /api/notifications/register-device
     */
    public function registerDevice(Request $request)
    {
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
                'message' => 'Device token already registered',
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
            'message' => 'Device token registered successfully',
            'created' => true
        ]);
    }

    /**
     * Desregistrar token de dispositivo
     * 
     * POST /api/notifications/unregister-device
     */
    public function unregisterDevice(Request $request)
    {
        $request->validate([
            'device_token' => 'required|string'
        ]);

        $user = Auth::user();

        $deleted = DeviceToken::where('user_id', $user->id)
            ->where('device_token', $request->device_token)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Device token unregistered successfully',
            'deleted' => $deleted > 0
        ]);
    }

    /**
     * Enviar notificación push a un usuario
     * 
     * Método estático para ser llamado desde otros controladores
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
                            'channel_id' => 'default'
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
     */
    private static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
