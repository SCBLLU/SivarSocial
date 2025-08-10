<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FollowUser extends Component
{
    public $user;
    public $isFollowing;
    public $followersCount;
    public $size;
    public $showCount;

    protected $listeners = [
        'follow-updated' => 'updateFollowStatus'
    ];

    public function mount($user, $size = 'normal', $showCount = false)
    {
        $this->user = $user;
        $this->size = $size;
        $this->showCount = $showCount;

        $currentUser = Auth::user();
        $this->isFollowing = $currentUser ? $currentUser->isFollowing($this->user) : false;
        $this->followersCount = $this->user->followers()->count();
    }

    public function toggleFollow()
    {
        $currentUser = Auth::user();

        if (!$currentUser) {
            return;
        }

        // No puede seguirse a sí mismo
        if ($this->user->id === $currentUser->id) {
            return;
        }

        try {
            if ($this->isFollowing) {
                // Dejar de seguir
                $this->user->followers()->detach($currentUser->id);
                $this->isFollowing = false;
                $this->followersCount--;
            } else {
                // Seguir
                $this->user->followers()->attach($currentUser->id);
                $this->isFollowing = true;
                $this->followersCount++;
            }

            // Refrescar la relación para asegurar datos actualizados
            $this->user->refresh();
            $this->followersCount = $this->user->followers()->count();

            // Notificar a otros componentes que el estado de seguimiento cambió
            $this->dispatch('follow-updated', userId: $this->user->id, isFollowing: $this->isFollowing);
        } catch (\Exception $e) {
            // Manejar error silenciosamente
            return;
        }
    }

    public function updateFollowStatus($userId, $isFollowing)
    {
        // Actualizar solo si es el mismo usuario
        if ($this->user->id == $userId) {
            $this->isFollowing = $isFollowing;
            // Refrescar el conteo desde la base de datos
            $this->user->refresh();
            $this->followersCount = $this->user->followers()->count();
        }
    }

    public function render()
    {
        return view('livewire.follow-user');
    }
}
