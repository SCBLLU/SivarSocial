<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LikePost extends Component
{
    public $post;
    public $isLiked;
    public $likes;
    public $color;

    public function mount($post, $color = 'purple')
    {
        $this->post = $post;
        $this->post->loadMissing('likes');

        $this->color = $color;

        $user = Auth::user();
        $this->isLiked = $user ? $this->post->checkLike($user) : false;

        $this->likes = $this->post->likes->count();
    }

    public function clickLike()
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        try {
            if ($this->post->checkLike($user)) {
                // Quitar like
                $this->post->likes()->where('user_id', $user->id)->delete();
                $this->isLiked = false;
                $this->likes--;
            } else {
                // Dar like
                $this->post->likes()->create(['user_id' => $user->id]);
                $this->isLiked = true;
                $this->likes++;
            }

            // Refrescar conteo
            $this->post->load('likes');
            $this->likes = $this->post->likes->count();

            // Notificar al modal de likes para que se actualice
            $this->dispatch('like-updated');
        } catch (\Exception $e) {
            // Manejar error silenciosamente
            return;
        }
    }

    public function render()
    {
        return view('livewire.like-post');
    }
}
