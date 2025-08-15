<?php

namespace App\Livewire;

use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class NotificationsList extends Component
{
    use WithPagination;

    public $showModal = false;
    public $currentPage = 1;
    public $perPage = 20;
    public $hasMore = true;
    public $isLoading = false;

    protected $listeners = [
        'open-notifications-dropdown' => 'refreshNotifications',
        'follow-updated' => 'refreshNotifications',
        'notification-created' => 'refreshNotifications',
        'refreshNotifications' => 'refreshNotifications',
    ];

    public function isFollowingUser($userId)
    {
        if (!Auth::check()) {
            return false;
        }

        $currentUser = Auth::user();
        return $currentUser->following()->where('user_id', $userId)->exists();
    }

    public function refreshNotifications()
    {
        // Forzar la recarga del usuario actual para actualizar las relaciones
        if (Auth::check()) {
            Auth::user()->load('following');
        }

        // Forzar actualización del componente
        $this->render();
    }

    public function followUser($userId)
    {
        if (!Auth::check()) {
            return;
        }

        $userToFollow = User::findOrFail($userId);
        $currentUser = Auth::user();

        // No puede seguirse a sí mismo
        if ($currentUser->id === $userToFollow->id) {
            return;
        }

        try {
            $notificationService = new NotificationService();
            $isNowFollowing = false;

            if ($currentUser->isFollowing($userToFollow)) {
                // Dejar de seguir
                $currentUser->following()->detach($userToFollow->id);
                $message = 'Dejaste de seguir a ' . $userToFollow->username;
                $isNowFollowing = false;
            } else {
                // Seguir
                $currentUser->following()->attach($userToFollow->id);
                $message = 'Ahora sigues a ' . $userToFollow->username;
                $isNowFollowing = true;

                // Crear notificación solo cuando se sigue, no cuando se deja de seguir
                $notificationService->createFollowNotification($currentUser, $userToFollow);
                $this->dispatch('notification-created');
                $this->dispatch('refreshNotifications');
            }

            // Forzar actualización de este componente para reflejar el cambio
            $this->refreshNotifications();

            // Emitir evento para actualizar otros componentes de follow
            $this->dispatch('follow-updated', userId: $userToFollow->id, isFollowing: $isNowFollowing);

            // Forzar re-renderizado inmediato
            $this->dispatch('$refresh');

            session()->flash('message', $message);
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error al procesar la acción.');
        }
    }

    public function render()
    {
        $notifications = collect();

        if (Auth::check()) {
            $notifications = Auth::user()
                ->notifications()
                ->with(['fromUser', 'post.user']) // Cargar también el usuario del post
                ->take(50) // Limitar a 50 notificaciones recientes
                ->get();
        }

        return view('livewire.notifications-list', [
            'notifications' => $notifications
        ]);
    }
}
