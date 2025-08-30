<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\NotificationService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FollowUser extends Component
{
    public $user;
    public $isFollowing;
    public $followersCount;
    public $size;
    public $showCount;

    protected $listeners = [];

    public function mount($user, $size = 'normal', $showCount = false)
    {
        $this->user = $user;
        $this->size = $size;
        $this->showCount = $showCount;

        $this->refreshFollowStatus();
    }

    public function refreshFollowStatus()
    {
        $currentUser = Auth::user();
        $this->isFollowing = $currentUser ? $currentUser->isFollowing($this->user) : false;
        $this->followersCount = $this->user->followers()->count();
    }

    public function toggleFollow()
    {
        $currentUser = Auth::user();

        if (!$currentUser) {
            // Emitir evento para mostrar modal de registro/login
            $this->dispatch('show-auth-modal', action: 'follow');
            return;
        }

        // No puede seguirse a sí mismo
        if ($this->user->id === $currentUser->id) {
            return;
        }

        try {
            $wasFollowing = $this->isFollowing;
            $notificationService = new NotificationService();

            if ($wasFollowing) {
                // Dejar de seguir
                $currentUser->following()->detach($this->user->id);
            } else {
                // Seguir
                $currentUser->following()->attach($this->user->id);

                // Crear notificación de follow
                $notificationService->createFollowNotification($currentUser, $this->user);

                // Emitir eventos para actualizar notificaciones
                $this->dispatch('notification-created');
                $this->dispatch('refreshNotifications');
            }

            // Actualizar el estado inmediatamente
            $this->isFollowing = !$wasFollowing;

            // Refrescar el conteo desde la base de datos
            $this->user->refresh();
            $this->followersCount = $this->user->followers()->count();

            // Emitir evento para actualizar otros componentes
            $this->dispatch('follow-updated', userId: $this->user->id, isFollowing: $this->isFollowing);
        } catch (\Exception $e) {
            // Restaurar estado en caso de error
            $this->refreshFollowStatus();
            return;
        }
    }

    public function render()
    {
        return view('livewire.follow-user');
    }
}
