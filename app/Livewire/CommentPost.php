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

    protected $listeners = [
        'comment-added' => 'refreshCommentsCount',
        'comment-deleted' => 'refreshCommentsCount'
    ];

    public function refreshCommentsCount($postId = null)
    {
        // Si no se pasa postId, simplemente actualizar
        // Si se pasa postId, solo actualizar si coincide
        if ($postId === null || $this->post->id == $postId) {
            // Refrescar el post desde la base de datos para asegurar datos actualizados
            $this->post->refresh();
            $this->updateCommentsCount();
        }
    }

    public function render()
    {
        return view('livewire.comment-post');
    }
}
