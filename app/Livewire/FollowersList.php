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
    public $searchTerm = '';
    
    protected $queryString = ['searchTerm'];
    protected $paginationTheme = 'tailwind';

    protected $listeners = [
        'follow-updated' => 'refreshList'
    ];

    public function mount($user, $type = 'followers')
    {
        $this->user = $user;
        $this->type = $type;
    }

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function refreshList()
    {
        // Refrescar la página para obtener datos actualizados
        $this->resetPage();
    }

    public function render()
    {
        $query = $this->type === 'followers' 
            ? $this->user->followers() 
            : $this->user->following();

        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('username', 'like', '%' . $this->searchTerm . '%');
            });
        }

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
