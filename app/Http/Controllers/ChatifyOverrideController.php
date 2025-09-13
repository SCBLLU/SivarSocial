<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatifyOverrideController extends Controller
{
    /**
     * Get contacts with proper ordering and unread count persistence
     * Supports both GET and POST methods for compatibility
     */
    public function getContacts(Request $request)
    {
        // Debug muy visible
        Log::error('=== CHATIFY OVERRIDE CONTROLLER getContacts CALLED ===', [
            'page' => $request->input('page', 1),
            'method' => $request->method(),
            'url' => $request->url(),
            'full_url' => $request->fullUrl(),
        ]);

        $page = $request->input('page', 1);
        $perPage = 10;
        $auth_user = Auth::user();

        // Get contacts with last message and unread count
        $contactsQuery = User::select('users.*')
            ->join('ch_messages', function ($join) use ($auth_user) {
                $join->on('users.id', '=', 'ch_messages.from_id')
                    ->orWhere(function ($query) use ($auth_user) {
                        $query->where('ch_messages.to_id', '=', $auth_user->id)
                            ->where('ch_messages.from_id', '!=', $auth_user->id);
                    });
            })
            ->orWhereExists(function ($query) use ($auth_user) {
                $query->select(DB::raw(1))
                    ->from('ch_messages')
                    ->whereColumn('ch_messages.to_id', 'users.id')
                    ->where('ch_messages.from_id', $auth_user->id);
            })
            ->where('users.id', '!=', $auth_user->id)
            ->groupBy('users.id', 'users.name', 'users.username', 'users.imagen', 'users.email', 'users.email_verified_at', 'users.password', 'users.remember_token', 'users.created_at', 'users.updated_at')
            ->orderByRaw('(
                SELECT MAX(created_at) 
                FROM ch_messages 
                WHERE (from_id = users.id AND to_id = ?) 
                   OR (from_id = ? AND to_id = users.id)
            ) DESC', [$auth_user->id, $auth_user->id]);

        $contacts = $contactsQuery->paginate($perPage, ['*'], 'page', $page);

        $contactsHtml = '';
        foreach ($contacts as $contact) {
            $lastMessage = ChMessage::where(function ($q) use ($auth_user, $contact) {
                $q->where('from_id', $auth_user->id)->where('to_id', $contact->id);
            })
                ->orWhere(function ($q) use ($auth_user, $contact) {
                    $q->where('from_id', $contact->id)->where('to_id', $auth_user->id);
                })
                ->orderBy('created_at', 'desc')
                ->first();

            if ($lastMessage) {
                // Count unread messages
                $unreadCount = ChMessage::where('from_id', $contact->id)
                    ->where('to_id', $auth_user->id)
                    ->where('seen', 0)
                    ->count();

                Log::info('Unread count for user ' . $contact->id, ['count' => $unreadCount]);

                // Add timeAgo property to lastMessage
                $lastMessage->timeAgo = $lastMessage->created_at->diffForHumans();

                // Use the existing Blade template for consistency
                $contactsHtml .= view('Chatify::layouts.listItem', [
                    'get' => 'users',
                    'user' => $contact,
                    'lastMessage' => $lastMessage,
                    'unseenCounter' => $unreadCount
                ])->render();
            }
        }

        return response()->json([
            'contacts' => $contactsHtml,
            'total' => $contacts->total(),
            'last_page' => $contacts->lastPage(),
            'current_page' => $contacts->currentPage(),
        ]);
    }

    /**
     * Endpoint para devolver info de usuario para Chatify, incluyendo avatar personalizado.
     */
    public function idInfo(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        if (!$user) {
            return response()->json(['fetch' => false]);
        }

        // Forzar siempre la imagen real o la imagen por defecto
        $avatar = $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/img.jpg');

        // Si el archivo no existe físicamente, usar la imagen por defecto
        if ($user->imagen && !file_exists(public_path('perfiles/' . $user->imagen))) {
            $avatar = asset('img/img.jpg');
        }

        return response()->json([
            'fetch' => [
                'name' => $user->name ?? $user->username,
                'id' => $user->id,
                'username' => $user->username,
            ],
            'user_avatar' => $avatar,
        ]);
    }

    /**
     * Update contact item - versión personalizada para tu aplicación con actualización en tiempo real
     */
    public function updateContactItem(Request $request)
    {
        $user_id = $request->user_id;
        $auth_user = Auth::user();

        // Obtener el usuario
        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found!',
            ], 401);
        }

        // Obtener el último mensaje entre el usuario autenticado y el usuario objetivo
        $lastMessage = ChMessage::where(function ($q) use ($auth_user, $user) {
            $q->where('from_id', $auth_user->id)->where('to_id', $user->id);
        })
            ->orWhere(function ($q) use ($auth_user, $user) {
                $q->where('from_id', $user->id)->where('to_id', $auth_user->id);
            })
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastMessage) {
            return response()->json([
                'contactItem' => '',
            ], 200);
        }

        // Contar mensajes no leídos
        $unreadCount = ChMessage::where('from_id', $user->id)
            ->where('to_id', $auth_user->id)
            ->where('seen', 0)
            ->count();

        // Generar el avatar correcto
        $avatar = $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/img.jpg');

        // Preparar el tiempo y el cuerpo del mensaje
        $lastMsgTime = $lastMessage->created_at->diffForHumans();
        $lastMsgBody = $lastMessage->body;

        // Truncar el mensaje si es muy largo
        if (strlen($lastMsgBody) > 20) {
            $lastMsgBody = mb_substr($lastMsgBody, 0, 20, 'UTF-8') . '...';
        }

        // Determinar quién envió el mensaje
        $messagePrefix = '';
        if ($lastMessage->from_id == $auth_user->id) {
            $messagePrefix = 'You : ';
        } else {
            $messagePrefix = ($user->name ?? $user->username) . ' : ';
        }

        // Badge para mensajes no leídos (solo mostrar si no estoy en la conversación con esa persona)
        $currentChatUser = $request->input('current_chat_user', null);
        $unreadBadge = '';
        if ($unreadCount > 0 && $currentChatUser != $user->id) {
            $unreadBadge = $unreadCount;
        } else {
            $unreadBadge = 0;
        }

        // Add timeAgo property to lastMessage
        $lastMessage->timeAgo = $lastMessage->created_at->diffForHumans();

        // Use the existing Blade template for consistency
        $contactHTML = view('Chatify::layouts.listItem', [
            'get' => 'users',
            'user' => $user,
            'lastMessage' => $lastMessage,
            'unseenCounter' => $unreadBadge
        ])->render();

        // Enviar la respuesta
        return response()->json([
            'contactItem' => $contactHTML,
        ], 200);
    }

    /**
     * Endpoint AJAX para devolver la lista de contactos mutuos como HTML (usado para recarga dinámica en Chatify).
     */
    public function mutualContactsList(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['html' => '']);
        }

        $mutualFollowers = User::whereIn('id', function ($query) use ($user) {
            $query->select('follower_id')->from('followers')->where('user_id', $user->id);
        })
            ->whereIn('id', function ($query) use ($user) {
                $query->select('user_id')->from('followers')->where('follower_id', $user->id);
            })
            ->get();

        $html = '';
        if ($mutualFollowers->count()) {
            foreach ($mutualFollowers as $follower) {
                $lastMessage = ChMessage::where(function ($q) use ($user, $follower) {
                    $q->where('from_id', $user->id)->where('to_id', $follower->id);
                })
                    ->orWhere(function ($q) use ($user, $follower) {
                        $q->where('from_id', $follower->id)->where('to_id', $user->id);
                    })
                    ->orderBy('created_at', 'desc')
                    ->first();

                // Contar mensajes no leídos
                $unreadCount = ChMessage::where('from_id', $follower->id)
                    ->where('to_id', $user->id)
                    ->where('seen', 0)
                    ->count();

                $avatar = $follower->imagen ? asset('perfiles/' . $follower->imagen) : asset('img/img.jpg');
                $lastMsgTime = $lastMessage ? $lastMessage->created_at->diffForHumans() : '';

                // Construir mensaje con truncado
                $lastMsgBody = '';
                if ($lastMessage) {
                    $prefix = $lastMessage->from_id == $user->id ? 'You : ' : ($follower->name ?? $follower->username) . ' : ';
                    $messageBody = $lastMessage->body;
                    $truncatedMessage = strlen($messageBody) > 20 ? mb_substr($messageBody, 0, 20, 'UTF-8') . '...' : $messageBody;
                    $lastMsgBody = $prefix . $truncatedMessage;
                }

                // Badge para mensajes no leídos
                $currentChatUser = $request->input('current_chat_user', null);
                $unreadBadge = '';
                if ($unreadCount > 0 && $currentChatUser != $follower->id) {
                    $unreadBadge = '<b>' . $unreadCount . '</b>';
                }

                $html .= '<table class="messenger-list-item" data-contact="' . $follower->id . '">
                    <tbody>
                        <tr data-action="1">
                            <td style="position: relative">
                                <div class="avatar av-m" style="background-image: url(\'' . $avatar . '\');" data-imagen="' . $avatar . '"></div>
                            </td>
                            <td>
                                <p data-id="' . $follower->id . '" data-type="user">' . e($follower->name ?? $follower->username) . '<span class="contact-item-time" data-time="' . ($lastMessage ? $lastMessage->created_at : '') . '">' . $lastMsgTime . '</span></p><span class="lastMessageIndicator">' . $lastMsgBody . '</span>' . $unreadBadge . '
                            </td>
                        </tr>
                    </tbody>
                </table>';
            }
        } else {
            $html = '<p class="mt-2 text-xs text-center text-gray-400">No tienes seguidores mutuos.</p>';
        }

        return response()->json(['html' => $html]);
    }
}
