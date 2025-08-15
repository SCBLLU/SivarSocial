<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Crear notificación de follow
     */
    public function createFollowNotification(User $follower, User $followed)
    {
        // No crear notificación si te sigues a ti mismo
        if ($follower->id === $followed->id) {
            return null;
        }

        // Crear notificación para cada acción de follow (estilo Instagram)
        // Cada seguimiento genera una notificación independiente
        return Notification::create([
            'user_id' => $followed->id,
            'from_user_id' => $follower->id,
            'type' => Notification::TYPE_FOLLOW,
            'data' => [
                'follower_username' => $follower->username,
                'follower_name' => $follower->name,
                'follower_image' => $follower->imagen
            ]
        ]);
    }

    /**
     * Crear notificación de like
     */
    public function createLikeNotification(User $liker, Post $post)
    {
        // No crear notificación si el like es en tu propio post
        if ($liker->id === $post->user_id) {
            return null;
        }

        // Crear notificación para cada acción de like (estilo Instagram)
        // Cada like genera una notificación independiente
        return Notification::create([
            'user_id' => $post->user_id,
            'from_user_id' => $liker->id,
            'type' => Notification::TYPE_LIKE,
            'post_id' => $post->id,
            'data' => [
                'liker_username' => $liker->username,
                'liker_name' => $liker->name,
                'liker_image' => $liker->imagen,
                'post_title' => $post->titulo ?? '',
                'post_image' => $post->imagen ?? null
            ]
        ]);
    }

    /**
     * Crear notificación de comentario
     */
    public function createCommentNotification(User $commenter, Post $post, $commentText = null)
    {
        // No crear notificación si comentas tu propio post
        if ($commenter->id === $post->user_id) {
            return null;
        }

        // Para comentarios, no verificamos duplicados ya que cada comentario
        // es una interacción única que merece su propia notificación
        // (a diferencia de los likes donde solo hay uno por usuario/post)

        return Notification::create([
            'user_id' => $post->user_id,
            'from_user_id' => $commenter->id,
            'type' => Notification::TYPE_COMMENT,
            'post_id' => $post->id,
            'data' => [
                'commenter_username' => $commenter->username,
                'commenter_name' => $commenter->name,
                'commenter_image' => $commenter->imagen,
                'post_title' => $post->titulo ?? '',
                'post_image' => $post->imagen ?? null,
                'comment_preview' => $commentText ? substr($commentText, 0, 100) . (strlen($commentText) > 100 ? '...' : '') : null
            ]
        ]);
    }

    /**
     * Marcar todas las notificaciones como leídas para un usuario
     */
    public function markAllAsRead(User $user)
    {
        return $user->unreadNotifications()->update(['read_at' => now()]);
    }

    /**
     * Eliminar notificaciones antiguas (más de X días)
     */
    public function cleanOldNotifications($days = 30)
    {
        return Notification::where('created_at', '<', now()->subDays($days))->delete();
    }

    /**
     * Obtener conteo de notificaciones no leídas
     */
    public function getUnreadCount(User $user)
    {
        return $user->unreadNotifications()->count();
    }
}
