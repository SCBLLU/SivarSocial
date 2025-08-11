<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class FollowersList extends Component
{
    use WithPagination;

    public $user;
    public $type; // 'followers' o 'following'
    
    protected $paginationTheme = 'tailwind';

    protected $listeners = [];

    public function mount($user, $type = 'followers')
    {
        $this->user = $user;
        $this->type = $type;
    }

    public function render()
    {
        $query = $this->type === 'followers' 
            ? $this->user->followers() 
            : $this->user->following();

        $users = $query->withCount(['followers', 'following', 'posts'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(20);

        $totalCount = $this->type === 'followers' 
            ? $this->user->followers()->count()
            : $this->user->following()->count();

        return view('livewire.followers-list', [
            'users' => $users,
            'totalCount' => $totalCount
        ]);
    }
}
