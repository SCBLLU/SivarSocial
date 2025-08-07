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
        $this->updateCommentsCount();
        $this->color = $color;
    }

    public function updateCommentsCount()
    {
        $this->comments = $this->post->comentarios()->count();
    }

    protected $listeners = ['comment-added' => 'refreshCommentsCount'];

    public function refreshCommentsCount($postId = null)
    {
        // Si no se pasa postId, simplemente actualizar
        // Si se pasa postId, solo actualizar si coincide
        if ($postId === null || $this->post->id == $postId) {
            $this->updateCommentsCount();
        }
    }

    public function render()
    {
        return view('livewire.comment-post');
    }
}
