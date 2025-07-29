<?php

namespace App\Livewire;

use Livewire\Component;

class CommentPost extends Component
{
    public $post;
    public $comments;
    public $color;

    public function mount($post, $color = 'gray')
    {
        $this->post = $post;
        $this->comments = $post->comentarios->count();
        $this->color = $color;
    }

    public function render()
    {
        return view('livewire.comment-post');
    }
}
