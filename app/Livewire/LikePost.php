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
        $this->isLiked = $post->checkLike(Auth::user());
        $this->likes = $post->likes->count();
        $this->color = $color;
    }

    public function clickLike()
    {
        if ($this->post->checkLike(Auth::user())) {
            $this->post->likes()->where('post_id', $this->post->id)->delete();
            $this->isLiked = false;
            $this->likes--;
        } else {
            $this->post->likes()->create([
                'user_id' => Auth::user()->id,
            ]);
            $this->isLiked = true;
            $this->likes++;
        }
    }

    public function render()
    {
        return view('livewire.like-post');
    }
}