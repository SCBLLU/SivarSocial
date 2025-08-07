<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class LikesModal extends Component
{
    public $showModal = false;
    public $currentPost = null;
    public $likes;
    public $totalLikes = 0;

    public function mount()
    {
        $this->likes = collect([]);
    }

    #[On('openLikesModal')]
    public function openModal($postId)
    {
        $post = Post::find($postId);
        if ($post) {
            $this->currentPost = $post;
            $this->loadLikes();
            $this->showModal = true;
        }
    }

    #[On('closeLikesModal')]
    public function closeModal()
    {
        $this->showModal = false;
        $this->currentPost = null;
        $this->likes = collect([]);
        $this->totalLikes = 0;
    }

    #[On('like-updated')]
    public function refreshLikes()
    {
        if ($this->currentPost) {
            $this->currentPost->refresh();
            $this->loadLikes();
        }
    }

    public function loadLikes()
    {
        if ($this->currentPost) {
            $this->likes = $this->currentPost->likes()
                ->with(['user:id,username,name,imagen'])
                ->latest()
                ->get();

            $this->totalLikes = $this->likes->count();
        }
    }

    public function render()
    {
        return view('livewire.likes-modal');
    }
}
