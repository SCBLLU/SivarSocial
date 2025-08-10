<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LikesModal extends Component
{
    public $post;
    public $likes = [];
    public $showModal = false;
    public $currentPage = 1;
    public $perPage = 20;
    public $hasMore = true;
    public $isLoading = false;

    protected $listeners = [
        'like-updated' => 'refreshLikes',
        'follow-updated' => 'updateFollowStatus',
        'open-likes-modal' => 'openModal'
    ];

    public function mount(Post $post = null)
    {
        if ($post) {
            $this->post = $post;
        }
    }

    public function openModal($postId)
    {
        $this->post = Post::findOrFail($postId);
        $this->showModal = true;
        $this->currentPage = 1;
        $this->likes = [];
        $this->hasMore = true;
        $this->loadLikes(true);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->likes = [];
        $this->currentPage = 1;
        $this->hasMore = true;
    }

    public function loadLikes($reset = false)
    {
        if ($this->isLoading || !$this->hasMore) {
            return;
        }

        $this->isLoading = true;

        if ($reset) {
            $this->currentPage = 1;
            $this->likes = [];
        }

        try {
            $offset = ($this->currentPage - 1) * $this->perPage;

            $likesData = $this->post->likes()
                ->with(['user:id,name,username,imagen,profession,insignia'])
                ->latest()
                ->offset($offset)
                ->limit($this->perPage)
                ->get()
                ->map(function ($like) {
                    $isFollowing = false;

                    if (Auth::check() && Auth::id() !== $like->user->id) {
                        $isFollowing = DB::table('followers')
                            ->where('follower_id', Auth::id())
                            ->where('user_id', $like->user->id)
                            ->exists();
                    }

                    return [
                        'id' => $like->id,
                        'user' => $like->user,
                        'isFollowing' => $isFollowing,
                        'created_at' => $like->created_at,
                    ];
                });

            if ($reset) {
                $this->likes = $likesData->toArray();
            } else {
                $this->likes = array_merge($this->likes, $likesData->toArray());
            }

            $this->hasMore = $likesData->count() === $this->perPage;
            $this->currentPage++;
        } catch (\Exception $e) {
            // Manejar error silenciosamente
        } finally {
            $this->isLoading = false;
        }
    }

    public function loadMore()
    {
        $this->loadLikes(false);
    }

    public function toggleFollow($userId)
    {
        $currentUser = Auth::user();

        if (!$currentUser || $userId == $currentUser->id) {
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            return;
        }

        try {
            $isCurrentlyFollowing = $currentUser->isFollowing($user);

            if ($isCurrentlyFollowing) {
                $user->followers()->detach($currentUser->id);
                $newFollowingStatus = false;
            } else {
                $user->followers()->attach($currentUser->id);
                $newFollowingStatus = true;
            }

            // Actualizar el estado en el array local
            foreach ($this->likes as &$like) {
                if ($like['user']->id == $userId) {
                    $like['isFollowing'] = $newFollowingStatus;
                    break;
                }
            }

            // Notificar a otros componentes
            $this->dispatch('follow-updated', userId: $userId, isFollowing: $newFollowingStatus);
        } catch (\Exception $e) {
            // Manejar error silenciosamente
        }
    }

    public function refreshLikes()
    {
        if ($this->showModal && $this->post) {
            $this->loadLikes(true);
        }
    }

    public function updateFollowStatus($userId, $isFollowing)
    {
        // Actualizar el estado de seguimiento en los likes locales
        foreach ($this->likes as &$like) {
            if ($like['user']->id == $userId) {
                $like['isFollowing'] = $isFollowing;
                break;
            }
        }
    }

    public function render()
    {
        return view('livewire.likes-modal');
    }
}
