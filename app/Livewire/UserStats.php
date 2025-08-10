<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class UserStats extends Component
{
    public $user;
    public $followersCount;
    public $followingCount;
    public $postsCount;

    protected $listeners = [
        'follow-updated' => 'updateStats'
    ];

    public function mount(User $user, $postsCount = 0)
    {
        $this->user = $user;
        $this->postsCount = $postsCount;
        $this->updateCounts();
    }

    public function updateCounts()
    {
        // Refrescar usuario para obtener datos actualizados
        $this->user->refresh();
        $this->followersCount = $this->user->followers()->count();
        $this->followingCount = $this->user->following()->count();
    }

    public function updateStats($userId, $isFollowing)
    {
        // Actualizar solo si es el mismo usuario que está siendo seguido
        if ($this->user->id == $userId) {
            $this->updateCounts();
        }
    }

    public function render()
    {
        return view('livewire.user-stats');
    }
}
